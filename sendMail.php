<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


$config = include 'mail_config.php';

function sendMail($to, $subject, $body) {
    global $config;

    $mail = new PHPMailer(true);
    try {
        // Configuration serveur
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = $config['encryption'];
        $mail->Port = $config['port'];

        // Expéditeur
        $mail->setFrom($config['from_email'], $config['from_name']);

        // Destinataire
        $mail->addAddress($to);

        // Contenu
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        echo 'Email envoyé avec succès !';
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
    }
}
