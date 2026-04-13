<?php
require_once '../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_consultation = $_POST['id_consultation'];
    $commentaire = $_POST['commentaire'] ?? '';

    try {
        // Ajouter l’évaluation si nécessaire (à adapter si tu as une table 'evaluation')
        // $stmt = $pdo->prepare("INSERT INTO evaluation (id_consultation, commentaire) VALUES (?, ?)");
        // $stmt->execute([$id_consultation, $commentaire]);

        // Mettre à jour le statut à "Completed"
        $update = $pdo->prepare("UPDATE consultation SET statut = 'Completed' WHERE id_consultation = ?");
        $update->execute([$id_consultation]);

        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
?>
