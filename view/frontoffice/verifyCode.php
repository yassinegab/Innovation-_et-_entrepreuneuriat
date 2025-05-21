<?php
session_start();

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_code'])) {
    header("Location: resetPassword.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codeBtn'])) {
    $enteredCode = $_POST['code'];

    if ($enteredCode == $_SESSION['reset_code']) {
        // Code is valid
        header("Location: newPassword.php"); // You can create this page next
        exit();
    } else {
        $error = "Code invalide. Réessayez.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Vérification du code</title>
  <link rel="stylesheet" href="assets/login.css">
</head>
<body>
  <div class="auth-container">
    <h1>Entrez le code de vérification</h1>
    <form method="POST">
      <input type="text" name="code" placeholder="Code à 6 chiffres" required>
      <button type="submit" name="codeBtn">Vérifier</button>
    </form>

    <?php if (!empty($error)): ?>
      <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
  </div>
</body>
</html>
