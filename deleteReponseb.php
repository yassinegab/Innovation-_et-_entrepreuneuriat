<?php
// Inclure les fichiers nécessaires
require_once('../../controller/reponseController.php');
require_once('../../model/reponse.php');

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si les IDs sont fournis
if (!isset($_GET['id_reponse']) || empty($_GET['id_reponse']) || !isset($_GET['id_consultation']) || empty($_GET['id_consultation'])) {
    header("Location: consultationListb.php");
    exit;
}

$id_reponse = intval($_GET['id_reponse']);
$id_consultation = intval($_GET['id_consultation']);

// Initialiser le contrôleur
$reponseC = new ReponseController();

// Vérifier si la réponse existe avant de la supprimer
$reponse = $reponseC->recupererReponse($id_reponse);

if ($reponse) {
    // Vérifier que la réponse appartient bien à la consultation indiquée
    if ($reponse->getid_consultation() == $id_consultation) {
        // Supprimer la réponse
        $reponseC->supprimerReponse($id_reponse);
        
        // Rediriger vers la page des détails de la consultation avec un message de succès
        header("Location: reponseListb.php?id_consultation=$id_consultation&delete_success=true");
        exit;
    } else {
        // La réponse n'appartient pas à cette consultation
        header("Location: consultationListb.php");
        exit;
    }
} else {
    // La réponse n'existe pas
    header("Location: consultationListb.php");
    exit;
}
?>