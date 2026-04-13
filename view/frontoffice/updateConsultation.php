<?php  
include('../../controller/ConsultationController.php'); 

$consultationController = new ConsultationController();

// ✅ Initialisation de la variable d'erreur
$error = "";

// ✅ Récupération de l'ID de la consultation
$id_consultation = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_consultation'])) {
    $id_consultation = $_POST['id_consultation'];
} elseif (isset($_GET['id_consultation'])) {
    $id_consultation = $_GET['id_consultation'];
}

// ✅ Récupération des détails de la consultation
if ($id_consultation) {
    $consultationDetails = $consultationController->getConsultationByid_consultation($id_consultation);

    if (!$consultationDetails) {
        die("❌ La consultation demandée n'existe pas.");
    }
} else {
    die("❌ ID de la consultation manquant.");
}

// ✅ Traitement de la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification des champs
    if (
        isset($_POST['titre'], $_POST['description'], $_POST['date_consultation'], $_POST['type'], $_POST['statut']) &&
        !empty($_POST['titre']) &&
        !empty($_POST['description']) &&
        !empty($_POST['date_consultation']) &&
        !empty($_POST['type']) &&
        !empty($_POST['statut'])
    ) {
        $titre = htmlspecialchars(trim($_POST['titre']));
        $description = htmlspecialchars(trim($_POST['description']));
        $date_consultation = $_POST['date_consultation'];
        $type = htmlspecialchars(trim($_POST['type']));
        $statut = htmlspecialchars(trim($_POST['statut']));

        // ✅ Mise à jour
        $updateStatus = $consultationController->updateConsultation($id_consultation, $titre, $description, $date_consultation, $type, $statut);

        if ($updateStatus) {
            header("Location: consultationList.php"); // Redirection après mise à jour réussie
            exit();
        } else {
            $error = "❌ Une erreur est survenue lors de la mise à jour de la consultation.";
        }
    } else {
        $error = "⚠️ Veuillez remplir tous les champs du formulaire.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mettre à jour la consultation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        label {
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            max-width: 500px;
        }
        button {
            margin-top: 15px;
            padding: 10px 15px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h3>📝 Mettre à jour la consultation</h3>

    <?php if (!empty($error)) : ?>
        <p class="error"><?= $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="updateConsultation.php">
        <input type="hidden" name="id_consultation" value="<?= htmlspecialchars($consultationDetails['id_consultation']); ?>" required>

        <label for="titre">Titre de la consultation :</label><br>
        <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($consultationDetails['titre']); ?>" required><br><br>

        <label for="description">Description :</label><br>
        <textarea name="description" id="description" rows="4" required><?= htmlspecialchars($consultationDetails['description']); ?></textarea><br><br>

        <label for="date_consultation">Date de la consultation :</label><br>
        <input type="date" name="date_consultation" id="date_consultation" value="<?= htmlspecialchars($consultationDetails['date_consultation']); ?>" required><br><br>

        <label for="type">Type de consultation :</label><br>
        <input type="text" name="type" id="type" value="<?= htmlspecialchars($consultationDetails['type']); ?>" required><br><br>

        <label for="statut">Statut :</label><br>
        <input type="text" name="statut" id="statut" value="<?= htmlspecialchars($consultationDetails['statut']); ?>" required><br><br>

        <button type="submit">✅ Mettre à jour la consultation</button>
    </form>
</body>
</html>
