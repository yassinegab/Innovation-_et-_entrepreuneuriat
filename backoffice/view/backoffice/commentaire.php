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
  <title>Administration des Commentaires - EntrepreHub</title>
  <link rel="stylesheet" href="commentaire.css" />
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
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
            <a href="#dashboard">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
              </svg>
              <span>Tableau de bord</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#events">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
              </svg>
              <span>Utilisateurs</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#articles">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16v16H4z"></path>
                <line x1="8" y1="8" x2="16" y2="8"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
                <line x1="8" y1="16" x2="12" y2="16"></line>
              </svg>
              <span>Articles</span>
            </a>
          </li>
          <li class="nav-item active">
            <a href="#comments">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
              </svg>
              <span>Commentaires</span>
              <span class="notification-badge">12</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#analytics">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
              </svg>
              <span>Analytiques</span>
            </a>
          </li>
        </ul>

        <div class="sidebar-divider"></div>

        <ul>
          <li class="nav-item">
            <a href="#settings">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"></circle>
                <path
                  d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                </path>
              </svg>
              <span>Paramètres</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#help">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
</body>

</html>