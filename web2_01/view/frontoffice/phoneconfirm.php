<?php
session_start();




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['phoneBtn'])) {
      $code=$_POST["phone"];
      if ($_SESSION['2fa_code']==$code) {
        $id = $_SESSION['user_id'];
        

        header("Location: general.php");
      }
      





    }



  }
?>




<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>verify your identaty</title>
  <link rel="stylesheet" href="assets/login.css">
</head>
<body>
  <div class="auth-container">
    <h1>verify your identaty</h1>
    <form method="POST">
      <input type="phone" name="phone" placeholder="enter the code" required>
      <button type="submit" name="phoneBtn">Envoyer</button>
    </form>
    
  </div>
</body>
</html>