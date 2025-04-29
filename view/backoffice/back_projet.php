<?php
include '../../controller/projectcontroller.php';
include '../../controller/suivicontroller.php';

$controller = new projectcontroller();
$liste = $controller->projet(); // récupère tous les projets
$controller2 = new suivicontroller();
$liste = $controller2->getProjectsWithSuivis();

// Supprimer un projet si delete_id est présent
if (isset($_GET['delete_id'])) {
    $controller->deleteproject($_GET['delete_id']);
    header('Location: back_projet.php'); // pour éviter de supprimer plusieurs fois en cas de refresh
    exit();
}

if (isset($_GET['delete_suivi'])) {
   

    try {
        $controller2->deleteSuivi($_GET['delete_suivi']);
    } catch (Exception $e) {
        die('❌ Erreur : ' . $e->getMessage());
    }

    header('Location: back_projet.php'); // Reviens à la page normale
    exit();
}


?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - EntrepreHub</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="theme-custom.css">
    <link rel="stylesheet" href="admin.css">
    
    <link rel="stylesheet" href="projects-front.css">
    <link rel="stylesheet" href="popup-style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
</head>
<body class="admin-body">
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>EntrepreHub</h2>
                <span class="admin-badge">Admin</span>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                <li class="nav-item">
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#events">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span>Événements</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#users">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Utilisateurs</span>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a href="#dashboard">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            <span>Projets</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#analytics">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                            <span>Analytiques</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#messages">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <span>Messages</span>
                            <span class="notification-badge">5</span>
                        </a>
                    </li>
                </ul>
                
                <div class="sidebar-divider"></div>
                
                <ul>
                    <li class="nav-item">
                        <a href="#settings">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            <span>Paramètres</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#help">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            <span>Aide</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="#logout" class="logout-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="menu-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <div class="breadcrumb">
                        <span>Administration</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                        <span>Projets</span>
                    </div>
                </div>
                <div class="header-right">
                    <div class="header-search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" placeholder="Rechercher...">
                    </div>
                    <div class="header-actions">
                        <button class="action-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            <span class="notification-dot"></span>
                        </button>
                        <div class="user-profile">
                            <img src="/placeholder.svg?height=40&width=40" alt="Admin User">
                            <div class="user-info">
                                <span class="user-name">Sophie Martin</span>
                                <span class="user-role">Administrateur</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Dashboard Content -->
            <div class="admin-content">
                <div class="dashboard-header">
                    <h1>Tableau de bord</h1>
                    <div class="date-filter">
                        <button class="btn btn-secondary">
                            <span>Derniers 30 jours</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: rgba(227, 196, 58, 0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgb(227, 196, 58)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <h3>Événements</h3>
                            <div class="stat-value">24</div>
                            <div class="stat-change positive">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="18 15 12 9 6 15"></polyline>
                                </svg>
                                <span>+12.5%</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: rgba(76, 175, 80, 0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4caf50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <h3>Participants</h3>
                            <div class="stat-value">1,254</div>
                            <div class="stat-change positive">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="18 15 12 9 6 15"></polyline>
                                </svg>
                                <span>+18.2%</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: rgba(33, 150, 243, 0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2196f3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <h3>Inscriptions</h3>
                            <div class="stat-value">876</div>
                            <div class="stat-change positive">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="18 15 12 9 6 15"></polyline>
                                </svg>
                                <span>+5.3%</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: rgba(244, 67, 54, 0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f44336" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <h3>Revenus</h3>
                            <div class="stat-value">€12,580</div>
                            <div class="stat-change negative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                                <span>-2.4%</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Section -->
                <div class="charts-section">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h2>Inscriptions aux événements</h2>
                            <div class="chart-actions">
                                <button class="btn btn-text active">Jour</button>
                                <button class="btn btn-text">Semaine</button>
                                <button class="btn btn-text">Mois</button>
                            </div>
                        </div>
                        <div class="chart-body">
                            <div class="chart-placeholder">
                                <!-- Placeholder for chart -->
                                <div class="chart-bars">
                                    <div class="chart-bar" style="height: 30%;"></div>
                                    <div class="chart-bar" style="height: 45%;"></div>
                                    <div class="chart-bar" style="height: 60%;"></div>
                                    <div class="chart-bar" style="height: 40%;"></div>
                                    <div class="chart-bar" style="height: 75%;"></div>
                                    <div class="chart-bar" style="height: 90%;"></div>
                                    <div class="chart-bar" style="height: 65%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <div class="chart-header">
                            <h2>Répartition des événements</h2>
                            <div class="chart-actions">
                                <button class="btn btn-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="19" cy="12" r="1"></circle>
                                        <circle cx="5" cy="12" r="1"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="chart-body">
                            <div class="chart-placeholder">
                                <!-- Placeholder for pie chart -->
                                <div class="pie-chart">
                                    <div class="pie-segment" style="--segment-start: 0; --segment-end: 0.35; --segment-color: rgb(227, 196, 58);"></div>
                                    <div class="pie-segment" style="--segment-start: 0.35; --segment-end: 0.6; --segment-color: #4caf50;"></div>
                                    <div class="pie-segment" style="--segment-start: 0.6; --segment-end: 0.8; --segment-color: #2196f3;"></div>
                                    <div class="pie-segment" style="--segment-start: 0.8; --segment-end: 1; --segment-color: #9c27b0;"></div>
                                </div>
                                <div class="pie-legend">
                                    <div class="legend-item">
                                        <span class="legend-color" style="background-color: rgb(227, 196, 58);"></span>
                                        <span>Conférences (35%)</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-color" style="background-color: #4caf50;"></span>
                                        <span>Ateliers (25%)</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-color" style="background-color: #2196f3;"></span>
                                        <span>Réseautage (20%)</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-color" style="background-color: #9c27b0;"></span>
                                        <span>Formations (20%)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
               
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
              'id_suivi' => $row['id_suivi'] ?? null,
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
      <div style="margin-top: 10px;">
        <a href="update_project.php?id_projet=<?= $info['id_projet'] ?>" class="btn btn-sm btn-text">Modifier</a>
        <a href="back_projet.php?delete_id=<?= $info['id_projet'] ?>" class="btn btn-sm btn-text">Supprimer</a>
      </div>

      <!-- 🔽 Suivis -->
      <h4>Suivis</h4>
      <?php foreach ($info['suivis'] as $suivi): ?>
        <div class="suivi-card" style="background-color: rgba(255,255,255,0.02); padding: 10px; margin: 5px 0; border-radius: 8px;">
          <p><strong>État:</strong> <?= htmlspecialchars($suivi['etat']) ?></p>
          <p><strong>Date:</strong> <?= htmlspecialchars($suivi['date_suivi']) ?></p>
          <p><strong>Commentaire:</strong> <?= htmlspecialchars($suivi['commentaire']) ?></p>

          <!-- 🟡 Barre de progression -->
          <div class="progress-container">
  <label for="progress-<?= $suivi['id_suivi'] ?>">Progression</label>

  <input 
    type="range" 
    id="progress-<?= $suivi['id_suivi'] ?>" 
    value="<?= intval($suivi['taux_avancement']) ?>" 
    min="0" 
    max="100" 
    step="1" 
    class="progress-slider"
    disabled
  >

  <span class="progress-value" id="progress-value-<?= $suivi['id_suivi'] ?>">
    <?= intval($suivi['taux_avancement']) ?>%
  </span>
</div>



          <!-- ✏️🗑️ Actions SVG -->
          <div class="suivi-actions" style="margin-top: 5px;">
            <a href="update_suivi.php?id_suivi=<?= $suivi['id_suivi'] ?>" title="Modifier">✏️</a>
            <a href="back_projet.php?delete_suivi=<?= $suivi['id_suivi'] ?>">🗑️</a>


           


          </div>
        </div>
      <?php endforeach; ?>

      <!-- ➕ Ajouter un suivi -->
      <a href="add_suivi.php?id_projet=<?= $info['id_projet'] ?>" class="btn btn-sm btn-outline" style="margin-top: 10px; display: inline-block;">
    ➕ Ajouter un suivi
</a>

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

            </div>
        </main>
    </div>

    <script src="admin.js"></script>
    



</body>
</html>