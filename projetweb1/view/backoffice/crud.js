<script>
function validateForm() {
    // Récupération des valeurs du formulaire
    var nom = document.getElementById("nom").value.trim();
    var date = document.getElementById("date").value;
    var lieu = document.getElementById("lieu").value;
    var capacite = document.getElementById("capacite").value;

    // accepte uniquement lettres + espaces (pas de chiffres ni symboles)
    var pattern = /^[\p{L}\s]+$/u;

    // Vérifie que le nom n'est pas vide
    if (nom === "") {
        alert("Le nom est obligatoire.");
        return false;
    }

    // Vérifie que le nom contient uniquement des lettres (avec accents) et des espaces
    if (!pattern.test(nom)) {
        alert("Le nom ne doit contenir que des lettres et des espaces (pas de chiffres ni de symboles).");
        return false;
    }

    // Vérification de la date
    if (date === "") {
        alert("La date de l'événement est obligatoire.");
        return false;
    }

    // Vérification du lieu
    if (lieu === "") {
        alert("Le lieu de l'événement est obligatoire.");
        return false;
    }

    // Vérification de la capacité
    if (capacite === "" || isNaN(capacite) || capacite <= 0) {
        alert("La capacité doit être un nombre valide et supérieur à zéro.");
        return false;
    }

    return true; // Tout est bon
}
</script>
