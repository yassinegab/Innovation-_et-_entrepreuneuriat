<?php
// Start session and enable error reporting
session_start();
$id_utilisateur=$_SESSION['user_id'];
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON content type header
header('Content-Type: application/json');

try {
    // Database connection
    $db = new PDO('mysql:host=localhost;dbname=mydbname', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get user ID from session or use default for testing
   // $id_utilisateur = isset($_SESSION['id_utilisateur']) ? $_SESSION['id_utilisateur'] : 1;
    
    // Query to mark all notifications as read
    $query = "UPDATE notifications SET lu = 1 WHERE id_utilisateur = :id_utilisateur";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt->execute();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Toutes les notifications ont été marquées comme lues.'
    ]);
    
} catch (PDOException $e) {
    // Return error message
    echo json_encode([
        'success' => false,
        'error' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
}
?>
