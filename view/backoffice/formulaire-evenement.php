<?php
require_once __DIR__ . '/../../controller/evenController.php';
require_once __DIR__ . '/../../model/evenmodel.php';

// Initialiser le contrôleur
$evenementcontroller = new EvenementController();

// Variables pour les messages
$successMessage = "";
$errorMessage = "";

// Traitement des filtres et de la recherche
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
$filterCategory = isset($_GET['category']) ? $_GET['category'] : '';
$filterEspaceFumeur = isset($_GET['espace_fumeur']) ? (int)$_GET['espace_fumeur'] : null;
$filterAccompagnateur = isset($_GET['accompagnateur']) ? (int)$_GET['accompagnateur'] : null;

// Récupérer toutes les catégories pour le filtre
$categories = $evenementcontroller->getAllCategories();

// Charger tous les événements par défaut
$list = $evenementcontroller->listeven();

// Appliquer les filtres et la recherche seulement si demandé
if (!empty($searchKeyword)) {
    $list = $evenementcontroller->searchEvents($searchKeyword);
} elseif (!empty($filterCategory)) {
    $list = $evenementcontroller->filterByCategory($filterCategory);
} elseif ($filterEspaceFumeur !== null) {
    $list = $evenementcontroller->filterByEspaceFumeur($filterEspaceFumeur);
} elseif ($filterAccompagnateur !== null) {
    $list = $evenementcontroller->filterByAccompagnateurAutorise($filterAccompagnateur);
}

// Traitement des actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ajout d'un événement
    if (isset($_POST['ajout'])) {
        $nom = $_POST['nom'] ?? '';
        $date = $_POST['date'] ?? '';
        $lieu = $_POST['lieu'] ?? '';
        $capacite = (int)($_POST['capacite'] ?? 0);
        $categorie = $_POST['categorie'] ?? null;
        $prix = (float)($_POST['prix'] ?? 0);
        $espace_fumeur = isset($_POST['espace_fumeur']) ? 1 : 0;
        $accompagnateur_autorise = isset($_POST['accompagnateur_autorise']) ? 1 : 0;

        if (empty($nom) || empty($date) || empty($lieu) || $capacite <= 0) {
            $errorMessage = "Les champs Nom, Date, Lieu et Capacité sont obligatoires. La capacité doit être supérieure à 0.";
        } else {
            $evenement = new Evenement(
                null, $nom, $date, $lieu, $capacite, $categorie, $prix, $espace_fumeur, $accompagnateur_autorise
            );
            
            if ($evenementcontroller->addEvenement($evenement)) {
                $successMessage = "Événement ajouté avec succès !";
                // Rafraîchir la liste des événements
                $list = $evenementcontroller->listeven();
            } else {
                $errorMessage = "Erreur lors de l'ajout de l'événement.";
            }
        }
    }

    // Suppression d'un événement
    else if (isset($_POST['id_ev']) && isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id_ev'];
        if ($evenementcontroller->deleteeven($id)) {
            $successMessage = "Événement supprimé avec succès !";
            // Rafraîchir la liste des événements
            $list = $evenementcontroller->listeven();
        } else {
            $errorMessage = "Erreur lors de la suppression de l'événement.";
        }
    }

   

    // Mise à jour d'un événement
    else if (isset($_POST['id_ev']) && isset($_POST['action']) && $_POST['action'] == 'update') {
        $id = $_POST['id_ev'];
        $nom = $_POST['nom'] ?? '';
        $date = $_POST['date'] ?? '';
        $lieu = $_POST['lieu'] ?? '';
        $capacite = (int)($_POST['capacite'] ?? 0);
        $categorie = $_POST['categorie'] ?? null;
        $prix = (float)($_POST['prix'] ?? 0);
        $espace_fumeur = isset($_POST['espace_fumeur']) ? 1 : 0;
        $accompagnateur_autorise = isset($_POST['accompagnateur_autorise']) ? 1 : 0;

        if (empty($nom) || empty($date) || empty($lieu) || $capacite <= 0) {
            $errorMessage = "Les champs Nom, Date, Lieu et Capacité sont obligatoires. La capacité doit être supérieure à 0.";
        } else {
            if ($evenementcontroller->updateven(
                $id, $nom, $date, $lieu, $capacite, $categorie, $prix, $espace_fumeur, $accompagnateur_autorise
            )) {
                $successMessage = "Événement mis à jour avec succès !";
                // Rafraîchir la liste des événements
                $list = $evenementcontroller->listeven();
            } else {
                $errorMessage = "Erreur lors de la mise à jour de l'événement.";
            }
        }
    }
}

