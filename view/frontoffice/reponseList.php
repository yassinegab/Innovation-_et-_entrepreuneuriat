<?php

// To these:
require_once('../../controller/reponseController.php');
require_once('../../controller/ConsultationController.php');

$reponseController = new reponseController();
$consultationController = new ConsultationController();

if (!isset($_GET['id_consultation'])) {
    die("ID de consultation non spécifié");
}

$id_consultation = (int)$_GET['id_consultation'];
$consultation = $consultationController->getConsultationById($id_consultation);
$reponses = $reponseController->getReponsesByConsultationId($id_consultation);

if (!$consultation) {
    die("Consultation introuvable");
}

if ($consultation) {
    $id_utilisateur = $consultation['id_utilisateur'];
} else {
    $id_utilisateur = null;  // Si aucune consultation n'est trouvée
}

// Vérifier si la consultation est complétée
$isCompleted = isset($consultation['statut']) && strtolower($consultation['statut']) === 'completed';

// Si complétée, vérifier s'il y a une évaluation à afficher
if ($isCompleted) {
    // Récupérer les données d'évaluation de la base de données si elles existent
    try {
        $db = new PDO('mysql:host=localhost;dbname=projet', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Utiliser le nom de table correct (consultation_evaluations au lieu de consultation_reviews)
        $query = "SELECT rating, comment FROM consultation_evaluations WHERE id_consultation = :id_consultation LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_consultation', $id_consultation);
        $stmt->execute();
        
        $evaluation = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Variable pour stocker le HTML de l'évaluation
        $evaluationHtml = '';
        
        if ($evaluation) {
            // Préparer l'affichage de l'évaluation
            $evaluationHtml = '<div class="evaluation-display">';
            $evaluationHtml .= '<h3>Évaluation de la consultation</h3>';
            
            // Afficher les étoiles en fonction de la note
            $evaluationHtml .= '<div class="rating-display">';
            $rating = (int)$evaluation['rating'];
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rating) {
                    $evaluationHtml .= '<span class="star filled">★</span>'; // Étoile pleine
                } else {
                    $evaluationHtml .= '<span class="star empty">☆</span>'; // Étoile vide
                }
            }
            $evaluationHtml .= '</div>';
            
            // Afficher le commentaire s'il existe
            if (!empty($evaluation['comment'])) {
                $evaluationHtml .= '<div class="comment-display">';
                $evaluationHtml .= '<h4>Commentaire:</h4>';
                $evaluationHtml .= '<p>' . htmlspecialchars($evaluation['comment']) . '</p>';
                $evaluationHtml .= '</div>';
            }
            
            $evaluationHtml .= '</div>';
        } else {
            // Aucune évaluation trouvée, mais la consultation est complétée
            $evaluationHtml = '<div class="no-evaluation">';
            $evaluationHtml .= '<h3>Consultation terminée</h3>';
            $evaluationHtml .= '<p>Cette consultation a été marquée comme terminée sans évaluation.</p>';
            $evaluationHtml .= '</div>';
        }
    } catch (PDOException $e) {
        // Gérer l'erreur silencieusement
        $evaluationHtml = '<div class="evaluation-error">';
        $evaluationHtml .= '<p>Impossible de charger l\'évaluation.</p>';
        $evaluationHtml .= '</div>';
    }
}
?>

<div class="consultation-details-wrapper">
    <div class="consultation-header">
        <div class="consultation-title-section">
            <h2><?= htmlspecialchars($consultation['titre']) ?></h2>
            <div class="consultation-meta">
                <span class="consultation-status <?= strtolower(htmlspecialchars($consultation['statut'])); ?>"><?= htmlspecialchars($consultation['statut']) ?></span>
                <span class="consultation-type"><?= htmlspecialchars($consultation['type']) ?></span>
                <span class="consultation-date"><i class="far fa-calendar-alt"></i> <?= htmlspecialchars($consultation['date_consultation']) ?></span>
            </div>
        </div>
    </div>

    <div class="consultation-content">
        <div class="description-section">
            <h3>Description</h3>
            <p><?= nl2br(htmlspecialchars($consultation['description'])) ?></p>
        </div>

        <div class="responses-section">
            <h3>Réponses</h3>
            <?php if (empty($reponses)) : ?>
                <div class="no-responses">Aucune réponse disponible pour cette consultation.</div>
            <?php else : ?>
                <?php foreach ($reponses as $reponse) : ?>
                    <div class="response-card">
                        <div class="response-header">
                            <div class="response-author">
                                <span class="author-name"><?php echo "mr. " . htmlspecialchars($id_utilisateur); ?></span>
                                <span class="response-date">Répondu le <?= htmlspecialchars($reponse->getdate_reponse()) ?></span>
                            </div>
                        </div>
                        <div class="response-content">
                            <p><?= nl2br(htmlspecialchars($reponse->getcontenu())) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($isCompleted && isset($evaluationHtml)): ?>
        <!-- Afficher l'évaluation pour les consultations complétées -->
        <div class="evaluation-section">
            <?= $evaluationHtml ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
