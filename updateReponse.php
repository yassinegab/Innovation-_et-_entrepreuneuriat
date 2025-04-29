<?php
include('../../controller/reponseController.php');

// Vérifier si l'ID de réponse est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: consultationListb.php");
    exit();
}

$id_reponse = intval($_GET['id']);

// Récupérer la réponse depuis la base de données
try {
    $db = new PDO('mysql:host=localhost;dbname=projet', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM reponses WHERE id_reponse = :id_reponse";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_reponse', $id_reponse);
    $stmt->execute();
    $reponse = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reponse) {
        header("Location: consultationListb.php");
        exit();
    }

    // Récupérer l'ID de la consultation pour la redirection
    $id_consultation = $reponse['id_consultation'];

    // Traitement du formulaire de modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['contenu'])) {
            $contenu = htmlspecialchars(trim($_POST['contenu']));

            $query = "UPDATE reponses SET contenu = :contenu WHERE id_reponse = :id_reponse";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':contenu', $contenu);
            $stmt->bindParam(':id_reponse', $id_reponse);

            if ($stmt->execute()) {
                header("Location: consultation-details.php?id=$id_consultation&updated=true");
                exit();
            } else {
                $error_message = "Erreur lors de la mise à jour de la réponse.";
            }
        } else {
            $error_message = "Le contenu de la réponse ne peut pas être vide.";
        }
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la réponse - EntrepreHub</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="theme-custom.css">
    <link rel="stylesheet" href="admin.css">
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
                        <a href="consultationListb.php">
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
                        <a href="consultationListb.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <span>Consultations</span>
                        </a>
                    </li>
                    <!-- Autres éléments du menu -->
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
                        <span>Consultations</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                        <span>Modifier la réponse</span>
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
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Edit Response Content -->
            <div class="admin-content">
                <div class="dashboard-header">
                    <div class="flex-between">
                        <h1>Modifier la réponse</h1>
                        <div class="action-buttons">
                            <a href="consultation-details.php?id=<?php echo $id_consultation; ?>" class="btn btn-outline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="19" y1="12" x2="5" y2="12"></line>
                                    <polyline points="12 19 5 12 12 5"></polyline>
                                </svg>
                                Retour
                            </a>
                        </div>
                    </div>
                </div>
                
                <?php if (isset($error_message)): ?>
                <div class="alert alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span><?php echo $error_message; ?></span>
                </div>
                <?php endif; ?>
                
                <div class="edit-response-container">
                    <div class="edit-card">
                        <div class="card-header">
                            <h2>Modifier la réponse #<?php echo $id_reponse; ?></h2>
                            <p class="response-meta">
                                <span>Consultation #<?php echo $id_consultation; ?></span>
                                <span>•</span>
                                <span>Date: <?php echo date('d/m/Y à H:i', strtotime($reponse['date_reponse'])); ?></span>
                            </p>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="form-group">
                                    <label for="contenu">Contenu de la réponse</label>
                                    <textarea id="contenu" name="contenu" rows="10" required><?php echo htmlspecialchars($reponse['contenu']); ?></textarea>
                                </div>
                                <div class="form-actions">
                                    <a href="consultation-details.php?id=<?php echo $id_consultation; ?>" class="btn btn-outline">Annuler</a>
                                    <button type="submit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                            <polyline points="7 3 7 8 15 8"></polyline>
                                        </svg>
                                        Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Script pour le toggle du menu sur mobile
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('active');
        });
    </script>

    <style>
        /* Styles spécifiques pour la page d'édition de réponse */
        .edit-response-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .edit-card {
            background-color: var(--bg-light);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }
        
        .card-header {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .card-header h2 {
            margin-bottom: 10px;
            font-size: 1.5rem;
        }
        
        .response-meta {
            display: flex;
            gap: 10px;
            color: var(--text-muted);
            font-size: 0.875rem;
        }
        
        .card-body {
            padding: 16px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background-color: var(--primary-color);
            color: var(--text-primary);
            resize: vertical;
            min-height: 200px;
            font-family: inherit;
            line-height: 1.5;
        }
        
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-color);
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s;
            border: none;
            text-decoration: none;
        }
        
        .btn-primary {
            background-color: var(--accent-color);
            color: var(--primary-color);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }
        
        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .alert-danger {
            background-color: rgba(248, 81, 73, 0.2);
            border: 1px solid var(--danger-color);
            color: var(--danger-color);
        }
    </style>
</body>
</html>