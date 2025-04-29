<?php
require_once 'config.php'; // Assure-toi que le fichier config.php est dans le même dossier ou adapte le chemin

try {
    $connexion = config::getConnexion();
    echo "✅ Connexion réussie à la base de données via la classe config !";
} catch (Exception $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage();
}
?>
