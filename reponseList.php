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


?>


<div class="consultation-details-wrapper">
    <div class="consultation-header">
        
        <div class="consultation-title-section">
            <h2><?= htmlspecialchars($consultation['titre']) ?></h2>
            <div class="consultation-meta">
            <span class="consultation-status"><?= htmlspecialchars($consultation['statut']) ?></span>

                <span class="consultation-type"><?= htmlspecialchars($consultation['type']) ?></span>
                <span class="consultation-date"><?= htmlspecialchars($consultation['date_consultation']) ?></span>
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
                            <span class="author-name"><?php echo "mr.   " . htmlspecialchars($id_utilisateur); ?></span>
                                <span class="response-date">Répondu le <?= htmlspecialchars($reponse->getdate_reponse()) ?></span>
                            </div>
                        </div>
                        <div class="response-content">
                            <p><?= nl2br(htmlspecialchars($reponse->getcontenu())) ?></p>
                        </div>
                    </div>
                    <div class="feedback-section">
                                <h4>Évaluation</h4>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <p class="feedback-comment">Très bons conseils, merci beaucoup !</p>
                            </div>
                        </div>
                    </div>
                   
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="modal-footer">
                        <button class="secondary-btn">Fermer</button>
                        <button class="primary-btn">Nouvelle question</button>
                    </div>
        </div>
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


/* Badge de statut orange */
.consultation-status {
    background-color: var(--accent-color);
    color: var(--primary-color);
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
}

body {
    background-color: var(--primary-color);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    color: var(--text-primary);
}

.consultation-details-wrapper {
    max-width: 800px;
    margin: 30px auto;
    background: var(--bg-light);
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    overflow: hidden;
}

.consultation-header {
    background: var(--bg-lighter);
    color: var(--text-primary);
    padding: 20px;
}

.consultation-header h1 {
    font-size: 22px;
    margin: 0;
    font-weight: 500;
}

.consultation-title-section {
    margin-top: 15px;
}

.consultation-title-section h2 {
    font-size: 20px;
    color: var(--text-primary);
    margin: 0;
}

.consultation-meta {
    display: flex;
    gap: 10px;
    margin-top: 8px;
    font-size: 13px;
    color: var(--text-muted);
}

.consultation-type {
    color: var(--accent-color);
    font-weight: bold;
}

.consultation-date {
    color: var(--text-muted);
}

.consultation-content {
    padding: 20px;
}

.description-section {
    background: var(--bg-lighter);
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 25px;
}

.description-section h3 {
    font-size: 16px;
    margin-bottom: 10px;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 5px;
    color: var(--text-secondary);
}

.responses-section h3 {
    color: var(--text-secondary);
    font-size: 16px;
    margin-bottom: 15px;
}

.no-responses {
    background-color: var(--bg-lighter);
    text-align: center;
    color: var(--text-muted);
    font-style: italic;
    padding: 20px;
    border-radius: 6px;
}

.response-card {
    background: var(--bg-lighter);
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.3);
}

.response-header {
    margin-bottom: 10px;
}

.response-author {
    display: flex;
    flex-direction: column;
}

.author-name {
    font-weight: 600;
    color: var(--text-primary);
}

.response-date {
    font-size: 12px;
    color: var(--text-muted);
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
</style>
