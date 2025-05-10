<?php
/**
 * Configuration des paramètres d'email
 * Vous pouvez modifier ces paramètres selon votre configuration
 */

// Paramètres SMTP
define('SMTP_HOST', 'smtp.votreserveur.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls'); // tls ou ssl
define('SMTP_AUTH', true);
define('SMTP_USERNAME', 'votre_email@votreserveur.com');
define('SMTP_PASSWORD', 'votre_mot_de_passe');

// Paramètres d'expéditeur
define('EMAIL_FROM', 'noreply@nutrition2025.tn');
define('NAME_FROM', 'Nutrition & Repas');

// Paramètres de débogage
define('SMTP_DEBUG', 0); // 0 = désactivé, 1 = messages client, 2 = messages client et serveur
?>
