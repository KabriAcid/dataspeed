// Define reusable copy icon and checkmark
const copyIconSVG = `
        <svg width="24" height="24" viewBox="0 0 24 24" ...>...</svg>`;

const checkmarkSVG = `
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 6L9 17L4 12" stroke="#0f0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>`;

// Add event listener to all copy buttons
document.querySelectorAll(".copy-btn").forEach(button => {
  button.addEventListener("click", function () {
    const targetId = this.getAttribute("data-copy-target");
    const targetEl = document.getElementById(targetId);
    if (!targetEl) return;

    const text = targetEl.innerText.trim();

    navigator.clipboard
      .writeText(text)
      .then(() => {
        showToasted("Copied successfully", "success");

        const originalIcon = this.innerHTML;
        this.innerHTML = checkmarkSVG;

        setTimeout(() => {
          this.innerHTML = originalIcon;
        }, 2000);
      })
      .catch(err => {
        console.error("Copy failed: ", err);
        showToasted("Failed to copy", "error");
      });
  });
});
