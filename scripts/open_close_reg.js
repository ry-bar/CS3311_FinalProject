let authModalInitialized = false;

function initAuthModal(options = {}) {
  const { silentIfMissing = false } = options;
  // Don't re-initialize if it's already wired
  if (authModalInitialized) {
    return;
  }

  const authOverlay    = document.getElementById("authOverlay");
  const authCloseBtn   = document.getElementById("authCloseBtn");
  const loginForm      = document.getElementById("loginForm");
  const registerForm   = document.getElementById("registerForm");
  const authErrorBox   = document.getElementById("authError");
  const authTabs       = document.querySelectorAll(".auth-tab");

  const openLoginBtn   = document.getElementById("openLoginBtn");
  const openRegisterBtn= document.getElementById("openRegisterBtn");

  if (!authOverlay || !authCloseBtn || !loginForm || !registerForm) {
    if (!silentIfMissing) {
      console.warn("Auth modal elements not found yet.");
    }
    return;
  }

  authModalInitialized = true;

  function openAuthModal(which) {
    authOverlay.classList.remove("hidden");
    authErrorBox.textContent = "";

    if (which === "register") {
      loginForm.classList.add("hidden");
      registerForm.classList.remove("hidden");
      setActiveTab("register");
    } else {
      loginForm.classList.remove("hidden");
      registerForm.classList.add("hidden");
      setActiveTab("login");
    }
  }

  function closeAuthModal() {
    authOverlay.classList.add("hidden");
  }

  function setActiveTab(which) {
    authTabs.forEach(tab => {
      const tabType = tab.getAttribute("data-tab");
      if (tabType === which) {
        tab.classList.add("auth-tab-active");
      } else {
        tab.classList.remove("auth-tab-active");
      }
    });
  }

  // Open buttons
  if (openLoginBtn) {
    openLoginBtn.addEventListener("click", () => openAuthModal("login"));
  }
  if (openRegisterBtn) {
    openRegisterBtn.addEventListener("click", () => openAuthModal("register"));
  }

  // Tab buttons
  authTabs.forEach(tab => {
    tab.addEventListener("click", () => {
      const tabType = tab.getAttribute("data-tab");
      openAuthModal(tabType);
    });
  });

  // Close
  authCloseBtn.addEventListener("click", closeAuthModal);
  authOverlay.addEventListener("click", (e) => {
    if (e.target === authOverlay) {
      closeAuthModal();
    }
  });

  // Helper: send JSON
  async function postJSON(url, data) {
    const response = await fetch(url, {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify(data),
    });
    return response.json();
  }

  // Handle login submit
  loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    authErrorBox.textContent = "Logging in...";

    const formData = new FormData(loginForm);
    const data = Object.fromEntries(formData.entries());

    try {
      const result = await postJSON("pages/auth_login.php", data);
      if (result.success) {
        location.reload(); // page comes back logged in
      } else {
        authErrorBox.textContent = (result.errors || ["Login failed."]).join(" ");
      }
    } catch (err) {
      console.error(err);
      authErrorBox.textContent = "Server error while logging in.";
    }
  });

  // Handle register submit
  registerForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    authErrorBox.textContent = "Creating account...";

    const formData = new FormData(registerForm);
    const data = Object.fromEntries(formData.entries());

    try {
      const result = await postJSON("pages/auth_register.php", data);
      if (result.success) {
        // User is now logged in via session
        location.reload();
      } else {
        authErrorBox.textContent = (result.errors || ["Registration failed."]).join(" ");
      }
    } catch (err) {
      console.error(err);
      authErrorBox.textContent = "Server error while registering.";
    }
  });
}

// Run once on normal pages where navbar is already in HTML
document.addEventListener("DOMContentLoaded", () => {
  initAuthModal({ silentIfMissing: true });
});

// Make it available to other scripts if needed.
window.initAuthModal = initAuthModal;
