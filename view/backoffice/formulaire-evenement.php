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
        $imgTmp = $_FILES['image']['tmp_name'];
        $imgData = file_get_contents($imgTmp);

        if (empty($nom) || empty($date) || empty($lieu) || $capacite <= 0) {
            $errorMessage = "Les champs Nom, Date, Lieu et Capacité sont obligatoires. La capacité doit être supérieure à 0.";
        } else {
            $evenement = new Evenement(
                null, $nom, $date, $lieu, $capacite, $categorie, $prix, $espace_fumeur, $accompagnateur_autorise,$imgData
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
        $imgTmp = $_FILES['image']['tmp_name'];
        $imgData = file_get_contents($imgTmp);

        if (empty($nom) || empty($date) || empty($lieu) || $capacite <= 0) {
            $errorMessage = "Les champs Nom, Date, Lieu et Capacité sont obligatoires. La capacité doit être supérieure à 0.";
        } else {
            if ($evenementcontroller->updateven(
                $id, $nom, $date, $lieu, $capacite, $categorie, $prix, $espace_fumeur, $accompagnateur_autorise,$imgData
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
    <title>Administration - STELLIFEROUS</title>
    <link rel="stylesheet" href="styleeya.css">
    <link rel="stylesheet" href="theme-customeya.css">
    <link rel="stylesheet" href="admineya.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/back.css">
    
    <style>
        :root {
            --primary: rgb(29, 30, 35);
            --primary-light: rgb(39, 40, 45);
            --secondary: rgb(227, 196, 58);
            --secondary-light: rgba(227, 196, 58, 0.1);
            --secondary-hover: rgb(237, 206, 68);
            --white: #ffffff;
            --white-50: rgba(255, 255, 255, 0.5);
            --white-20: rgba(255, 255, 255, 0.2);
            --white-10: rgba(255, 255, 255, 0.1);
            --white-05: rgba(255, 255, 255, 0.05);
            --dark-gray: rgb(32, 33, 38);
            --dark-gray-light: rgb(42, 43, 48);
            --danger: #e74c3c;
            --danger-light: rgba(231, 76, 60, 0.1);
            --danger-hover: #c0392b;
            --success: #2ecc71;
            --success-light: rgba(46, 204, 113, 0.1);
            --success-hover: #27ae60;
            --info: #3498db;
            --info-light: rgba(52, 152, 219, 0.1);
            --border-radius-sm: 6px;
            --border-radius-md: 10px;
            --border-radius-lg: 16px;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.15);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.25);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--primary);
            color: var(--white);
            line-height: 1.6;
            padding: 20px;
        }

        .container2234 {
            max-width: 1200px;
            
            margin-left: 300px;
        }

        h1, h2 {
            color: var(--white);
            margin-bottom: 20px;
        }

        h1 {
            font-size: 28px;
            border-bottom: 2px solid var(--secondary);
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        h2 {
            font-size: 22px;
            position: relative;
            padding-left: 15px;
        }

        h2::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 5px;
            background-color: var(--secondary);
            border-radius: var(--border-radius-sm);
        }

        .card {
            background-color: var(--primary-light);
            border-radius: var(--border-radius-md);
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--white-10);
        }

        /* Search and Filter Section */
        .search-filter-container {
            margin-bottom: 30px;
        }

        .search-filter-container form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            width: 100%;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        select {
            background-color: var(--dark-gray);
            border: 1px solid var(--white-20);
            color: var(--white);
            padding: 12px 15px;
            border-radius: var(--border-radius-sm);
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 2px var(--secondary-light);
        }

        input[type="text"]::placeholder,
        input[type="number"]::placeholder {
            color: var(--white-50);
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
        }

        select option {
            background-color: var(--dark-gray);
            color: var(--white);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            outline: none;
            text-decoration: none;
            min-width: 120px;
        }

        .btn-primary {
            background-color: var(--secondary);
            color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--secondary-hover);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--white-20);
            color: var(--white);
        }

        .btn-outline:hover {
            border-color: var(--white);
            background-color: var(--white-05);
        }

        /* Form Grid Layout */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 5px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--white-50);
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
        }

        /* Checkbox Styling */
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            cursor: pointer;
        }

        .checkbox-group input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkbox-group label {
            position: relative;
            padding-left: 35px;
            cursor: pointer;
            font-size: 14px;
            user-select: none;
            display: flex;
            align-items: center;
            min-height: 24px;
        }

        .checkbox-group label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 22px;
            height: 22px;
            border-radius: var(--border-radius-sm);
            background-color: var(--dark-gray);
            border: 1px solid var(--white-20);
            transition: all 0.3s ease;
        }

        .checkbox-group input:checked + label:before {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }

        .checkbox-group label:after {
            content: '';
            position: absolute;
            left: 8px;
            top: 4px;
            width: 6px;
            height: 12px;
            border: solid var(--primary);
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .checkbox-group input:checked + label:after {
            opacity: 1;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        /* Error Message */
        .error-message {
            color: var(--danger);
            background-color: var(--danger-light);
            padding: 10px 15px;
            border-radius: var(--border-radius-sm);
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        /* Success Message */
        .success-message {
            color: var(--success);
            background-color: var(--success-light);
            padding: 10px 15px;
            border-radius: var(--border-radius-sm);
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        /* Required Field Indicator */
        .required-field::after {
            content: ' *';
            color: var(--danger);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .search-filter-container form {
                flex-direction: column;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }

        /* Animation for form submission */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease forwards;
        }

        /* Custom styling for date input */
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.5;
        }

        /* Focus styles */
        .btn:focus {
            box-shadow: 0 0 0 3px var(--secondary-light);
        }

        /* Tooltip for form fields */
        .tooltip {
            position: relative;
            display: inline-block;
            margin-left: 5px;
            cursor: help;
        }

        .tooltip .tooltip-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            background-color: var(--white-20);
            color: var(--white);
            border-radius: 50%;
            font-size: 12px;
            font-weight: bold;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: var(--dark-gray-light);
            color: var(--white);
            text-align: center;
            border-radius: var(--border-radius-sm);
            padding: 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--white-10);
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        .event-actions .action-buttons {
    display: flex;
    gap: 10px; /* espace entre les boutons */
    align-items: center;
}

.event-actions form {
    margin: 0; /* enlever tout espacement inutile */
}

    </style>
</head>
<body >
    
        <!-- Sidebar (inchangé) -->
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
        <div class="container2234">
        
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
                
            </header>
            
            <!-- Main Content -->
            
                <h1>Gestion des événements</h1>
                 
                
                <!-- Messages de succès/erreur -->
                <div id="success-message" class="success-message">
                    <?= htmlspecialchars($successMessage) ?>
                </div>
                
                <div id="error-message" class="error-message">
                    <?= htmlspecialchars($errorMessage) ?>
                </div>
            <div class="card">
                <!-- Barre de recherche et filtres -->
                <div class="search-filter-container">
                    <form action="" method="GET" >
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
                        
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                        
                        <?php if (!empty($searchKeyword) || !empty($filterCategory) || $filterEspaceFumeur !== null || $filterAccompagnateur !== null): ?>
                            <a href="formulaire-evenement.php" class="btn btn-outline">Réinitialiser</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div> 
                
                <!-- Formulaire d'ajout/modification -->
                 <div class="card">
                <form id="event-form" action="formulaire-evenement.php" method="POST" onsubmit="return validateForm()" enctype="multipart/form-data">
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
                            <label for="image">image</label>
                            <input type="file" name="image" id="image" required>   
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
                </div>



                
                
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
                                                <div class="action-buttons">
                                                <a href="?edit=<?= $even['id_ev'] ?>" class="btn btn-primary">Modifier</a>
                                                
                                                <form method="POST"  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                                                    <input type="hidden" name="id_ev" value="<?= $even['id_ev'] ?>">
                                                    <input type="hidden" name="action" value="delete">
                                                    <button type="submit" class="btn btn-primary">Supprimer</button>
                                                </form>
                                                </div>
                                                
                                               
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            
        </div>
    
    <script src="assets/back.js"></script>
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
