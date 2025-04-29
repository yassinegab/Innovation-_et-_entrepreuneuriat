document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("create-project-form");
  
    form.addEventListener("submit", function (e) {
      const nom = document.getElementById("project-name").value.trim();
      const desc = document.getElementById("project-description").value.trim();
      const domaine = document.getElementById("project-domain").value.trim();
      const besoin = document.getElementById("project-besoin").value.trim();
      const date = document.getElementById("project-date").value.trim();
  
      const nameError = document.getElementById("project-name-error");
      const descError = document.getElementById("project-description-error");
      const domaineError = document.getElementById("project-domain-error");
      const besoinError = document.getElementById("project-besoin-error");
      const dateError = document.getElementById("project-date-error"); // nouveau pour date
  
      // Clear previous errors
      nameError.textContent = "";
      descError.textContent = "";
      domaineError.textContent = "";
      besoinError.textContent = "";
      dateError.textContent = ""; // vider ancienne erreur date
  
      let valid = true;
  
      if (!nom || nom.length > 15) {
        nameError.textContent = "Le nom du projet est obligatoire et doit avoir maximum 15 lettres.";
        valid = false;
      }
      if (!desc || desc.length > 150) {
        descError.textContent = "La description est obligatoire et doit avoir maximum 150 lettres.";
        valid = false;
      }
      if (!domaine || domaine.length > 10) {
        domaineError.textContent = "Le domaine est obligatoire et doit avoir maximum 10 lettres.";
        valid = false;
      }
      if (!besoin || besoin.length > 10) {
        besoinError.textContent = "Le besoin est obligatoire et doit avoir maximum 10 lettres.";
        valid = false;
      }
      if (!date) {
        dateError.textContent = "La date de création est obligatoire.";
        valid = false;
      }
  
      if (!valid) {
        e.preventDefault(); // empêcher l'envoi seulement si une erreur
      }
    });
  });
  