<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Connexion | EntrepreHub</title>
  <link rel="stylesheet" href="assets/login.css" />
</head>
<body>
  <div class="auth-container">
    <p id="loginError" class="error-message"></p>
    <h1>Connexion</h1>
    <form method="POST" id="loginForm" oninput="validateForm()">
      <input type="email" id="email" name="email" placeholder="Adresse e-mail" required />
      <input type="password" id="password" name="password" placeholder="Mot de passe" required />

      <p style="text-align: right; margin: 0;">
        <a href="resetPassword.php" style="font-size: 0.9em;">Mot de passe oublié ?</a>
      </p>


      
      
      <button type="submit" id="loginButton" disabled>Se connecter</button> 
      <p>Pas encore de compte ? <a href="signup.php">Inscription</a></p>
    </form>

    <?php 
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    include_once (__DIR__ . '/../../controller/user_controller.php');
    $usr = new User(null, null, null, null, null, null, $password, $email);
    $userC = new User_controller();

    $user = $userC->login_user1($usr); // login_user doit retourner le user avec id et role

    if ($user && isset($user['id'])) {
        $_SESSION['user_id'] = $user['id']; // stocke en session

        // ➤ Vérifie si c'est l'admin
        if ($user['email'] === 'admin@admin.com') {
            header("Location: /intg/web2_01/view/backoffice/admin.php");
        } else {
            header("Location: /intg/produittt/view/frontoffice/projects-front.php?user_id=" . $user['id']);
        }
        exit();
    } else {
        echo "<script>document.getElementById('loginError').textContent = 'Email ou mot de passe incorrect.';</script>";
    }
}
?>




  </div>
  <script src="assets/login.js"></script>
</body>
</html>
