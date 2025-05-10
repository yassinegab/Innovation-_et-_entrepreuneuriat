<?php
// Utilisation de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Chargement de l'autoloader de Composer
require 'vendor/autoload.php';

/**
 * Fonction pour envoyer un email de bienvenue à un nouvel utilisateur avec PHPMailer
 * 
 * @param string $nom Le nom de l'utilisateur
 * @param string $email L'adresse email de l'utilisateur
 * @return bool Retourne true si l'email a été envoyé avec succès, false sinon
 */
function envoyerEmailBienvenue($nom, $email) {
    // Création d'une nouvelle instance de PHPMailer
    $mail = new PHPMailer(true);
    
    try {
        // Configuration du serveur
        $mail->isSMTP();                                      // Utilisation de SMTP
        $mail->Host       = 'smtp.votreserveur.com';          // Serveur SMTP
        $mail->SMTPAuth   = true;                             // Activation de l'authentification SMTP
        $mail->Username   = 'votre_email@votreserveur.com';   // Nom d'utilisateur SMTP
        $mail->Password   = 'votre_mot_de_passe';             // Mot de passe SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Activation du chiffrement TLS
        $mail->Port       = 587;                              // Port TCP pour se connecter
        
        // Paramètres des destinataires
        $mail->setFrom('noreply@nutrition2025.tn', 'Nutrition & Repas');
        $mail->addAddress($email, $nom);                      // Ajout d'un destinataire
        
        // Contenu
        $mail->isHTML(true);                                  // Format de l'email en HTML
        $mail->CharSet = 'UTF-8';                             // Encodage en UTF-8
        $mail->Subject = 'Bienvenue sur notre plateforme de nutrition !';
        
        // Corps de l'email en HTML
        $mail->Body = '
        <html>
        <head>
            <title>Bienvenue sur notre plateforme de nutrition</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                .header {
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px;
                    text-align: center;
                    border-radius: 5px 5px 0 0;
                }
                .content {
                    padding: 20px;
                }
                .footer {
                    background-color: #f1f1f1;
                    padding: 10px;
                    text-align: center;
                    border-radius: 0 0 5px 5px;
                    font-size: 12px;
                }
                .button {
                    display: inline-block;
                    background-color: #4CAF50;
                    color: white;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 4px;
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Bienvenue sur Nutrition & Repas !</h1>
                </div>
                <div class="content">
                    <h2>Bonjour ' . htmlspecialchars($nom) . ',</h2>
                    <p>Nous sommes ravis de vous accueillir sur notre plateforme de nutrition personnalisée.</p>
                    <p>Grâce à votre compte, vous pouvez maintenant :</p>
                    <ul>
                        <li>Découvrir des recettes équilibrées</li>
                        <li>Suivre vos apports caloriques quotidiens</li>
                        <li>Définir et atteindre vos objectifs nutritionnels</li>
                    </ul>
                    <p>N\'hésitez pas à explorer toutes les fonctionnalités de notre site !</p>
                    <a href="https://nutrition2025.tn/accueil.php" class="button">Accéder à mon compte</a>
                    <p>Si vous avez des questions, notre équipe est à votre disposition.</p>
                    <p>Cordialement,<br>L\'équipe Nutrition & Repas</p>
                </div>
                <div class="footer">
                    <p>© 2025 Nutrition & Repas. Tous droits réservés.</p>
                    <p>Si vous n\'êtes pas à l\'origine de cette inscription, veuillez ignorer cet email.</p>
                </div>
            </div>
        </body>
        </html>
        ';
        
        // Version texte de l'email pour les clients qui ne supportent pas le HTML
        $mail->AltBody = "Bonjour " . $nom . ",\n\n" .
                        "Nous sommes ravis de vous accueillir sur notre plateforme de nutrition personnalisée.\n\n" .
                        "Grâce à votre compte, vous pouvez maintenant :\n" .
                        "- Découvrir des recettes équilibrées\n" .
                        "- Suivre vos apports caloriques quotidiens\n" .
                        "- Définir et atteindre vos objectifs nutritionnels\n\n" .
                        "N'hésitez pas à explorer toutes les fonctionnalités de notre site !\n\n" .
                        "Cordialement,\n" .
                        "L'équipe Nutrition & Repas";
        
        // Envoi de l'email
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log l'erreur pour le débogage
        error_log("Erreur lors de l'envoi de l'email: " . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Fonction pour tester la configuration de PHPMailer
 * Utile pour vérifier que tout fonctionne correctement
 */
function testerConfigurationEmail() {
    try {
        $resultat = envoyerEmailBienvenue('Utilisateur Test', 'test@example.com');
        if ($resultat) {
            echo "Email de test envoyé avec succès !";
        } else {
            echo "Échec de l'envoi de l'email de test.";
        }
    } catch (Exception $e) {
        echo "Erreur lors du test : " . $e->getMessage();
    }
}
?>
