<?php
require_once __DIR__ . '/../../controller/evenController.php';
require_once __DIR__ . '/../../model/evenmodel.php';

// Initialiser le contrôleur
$evenementController = new EvenementController();

// Variables pour les messages
$successMessage = "";
$errorMessage = "";

// Vérifier si un ID d'événement est fourni
if (!isset($_GET['id_ev']) || !is_numeric($_GET['id_ev'])) {
    header('Location: formulaire-evenement.php');
    exit;
}

$id_ev = (int)$_GET['id_ev'];
$event = $evenementController->getEventById($id_ev);

// Vérifier si l'événement existe
if (!$event) {
    header('Location: formulaire-evenement.php');
    exit;
}

// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $nom = $_POST['nom'] ?? '';
    $date = $_POST['date'] ?? '';
    $lieu = $_POST['lieu'] ?? '';
    $capacite = (int)($_POST['capacite'] ?? 0);
    $categorie = $_POST['categorie'] ?? null;
    $espace_fumeur = isset($_POST['espace_fumeur']) ? 1 : 0;
    $description = $_POST['description'] ?? null;
    $image = $_POST['image'] ?? null;
    $prix = (float)($_POST['prix'] ?? 0);
    $public_cible = $_POST['public_cible'] ?? 'Tout public';
    $statut = $_POST['statut'] ?? 'À venir';
    $accessibilite_pmr = isset($_POST['accessibilite_pmr']) ? 1 : 0;

    if (empty($nom) || empty($date) || empty($lieu) || $capacite <= 0) {
        $errorMessage = "Les champs Nom, Date, Lieu et Capacité sont obligatoires. La capacité doit être supérieure à 0.";
    } else {
        if ($evenementController->updateven(
            $id_ev, $nom, $date, $lieu, $capacite, $categorie, $espace_fumeur, 
            $description, $image, $prix, $public_cible, $statut, $accessibilite_pmr
        )) {
            $successMessage = "Événement mis à jour avec succès !";
            // Mettre à jour les données de l'événement
            $event = $evenementController->getEventById($id_ev);
        } else {
            $errorMessage = "Erreur lors de la mise à jour de l'événement.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un événement - EntrepreHub</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="theme-custom.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        h1 {
            margin-bottom: 20px;
            color: #10b981;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        input[type="text"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            font-size: 16px;
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
        }
        
        button {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        
        button:hover {
            background-color: #059669;
        }
        
        .btn-secondary {
            background-color: #6b7280;
            margin-right: 10px;
        }
        
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        
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
        
        .button-group {
            display: flex;
            margin-top: 20px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .form-full-width {
            grid-column: span 2;
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-full-width {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body class="admin-body">
    <div class="admin-container">
        <!-- Sidebar (même code que dans formulaire-evenement.php) -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>EntrepreHub</h2>
                <span class="admin-badge">Admin</span>
            </div>
            
            <nav class="sidebar-nav">
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
                        <span>Modifier un événement</span>
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
            
            <div class="container">
                <h1>Modifier un événement</h1>
                
                <div id="success-message" class="success-message">
                    <?= htmlspecialchars($successMessage) ?>
                </div>
                
                <div id="error-message" class="error-message">
                    <?= htmlspecialchars($errorMessage) ?>
                </div>
                
                <form action="" method="POST" onsubmit="return validateForm()">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nom">Nom de l'événement *</label>
                            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($event['nom']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="date">Date *</label>
                            <input type="date" id="date" name="date" value="<?= htmlspecialchars($event['date']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="lieu">Lieu *</label>
                            <input type="text" id="lieu" name="lieu" value="<?= htmlspecialchars($event['lieu']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="capacite">Capacité *</label>
                            <input type="number" id="capacite" name="capacite" min="1" value="<?= htmlspecialchars($event['capacite']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="categorie">Catégorie</label>
                            <input type="text" id="categorie" name="categorie" value="<?= htmlspecialchars($event['categorie'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="prix">Prix (0 = gratuit)</label>
                            <input type="number" id="prix" name="prix" min="0" step="0.01" value="<?= htmlspecialchars($event['prix'] ?? '0.00') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="public_cible">Public cible</label>
                            <input type="text" id="public_cible" name="public_cible" value="<?= htmlspecialchars($event['public_cible'] ?? 'Tout public') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="statut">Statut</label>
                            <select id="statut" name="statut">
                                <option value="À venir" <?= ($event['statut'] ?? '') == 'À venir' ? 'selected' : '' ?>>À venir</option>
                                <option value="Complet" <?= ($event['statut'] ?? '') == 'Complet' ? 'selected' : '' ?>>Complet</option>
                                <option value="Annulé" <?= ($event['statut'] ?? '') == 'Annulé' ? 'selected' : '' ?>>Annulé</option>
                                <option value="Terminé" <?= ($event['statut'] ?? '') == 'Terminé' ? 'selected' : '' ?>>Terminé</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">URL de l'image</label>
                            <input type="text" id="image" name="image" value="<?= htmlspecialchars($event['image'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group checkbox-group">
                            <input type="checkbox" id="espace_fumeur" name="espace_fumeur" <?= ($event['espace_fumeur'] ?? 0) ? 'checked' : '' ?>>
                            <label for="espace_fumeur">Espace fumeur</label>
                        </div>
                        
                        <div class="form-group checkbox-group">
                            <input type="checkbox" id="accessibilite_pmr" name="accessibilite_pmr" <?= ($event['accessibilite_pmr'] ?? 0) ? 'checked' : '' ?>>
                            <label for="accessibilite_pmr">Accessible PMR</label>
                        </div>
                        
                        <div class="form-group form-full-width">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="4"><?= htmlspecialchars($event['description'] ?? '') ?></textarea>
                        </div>
                    </div>
                    
                    <div class="button-group">
                        <a href="formulaire-evenement.php" class="btn btn-secondary">Annuler</a>
                        <button type="submit" name="update">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <script>
        function validateForm() {
            const nom = document.getElementById('nom').value;
            const date = document.getElementById('date').value;
            const lieu = document.getElementById('lieu').value;
            const capacite = document.getElementById('capacite').value;
            
            if (!nom || !date || !lieu || !capacite || capacite < 1) {
                alert('Veuillez remplir tous les champs obligatoires correctement. La capacité doit être supérieure à 0.');
                return false;
            }
            
            return true;
        }
        
        // Masquer les messages après 5 secondes
        setTimeout(function() {
            document.getElementById('success-message').style.display = 'none';
            document.getElementById('error-message').style.display = 'none';
        }, 5000);
    </script>
</body>
</html>
