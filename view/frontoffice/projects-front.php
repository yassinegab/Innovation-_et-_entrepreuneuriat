<?php
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
?>


<?php
include '../../controller/projectcontroller.php';
include '../../controller/suivicontroller.php';
$controller = new projectcontroller();
$controller2 = new suivicontroller();
$liste = $controller->projet();
$liste = $controller2->getProjectsWithSuivis();
if (isset($_GET['delete_id'])) {
    $controller->deleteproject($_GET['delete_id']);

    // Rediriger pour enlever delete_id de l'URL et garder user_id
    header('Location: projects-front.php?user_id=' . $user_id);
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets Entrepreneuriaux - STELLIFEROUS
        
    </title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="theme-custom.css">
    <link rel="stylesheet" href="projects-front.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <a href="index.html">
                <h1>STELLIFEROUS
                    
                </h1>
            </a>
        </div>
        <a href="projects-front.php?user_id=1" class="btn btn-primary">Se connecter</a>
        <a href="projects-front.php" class="btn btn-secondary">Se déconnecter</a>


        
        <div class="sidebar-search">
            <input type="text" placeholder="Rechercher...">
            <button class="search-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <ul>
                <li><a href="index.html">Accueil</a></li>
                <li><a href="events.html">Événements</a></li>
                <li class="active"><a href="projects-front.html">Projets</a></li>
                <li><a href="mentors.html">Mentors</a></li>
                <li><a href="resources.html">Ressources</a></li>
                <li><a href="about.html">À propos</a></li>
            </ul>
        </nav>
        
        <div class="sidebar-user">
            <button class="user-menu-btn">
                <img src="/placeholder.svg?height=36&width=36" alt="User Profile">
                <span class="user-name">Meriem.B</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
            <div class="user-dropdown">
                <ul>
                    <li>
                        <a href="profile.html">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Mon profil</span>
                        </a>
                    </li>
                    <li>
                        <a href="my-projects.html">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <span>Mes projets</span>
                        </a>
                    </li>
                    <li>
                        <a href="messages.html">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <span>Messages</span>
                            <span class="badge">3</span>
                        </a>
                    </li>
                    <li>
                        <a href="settings.html">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            <span>Paramètres</span>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="logout.html" class="logout">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Déconnexion</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    <div class="main-wrapper">
        <!-- Header (caché) -->
        <header class="site-header" style="display: none;">
            <!-- Contenu du header original -->
            <

        </header>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
            

                <div class="hero-content">
                    <h1>Découvrez et collaborez sur des projets innovants</h1>
                    <p>Explorez des projets entrepreneuriaux, trouvez des collaborateurs ou proposez votre expertise pour faire avancer l'innovation.</p>
                    <div class="hero-actions">
                    <div class="search-bar">
                    <input type="text" id="search-projects" placeholder="Rechercher un domaine..." style="margin-bottom: 20px; width: 250px; height: 30px; font-size: 16px;">
                    <div id="suggestions" style="position: relative;"></div>
 
</div>

                        <button class="btn btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            Explorer les projets
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <main class="main-content">
        <div class="projects-grid">
  <?php
  $current_project_id = null;
  $project_data = [];

  foreach ($liste as $row) {
      $id = $row['id_projet'];
      if (!isset($project_data[$id])) {
          $project_data[$id] = [
              'id_projet' => $row['id_projet'],
              'nom_projet' => $row['nom_projet'],
              'domaine' => $row['domaine'],
              'date_creation' => $row['date_creation'],
              'besoin' => $row['besoin'],
              'suivis' => []
          ];
      }

      if (!empty($row['etat']) || !empty($row['date_suivi']) || !empty($row['commentaire']) || !empty($row['taux_avancement'])) {
          $project_data[$id]['suivis'][] = [
              'etat' => $row['etat'],
              'date_suivi' => $row['date_suivi'],
              'commentaire' => $row['commentaire'],
              'taux_avancement' => $row['taux_avancement']
          ];
      }
  }

  foreach ($project_data as $info):
  ?>
    <div class="project-card">
      <div class="project-header">
        <div class="project-logo" style="background-color: rgba(33, 150, 243, 0.1);">
          <span><?= strtoupper(substr($info['nom_projet'], 0, 2)) ?></span>
        </div>
      </div>

      <h3>ID : <?= $info['id_projet'] ?></h3>
      <h3>Nom : <?= htmlspecialchars($info['nom_projet']) ?></h3>
      <p>Domaine : <?= htmlspecialchars($info['domaine']) ?></p>
      <p>Date de création : <?= htmlspecialchars($info['date_creation']) ?></p>
      <p>Besoins : <?= htmlspecialchars($info['besoin']) ?></p>

      <!-- 🔘 Boutons Modifier / Supprimer -->
      <?php if ($user_id == 1): ?>
        <div style="margin-top: 10px;">
          <a href="update_project.php?id_projet=<?= $info['id_projet'] ?>&user_id=<?= $user_id ?>" class="btn btn-sm btn-text">Modifier</a>
          <a href="projects-front.php?delete_id=<?= $info['id_projet'] ?>&user_id=<?= $user_id ?>" class="btn btn-sm btn-text">Supprimer</a>
        </div>
      <?php endif; ?>

      <!-- 🔽 Suivis -->
      <h4>Suivis</h4>
      <?php foreach ($info['suivis'] as $suivi): ?>
        <div class="suivi-card" style="background-color: rgba(255,255,255,0.02); padding: 10px; margin: 5px 0; border-radius: 8px;">
          <p><strong>État:</strong> <?= htmlspecialchars($suivi['etat']) ?></p>
          <p><strong>Date:</strong> <?= htmlspecialchars($suivi['date_suivi']) ?></p>
          <p><strong>Commentaire:</strong> <?= htmlspecialchars($suivi['commentaire']) ?></p>

          <!-- 🟡 Barre de progression -->
          <div class="project-progress">
            <div class="progress-label">
              <span>Progression</span>
              <span><?= $suivi['taux_avancement'] ?>%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: <?= $suivi['taux_avancement'] ?>%; background-color: #e3c43a; height: 5px;"></div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
</div>


            <div class="container">
                <!-- Projects Filter Bar -->
                
                
                <!-- Call to Action -->
                <div class="cta-section">
                    <div class="cta-content">
                        <h2>Vous avez un projet innovant ?</h2>
                        <p>Partagez votre idée, trouvez des collaborateurs et obtenez le soutien dont vous avez besoin pour réussir.</p>
                        <a href="create-project.php" class="btn btn-primary" id="cta-create-project-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Proposer un projet
                        </a>
                        
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="site-footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-logo">
                        <h2>STELLIFEROUS
                            
                        </h2>
                        <p>La plateforme des entrepreneurs innovants</p>
                    </div>
                    <div class="footer-links">
                        <div class="footer-column">
                            <h3>Navigation</h3>
                            <ul>
                                <li><a href="index.html">Accueil</a></li>
                                <li><a href="events.html">Événements</a></li>
                                <li><a href="projects-front.php">Projets</a></li>
                                <li><a href="mentors.html">Mentors</a></li>
                                <li><a href="resources.html">Ressources</a></li>
                            </ul>
                        </div>
                        <div class="footer-column">
                            <h3>Ressources</h3>
                            <ul>
                                <li><a href="#">Guide de l'entrepreneur</a></li>
                                <li><a href="#">Financement</a></li>
                                <li><a href="#">Mentorat</a></li>
                                <li><a href="#">Formations</a></li>
                                <li><a href="#">Blog</a></li>
                            </ul>
                        </div>
                        <div class="footer-column">
                            <h3>À propos</h3>
                            <ul>
                                <li><a href="#">Notre mission</a></li>
                                <li><a href="#">L'équipe</a></li>
                                <li><a href="#">Partenaires</a></li>
                                <li><a href="#">Témoignages</a></li>
                                <li><a href="#">Nous contacter</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="footer-newsletter">
                        <h3>Restez informé</h3>
                        <p>Inscrivez-vous à notre newsletter pour recevoir les dernières actualités et opportunités.</p>
                        <form class="newsletter-form">
                            <input type="email" placeholder="Votre email">
                            <button type="submit" class="btn btn-primary">S'inscrire</button>
                        </form>
                        <div class="social-links">
                            <a href="#" class="social-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                </svg>
                            </a>
                            <a href="#" class="social-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                                </svg>
                            </a>
                            <a href="#" class="social-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                    <rect x="2" y="9" width="4" height="12"></rect>
                                    <circle cx="4" cy="4" r="2"></circle>
                                </svg>
                            </a>
                            <a href="#" class="social-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; 2025 STELLIFEROUS
                        . Tous droits réservés.</p>
                    <div class="footer-legal">
                        <a href="#">Conditions d'utilisation</a>
                        <a href="#">Politique de confidentialité</a>
                        <a href="#">Mentions légales</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="search.js"></script>
  

    
</body>
</html>