// Récupérer un événement pour l'édition
$eventToEdit = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $eventToEdit = $evenementcontroller->getEventById($_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - EntrepreHub</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="theme-custom.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <style>
        /* Styles pour les messages */
        .success-message {
            background-color: rgba(16, 185, 129, 0.1);
            border-left: 4px solid #10b981;
            color: #065f46;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: <?= !empty($successMessage) ? 'block' : 'none' ?>;
        }
        
        .error-message {
            background-color: rgba(239, 68, 68, 0.1);
            border-left: 4px solid #ef4444;
            color: #b91c1c;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: <?= !empty($errorMessage) ? 'block' : 'none' ?>;
        }
        
        /* Style pour la barre de recherche et les filtres */
        .search-filter-container {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .search-filter-container input,
        .search-filter-container select {
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
        }
        
        .search-filter-container button {
            background-color:rgb(229, 145, 28);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        /* Style pour les formulaires */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .form-full-width {
            grid-column: span 2;
        }
        
        /* Style pour les badges */
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .status-yes {
            background-color: rgba(16, 185, 129, 0.1);
            color: #065f46;
        }
        
        .status-no {
            background-color: rgba(239, 68, 68, 0.1);
            color: #b91c1c;
        }
        
        /* Style pour les prix */
        .price-tag {
            font-weight: 600;
        }
        
        .price-free {
            color: #10b981;
        }
        
        /* Checkbox styling */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
        }
        
        /* Actions sur les événements */
        .event-actions {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        
        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.875rem;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background-color:rgb(158, 128, 10);
            color: white;
        }
        
        .btn-primary:hover {
            background-color:rgb(185, 115, 11);
        }
        
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        
        .btn-danger {
            background-color: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid #e5e7eb;
            color: #374151;
        }
        
        .btn-outline:hover {
            background-color: #f9fafb;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-full-width {
                grid-column: span 1;
            }
            
            .event-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body class="admin-body">
    <div class="admin-container">
        <!-- Sidebar (inchangé) -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>EntrepreHub</h2>
                <span class="admin-badge">Admin</span>
            </div>
            
            <nav class="sidebar-nav">
                <!-- Navigation sidebar inchangée -->
                <ul>
                    <li class="nav-item">
                        <a href="#dashboard">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li class="nav-item active">
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
                    <li class="nav-item">
                        <a href="#speakers">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Intervenants</span>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"  width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                        <span>Événements</span>
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
            
            <!-- Main Content -->
            <div class="container">
                <h1>Gestion des événements</h1>
                
                <!-- Messages de succès/erreur -->
                <div id="success-message" class="success-message">
                    <?= htmlspecialchars($successMessage) ?>
                </div>
                
                <div id="error-message" class="error-message">
                    <?= htmlspecialchars($errorMessage) ?>
                </div>
                
                <!-- Barre de recherche et filtres -->
                <div class="search-filter-container">
                    <form action="" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; width: 100%;">
                        <input type="text" name="search" placeholder="Rechercher un événement..." value="<?= htmlspecialchars($searchKeyword) ?>" style="flex: 1;">
                        
                        <select name="category" style="min-width: 150px;">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category) ?>" <?= $filterCategory == $category ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <select name="espace_fumeur" style="min-width: 150px;">
                            <option value="">Espace fumeur</option>
                            <option value="1" <?= $filterEspaceFumeur === 1 ? 'selected' : '' ?>>Oui</option>
                            <option value="0" <?= $filterEspaceFumeur === 0 ? 'selected' : '' ?>>Non</option>
                        </select>
                        
                        <select name="accompagnateur" style="min-width: 150px;">
                            <option value="">Accompagnateur</option>
                            <option value="1" <?= $filterAccompagnateur === 1 ? 'selected' : '' ?>>Autorisé</option>
                            <option value="0" <?= $filterAccompagnateur === 0 ? 'selected' : '' ?>>Non autorisé</option>
                        </select>
                        
                        <button type="submit">Rechercher</button>
                        
                        <?php if (!empty($searchKeyword) || !empty($filterCategory) || $filterEspaceFumeur !== null || $filterAccompagnateur !== null): ?>
                            <a href="formulaire-evenement.php" class="btn btn-outline">Réinitialiser</a>
                        <?php endif; ?>
                    </form>
                </div>
                
                <!-- Formulaire d'ajout/modification -->
                <form id="event-form" action="formulaire-evenement.php" method="POST" onsubmit="return validateForm()">
                    <?php if ($eventToEdit): ?>
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id_ev" value="<?= $eventToEdit['id_ev'] ?>">
                        <h2>Modifier l'événement</h2>
                    <?php else: ?>
                        <h2>Ajouter un événement</h2>
                    <?php endif; ?>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nom">Nom de l'événement *</label>
                            <input type="text" id="nom" name="nom" placeholder="Entrez le nom de l'événement" value="<?= $eventToEdit ? htmlspecialchars($eventToEdit['nom']) : '' ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="date">Date *</label>
                            <input type="date" id="date" name="date" value="<?= $eventToEdit ? htmlspecialchars($eventToEdit['date']) : '' ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="lieu">Lieu *</label>
                            <input type="text" id="lieu" name="lieu" placeholder="Entrez le lieu de l'événement" value="<?= $eventToEdit ? htmlspecialchars($eventToEdit['lieu']) : '' ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="capacite">Capacité *</label>
                            <input type="number" id="capacite" name="capacite" min="1" placeholder="Nombre de participants" value="<?= $eventToEdit ? htmlspecialchars($eventToEdit['capacite']) : '' ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="prix">Prix (TND) *</label>
                            <input type="number" id="prix" name="prix" min="0" step="0.001" placeholder="Prix en dinars tunisiens" value="<?= $eventToEdit && isset($eventToEdit['prix']) ? htmlspecialchars($eventToEdit['prix']) : '0.000' ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="categorie">Catégorie *</label>
                            <select id="categorie" name="categorie" required>
                                <option value="">Sélectionnez une catégorie</option>
                                <option value="Conférence" <?= ($eventToEdit && isset($eventToEdit['categorie']) && $eventToEdit['categorie'] == 'Conférence') ? 'selected' : '' ?>>Conférence</option>
                                <option value="Atelier" <?= ($eventToEdit && isset($eventToEdit['categorie']) && $eventToEdit['categorie'] == 'Atelier') ? 'selected' : '' ?>>Atelier</option>
                                <option value="Formation" <?= ($eventToEdit && isset($eventToEdit['categorie']) && $eventToEdit['categorie'] == 'Formation') ? 'selected' : '' ?>>Formation</option>
                                <option value="Networking" <?= ($eventToEdit && isset($eventToEdit['categorie']) && $eventToEdit['categorie'] == 'Networking') ? 'selected' : '' ?>>Networking</option>
                                <option value="Séminaire" <?= ($eventToEdit && isset($eventToEdit['categorie']) && $eventToEdit['categorie'] == 'Séminaire') ? 'selected' : '' ?>>Séminaire</option>
                                <option value="Exposition" <?= ($eventToEdit && isset($eventToEdit['categorie']) && $eventToEdit['categorie'] == 'Exposition') ? 'selected' : '' ?>>Exposition</option>
                                <option value="Lancement de produit" <?= ($eventToEdit && isset($eventToEdit['categorie']) && $eventToEdit['categorie'] == 'Lancement de produit') ? 'selected' : '' ?>>Lancement de produit</option>
                                <option value="Hackathon" <?= ($eventToEdit && isset($eventToEdit['categorie']) && $eventToEdit['categorie'] == 'Hackathon') ? 'selected' : '' ?>>Hackathon</option>
                                <option value="Table ronde" <?= ($eventToEdit && isset($eventToEdit['categorie']) && $eventToEdit['categorie'] == 'Table ronde') ? 'selected' : '' ?>>Table ronde</option>
                                <option value="Autre" <?= ($eventToEdit && isset($eventToEdit['categorie']) && $eventToEdit['categorie'] == 'Autre') ? 'selected' : '' ?>>Autre</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="espace_fumeur" name="espace_fumeur" <?= ($eventToEdit && isset($eventToEdit['espace_fumeur']) && $eventToEdit['espace_fumeur']) ? 'checked' : '' ?>>
                        <label for="espace_fumeur">Espace fumeur disponible</label>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="accompagnateur_autorise" name="accompagnateur_autorise" <?= ($eventToEdit && isset($eventToEdit['accompagnateur_autorise']) && $eventToEdit['accompagnateur_autorise']) ? 'checked' : '' ?>>
                        <label for="accompagnateur_autorise">Accompagnateur autorisé</label>
                    </div>
                    
                    <button type="submit" name="<?= $eventToEdit ? 'update' : 'ajout' ?>" class="btn btn-primary">
                        <?= $eventToEdit ? 'Mettre à jour' : 'Ajouter l\'événement' ?>
                    </button>
                    
                    <?php if ($eventToEdit): ?>
                        <a href="formulaire-evenement.php" class="btn btn-outline">Annuler</a>
                    <?php endif; ?>
                </form>
                
                <!-- Tableau des événements -->
                <div class="section">
                    <h2 class="section-title">Liste des événements</h2>
                    <div class="table-container">
                        <table id="events-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Date</th>
                                    <th>Lieu</th>
                                    <th>Capacité</th>
                                    <th>Catégorie</th>
                                    <th>Prix (TND)</th>
                                    <th>Espace fumeur</th>
                                    <th>Accompagnateur</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="events-list">
                                <?php if (empty($list)): ?>
                                    <tr class="no-events">
                                        <td colspan="10">Aucun événement n'a été trouvé</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($list as $even): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($even['id_ev']) ?></td>
                                            <td><?= htmlspecialchars($even['nom']) ?></td>
                                            <td><?= htmlspecialchars($even['date']) ?></td>
                                            <td><?= htmlspecialchars($even['lieu']) ?></td>
                                            <td><?= htmlspecialchars($even['capacite']) ?></td>
                                            <td><?= isset($even['categorie']) ? htmlspecialchars($even['categorie']) : 'Non définie' ?></td>
                                            
                                            <td>
                                                <?php if (isset($even['prix']) && $even['prix'] > 0): ?>
                                                    <span class="price-tag"><?= number_format((float)$even['prix'], 3, ',', ' ') ?> TND</span>
                                                <?php else: ?>
                                                    <span class="price-tag price-free">Gratuit</span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <td>
                                                <?php if (isset($even['espace_fumeur']) && $even['espace_fumeur']): ?>
                                                    <span class="status-badge status-yes">Oui</span>
                                                <?php else: ?>
                                                    <span class="status-badge status-no">Non</span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <td>
                                                <?php if (isset($even['accompagnateur_autorise']) && $even['accompagnateur_autorise']): ?>
                                                    <span class="status-badge status-yes">Autorisé</span>
                                                <?php else: ?>
                                                    <span class="status-badge status-no">Non autorisé</span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <td class="event-actions">
                                                <a href="?edit=<?= $even['id_ev'] ?>" class="btn btn-primary">Modifier</a>
                                                
                                                <form method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                                                    <input type="hidden" name="id_ev" value="<?= $even['id_ev'] ?>">
                                                    <input type="hidden" name="action" value="delete">
                                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                                </form>
                                                
                                               
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Fonction de validation du formulaire
        function validateForm() {
            const nom = document.getElementById('nom').value;
            const date = document.getElementById('date').value;
            const lieu = document.getElementById('lieu').value;
            const capacite = document.getElementById('capacite').value;
            const categorie = document.getElementById('categorie').value;
            
            if (!nom || !date || !lieu || !capacite || capacite < 1) {
                alert('Veuillez remplir tous les champs obligatoires correctement. La capacité doit être supérieure à 0.');
                return false;
            }
            
            if (!categorie) {
                alert('Veuillez sélectionner une catégorie.');
                return false;
            }
            
            return true;
        }
        
        // Masquer les messages après 5 secondes
        setTimeout(function() {
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            
            if (successMessage) successMessage.style.display = 'none';
            if (errorMessage) errorMessage.style.display = 'none';
        }, 5000);
    </script>
</body>
</html>
