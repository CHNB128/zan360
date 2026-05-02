const navToggle = document.querySelector(".site-header__toggle");
const siteHeader = document.querySelector(".site-header");

if (navToggle && siteHeader) {
  navToggle.addEventListener("click", () => {
    const isOpen = siteHeader.classList.toggle("site-header--open");
    navToggle.setAttribute("aria-expanded", String(isOpen));
  });
}

