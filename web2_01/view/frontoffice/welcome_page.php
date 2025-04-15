<?php
session_start();

// Si l'utilisateur n'est pas connecté, on le renvoie vers la page de login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="welcome_page.css">
</head>
<body>
  <header>
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?> !</h1>
    <nav>
      <a href="profile.php">Mon Profil</a>
      <a href="settings.php">Paramètres</a>
      <a href="logout.php">Se déconnecter</a>
    </nav>
  </header>

  <main>
    <section class="welcome">
      <h2>Tableau de bord</h2>
      <p>Vous êtes connecté ! Explorez vos fonctionnalités ci‑dessous.</p>
    </section>

    <section class="stats">
      <h2>Vos statistiques</h2>
      <p>— À venir —</p>
    </section>
  </main>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> MonSite. Tous droits réservés.</p>
  </footer>
</body>
</html>
