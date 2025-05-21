<?php
// Include your database connection file or the necessary functions

include('../../controller/ConsultationController.php');


// Add response
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_reponse'])) {
    if (!empty($_POST['contenu']) && !empty($_POST['id_consultation'])) {
        $contenu = htmlspecialchars(trim($_POST['contenu']));
        $id_consultation = intval($_POST['id_consultation']);
        $date_reponse = date('Y-m-d H:i:s');
        $id_utilisateur = 1; // ID de l'administrateur (à adapter selon votre système)

        // Créer une nouvelle réponse
        $reponse = new Reponse(null, $contenu, $date_reponse, $id_consultation, $id_utilisateur);
        
        // Ajouter la réponse
        $reponseC->ajouterReponse($reponse);
        
        // Mettre à jour le statut de la consultation si nécessaire
        $consultation = $consultationC->getConsultationById($id_consultation);
        if ($consultation['statut'] === 'pending') {
            $reponseC->updateConsultationStatus($id_consultation, 'in-progress');
        }
        
        // Rediriger pour éviter la soumission multiple du formulaire
        header("Location: consultationListb.php?response_success=true");
        exit;
    } else {
        $error_message = "Le contenu de la réponse ne peut pas être vide.";
    }
}
?>
    <!-- Modal pour ajouter une nouvelle consultation -->
    <div id="new-consultation-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Nouvelle consultation</h2>
                <button class="close-modal" onclick="document.getElementById('new-consultation-modal').style.display='none'">&times;</button>
            </div>
            <div class="modal-body">
                <form id="consultation-form" method="POST" action="consultationListb.php">
                    <div class="form-group">
                        <label for="consultation-subject">Sujet</label>
                        <input type="text" id="consultation-subject" name="titre" required>
                    </div>
                    <div class="form-group">
                        <label for="consultation-type">Type</label>
                        <select id="consultation-type" name="type" required>
                            <option value="">Sélectionnez un type</option>
                            <option value="financing">Financement</option>
                            <option value="legal">Juridique</option>
                            <option value="marketing">Marketing</option>
                            <option value="technical">Technique</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="consultation-description">Description</label>
                        <textarea id="consultation-description" name="description" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="consultation-date">Date</label>
                        <input type="date" id="consultation-date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="consultation-id_utilisateur">ID Utilisateur</label>
                        <input type="text" id="consultation-id_utilisateur" name="id_utilisateur" required>
                    </div>
                    <div class="form-group">
                        <label for="consultation-status">Statut</label>
                        <select id="consultation-status" name="statut" required>
                            <option value="pending">En attente</option>
                            <option value="in-progress">En cours</option>
                            <option value="completed">Terminée</option>
                            <option value="cancelled">Annulée</option>
                            
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="secondary-btn" type="button" onclick="document.getElementById('new-consultation-modal').style.display='none'">Annuler</button>
                        <button class="primary-btn" type="submit">Soumettre</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
