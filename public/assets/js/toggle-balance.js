const eyeOnSvg = `
<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
    xmlns="http://www.w3.org/2000/svg">
    <path d="M3 13C6.6 5 17.4 5 21 13M9 14C9 15.6569 10.3431 17 12 17C13.6569 17 15 15.6569 15 14C15 12.3431 13.6569 11 12 11C10.3431 11 9 12.3431 9 14Z"
        stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>`;

const eyeOffSvg = `
<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
    xmlns="http://www.w3.org/2000/svg">
    <path d="M3 3L21 21M10.5843 10.5875C10.2105 10.9623 10 11.4617 10 12C10 13.1046 10.8954 14 12 14C12.5383 14 13.0377 13.7895 13.4125 13.4157M17.2537 17.2581C15.6534 18.1072 13.8683 18.5909 12 18.6C6 18.6 2 12 2 12C2.96464 10.4788 4.1906 9.16451 5.60743 8.11594M9.60096 5.84161C10.3877 5.63048 11.1896 5.5237 12 5.52139C18 5.52139 22 12 22 12C21.6242 12.6214 21.1871 13.2094 20.6972 13.7558"
        stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>`;

const balanceAmount = document.getElementById("balanceAmount");
const hiddenBalance = document.getElementById("hiddenBalance");
const toggleBalance = document.getElementById("toggleBalance");
const balanceEye = document.getElementById("balanceEye");

toggleBalance.addEventListener("click", () => {
  const isVisible = !balanceAmount.classList.contains("d-none");

  balanceAmount.classList.toggle("d-none", isVisible);
  hiddenBalance.classList.toggle("d-none", !isVisible);
  balanceEye.innerHTML = isVisible ? eyeOffSvg : eyeOnSvg;
});
