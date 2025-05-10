<?php
include_once('../../config.php');
include '../../model/inscmodel.php';
// Au début de votre fichier insccontroller.php
require_once __DIR__ . '/../lib/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../lib/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/../mail_config.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class InscriptionController {
    
    public function addInscription($inscription) {
        try {
            // Vérifier si l'événement existe et s'il reste de la place
            $db = config::getConnexion();
            $query = $db->prepare("SELECT capacite FROM evenement WHERE id_ev = ?");
            $query->execute([$inscription->getIdEvenement()]);
            $evenement = $query->fetch();
            
            if (!$evenement) {
                error_log("Événement non trouvé: " . $inscription->getIdEvenement());
                return false;
            }
            
            // Vérifier si l'utilisateur est déjà inscrit à cet événement
            $query = $db->prepare("SELECT COUNT(*) FROM inscription WHERE id_eve = ? AND id_uti = ?");
            $query->execute([$inscription->getIdEvenement(), $inscription->getIdUtilisateur()]);
            $dejaInscrit = (int)$query->fetchColumn();
            
            if ($dejaInscrit > 0) {
                error_log("Utilisateur déjà inscrit: id_uti=" . $inscription->getIdUtilisateur() . ", id_eve=" . $inscription->getIdEvenement());
                return "already_registered";
            }
            
            // Vérifier s'il reste de la place
            $query = $db->prepare("SELECT COUNT(*) FROM inscription WHERE id_eve = ?");
            $query->execute([$inscription->getIdEvenement()]);
            $nbInscrits = (int)$query->fetchColumn();
            
            if ($nbInscrits >= $evenement['capacite']) {
                error_log("Événement complet: " . $inscription->getIdEvenement());
                return "full";
            }
            
            // Insérer l'inscription
            $query = $db->prepare("INSERT INTO inscription (id_eve, id_uti, statut, email_utilisateur) VALUES (?, ?, ?, ?)");
            $result = $query->execute([
                $inscription->getIdEvenement(),
                $inscription->getIdUtilisateur(),
                $inscription->getStatut(),
                $inscription->getMail()
            ]);
            
            error_log("Inscription ajoutée: id_eve=" . $inscription->getIdEvenement() . 
                      ", id_uti=" . $inscription->getIdUtilisateur() . 
                      ", email=" . $inscription->getMail());
            
            return $result;
        } catch (PDOException $e) {
            error_log("PDOException dans addInscription: " . $e->getMessage());
            throw $e;
        }
    }
    
    function afficherInscriptionsAvecEvenement() {
        $sql = "SELECT i.*, e.nom, e.date, e.lieu, e.capacite
                FROM inscription i 
                JOIN evenement e ON i.id_eve = e.id_ev";
    
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    
    public function deleteinsc($id)
    {
        $sql = "DELETE FROM inscription WHERE id_inscription = :id"; 
        $db = config::getConnexion();
    
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT); // on précise le type pour sécuriser
            $query->execute();
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
    
    public function updateStatut($id_inscription, $nouveau_statut) {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('UPDATE inscription SET statut = :statut WHERE id_inscription = :id_inscription');
            $query->execute([
                'statut' => $nouveau_statut,
                'id_inscription' => $id_inscription
            ]);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur: ' . $e->getMessage();
            return false;
        }
    }
    
    // Nouvelle méthode pour confirmer une inscription et envoyer un email
    public function confirmerInscription($id_inscription) {
        try {
            // Récupérer les informations de l'inscription et de l'événement
            $db = config::getConnexion();
            $query = $db->prepare("
                SELECT i.*, e.nom, e.date, e.lieu 
                FROM inscription i 
                JOIN evenement e ON i.id_eve = e.id_ev 
                WHERE i.id_inscription = ?
            ");
            $query->execute([$id_inscription]);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            
            if (!$data) {
                error_log("Inscription non trouvée: " . $id_inscription);
                return false;
            }
            
            // Mettre à jour le statut
            $query = $db->prepare("UPDATE inscription SET statut = 'Confirmée' WHERE id_inscription = ?");
            $result = $query->execute([$id_inscription]);
            
            if ($result && !empty($data['email_utilisateur'])) {
                // Envoyer l'email de confirmation
                $emailResult = $this->envoyerEmailConfirmation(
                    $data['email_utilisateur'],
                    $data['nom'],
                    $data['date'],
                    $data['lieu']
                );
                
                if (!$emailResult) {
                    error_log("Email de confirmation non envoyé pour l'inscription: " . $id_inscription);
                }
            } else if (empty($data['email_utilisateur'])) {
                error_log("Pas d'email disponible pour l'inscription: " . $id_inscription);
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("PDOException dans confirmerInscription: " . $e->getMessage());
            return false;
        }
    }
    
    // Nouvelle méthode pour refuser une inscription et envoyer un email
    public function refuserInscription($id_inscription) {
        try {
            // Récupérer les informations de l'inscription et de l'événement
            $db = config::getConnexion();
            $query = $db->prepare("
                SELECT i.*, e.nom 
                FROM inscription i 
                JOIN evenement e ON i.id_eve = e.id_ev 
                WHERE i.id_inscription = ?
            ");
            $query->execute([$id_inscription]);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            
            if (!$data) {
                error_log("Inscription non trouvée: " . $id_inscription);
                return false;
            }
            
            // Mettre à jour le statut
            $query = $db->prepare("UPDATE inscription SET statut = 'Refusée' WHERE id_inscription = ?");
            $result = $query->execute([$id_inscription]);
            
            if ($result && !empty($data['email_utilisateur'])) {
                // Envoyer l'email de refus
                $emailResult = $this->envoyerEmailRefus(
                    $data['email_utilisateur'],
                    $data['nom']
                );
                
                if (!$emailResult) {
                    error_log("Email de refus non envoyé pour l'inscription: " . $id_inscription);
                }
            } else if (empty($data['email_utilisateur'])) {
                error_log("Pas d'email disponible pour l'inscription: " . $id_inscription);
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("PDOException dans refuserInscription: " . $e->getMessage());
            return false;
        }
    }
    
    // Méthode pour envoyer un email de confirmation
    private function envoyerEmailConfirmation($email, $nom_evenement, $date_evenement, $lieu_evenement) {
        try {
            // Créer une nouvelle instance de PHPMailer
            $mail = new PHPMailer(true);
            
            // Configuration du serveur SMTP
            $mail->isSMTP();                                      // Utiliser SMTP
            $mail->Host       = SMTP_HOST;                        // Serveur SMTP
            $mail->SMTPAuth   = true;                             // Activer l'authentification SMTP
            $mail->Username   = SMTP_USERNAME;                    // Nom d'utilisateur SMTP
            $mail->Password   = SMTP_PASSWORD;                    // Mot de passe SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Activer STARTTLS
            $mail->Port       = SMTP_PORT;                        // Port TCP
            $mail->CharSet    = 'UTF-8';                          // Encodage UTF-8
            
            // Expéditeur et destinataires
            $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
            $mail->addAddress($email);                            // Ajouter un destinataire
            
            // Contenu
            $mail->isHTML(true);                                  // Format HTML
            $mail->Subject = "Confirmation d'inscription à l'événement: " . $nom_evenement;
            
            // Corps du message en HTML
            $mail->Body = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Confirmation d\'inscription</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #333;
                        max-width: 600px;
                        margin: 0 auto;
                    }
                    .header {
                        background-color: #10b981;
                        color: white;
                        padding: 20px;
                        text-align: center;
                    }
                    .content {
                        padding: 20px;
                        background-color: #f9f9f9;
                    }
                    .footer {
                        text-align: center;
                        padding: 15px;
                        font-size: 12px;
                        color: #666;
                    }
                    .event-details {
                        background-color: #fff;
                        border-left: 4px solid #10b981;
                        padding: 15px;
                        margin: 20px 0;
                    }
                    .btn {
                        display: inline-block;
                        background-color: #10b981;
                        color: white;
                        text-decoration: none;
                        padding: 10px 20px;
                        border-radius: 4px;
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>EntrepreHub</h1>
                </div>
                <div class="content">
                    <p>Bonjour,</p>
                    
                    <p>Nous avons le plaisir de vous confirmer votre inscription à l\'événement <strong>' . $nom_evenement . '</strong>.</p>
                    
                    <div class="event-details">
                        <p><strong>Événement:</strong> ' . $nom_evenement . '</p>
                        <p><strong>Date:</strong> ' . $date_evenement . '</p>
                        <p><strong>Lieu:</strong> ' . $lieu_evenement . '</p>
                    </div>
                    
                    <p>Nous sommes ravis de vous compter parmi nos participants et nous espérons que cet événement répondra à vos attentes.</p>
                    
                    <p>N\'hésitez pas à nous contacter si vous avez des questions.</p>
                    
                    <a href="https://entreprehub.com/mon-compte" class="btn">Accéder à mon compte</a>
                    
                    <p>Cordialement,<br>L\'équipe EntrepreHub</p>
                </div>
                <div class="footer">
                    <p>© 2023 EntrepreHub. Tous droits réservés.</p>
                    <p>Si vous avez reçu cet email par erreur, merci de nous en informer et de le supprimer.</p>
                </div>
            </body>
            </html>';
            
            // Version texte pour les clients mail qui ne supportent pas HTML
            $mail->AltBody = "Confirmation d'inscription à l'événement: " . $nom_evenement . "\n\n" .
                            "Événement: " . $nom_evenement . "\n" .
                            "Date: " . $date_evenement . "\n" .
                            "Lieu: " . $lieu_evenement . "\n\n" .
                            "Nous sommes ravis de vous compter parmi nos participants.";
            
            // Envoyer l'email
            $mail->send();
            error_log("Email envoyé avec succès à: " . $email);
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email: " . $mail->ErrorInfo);
            return false;
        }
    } // Accolade fermante manquante ici
    
    private function envoyerEmailRefus($email, $nom_evenement) {
        try {
            $mail = new PHPMailer(true);
            
            // Configuration du serveur
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = SMTP_PORT;
            $mail->CharSet    = 'UTF-8';
            
            // Expéditeur et destinataires
            $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
            $mail->addAddress($email);
            
            // Contenu
            $mail->isHTML(true);
            $mail->Subject = "Information concernant votre inscription à l'événement: " . $nom_evenement;
            
            // Corps du message en HTML
            $mail->Body = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Information concernant votre inscription</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #333;
                        max-width: 600px;
                        margin: 0 auto;
                    }
                    .header {
                        background-color: #4b5563;
                        color: white;
                        padding: 20px;
                        text-align: center;
                    }
                    .content {
                        padding: 20px;
                        background-color: #f9f9f9;
                    }
                    .footer {
                        text-align: center;
                        padding: 15px;
                        font-size: 12px;
                        color: #666;
                    }
                    .btn {
                        display: inline-block;
                        background-color: #4b5563;
                        color: white;
                        text-decoration: none;
                        padding: 10px 20px;
                        border-radius: 4px;
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>EntrepreHub</h1>
                </div>
                <div class="content">
                    <p>Bonjour,</p>
                    
                    <p>Nous vous informons que votre demande d\'inscription à l\'événement <strong>' . $nom_evenement . '</strong> n\'a pas pu être acceptée.</p>
                    
                    <p>Cela peut être dû à plusieurs raisons, notamment:</p>
                    <ul>
                        <li>L\'événement a atteint sa capacité maximale</li>
                        <li>L\'événement a été annulé ou reporté</li>
                        <li>Votre profil ne correspond pas aux critères de participation</li>
                    </ul>
                    
                    <p>Nous vous invitons à consulter nos autres événements qui pourraient vous intéresser.</p>
                    
                    <a href="https://entreprehub.com/evenements" class="btn">Découvrir d\'autres événements</a>
                    
                    <p>Cordialement,<br>L\'équipe EntrepreHub</p>
                </div>
                <div class="footer">
                    <p>© 2023 EntrepreHub. Tous droits réservés.</p>
                    <p>Si vous avez reçu cet email par erreur, merci de nous en informer et de le supprimer.</p>
                </div>
            </body>
            </html>';
            
            // Version texte
            $mail->AltBody = "Information concernant votre inscription à l'événement: " . $nom_evenement . "\n\n" .
                            "Nous vous informons que votre demande d'inscription n'a pas pu être acceptée.\n\n" .
                            "Cordialement,\nL'équipe EntrepreHub";
            
            $mail->send();
            error_log("Email de refus envoyé avec succès à: " . $email);
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email de refus: " . $mail->ErrorInfo);
            return false;
        }
    }
}
