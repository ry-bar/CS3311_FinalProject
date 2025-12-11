document.addEventListener("DOMContentLoaded", () => {
  const lightbox = document.querySelector(".panel-lightbox");
  if (!lightbox) return;

  const lightboxImage = lightbox.querySelector(".lightbox-image");
  const lightboxCaption = lightbox.querySelector(".lightbox-caption");
  const closeBtn = lightbox.querySelector(".lightbox-close");

  const openLightbox = (img) => {
    lightboxImage.src = img.getAttribute("src");
    lightboxImage.alt = img.getAttribute("alt") ?? "";

    const panelNote = img.closest(".panel")?.querySelector(".panel-note");
    lightboxCaption.textContent = panelNote ? panelNote.textContent.trim() : img.alt;

    lightbox.classList.add("open");
    lightbox.setAttribute("aria-hidden", "false");
    document.body.classList.add("lightbox-open");
    closeBtn.focus();
  };

  const closeLightbox = () => {
    lightbox.classList.remove("open");
    lightbox.setAttribute("aria-hidden", "true");
    document.body.classList.remove("lightbox-open");
    lightboxImage.removeAttribute("src");
  };

  document.querySelectorAll(".panel img").forEach((img) => {
    img.style.cursor = "zoom-in";
    img.setAttribute("tabindex", "0");
    img.setAttribute("role", "button");
    img.setAttribute("aria-label", "View panel in full size");

    const activate = () => openLightbox(img);

    img.addEventListener("click", activate);
    img.addEventListener("keypress", (e) => {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        activate();
      }
    });
  });

  closeBtn.addEventListener("click", closeLightbox);

  lightbox.addEventListener("click", (e) => {
    if (e.target === lightbox) {
      closeLightbox();
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && lightbox.classList.contains("open")) {
      closeLightbox();
    }
  });
});
