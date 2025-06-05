const eyeOn = `
<svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M3 13C6.6 5 17.4 5 21 13M9 14C9 15.6569 10.3431 17 12 17C13.6569 17 15 15.6569 15 14C15 12.3431 13.6569 11 12 11C10.3431 11 9 12.3431 9 14Z" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
`;

const eyeOff = `
<svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M3 3L21 21M10.5 10.6771C10.1888 11.0297 10 11.4928 10 12C10 13.1046 10.8954 14 12 14C12.5072 14 12.9703 13.8112 13.3229 13.5M7.36185 7.56107C5.68002 8.73966 4.27894 10.4188 3 12C4.88856 14.991 8.2817 18 12 18C13.5499 18 15.0434 17.4772 16.3949 16.6508M12 6C16.0084 6 18.7015 9.1582 21 12C20.6815 12.5043 20.3203 13.0092 19.922 13.5" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
`;

document.querySelectorAll(".password-wrapper").forEach(wrapper => {
  const input = wrapper.querySelector("input");
  const toggle = wrapper.querySelector(".eye-button");
  const icon = toggle.querySelector(".eye-icon");

  icon.innerHTML = eyeOn;

  input.addEventListener("input", () => {
    if (input.value.length > 0) {
      toggle.classList.add("visible");
    } else {
      toggle.classList.remove("visible");
      input.type = "password";
      icon.innerHTML = eyeOn;
    }
  });

  toggle.addEventListener("click", () => {
    const isPassword = input.type === "password";
    input.type = isPassword ? "text" : "password";
    icon.innerHTML = isPassword ? eyeOff : eyeOn; 
  });
});
