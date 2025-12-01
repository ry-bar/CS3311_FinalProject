document.addEventListener("DOMContentLoaded", () => {
  const userBtn = document.getElementById("username_button");
  const dropdownMenu = document.querySelector(".dropdown_menu");

  if (!userBtn || !dropdownMenu) {
    return;
  }

  const closeMenu = () => {
    dropdownMenu.classList.remove("show");
    userBtn.setAttribute("aria-expanded", "false");
  };

  const openMenu = () => {
    dropdownMenu.classList.add("show");
    userBtn.setAttribute("aria-expanded", "true");
  };

  const toggleMenu = () => {
    if (dropdownMenu.classList.contains("show")) {
      closeMenu();
    } else {
      openMenu();
    }
  };

  userBtn.addEventListener("click", (event) => {
    event.stopPropagation();
    toggleMenu();
  });

  dropdownMenu.addEventListener("click", (event) => {
    event.stopPropagation();
  });

  document.addEventListener("click", (event) => {
    const target = event.target;
    if (target !== userBtn && !dropdownMenu.contains(target)) {
      closeMenu();
    }
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      closeMenu();
      userBtn.focus();
    }
  });

  dropdownMenu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      closeMenu();
    });
  });
});
