document.addEventListener("DOMContentLoaded", () => {
  const header = document.querySelector(".page-header");
  window.addEventListener("scroll", () => {
    header.classList.toggle("scrolled", window.scrollY > 10);
  });
});
