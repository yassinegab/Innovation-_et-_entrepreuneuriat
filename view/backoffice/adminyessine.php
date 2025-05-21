<?php
// admin.php
include("../../controller/articlecontroller.php");
// En haut du fichier
require_once '../../controller/commentairecontroller.php';
$commentController = new CommentaireController();
$comments = $commentController->getAllComments();
$unreadCommentsCount = $commentController->getUnreadCommentsCount();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (ob_get_level()) {
    ob_end_clean();
  }
  header('Content-Type: application/json');


  $articleController = new articlecontroller();


  try {
    // --- MISE À JOUR ---
    if (isset($_POST['update'])) {
      $existingArticle = $articleController->getArticleById($_POST['id']);
      // Gestion d'image pour modification
      $imagePath = null;
      if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0777, true);
        }
        $imageName = basename($_FILES['image']['name']);
        $imagePath = $uploadDir . time() . "_" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
      } elseif (isset($_POST['existingImage'])) {
        $imagePath = $_POST['existingImage']; // garder ancienne image
      }

      $updatedArticle = new article(
        $_POST['id'],
        htmlspecialchars($_POST['nom_article']),
        $_POST['date_soumission'],
        htmlspecialchars($_POST['categorie']),
        htmlspecialchars($_POST['contenu']),
        $imagePath,
        $existingArticle->getViews(),  // Preserve existing views
        $existingArticle->getLikes(),

      );

      $ok = $articleController->modification($updatedArticle);
      echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Article mis à jour avec succès.' : 'Échec de la mise à jour.'
      ]);
      exit();
    }

    // --- AJOUT ---
    $imagePath = null;
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
      $uploadDir = __DIR__ . '/uploads/';
      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
      }
      $imageName = basename($_FILES['image']['name']);
      $targetPath = $uploadDir . $imageName;
      if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $imagePath = '/uploads/' . $imageName;
      } else {
        echo json_encode([
          'success' => false,
          'message' => "Échec du téléchargement de l'image."
        ]);
        exit();
      }
    }

    $newArticle = new article(
      null,
      htmlspecialchars($_POST['articlenom']),
      $_POST['datesoumission'],
      htmlspecialchars($_POST['categoriearticle']),
      htmlspecialchars($_POST['contenu']),
      $imagePath,
      0, // views
      0  // likes
    );

    $ok = $articleController->ajout($newArticle);
    echo json_encode([
      'success' => $ok,
      'message' => $ok ? 'Article ajouté avec succès.' : "Échec de l'ajout de l'article."
    ]);
  } catch (Exception $e) {
    echo json_encode([
      'success' => false,
      'message' => 'Erreur interne : ' . $e->getMessage()
    ]);
  }

  exit();
}

// 2) Sinon (GET), on inclut le controller et on affiche la page HTML

