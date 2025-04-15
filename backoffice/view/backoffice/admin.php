<?php
include("../../controller/articlecontroller.php");
$article = new articlecontroller();
$list = $article->afficher();

// Fonctions de validation
function isValidName($name)
{
  return !preg_match('/[0-9]/', $name);
}

function isValidDate($date)
{
  $today = new DateTime('now');
  $inputDate = DateTime::createFromFormat('Y-m-d', $date);
  return $inputDate && $inputDate >= $today;
}

// Traitement des requêtes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['update'])) {
    // Validation modification
    if (!isValidName($_POST['nom_article'])) {
      header("Location: admin.php?error=invalid_name");
      exit();
    }

    if (!isValidDate($_POST['date_soumission'])) {
      header("Location: admin.php?error=invalid_date");
      exit();
    }

    // Mise à jour de l'article
    $updatedArticle = new article(
      $_POST['id'],
      htmlspecialchars($_POST['nom_article']),
      $_POST['date_soumission'],
      htmlspecialchars($_POST['categorie'])
    );

    if ($article->modification($updatedArticle)) {
      header("Location: admin.php?message=updated");
    } else {
      header("Location: admin.php?error=update_failed");
    }
    exit();
  } else {
    // Validation ajout
    if (!isValidName($_POST['articlenom'])) {
      header("Location: admin.php?error=invalid_name");
      exit();
    }

    if (!isValidDate($_POST['datesoumission'])) {
      header("Location: admin.php?error=invalid_date");
      exit();
    }

    // Ajout du nouvel article
    $newArticle = new article(
      null,
      htmlspecialchars($_POST['articlenom']),
      $_POST['datesoumission'],
      htmlspecialchars($_POST['categoriearticle'])
    );

    if ($article->ajout($newArticle)) {
      header("Location: admin.php?message=added");
    } else {
      header("Location: admin.php?error=add_failed");
    }
    exit();
  }
}

// Gestion de la suppression
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  if ($article->supp($id)) {
    header("Location: admin.php?message=deleted");
  } else {
    header("Location: admin.php?error=delete_failed");
  }
  exit();
}
?>
<!-- Ajoutez cette section en haut de votre admin.php -->
<div class="alert-container">
  <?php if (isset($_GET['message'])): ?>
    <div class="alert alert-success">
      <?php
      switch ($_GET['message']) {
        case 'added':
          echo "✅ Article ajouté avec succès !";
          break;
        case 'updated':
          echo "✅ Article modifié avec succès !";
          break;
        case 'deleted':
          echo "✅ Article supprimé avec succès !";
          break;
        default:
          echo "Opération réussie !";
      }
      ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">
      <?php
      switch ($_GET['error']) {
        case 'invalid_name':
          echo "❌ Le nom ne doit pas contenir de chiffres !";
          break;
        case 'invalid_date':
          echo "❌ La date doit être aujourd'hui ou ultérieure !";
          break;
        case 'missing_fields':
          echo "❌ Tous les champs sont obligatoires !";
          break;
        case 'add_failed':
          echo "❌ Échec de l'ajout de l'article !";
          break;
        case 'update_failed':
          echo "❌ Échec de la mise à jour !";
          break;
        case 'delete_failed':
          echo "❌ Échec de la suppression !";
          break;
        default:
          echo "Une erreur est survenue !";
      }
      ?>
    </div>
  <?php endif; ?>
</div>








<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Administration - EntrepreHub</title>
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="theme-custom.css" />
  <link rel="stylesheet" href="admin.css" />
  <link
    rel="stylesheet"
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
          <li class="nav-item active">
            <a href="#dashboard">
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
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
              </svg>
              <span>Users</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#articles">
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
                <path d="M4 4h16v16H4z"></path>
                <line x1="8" y1="8" x2="16" y2="8"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
                <line x1="8" y1="16" x2="12" y2="16"></line>
              </svg>
              <span>Articles</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#analytics">
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
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
              </svg>
              <span>Analytiques</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#messages">
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
                <path
                  d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
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
                <circle cx="12" cy="12" r="3"></circle>
                <path
                  d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
              </svg>
              <span>Paramètres</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#help">
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
      <div class="admin-content">
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
            <!-- <div class="add-article-form-container" style="display: none;">

              <h3>Ajouter un article</h3>
              <form action="admin.php" method="POST" id="addArticleForm">
                <input type="hidden" id="articlenom" name="articlenom" />
                <input type="hidden" id="datesoumission" name="datesoumission" />
                <input type="hidden" id="categoriearticle" name="categoriearticle" />
                <button type="submit" class="btn btn-primary" name="confirm">Enregistrer</button>
              </form>
            </div> -->
            <h2>Articles récents</h2>
            <button class="btn btn-primary" id="addArticleBtn">
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
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($list as $artic) {
                ?>
                  <tr>
                    <td><?= $artic['ID_Article']; ?></td>
                    <td>
                      <div class="article-cell">
                        <span><?= $artic['nom_article']; ?></span>
                      </div>
                    </td>
                    <td><?= $artic['Date_de_soumission']; ?></span>
          </div>
          </td>
          <td>
            <span class="table-badge technologie"><?= $artic['Catégorie']; ?></span>
          </td>
          <td>
            <div class="table-actions">
              <button type="button" class="action-icon modify-btn">

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
                  <path
                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                  <path
                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
              </button>
              <a class="action-icon" href="admin.php?id=<?= $artic['ID_Article']; ?>">
                <button class="action-icon">
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
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path
                      d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  </svg>
              </a>
              </button>
            </div>
          </td>
          </tr>
          <tr>
          <?php

                }
          ?>








          </tbody>
          </table>
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

  </main>
  </div>

  <script src="admin.js"></script>
</body>

</html>