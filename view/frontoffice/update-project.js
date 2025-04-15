document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("update-project-form");
    const submitBtn = document.getElementById("submit-update-btn");

    submitBtn.addEventListener("click", function (e) {
        e.preventDefault();

        
        const nom = document.getElementById("project-name").value.trim();
        const desc = document.getElementById("project-description").value.trim();
        const domaine = document.getElementById("project-domain").value.trim();
        const besoin = document.getElementById("project-besoin").value.trim();

        let erreur = "";

        
        if (!nom || nom.length > 15) {
            erreur += "- Le nom du projet est obligatoire et doit avoir max 15 lettres.\n";
        }
        if (!desc || desc.length > 150) {
            erreur += "- La description est obligatoire et ne doit pas dépasser 150 lettres.\n";
        }
        if (!domaine || domaine.length > 10) {
            erreur += "- Le domaine est obligatoire et max 10 lettres.\n";
        }
        if (!besoin || besoin.length > 10) {
            erreur += "- Le besoin est obligatoire et max 10 lettres.\n";
        }

        if (erreur !== "") {
            alert("Corrigez les erreurs suivantes :\n\n" + erreur);
        } else {
            form.submit(); // Envoyer si tout est valide
        }
    });
});
