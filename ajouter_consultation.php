<?php
// Include your database connection file or the necessary functions
include('../../controller/ConsultationController.php'); 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture form data
    $sujet = $_POST['sujet'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $date_consultation = $_POST['date_consultation'];
    $statut = $_POST['statut'];

    // Create an instance of the ConsultationController class (or whatever class you use to handle consultations)
    $consultationController = new ConsultationController();

    // Call the function to add the consultation to the database
    $consultationController->ajouter_Consultation($sujet, $description, $type, $date_consultation, $statut);

    // Redirect to another page after successful submission (e.g., confirmation page or consultation list)
    header("Location: confirmation.php"); // Adjust to your actual desired page
    exit();
}
?>
