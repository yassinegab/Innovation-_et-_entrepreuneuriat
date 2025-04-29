<?php
include '../../controller/projectcontroller.php';

$controller = new projectcontroller();

// Connexion à la base
$db = config::getConnexion();

// Si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_projet = isset($_POST['id_projet']) ? intval($_POST['id_projet']) : null;
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;

    if ($id_projet) {
        $updatedProject = new project(
            $id_projet,
            $_POST['nom_projet'],
            $_POST['descriptionn'],
            $_POST['domaine'],
            $_POST['date_creation'],
            $_POST['besoin']
        );

        $controller->updateproject($updatedProject);

        // Rediriger correctement
        header("Location: projects-front.php?user_id=$user_id&success=1");
        exit();
    } else {
        header('Location: projects-front.php?error=missing_id');
        exit();
    }
}

// Traitement GET uniquement ici
$id_projet = isset($_GET['id_projet']) ? intval($_GET['id_projet']) : null;
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;

// Récupérer projet pour affichage
if (!$id_projet) {
    header('Location: projects-front.php?error=missing_id');
    exit();
}

$query = $db->prepare("SELECT * FROM projet WHERE id_projet = :id");
$query->bindValue(':id', $id_projet);
$query->execute();
$project = $query->fetch();

if (!$project) {
    header('Location: projects-front.php?error=notfound');
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Projet - Stelliferous</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="theme-custom.css">
    <link rel="stylesheet" href="projects-front.css">
    <link rel="stylesheet" href="popup-style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
</head>
<body class="popup-mode">
    <div class="popup-container">
        <div class="popup-header">
            <h2>Modifier le projet</h2>
        </div>
        <div class="popup-body">
  <form method="POST" id="update-project-form">

    <div class="form-group">
      <label for="project-name">Nom du projet</label>
      <input 
        type="text" 
        id="project-name" 
        name="nom_projet"
        value="<?= htmlspecialchars($project['nom_projet']) ?>"
      >
      <small id="project-name-error" class="error-message"></small>
    </div>

    <div class="form-group">
      <label for="project-description">Description</label>
      <textarea 
        id="project-description" 
        name="descriptionn" 
        rows="3"
      ><?= htmlspecialchars($project['descriptionn']) ?></textarea>
      <small id="project-description-error" class="error-message"></small>
    </div>

    <div class="form-group">
      <label for="project-domain">Domaine</label>
      <input 
        type="text" 
        id="project-domain" 
        name="domaine" 
        value="<?= htmlspecialchars($project['domaine']) ?>"
        placeholder="Ex: Technologie, Santé..."
      >
      <small id="project-domain-error" class="error-message"></small>
    </div>

    <div class="form-group">
      <label for="project-date">Date de création</label>
      <input 
        type="date" 
        id="project-date" 
        name="date_creation" 
        value="<?= htmlspecialchars($project['date_creation']) ?>"
      >
    </div>

    <div class="form-group">
      <label for="project-besoin">Besoins du projet</label>
      <input 
        type="text" 
        id="project-besoin" 
        name="besoin" 
        value="<?= htmlspecialchars($project['besoin']) ?>"
        placeholder="Ex: financement, mentorat..."
      >
      <small id="project-besoin-error" class="error-message"></small>
    </div>

    
</div>


        <div class="popup-footer">
        <button type="button" id="submit-update-btn" class="btn btn-primary">Enregistrer</button>

            <a href="back_projet.php" class="btn">Annuler</a>
        </div>
        </form>
    </div>
    <script src="update-projet.js"></script>

</body>
</html>
