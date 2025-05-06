<?php
require_once __DIR__ . '/../../controller/evenController.php';

require_once __DIR__ . '/../../model/evenmodel.php';


$evenementcontroller = new EvenementController();

if (isset($_GET['id_ev'])) {
    $id = $_GET['id_ev'];
    $db = config::getConnexion();
    $query = $db->prepare("SELECT * FROM evenement WHERE id_ev= :id");
    $query->bindValue(':id_ev', $id);
    $query->execute();
    $offre = $query->fetch(PDO::FETCH_ASSOC);

    if (!$offre) {
        die("Offre non trouvée !");
    }
}

// Vérifier si le formulaire a été soumis pour modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id_ev'] ?? null;
    $nom = $_POST['nom'] ?? null;
    $lieu = $_POST['lieu'] ?? null;
    $capacite = $_POST['capacite'] ?? null;

    if ($id && $nom && $lieu && $capacite) {
        $evenementcontroller->updateven($id, $nom, $lieu, $capacite);
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>



<html>
<head>
    <title>Modifier even</title>
</head>
<body>
    <h2>Modifier even</h2>
    
    <form method="post">
        <input type="hidden" name="id" value="<?= $even['id_ev']; ?>">
        <label>id:</label>
        <input type="text" name="nom" value="<?= $even['nom']; ?>" required><br>
        <label>nom :</label>
        <input type="text" name="lieu" value="<?= $even['lieu']; ?>" required><br>
        <label>Date :</label>
        <input type="date" name="date" value="<?= $even['date']; ?>" required><br>
      
       
        <label>capacite :</label>
        <input type="number" name="capacite" value="<?= $even['capcite']; ?>" required><br>
       
        <button type="submit">Enregistrer</button>
        <a href="formulaire-evenement.php">Annuler</a>
        
    </form>
</body>
</html>
