<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Connexion | EntrepreHub</title>
  <link rel="stylesheet" href="login.css" />
</head>
<body>
  <div class="auth-container">
  <p id="loginError" class="error-message"></p>
    <h1>Connexion</h1>
    <form  method="POST" id="loginForm" oninput="validateForm()">
      <input type="email" id="email" name="email" placeholder="Adresse e-mail" required />
      <input type="password" id="password" name="password" placeholder="Mot de passe" required />
      <button type="submit" id="loginButton" disabled>Se connecter</button>
      <p>Pas encore de compte ? <a href="signup.php">Inscription</a></p>
    </form>
    <?php 
    session_start();
    if ($_SERVER['REQUEST_METHOD']==='POST'){

        $email=$_POST['email'];
        $password=$_POST['password'];
        include_once (__DIR__ .'/../../controller/user_controller.php');
        $usr=new User(null,null,null,null,null,null,$password,$email);
        $userC=new User_controller();
        $userC->login_user($usr);
        
    }
    
    ?>
  </div>
  <script src="assets/login.js"></script>
</body>
</html>
