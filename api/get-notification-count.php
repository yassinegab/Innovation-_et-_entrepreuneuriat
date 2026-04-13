<?php
// Start session and enable error reporting
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON content type header
header('Content-Type: application/json');

try {
    // Database connection
    $db = new PDO('mysql:host=localhost;dbname=mydbname', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get user ID from session or use default for testing
    $id_utilisateur = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
    
    // Query to count unread notifications
    $query = "SELECT COUNT(*) as count FROM notifications WHERE id_utilisateur = :id_utilisateur AND lu = 0";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Return notification count
    echo json_encode([
        'success' => true,
        'count' => (int)$result['count']
    ]);
    
} catch (PDOException $e) {
    // Return error message
    echo json_encode([
        'success' => false,
        'error' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
}
?>
