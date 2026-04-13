<?php
// test_email.php
require_once 'lib/PHPMailer-master/src/Exception.php';
require_once 'lib/PHPMailer-master/src/PHPMailer.php';
require_once 'lib/PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/mail_config.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    $mail = new PHPMailer(true);
    
    // Activer le débogage détaillé
    $mail->SMTPDebug = 2; // 0 = désactivé, 1 = messages client, 2 = messages client et serveur
    
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
    $mail->addAddress('eyaboughdiri861@gmail.com'); // Adresse où vous pouvez vérifier la réception
    
    // Contenu
    $mail->isHTML(true);
    $mail->Subject = "Test d'envoi d'email avec PHPMailer";
    $mail->Body    = "<h1>Test réussi!</h1><p>Ceci est un test d'envoi d'email avec PHPMailer.</p>";
    $mail->AltBody = "Test réussi! Ceci est un test d'envoi d'email avec PHPMailer.";
    
    $mail->send();
    echo "<h2 style='color:green;'>Email envoyé avec succès!</h2>";
} catch (Exception $e) {
    echo "<h2 style='color:red;'>Erreur d'envoi d'email:</h2>";
    echo "<pre>" . $mail->ErrorInfo . "</pre>";
}
?>