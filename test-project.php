<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "✅ Formulaire bien soumis !<br>";
    var_dump($_POST);
    exit();
}
?>

<form method="POST" action="">
    <label>ID :</label><input type="text" name="id_projet"><br>
    <label>Nom :</label><input type="text" name="nom_projet"><br>
    <label>Description :</label><input type="text" name="descriptionn"><br>
    <label>Domaine :</label><input type="text" name="domaine"><br>
    <label>Date :</label><input type="date" name="date_creation"><br>
    <label>Besoin :</label><input type="text" name="besoin"><br>

    <button type="submit">Créer</button>
</form>
