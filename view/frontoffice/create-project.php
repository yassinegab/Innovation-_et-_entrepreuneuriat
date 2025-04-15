<?php
include '../../controller/projectcontroller.php';

$erreur_message = ""; // Pour stocker l'erreur

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project = new project(
        $_POST['id_projet'],
        $_POST['nom_projet'],
        $_POST['descriptionn'],
        $_POST['domaine'],
        $_POST['date_creation'],
        $_POST['besoin']
    );

    $controller = new projectcontroller();

    try {
        $controller->addproject($project);
        header('Location: projects-front.php');
        exit();
    } catch (Exception $e) {
        $erreur_message = "Erreur lors de l'ajout du projet : " . $e->getMessage();
    }
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
<body>
    <div class="popup-container">
        <div class="popup-header">
            <h2>Proposer un nouveau projet</h2>
            
                
        </div>
        <div class="popup-body">
        <form method="POST" id="create-project-form">

                <div class="form-group">
                    <label for="project-id">ID du projet </label>
                    <input type="text" id="project-id" name="id_projet" >
                </div>
                
                <div class="form-group">
                    <label for="project-name">Nom du projet </label>
                    <input type="text" id="project-name" name="nom_projet" >
                </div>
                
                <div class="form-group">
                    <label for="project-description">Description </label>
                    <textarea id="project-description" name="descriptionn"></textarea>
                    <div class="form-hint">Décrivez votre projet, sa mission et son impact potentiel (100 caractères max).</div>
                    <div class="character-count"><span id="char-count">0</span>/100</div>
                </div>
                
                <div class="form-group">
    <label for="project-domain">Domaine </label>
    <input type="text" id="project-domain" name="domaine" placeholder="Ex: Technologie, Santé..." >
</div>
                
                <div class="form-group">
                    <label for="project-date">Date de création </label>
                    <input type="date" id="project-date" name="date_creation" >
                </div>
                
                <div class="form-group">
    <label for="project-besoin">Besoins du projet </label>
    <input type="text" id="project-besoin" name="besoin" placeholder="Ex: financement, mentorat..." >
    <div class="form-hint">Tu peux entrer plusieurs besoins séparés par des virgules.</div>
</div>

</div>
        <div class="popup-footer">
        <button type="button" id="submit-project-btn" class="btn btn-primary">Créer le projet</button>

        <button type="button" class="btn btn-secondary" onclick="window.location.href='projects-front.php'">Annuler</button>
    </div>
    </div>

            </form>
        
            <script src="create-project.js"></script>


    
</body>
</html>