:root {
    --primary-color: rgb(29, 30, 35);
    --secondary-color: rgb(255, 255, 255);
    --accent-color: rgb(227, 196, 58);
    --text-primary: rgb(255, 255, 255);
    --text-secondary: rgba(255, 255, 255, 0.7);
    --text-muted: rgba(255, 255, 255, 0.5);
    --border-color: rgba(255, 255, 255, 0.1);
    --bg-light: rgb(36, 37, 43);
    --bg-lighter: rgb(45, 46, 54);
}

body {
    background-color: var(--primary-color);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    color: var(--text-primary);
}

.consultation-details-wrapper {
    max-width: 800px;
    margin: 0 auto;
    background: var(--bg-light);
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    overflow: hidden;
}

.consultation-header {
    background: var(--bg-lighter);
    color: var(--text-primary);
    padding: 20px;
    border-bottom: 1px solid rgba(227, 196, 58, 0.2);
}

.consultation-header h1 {
    font-size: 22px;
    margin: 0;
    font-weight: 500;
}

.consultation-title-section {
    margin-top: 5px;
}

.consultation-title-section h2 {
    font-size: 20px;
    color: var(--text-primary);
    margin: 0 0 10px 0;
}

.consultation-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 8px;
    font-size: 13px;
    color: var(--text-muted);
    align-items: center;
}

.consultation-date {
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 5px;
}

.consultation-date i {
    color: var(--accent-color);
    font-size: 14px;
}

.consultation-content {
    padding: 20px;
}

.description-section {
    background: var(--bg-lighter);
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-left: 3px solid var(--accent-color);
}

.description-section h3 {
    font-size: 16px;
    margin-bottom: 10px;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 8px;
    color: var(--accent-color);
}

.description-section p {
    line-height: 1.6;
    margin: 0;
    color: white;
}

.responses-section h3 {
    color: var(--accent-color);
    font-size: 16px;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--border-color);
}

.no-responses {
    background-color: var(--bg-lighter);
    text-align: center;
    color: var(--text-muted);
    font-style: italic;
    padding: 20px;
    border-radius: 6px;
    border: 1px dashed rgba(255, 255, 255, 0.1);
}

.response-card {
    background: var(--bg-lighter);
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-left: 3px solid rgba(255, 255, 255, 0.2);
}

.response-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.response-header {
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.response-author {
    display: flex;
    flex-direction: column;
}

.author-name {
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 5px;
}

.author-name::before {
    content: '';
    display: inline-block;
    width: 8px;
    height: 8px;
    background-color: var(--accent-color);
    border-radius: 50%;
}

.response-date {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 3px;
}

.response-content p {
    margin: 0;
    line-height: 1.6;
    color: var(--text-secondary);
}

.response-content ul {
    padding-left: 20px;
    margin: 10px 0;
}

.response-content li {
    margin-bottom: 5px;
    color: var(--text-secondary);
}

.consultation-type {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
    text-transform: capitalize;
}

/* Styles pour l'affichage de l'évaluation */
.evaluation-section {
    background: var(--bg-lighter);
    border-radius: 8px;
    padding: 15px;
    margin-top: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-left: 3px solid var(--accent-color);
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.evaluation-display h3, 
.no-evaluation h3 {
    color: var(--accent-color);
    font-size: 16px;
    margin-top: 0;
    margin-bottom: 15px;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 8px;
}

.rating-display {
    margin-bottom: 15px;
    display: flex;
    gap: 5px;
}

.star {
    font-size: 24px;
}

.star.filled {
    color: var(--accent-color);
}

.star.empty {
    color: rgba(255, 255, 255, 0.3);
}

.comment-display {
    background-color: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    padding: 15px;
    margin-top: 10px;
}

.comment-display h4 {
    margin-top: 0;
    margin-bottom: 10px;
    color: rgba(255, 255, 255, 0.8);
    font-size: 14px;
}

.comment-display p {
    margin: 0;
    line-height: 1.5;
    color: var(--text-secondary);
}

.no-evaluation, 
.evaluation-error {
    text-align: center;
    padding: 15px;
    color: var(--text-muted);
}

.evaluation-error {
    color: #ff6b6b;
}
</style>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.consultation-type').forEach(element => {
      const typeText = element.textContent.trim().toLowerCase();
      console.log("Consultation type detected:", typeText);

      if (typeText.includes('financing') || typeText.includes('financement')) {
        element.style.backgroundColor = 'rgb(207, 97, 125)';
        element.style.color = 'black';
      } else if (typeText.includes('legal') || typeText.includes('juridique')) {
        element.style.backgroundColor = 'rgb(150, 100, 202)';
        element.style.color = 'black';
      } else if (typeText.includes('marketing')) {
        element.style.backgroundColor = 'rgb(226, 178, 47)';
        element.style.color = 'black';
      } else if (typeText.includes('technical') || typeText.includes('technique')) {
        element.style.backgroundColor = 'rgb(115, 185, 223)';
        element.style.color = 'black';
      } else {
        // Default case for unknown types
        element.style.backgroundColor = 'gray';
        element.style.color = 'white';
      }
    });
  });
</script>
