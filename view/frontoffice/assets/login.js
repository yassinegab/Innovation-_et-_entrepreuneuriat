const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const loginBtn = document.getElementById('loginButton');
    const errorDisplay = document.getElementById('loginError');

    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;

    function validateForm() {
      const email = emailInput.value.trim();
      const password = passwordInput.value;

      if (!emailRegex.test(email)) {
        errorDisplay.textContent = "Adresse e-mail invalide.";
        loginBtn.disabled = true;
        return;
      }

      if (!passwordRegex.test(password)) {
        errorDisplay.textContent = "Mot de passe invalide. Min 8 caractères, un chiffre, un caractère spécial.";
        loginBtn.disabled = true;
        return;
      }

      errorDisplay.textContent = "";
      loginBtn.disabled = false;
    }

    emailInput.addEventListener('input', validateForm);
    passwordInput.addEventListener('input', validateForm);