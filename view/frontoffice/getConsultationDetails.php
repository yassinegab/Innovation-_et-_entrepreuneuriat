<?php
require_once '../../controller/ConsultationController.php';

if (isset($_GET['id_consultation'])) {
    $id = $_GET['id_consultation'];
    $controller = new ConsultationController();
    $consultation = $controller->getConsultationById($id);

    if ($consultation) {
        echo "<h2>" . htmlspecialchars($consultation->getTitre()) . "</h2>";
        echo "<p><strong>Description:</strong> " . htmlspecialchars($consultation->getDescription()) . "</p>";
        echo "<p><strong>Date:</strong> " . htmlspecialchars($consultation->getDateConsultation()) . "</p>";
        echo "<p><strong>Type:</strong> " . htmlspecialchars($consultation->getType()) . "</p>";
        echo "<p><strong>Statut:</strong> " . htmlspecialchars($consultation->getStatut()) . "</p>";
    } else {
        echo "<p>Consultation introuvable.</p>";
    }
}
?>
