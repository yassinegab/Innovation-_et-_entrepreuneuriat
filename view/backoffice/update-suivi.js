document.addEventListener('DOMContentLoaded', function() {

    const form = document.getElementById('update-suivi-form');
    const etatInput = document.getElementById('etat');
    const commentaireInput = document.getElementById('commentaire');
    const dateSuiviInput = document.getElementById('date_suivi');
    const tauxAvancementInput = document.getElementById('taux_avancement');
  
    const etatError = document.getElementById('etat-error');
    const commentaireError = document.getElementById('commentaire-error');
    const dateSuiviError = document.getElementById('date_suivi-error');
    const tauxAvancementError = document.getElementById('taux_avancement-error');
  
    form.addEventListener('submit', function(event) {
      let valid = true;
  
      // Clear old errors
      etatError.textContent = '';
      commentaireError.textContent = '';
      dateSuiviError.textContent = '';
      tauxAvancementError.textContent = '';
  
      // Validate Etat
      if (etatInput.value.trim() === '') {
        valid = false;
        etatError.textContent = "L'état ne peut pas être vide.";
      }
  
      // Validate Commentaire
      if (commentaireInput.value.trim() === '') {
        valid = false;
        commentaireError.textContent = "Le commentaire ne peut pas être vide.";
      }
  
      // Validate Date
      if (dateSuiviInput.value.trim() === '') {
        valid = false;
        dateSuiviError.textContent = "La date de suivi ne peut pas être vide.";
      }
  
      // Validate Taux d'avancement
      const taux = parseInt(tauxAvancementInput.value);
      if (isNaN(taux) || taux < 0 || taux > 100) {
        valid = false;
        tauxAvancementError.textContent = "Le taux d'avancement doit être entre 0 et 100.";
      }
  
      if (!valid) {
        event.preventDefault(); // Prevent form sending if invalid
      }
    });
  
  });
  