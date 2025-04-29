document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const submitBtn = document.getElementById("submit-suivi-btn");

    submitBtn.addEventListener("click", function (e) {
        e.preventDefault(); 

        // Réinitialiser les erreurs
        document.getElementById("error-etat").textContent = "";
        document.getElementById("error-commentaire").textContent = "";
        document.getElementById("error-date").textContent = "";
        document.getElementById("error-taux").textContent = "";

        let hasError = false;

        const etat = document.getElementById("etat").value.trim();
        const commentaire = document.getElementById("commentaire").value.trim();
        const date_suivi = document.getElementById("date_suivi").value.trim();
        const taux = document.getElementById("taux_avancement").value.trim();

        // Validation par champ
        if (!etat) {
            document.getElementById("error-etat").textContent = "L'état est obligatoire.";
            hasError = true;
        }
        if (!commentaire || commentaire.length > 150) {
            document.getElementById("error-commentaire").textContent = "Commentaire obligatoire (max 150 caractères).";
            hasError = true;
        }
        if (!date_suivi) {
            document.getElementById("error-date").textContent = "La date est obligatoire.";
            hasError = true;
        }
        if (!taux || isNaN(taux) || taux < 0 || taux > 100) {
            document.getElementById("error-taux").textContent = "Le taux doit être un nombre entre 0 et 100.";
            hasError = true;
        }

        // Si tout est bon, on envoie le formulaire
        if (!hasError) {
            form.submit();
        }
    });
});
document.querySelectorAll('.progress-slider').forEach(slider => {
    slider.addEventListener('input', function() {
      const value = this.value;
      const id = this.dataset.id;
      const valueDisplay = document.getElementById('progress-value-' + id);
  
      valueDisplay.textContent = value + '%';
  
      // Remove previous classes
      this.classList.remove('low', 'medium', 'high');
  
      // Add color based on value
      if (value < 40) {
        this.classList.add('low');
      } else if (value < 70) {
        this.classList.add('medium');
      } else {
        this.classList.add('high');
      }
    });
  
    slider.addEventListener('change', function() {
      const value = this.value;
      const id = this.dataset.id;
  
      // AJAX request to update the database
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'update_progress.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.send('id_suivi=' + id + '&taux_avancement=' + value);
  
      xhr.onload = function() {
        if (xhr.status == 200) {
          console.log('Progression updated successfully!');
        } else {
          console.error('Error updating progression');
        }
      };
    });
  });
  