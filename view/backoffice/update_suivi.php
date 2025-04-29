<?php
include '../../controller/suivicontroller.php';

$controller = new suivicontroller();

$id_suivi = isset($_GET['id_suivi']) ? $_GET['id_suivi'] : null;

if (!$id_suivi) {
    die("ID du suivi manquant.");
}

// Récupérer les données existantes
$suivi = $controller->getSuiviById($id_suivi);
if (!$suivi) {
    die("Suivi introuvable.");
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedSuivi = new suivi(
        $id_suivi,
        $suivi['id_projet'], // on ne change pas l'id_projet
        $_POST['etat'],
        $_POST['commentaire'],
        $_POST['date_suivi'],
        $_POST['taux_avancement']
    );
    $controller->updateSuivi($updatedSuivi);
    header("Location: back_projet.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Suivi</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="theme-custom.css">
    <link rel="stylesheet" href="projects-front.css">
    <link rel="stylesheet" href="popup-style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
</head>
<body class="popup-mode">
<div class="popup-container">
    <div class="popup-header">
        <h2>Modifier le suivi</h2>
    </div>
    <div class="popup-body">
    <form id="update-suivi-form" method="POST">

        <div class="form-group">
            <label for="etat">Etat</label>
            <input type="text" id="etat" name="etat" value="<?= htmlspecialchars($suivi['etat']) ?>">
            <small class="error-message" id="etat-error"></small>
        </div>

        <div class="form-group">
            <label for="commentaire">Commentaire</label>
            <textarea id="commentaire" name="commentaire"><?= htmlspecialchars($suivi['commentaire']) ?></textarea>
            <small class="error-message" id="commentaire-error"></small>
        </div>

        <div class="form-group">
            <label for="date_suivi">Date de suivi</label>
            <input type="date" id="date_suivi" name="date_suivi" value="<?= $suivi['date_suivi'] ?>">
            <small class="error-message" id="date_suivi-error"></small>
        </div>

        <div class="form-group">
            <label for="taux_avancement">Taux d'avancement (%)</label>
            <input type="number" id="taux_avancement" name="taux_avancement" value="<?= intval($suivi['taux_avancement']) ?>">
            <small class="error-message" id="taux_avancement-error"></small>
        </div>

       
</div>



    <div class="popup-footer">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="back_projet.php" class="btn">Annuler</a>
    </div>
        </form>
</div>
<script src="update-suivi.js"></script>

</body>
</html>
