<?php
// Fichier pour ajouter une notification de test
// Activer l'affichage des erreurs pour le débogage
session_start();
$id_utilisateur=$_SESSION['user_id'];
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Définir les en-têtes pour JSON
header('Content-Type: application/json');

try {
    // Connexion à la base de données
    $db = new PDO('mysql:host=localhost;dbname=mydbname', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Récupérer l'ID de l'utilisateur (à adapter selon votre système)
    //$id_utilisateur = isset($_POST['id_utilisateur']) ? $_POST['id_utilisateur'] : 1;
    $id_consultation = isset($_POST['id_consultation']) ? $_POST['id_consultation'] : 1;
    $message = isset($_POST['message']) ? $_POST['message'] : 'Nouvelle réponse à votre consultation';
    $type = isset($_POST['type']) ? $_POST['type'] : 'reponse';
    
    // Requête pour ajouter une notification
    $query = "INSERT INTO notifications (id_utilisateur, id_consultation, message, type, date_notification, lu) 
              VALUES (:id_utilisateur, :id_consultation, :message, :type, NOW(), 0)";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur);
    $stmt->bindParam(':id_consultation', $id_consultation);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':type', $type);
    $stmt->execute();
    
    // Retourner le succès
    echo json_encode([
        'success' => true,
        'message' => 'Notification ajoutée avec succès.'
    ]);
    
} catch (PDOException $e) {
    // En cas d'erreur, retourner un message d'erreur
    echo json_encode([
        'success' => false,
        'error' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
}
?>
