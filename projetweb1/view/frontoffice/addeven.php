<?php
require_once __DIR__ . '/../../controller/evenController.php';

require_once __DIR__ . '/../../model/evenmodel.php';


$evenementcontroller = new EvenementController();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajout']) ) {

    $nom = $_POST['Nom'];
    $date = $_POST['Date'];
    $lieu = $_POST['lieu'];
    $capacite = $_POST['Capacite'];

    // Créer un nouvel objet Evenement avec les données du formulaire
    $evenement = new Evenement(null, $nom, $date, $lieu, $capacite);

    // Ajouter l'événement à la base de données
    $evenementcontroller->addEvenement($evenement);

    // Redirection après l'ajout
    header("Location: evenementListe.php"); // Remplacez par la page de votre choix
    exit();
}




?>