const addBtn    = document.getElementById('addUserBtn');
    const newRow    = document.getElementById('newUserRow');
    const cancelBtn = document.getElementById('cancelBtn');
    function confirmDelete(userId) {
    if (confirm("Are you sure you want to delete this user?")) {
        document.getElementById("myInput").value = userId;
    }
}
    
    addBtn.addEventListener('click', () => {
      newRow.classList.remove('hidden');
      
      newRow.scrollIntoView({ behavior: 'smooth' });
    });
    cancelBtn.addEventListener('click', () => {
      newRow.classList.add('hidden');
      
      newRow.querySelectorAll('input').forEach(i => i.value = '');
    });

    
    document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registerForm");
    const submitButton = document.getElementById("addButton");
    const formError = document.getElementById("formError");

    const inputs = [
      document.getElementById("firstName"),
      document.getElementById("lastName"),
      document.getElementById("email"),
      document.getElementById("password"),
      document.getElementById("day"),
      document.getElementById("month"),
      document.getElementById("year"),
    ];

    function validateForm() {
      let isValid = true;
      formError.textContent = ""; 

      for (const input of inputs) {
        if (!input.value) {
          isValid = false;
          break;
        }
      }

      submitButton.disabled = !isValid;

      if (!isValid) {
        formError.textContent = "Please fill in all fields.";
      }
    }

    
    for (const input of inputs) {
      input.addEventListener("input", validateForm);
      input.addEventListener("change", validateForm); 
    }

    
    validateForm();
  });