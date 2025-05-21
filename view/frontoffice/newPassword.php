<?php
session_start();

// Prevent direct access without code verification
if (!isset($_SESSION['reset_email'])) {
    header("Location: resetPassword.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($newPassword) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        // TODO: Hash the password and update in the database
        // Example:
        require_once('../../controller/user_controller.php');
        $userC = new User_controller();
        
        $userC->updatePassword($_SESSION['reset_email'], $confirmPassword);

        // Clean session data
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_code']);

        $success = "Mot de passe mis à jour avec succès.";
        header("Location: login.php?reset=success");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nouveau mot de passe</title>
  <link rel="stylesheet" href="assets/login.css">
</head>
<body>
  <div class="auth-container">
    <h1>Définir un nouveau mot de passe</h1>
    <form method="POST">
      <input type="password" name="password" placeholder="Nouveau mot de passe" required>
      <input type="password" name="confirm_password" placeholder="Confirmez le mot de passe" required>
      <button type="submit">Mettre à jour</button>
    </form>

    <?php if (!empty($error)): ?>
      <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
  </div>
</body>
</html>