$articleController = new articlecontroller();
$list = $articleController->afficher();
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  if ($articleController->supp($id)) {
    header("Location: admin.php?message=deleted");
  } else {
    header("Location: admin.php?error=delete_failed");
  }
  exit();
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Administration - STELLIFEROUS</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="stylesyessine.css" />
  <link rel="stylesheet" href="theme-customyessine.css" />
  <link rel="stylesheet" href="adminyessine.css" />
  <link rel="stylesheet" href="assets/back.css">
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
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
                <a href="#" class="sidebar-menu-link has-submenu"  onclick="toggleSubmenu(event)">
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
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
          </button>
          <div class="breadcrumb">
            <span>Administration</span>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
            <span>Tableau de bord</span>
          </div>
        </div>
        <div class="header-right">
          <div class="header-search">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" placeholder="Rechercher..." />
          </div>
          <div class="header-actions">
            <button class="action-btn">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
              </svg>
              <span class="notification-dot"></span>
            </button>
            <div class="user-profile">
              <img
                src="/placeholder.svg?height=40&width=40"
                alt="Admin User" />
              <div class="user-info">
                <span class="user-name">Sophie Martin</span>
                <span class="user-role">Administrateur</span>
              </div>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </div>
          </div>
        </div>
      </header>

      <!-- Dashboard Content -->
      <div class="admin-content" id="articlesDashboard">
        <div class="dashboard-header">
          <h1>Tableau de bord</h1>
          <div class="category-filter">
            <select id="categoryFilter" class="btn btn-secondary">
              <option value="all">Toutes les catégories</option>
              <option value="technologie">Technologie</option>
              <option value="marketing">Marketing</option>
              <option value="innovation">Innovation</option>
            </select>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
          <!-- Total Articles -->
          <div class="stat-card">
            <div
              class="stat-icon"
              style="background-color: rgba(33, 150, 243, 0.1)">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#2196f3"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M4 4h16v16H4z"></path>
                <line x1="8" y1="8" x2="16" y2="8"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
                <line x1="8" y1="16" x2="12" y2="16"></line>
              </svg>
            </div>
            <div class="stat-content">
              <h3>Total Articles</h3>
              <div class="stat-value">120</div>
              <div class="stat-change positive">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">
                  <polyline points="18 15 12 9 6 15"></polyline>
                </svg>
                <span>+8%</span>
              </div>
            </div>
          </div>

          <!-- Articles Published This Month -->
          <div class="stat-card">
            <div
              class="stat-icon"
              style="background-color: rgba(76, 175, 80, 0.1)">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#4caf50"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M3 3h18v18H3z"></path>
                <line x1="3" y1="8" x2="21" y2="8"></line>
                <line x1="3" y1="16" x2="21" y2="16"></line>
              </svg>
            </div>
            <div class="stat-content">
              <h3>Articles This Month</h3>
              <div class="stat-value">15</div>
              <div class="stat-change positive">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">
                  <polyline points="18 15 12 9 6 15"></polyline>
                </svg>
                <span>+12%</span>
              </div>
            </div>
          </div>

          <!-- Most Popular Category -->
          <div class="stat-card">
            <div
              class="stat-icon"
              style="background-color: rgba(227, 196, 58, 0.1)">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="rgb(227, 196, 58)"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
            </div>
            <div class="stat-content">
              <h3>Popular Category</h3>
              <div class="stat-value">Technologie</div>
              <div class="stat-change positive">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">
                  <polyline points="18 15 12 9 6 15"></polyline>
                </svg>
                <span>+20%</span>
              </div>
            </div>
          </div>

          <!-- Articles Pending Review -->
          <div class="stat-card">
            <div
              class="stat-icon"
              style="background-color: rgba(244, 67, 54, 0.1)">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#f44336"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path
                  d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
              </svg>
            </div>
            <div class="stat-content">
              <h3>Pending Review</h3>
              <div class="stat-value">5</div>
              <div class="stat-change negative">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round">
                  <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
                <span>-10%</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
          <!-- Articles Submissions Over Time -->
          <div class="chart-container">
            <div class="chart-header">
              <h2>Soumissions d'articles</h2>
              <div class="chart-actions">
                <button class="btn btn-text active">Jour</button>
                <button class="btn btn-text">Semaine</button>
                <button class="btn btn-text">Mois</button>
              </div>
            </div>
            <div class="chart-body">
              <div class="chart-placeholder">
                <!-- Placeholder for bar chart -->
                <div class="chart-bars">
                  <div class="chart-bar" style="height: 20%"></div>
                  <div class="chart-bar" style="height: 50%"></div>
                  <div class="chart-bar" style="height: 70%"></div>
                  <div class="chart-bar" style="height: 40%"></div>
                  <div class="chart-bar" style="height: 60%"></div>
                  <div class="chart-bar" style="height: 80%"></div>
                  <div class="chart-bar" style="height: 30%"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Articles Distribution by Category -->
          <div class="chart-container">
            <div class="chart-header">
              <h2>Répartition des articles</h2>
              <div class="chart-actions">
                <button class="btn btn-icon">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">
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
                  <div
                    class="pie-segment"
                    style="
                        --segment-start: 0;
                        --segment-end: 0.4;
                        --segment-color: rgb(227, 196, 58);
                      "></div>
                  <div
                    class="pie-segment"
                    style="
                        --segment-start: 0.4;
                        --segment-end: 0.7;
                        --segment-color: #4caf50;
                      "></div>
                  <div
                    class="pie-segment"
                    style="
                        --segment-start: 0.7;
                        --segment-end: 0.9;
                        --segment-color: #2196f3;
                      "></div>
                  <div
                    class="pie-segment"
                    style="
                        --segment-start: 0.9;
                        --segment-end: 1;
                        --segment-color: #9c27b0;
                      "></div>
                </div>
                <div class="pie-legend">
                  <div class="legend-item">
                    <span
                      class="legend-color"
                      style="background-color: rgb(227, 196, 58)"></span>
                    <span>Technologie (40%)</span>
                  </div>
                  <div class="legend-item">
                    <span
                      class="legend-color"
                      style="background-color: #4caf50"></span>
                    <span>Marketing (30%)</span>
                  </div>
                  <div class="legend-item">
                    <span
                      class="legend-color"
                      style="background-color: #2196f3"></span>
                    <span>Innovation (20%)</span>
                  </div>
                  <div class="legend-item">
                    <span
                      class="legend-color"
                      style="background-color: #9c27b0"></span>
                    <span>Autres (10%)</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <!-- Recent Articles Table -->
        <div class="table-section">
          <div class="table-header">
            <h2>Articles récents</h2>
            <button class="btn btn-primary" id="addArticleBtn">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
              Ajouter un article
            </button>
          </div>

          <div class="table-container">
            <table class="admin-table" id="articlesTable">
              <thead>
                <tr>
                  <th>ID Article</th>
                  <th>Nom de l'article</th>
                  <th>Date de soumission</th>
                  <th>Catégorie</th>
                  <th>Contenu</th>
                  <th>Image</th>
                  <th>views</th>
                  <th>Likes</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($list as $artic): ?>
                  <tr>
                    <td><?= htmlspecialchars($artic['ID_Article']); ?></td>
                    <td>
                      <div class="article-cell">
                        <span><?= htmlspecialchars($artic['nom_article']); ?></span>
                      </div>
                    </td>
                    <td><?= htmlspecialchars($artic['Date_de_soumission']); ?></td>
                    <td>
                      <span class="table-badge technologie">
                        <?= htmlspecialchars($artic['Catégorie']); ?>
                      </span>
                    </td>
                    <td>
                      <div class="article-cell">
                        <?= nl2br(htmlspecialchars($artic['contenu'])); ?>
                      </div>
                    </td>
                    <td>
                      <div class="article-cell">
                        <?php if (!empty($artic['image'])): ?>
                          <img src="uploads/<?= htmlspecialchars(basename($artic['image'])) ?>"
                            alt="Visuel de <?= htmlspecialchars($artic['nom_article']) ?>"
                            style="max-width:100px; height:auto;">
                        <?php endif; ?>
                      </div>
                    </td>
                    <td class="article-cell">
                      <?= htmlspecialchars($artic['views']); ?>
                    </td>
                    <td class="article-cell">
                      <?= htmlspecialchars($artic['likes']); ?>
                    </td>
                    <td>
                      <div class="table-actions">
                        <button type="button" class="action-icon modify-btn" title="Modifier">
                          <!-- pencil/edit icon -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                          </svg>
                        </button>
                        <a class="action-icon" href="admin.php?id=<?= $artic['ID_Article']; ?>" title="Supprimer">
                          <!-- trash/delete icon -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                          </svg>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="table-footer">
          <div class="pagination">
            <!-- Gardez la même pagination -->
            <button class="pagination-btn" disabled>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
              </svg>
            </button>
            <button class="pagination-btn active">1</button>
            <button class="pagination-btn">2</button>
            <button class="pagination-btn">3</button>
            <span class="pagination-ellipsis">...</span>
            <button class="pagination-btn">8</button>
            <button class="pagination-btn">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </button>
          </div>
        </div>
      </div>
  </div>
  <!-- Comments Section -->
  <div class="admin-content" id="commentsDashboard" style="display: none;">
    <div class="dashboard-header">
      <h1>Gestion des commentaires</h1>
      <div class="category-filter">
        <select id="categoryFilter" class="btn btn-secondary">
          <option value="all">Tous les articles</option>
          <?php foreach ($articles as $article): ?>
            <option value="<?= htmlspecialchars($article['ID_Article']); ?>">
              <?= htmlspecialchars($article['nom_article']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="table-header">
      <h2>Commentaires récents</h2>
      <button class="btn btn-primary" id="addCommentBtn">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"></line>
          <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Ajouter un commentaire
      </button>
      <div class="table-section" id="commentsSection">
        <div class="table-header">
          <h2>Gestion des commentaires</h2>
          <div class="filter-options">
            <select id="commentFilter" class="btn btn-secondary">
              <option value="all">Tous les commentaires</option>
              <option value="reported">Signalés</option>
              <option value="pending">En attente</option>
              <option value="approved">Approuvés</option>
            </select>
          </div>
        </div>

        <div class="table-container">
          <table class="admin-table" id="commentsTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Auteur</th>
                <th>Contenu</th>
                <th>Article</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($comments as $comment): ?>
                <tr data-status="<?= strtolower($comment->getStatus()) ?>">
                  <td><?= $comment->getId() ?></td>
                  <td><?= htmlspecialchars($comment->getAuteur()) ?></td>
                  <td class="comment-content"><?= nl2br(htmlspecialchars($comment->getContenu())) ?></td>
                  <td><?= htmlspecialchars($comment->getArticleTitle()) ?></td>
                  <td><?= $comment->getDatePublication()->format('d/m/Y H:i') ?></td>
                  <td>
                    <span class="table-badge status-<?= strtolower($comment->getStatus()) ?>">
                      <?= $comment->getStatus() ?>
                    </span>
                  </td>
                  <td>
                    <div class="table-actions">
                      <button class="action-icon approve-btn" title="Approuver">
                        <svg><!-- Icône check --></svg>
                      </button>
                      <button class="action-icon delete-btn" title="Supprimer">
                        <svg><!-- Icône poubelle --></svg>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      </main>
    </div>

    <script src="adminyessine.js"></script>
    <script src="assets/back.js"></script>
</body>

</html>