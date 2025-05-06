<?php
include '../../controller/suivicontroller.php';
require '../../sendMail.php';
 // assure-toi que le chemin est correct

$controller = new suivicontroller();
$erreur_message = "";
$id_projet = isset($_GET['id_projet']) ? intval($_GET['id_projet']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suivi = new suivi(
        null, // id_suivi auto-incrémenté
        $_POST['id_projet'],
        $_POST['etat'],
        $_POST['commentaire'],
        $_POST['date_suivi'],
        $_POST['taux_avancement'],
        $_POST['tache']
    );

    try {
        $controller->addsuivi($suivi);

        // Envoi d'un e-mail au propriétaire (adresse temporaire pour l'instant)
        sendMail('meriembennour2004@gmail.com', 'Nouveau suivi ajouté', 
            'Un nouveau suivi a été ajouté au projet ID ' . $_POST['id_projet'] . 
            '. Veuillez consulter votre tableau de bord.');

        header('Location: back_projet.php');
        exit();
    } catch (Exception $e) {
        $erreur_message = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un Suivi</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="theme-custom.css">
  <link rel="stylesheet" href="projects-front.css">
  <link rel="stylesheet" href="popup-style.css">
</head>
<body>
  <div class="popup-container">
    <div class="popup-header">
      <h2>Ajouter un nouveau suivi</h2>
    </div>

    <form method="POST">
      <input type="hidden" name="id_projet" value="<?= htmlspecialchars($id_projet) ?>">

      <div class="popup-body">
        <div class="form-group">
        <label for="etat">État</label>
      <select name="etat" id="etat">
        <option value="">-- Sélectionnez un état --</option>
        <option value="fini">Fini</option>
        <option value="en cours">En cours</option>
        <option value="en attente">En attente</option>
      </select>
      <small id="error-etat" class="error-message"></small>
        </div>

        <div class="form-group">
          <label for="commentaire">Commentaire</label>
          <textarea name="commentaire" id="commentaire"></textarea>
          <small id="error-commentaire" class="error-message"></small>
        </div>

        <div class="form-group">
          <label for="date_suivi">Date de suivi</label>
          <input type="date" name="date_suivi" id="date_suivi">
          <small id="error-date" class="error-message"></small>
        </div>

        <div class="form-group">
          <label for="taux_avancement">Taux d'avancement (%)</label>
          <input type="number" name="taux_avancement" id="taux_avancement">
          <small id="error-taux" class="error-message"></small>
        </div>

        <div class="form-group">
          <label for="tache">Tâche</label>
          <input type="text" name="tache" id="tache">
          <small id="error-tache" class="error-message"></small>
        </div>
      </div>

      <div class="popup-footer">
        <button type="submit" class="btn btn-primary">AJOUTER LE SUIVI</button>
        <button type="button" class="btn btn-secondary" onclick="window.location.href='back_projet.php'">Annuler</button>
      </div>
    </form>
  </div>
  <script src="add_suivi.js"></script>
</body>
</html>
