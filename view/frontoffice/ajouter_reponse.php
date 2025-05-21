<?php
// Inclure le contrôleur des réponses
include('../../controller/ReponseController.php');

// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturer les données du formulaire
    $contenu = $_POST['contenu'];
    $date_reponse = $_POST['date_reponse'];
    $id_consultation = $_POST['id_consultation'];
    $id_utilisateur = $_POST['id_utilisateur']; // Optionnel : tu peux le récupérer de la session si tu veux

    // Générer un ID unique pour la réponse (ou le laisser auto-incrémenté si c’est défini dans ta base)
    $id_reponse = uniqid();

    // Créer une instance du contrôleur
    $reponseController = new ReponseController();

    // Ajouter la réponse dans la base de données
    $reponseController->ajouterReponse($id_reponse, $contenu, $date_reponse, $id_consultation, $id_utilisateur);

    // Rediriger après soumission
    header("Location: confirmation.php"); // Change selon ta page
    exit();
}
?>
<form action="ajouter_reponse.php" method="POST">
    <label for="contenu">Contenu de la réponse :</label><br>
    <textarea name="contenu" required></textarea><br><br>

    <label for="date_reponse">Date de la réponse :</label><br>
    <input type="date" name="date_reponse" required><br><br>

    <label for="id_consultation">ID Consultation :</label><br>
    <input type="text" name="id_consultation" required><br><br>

    <label for="id_utilisateur">ID Utilisateur :</label><br>
    <input type="text" name="id_utilisateur" required><br><br>

    <input type="submit" value="Ajouter la réponse">
</form>
