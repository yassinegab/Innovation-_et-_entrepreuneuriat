// Variable Declaration
const loginBtn = document.querySelector("#login");
const registerBtn = document.querySelector("#register");
const loginForm = document.querySelector(".login-form");
const registerForm = document.querySelector(".register-form");
const forget = document.querySelector("#forget");
const forgetform = document.querySelector(".forget-form");

// Login button function
loginBtn.addEventListener("click", () => {
  loginBtn.style.backgroundColor = "#f7d84e";
  loginBtn.style.color = "rgb(29, 30, 35)";
  registerBtn.style.backgroundColor = "rgba(255, 255, 255, 0.1)";
  registerBtn.style.color = "#ffffff";
  loginForm.style.left = "50%";
  registerForm.style.left = "-50%";
  loginForm.style.opacity = 1;
  registerForm.style.opacity = 0;
  document.querySelector(".col-1").style.borderRadius = "0 30% 20% 0";
});

registerBtn.addEventListener("click", () => {
  loginBtn.style.backgroundColor = "rgba(255, 255, 255, 0.1)";
  loginBtn.style.color = "#ffffff";
  registerBtn.style.backgroundColor = "#f7d84e";
  registerBtn.style.color = "rgb(29, 30, 35)";
  loginForm.style.left = "150%";
  registerForm.style.left = "50%";
  loginForm.style.opacity = 0;
  registerForm.style.opacity = 1;
  document.querySelector(".col-1").style.borderRadius = "0 20% 30% 0";
  forgetform.style.display='none';
});
forget.addEventListener("click", () => {
  loginBtn.style.backgroundColor = "rgba(255, 255, 255, 0.1)";
  loginBtn.style.color = "#ffffff";
  registerBtn.style.backgroundColor = "rgba(255, 255, 255, 0.1)";
  registerBtn.style.color = "#ffffff";

  loginForm.style.left = "150%";
  loginForm.style.opacity = 0;

  forgetform.style.left = "50%";
  forgetform.style.opacity = 1;

  document.querySelector(".col-1").style.borderRadius = "0 20% 30% 0";
});
