window.onload = function () {
    const formElements = ['firstName', 'lastName', 'email', 'password', 'day', 'month', 'year'];
    formElements.forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        el.addEventListener('input', validateForm);
        el.addEventListener('change', validateForm);
      }
    });
    validateForm();
  };

  function validateForm() {
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const day = document.getElementById('day').value;
    const month = document.getElementById('month').value;
    const year = document.getElementById('year').value;
    const errorDisplay = document.getElementById('formError');
    const submitBtn = document.querySelector('button[type="submit"]');

    const nameRegex = /^[a-zA-Z]{2,}$/;
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;

    let errorMessage = '';

    if (!nameRegex.test(firstName)) {
      errorMessage = 'First name must be at least 2 letters.';
    } else if (!nameRegex.test(lastName)) {
      errorMessage = 'Last name must be at least 2 letters.';
    } else if (!emailRegex.test(email)) {
      errorMessage = 'Please enter a valid email address.';
    } else if (!passwordRegex.test(password)) {
      errorMessage = 'Password must be 8+ chars, include 1 number and 1 special char.';
    } else if (!day || !month || !year) {
      errorMessage = 'Please select a complete birthdate.';
    }

    if (errorMessage) {
      errorDisplay.textContent = errorMessage;
      submitBtn.disabled = true;
    } else {
      errorDisplay.textContent = '';
      submitBtn.disabled = false;
    }
  }