<?php
// Ce fichier est utilisé pour sauvegarder l'évaluation lorsqu'une consultation est marquée comme complétée

// Vérifier si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Récupérer les données de l'évaluation
$id_consultation = isset($_POST['consultation_id']) ? intval($_POST['consultation_id']) : 0;
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

// Valider les données
if ($id_consultation <= 0 || $rating <= 0 || $rating > 5) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Données d\'évaluation invalides']);
    exit;
}

try {
    // Connexion à la base de données
     $db = new PDO('mysql:host=localhost;dbname=mydbname', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si une évaluation existe déjà pour cette consultation
    $checkQuery = "SELECT id FROM consultation_evaluations WHERE id_consultation = :id_consultation";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':id_consultation', $id_consultation);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() > 0) {
        // Mettre à jour l'évaluation existante
        $query = "UPDATE consultation_evaluations 
                  SET rating = :rating, comment = :comment, date_evaluation = NOW() 
                  WHERE id_consultation = :id_consultation";
    } else {
        // Insérer une nouvelle évaluation
        $query = "INSERT INTO consultation_evaluations 
                  (id_consultation, rating, comment, date_evaluation) 
                  VALUES (:id_consultation, :rating, :comment, NOW())";
    }
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_consultation', $id_consultation);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);
    
    if ($stmt->execute()) {
        // Mettre à jour le statut de la consultation à "completed"
        $updateQuery = "UPDATE consultations SET statut = 'completed' WHERE id_consultation = :id_consultation";
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bindParam(':id_consultation', $id_consultation);
        $updateStmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Évaluation enregistrée avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement de l\'évaluation']);
    }
    
} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()]);
}
?>
