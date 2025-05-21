<?php
require_once '../../controller/commentairecontroller.php';
$CommentaireController = new CommentaireController();
$listeCommentaire = $CommentaireController->afficher();
$stats = $CommentaireController->getCommentStats();
$statusDistribution = $CommentaireController->getStatusDistribution();
?>



<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Administration des Commentaires - STELLIFEROUS</title>
  <link rel="stylesheet" href="commentaire.css" />
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/back.css">
    
</head>

<body class="admin-body">
  <div class="admin-container">
    <!-- Sidebar -->
   <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">B</div>
            <div class="sidebar-logo-text">Backoffice</div>
        </div>
        
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item active">
                <a href="#" class="sidebar-menu-link">
                    <span class="sidebar-menu-icon">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"></path>
                        </svg>
                    </span>
                    Tableau de bord
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="#" class="sidebar-menu-link has-submenu" onclick="toggleSubmenu(event)">
                    <span class="sidebar-menu-icon">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"></path>
                        </svg>
                    </span>
                    Listes
                </a>
                <ul class="submenu">
                    <li class="submenu-item">
                        <a href="back.php" class="submenu-link">
                            <i class="fas fa-handshake"></i> Collaborations
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="reponseListb.php" class="submenu-link">
                            <i class="fas fa-comments"></i> Consultations
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="formulaire-evenement.php" class="submenu-link">
                            <i class="fas fa-calendar-alt"></i> Événements
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="formulaire-inscription.php" class="submenu-link">
                            <i class="fas fa-user-plus"></i> Inscriptions
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="adminyessine.php" class="submenu-link">
                            <i class="fas fa-newspaper"></i> Articles
                        </a>
                    </li>
                     <li class="submenu-item">
                        <a href="commentaire.php" class="submenu-link">
                            <i class="fas fa-newspaper"></i> Commentaires
                        </a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-menu-item">
                <a href="admin.php" class="sidebar-menu-link">
                    <span class="sidebar-menu-icon">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                        </svg>
                    </span>
                    Utilisateurs
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="#" class="sidebar-menu-link">
                    <span class="sidebar-menu-icon">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"></path>
                        </svg>
                    </span>
                    Rapports
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="#" class="sidebar-menu-link">
                    <span class="sidebar-menu-icon">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z"></path>
                        </svg>
                    </span>
                    Paramètres
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="../frontoffice/index.php" class="sidebar-menu-link">
                    <span class="sidebar-menu-icon">
                        
                    </span>
                    page d acceuil
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
      <!-- Header -->
      <header class="admin-header">
        <div class="header-left">
          <button class="menu-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
          </button>
          <div class="breadcrumb">
            <span>Administration</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
            <span>Commentaires</span>
          </div>
        </div>
        <div class="header-right">
          <div class="header-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" placeholder="Rechercher..." />
          </div>
          <div class="header-actions">
            <button class="action-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
              </svg>
              <span class="notification-dot"></span>
            </button>
            <div class="user-profile">
              <img src="https://via.placeholder.com/36" alt="Admin User" />
              <div class="user-info">
                <span class="user-name">Sophie Martin</span>
                <span class="user-role">Administrateur</span>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </div>
          </div>
        </div>
      </header>

      <!-- Dashboard Content -->
      <div class="admin-content">
        <div class="dashboard-header">
          <h1>Gestion des Commentaires</h1>
          <div class="date-filter">
            <select id="commentFilter" class="btn btn-secondary">
              <option value="all">Tous les commentaires</option>
              <option value="pending">En attente</option>
              <option value="approved">Approuvés</option>
              <option value="reported">Signalés</option>
              <option value="spam">Spam</option>
            </select>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
          <!-- Total Comments -->
          <div class="stat-card">
            <div class="stat-icon" style="background-color: rgba(33, 150, 243, 0.1)">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="#2196f3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
              </svg>
            </div>
            <div class="stat-content">
              <h3>Total Commentaires</h3>
              <div class="stat-value"><?= $stats['total'] ?? 0 ?></div>
              <div class="stat-change positive">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="18 15 12 9 6 15"></polyline>
                </svg>
                <span>+12%</span>
              </div>
            </div>
          </div>

          <!-- Pending Comments -->
          <div class="stat-card">
            <div class="stat-icon" style="background-color: rgba(227, 196, 58, 0.1)">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="rgb(227, 196, 58)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
            </div>
            <div class="stat-content">
              <h3>En attente</h3>
              <div class="stat-value"><?= $stats['pending'] ?? 0 ?></div>
              <div class="stat-change negative">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
                <span>+5%</span>
              </div>
            </div>
          </div>

          <!-- Reported Comments -->
          <div class="stat-card">
            <div class="stat-icon" style="background-color: rgba(244, 67, 54, 0.1)">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="#f44336" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
              </svg>
            </div>
            <div class="stat-content">
              <h3>Signalés</h3>
              <div class="stat-value"><?= $stats['reported'] ?? 0 ?></div>
              <div class="stat-change negative">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
                <span>+2%</span>
              </div>
            </div>
          </div>

          <!-- Approved Comments -->
          <div class="stat-card">
            <div class="stat-icon" style="background-color: rgba(76, 175, 80, 0.1)">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="#4caf50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
            </div>
            <div class="stat-content">
              <h3>Approuvés</h3>
              <div class="stat-value"><?= $stats['approved'] ?? 0 ?></div>
              <div class="stat-change positive">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="18 15 12 9 6 15"></polyline>
                </svg>
                <span>+15%</span>
              </div>
            </div>
          </div>
        </div>
        <!-- Charts Section -->
        <div class="charts-section">
          <!-- Comments Over Time Chart -->
          <div class="chart-container">
            <div class="chart-header">
              <h2>Commentaires par période</h2>
              <div class="chart-actions">
                <button class="btn btn-text active" onclick="updateCharts('day')">Jour</button>
                <button class="btn btn-text" onclick="updateCharts('week')">Semaine</button>
                <button class="btn btn-text" onclick="updateCharts('month')">Mois</button>
              </div>
            </div>
            <div class="chart-body">
              <div class="chart-placeholder">
                <div class="chart-bars" id="chartBars">
                  <!-- Dynamic bars will be inserted here by JavaScript -->
                </div>
              </div>
            </div>
          </div>

          <!-- Status Distribution Pie Chart -->
          <div class="chart-container">
            <div class="chart-header">
              <h2>Répartition par statut</h2>
              <div class="chart-actions">
                <button class="btn btn-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="19" cy="12" r="1"></circle>
                    <circle cx="5" cy="12" r="1"></circle>
                  </svg>
                </button>
              </div>
            </div>
            <div class="chart-body">
              <div class="chart-placeholder">
                <div class="pie-chart">
                  <?php
                  $start = 0;
                  $colors = [
                    'approved' => '#4caf50', // Green
                    'pending' => 'rgb(227, 196, 58)', // Yellow
                    'reported' => '#f44336', // Red
                    'spam' => '#9c27b0' // Purple
                  ];

                  foreach ($statusDistribution as $status => $percentage):
                    $end = $start + ($percentage / 100);
                  ?>
                    <div class="pie-segment" style="
              --segment-start: <?= $start ?>;
              --segment-end: <?= $end ?>;
              --segment-color: <?= $colors[$status] ?>;
            "></div>
                  <?php $start = $end;
                  endforeach; ?>
                </div>
                <div class="pie-legend">
                  <?php foreach ($statusDistribution as $status => $percentage): ?>
                    <div class="legend-item">
                      <span class="legend-color" style="background-color: <?= $colors[$status] ?>"></span>
                      <span>
                        <?= match ($status) {
                          'approved' => 'Approuvés',
                          'pending' => 'En attente',
                          'reported' => 'Signalés',
                          'spam' => 'Spam'
                        } ?> (<?= number_format($percentage, 0) ?>%)
                      </span>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Comments Table -->
        <div class="table-section">
          <div class="table-header">
            <h2>Liste des commentaires</h2>
            <div class="table-actions">
              <div class="article-filter">
                <select id="articleFilter" class="btn btn-secondary">
                  <option value="all">Tous les articles</option>
                  <option value="1">Les tendances entrepreneuriales en 2023</option>
                  <option value="2">Comment lever des fonds pour votre startup</option>
                  <option value="3">L'importance du marketing digital</option>
                  <option value="4">Innovations technologiques pour entrepreneurs</option>
                </select>
              </div>
            </div>
          </div>

          <div class="table-container">
            <table class="admin-table" id="commentsTable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>ID De l'article</th>
                  <th>Nom de l'article</th>
                  <th>User Id</th>
                  <th>Auteur</th>
                  <th>Contenu</th>
                  <th>Date De Publication</th>
                  <th>likes</th>
                  <th>Parent id </th>
                  <th>Status</th>
                  <th>Read comment</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              <tbody>
                <?php
                // Define status labels in French
                $statusLabels = [
                  'pending' => 'En attente',
                  'approved' => 'Approuvé',
                  'reported' => 'Signalé',
                  'spam' => 'Spam'
                ];

                foreach ($listeCommentaire as $comment) :
                  // Format date
                  $formattedDate = date('d/m/Y H:i', strtotime($comment['date_publication']));
                ?>
                  <tr data-status="<?= htmlspecialchars($comment['status']) ?>">
                    <td><?= htmlspecialchars($comment['id']) ?></td>
                    <td><?= htmlspecialchars($comment['article_id']) ?></td>
                    <td>
                      <div class="comment-author-cell">

                        <span><?= htmlspecialchars($comment['nom_article'] ?? 'N/A') ?></span>
                      </div>
                    </td>
                    <td><?= htmlspecialchars($comment['user_id']) ?></td>
                    <td><?= htmlspecialchars($comment['auteur']) ?></td>
                    <td class="comment-content-cell"><?= htmlspecialchars($comment['contenu']) ?></td>
                    <td><?= $formattedDate ?></td>
                    <td><?= htmlspecialchars($comment['likes']) ?></td>
                    <td><?= htmlspecialchars($comment['parent_id']) ?></td>
                    <td>
                      <span class="table-badge status <?= htmlspecialchars($comment['status']) ?>">
                        <?= $statusLabels[$comment['status']] ?? htmlspecialchars($comment['status']) ?>
                      </span>
                    </td>
                    <td><?= htmlspecialchars($comment['is_read']) ?></td>
                    <td>
                      <div class="table-actions">
                        <button class="action-icon approve-btn" title="Approuver">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                          </svg>
                        </button>
                        <button class="action-icon delete-btn" title="Supprimer">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <div class="table-footer">
                <div class="pagination">
                  <button class="pagination-btn" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                  </button>
                  <button class="pagination-btn active">1</button>
                  <button class="pagination-btn">2</button>
                  <button class="pagination-btn">3</button>
                  <span class="pagination-ellipsis">...</span>
                  <button class="pagination-btn">8</button>
                  <button class="pagination-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                  </button>
                </div>
              </div>
          </div>

          <!-- Alert Container -->
          <div class="alert-container" id="alertContainer"></div>
        </div>
    </main>
  </div>

  <script src="commentaire.js"></script>
  <script src="assets/back.js"></script>
</body>

</html>