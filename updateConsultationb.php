<?php 
include('../../controller/ConsultationController.php');

$consultationController = new ConsultationController();

$id_consultation = null;

// Get id from POST (after submit) or GET (when first loading the page)
if (!empty($_POST['id_consultation'])) {
    $id_consultation = $_POST['id_consultation'];
} elseif (!empty($_GET['id_consultation'])) {
    $id_consultation = $_GET['id_consultation'];
}

if ($id_consultation) {

    // If the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $date_consultation = $_POST['date_consultation'];
        $type = $_POST['type'];
        $statut = $_POST['statut'];
        
        // Update the consultation
        $updateStatus = $consultationController->updateConsultation($id_consultation, $titre, $description, $date_consultation, $type, $statut);

        if ($updateStatus) {
            header("Location: consultationListb.php?update_success=true");
            exit();
        } else {
            $error = "Échec de la mise à jour.";
        }
    }

    // Fetch the consultation details to prefill the form
    $consultationDetails = $consultationController->getConsultationById($id_consultation);

    if (!$consultationDetails) {
        die("Consultation introuvable.");
    }

} else {
    die("ID de la consultation manquant.");
}

// Function to get type label
function getTypeLabel($type) {
    $types = [
        'financing' => 'Financement',
        'legal' => 'Juridique',
        'marketing' => 'Marketing',
        'technical' => 'Technique'
    ];
    
    return isset($types[$type]) ? $types[$type] : $type;
}

// Function to get status label
function getStatusLabel($statut) {
    $statuts = [
        'pending' => 'En attente',
        'in-progress' => 'En cours',
        'completed' => 'Terminée',
        'cancelled' => 'Annulée'
    ];
    
    return isset($statuts[$statut]) ? $statuts[$statut] : $statut;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mettre à jour la consultation - EntrepreHub</title>
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
                    <!-- Other menu items -->
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
                        <span>Modifier</span>
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
            
            <!-- Main Content Area -->
            <div class="admin-content">
                <div class="dashboard-header">
                    <h1>Modifier la consultation #<?php echo $consultationDetails['id_consultation']; ?></h1>
                    <div class="action-buttons">
                        <a href="consultationListb.php" class="btn btn-outline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Retour
                        </a>
                    </div>
                </div>
                
                <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span><?php echo $error; ?></span>
                </div>
                <?php endif; ?>
                
                <!-- Update Consultation Modal -->
                <div id="update-consultation-modal" class="modal modal-active">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Modifier la consultation</h2>
                            <a href="consultationListb.php" class="close-modal">&times;</a>
                        </div>
                        <div class="modal-body">
                            <form id="update-consultation-form" method="POST" action="updateConsultationb.php">
                                <input type="hidden" name="id_consultation" value="<?= htmlspecialchars($consultationDetails['id_consultation']); ?>">
                                
                                <div class="form-group">
                                    <label for="consultation-subject">Titre</label>
                                    <input type="text" id="consultation-subject" name="titre" value="<?= htmlspecialchars($consultationDetails['titre']); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="consultation-type">Type</label>
                                    <select id="consultation-type" name="type" required>
                                        <option value="">Sélectionnez un type</option>
                                        <option value="financing" <?= $consultationDetails['type'] === 'financing' ? 'selected' : '' ?>>Financement</option>
                                        <option value="legal" <?= $consultationDetails['type'] === 'legal' ? 'selected' : '' ?>>Juridique</option>
                                        <option value="marketing" <?= $consultationDetails['type'] === 'marketing' ? 'selected' : '' ?>>Marketing</option>
                                        <option value="technical" <?= $consultationDetails['type'] === 'technical' ? 'selected' : '' ?>>Technique</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="consultation-description">Description</label>
                                    <textarea id="consultation-description" name="description" rows="5" required><?= htmlspecialchars($consultationDetails['description']); ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="consultation-date">Date</label>
                                    <input type="date" id="consultation-date" name="date_consultation" value="<?= htmlspecialchars($consultationDetails['date_consultation']); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="consultation-status">Statut</label>
                                    <select id="consultation-status" name="statut" required>
                                        <option value="pending" <?= $consultationDetails['statut'] === 'pending' ? 'selected' : '' ?>>En attente</option>
                                        <option value="in-progress" <?= $consultationDetails['statut'] === 'in-progress' ? 'selected' : '' ?>>En cours</option>
                                        <option value="completed" <?= $consultationDetails['statut'] === 'completed' ? 'selected' : '' ?>>Terminée</option>
                                        <option value="cancelled" <?= $consultationDetails['statut'] === 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                                    </select>
                                </div>
                                
                                <div class="modal-footer">
                                    <a href="consultationListb.php" class="secondary-btn">Annuler</a>
                                    <button type="submit" class="primary-btn">
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
        /* Base Styles */
        :root {
            --primary-color: rgb(29, 30, 35);
            --secondary-color: rgb(255, 255, 255);
            --accent-color: rgb(227, 196, 58);
            --text-primary: rgb(255, 255, 255);
            --text-secondary: rgba(255, 255, 255, 0.7);
            --text-muted: rgba(255, 255, 255, 0.5);
            --border-color: rgba(255, 255, 255, 0.1);
            --bg-light: rgb(36, 37, 43);
            --bg-lighter: rgb(45, 46, 54);
            --success-color: #2ea043;
            --warning-color: #d29922;
            --danger-color: #f85149;
            --info-color: #58a6ff;
            
           /* Couleurs vibrantes pour les cartes statistiques */
--pastel-pink: #FF4D6D;
--pastel-yellow: #FFC300;
--pastel-green: #00B86B;
--pastel-red: #FF5733;
--pastel-blue: #3498DB;
--pastel-purple: #9B59B6;

        }

        /* Update Consultation Modal Styles */
        #update-consultation-modal {
            display: flex;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        #update-consultation-modal .modal-content {
            background-color: var(--bg-light);
            margin: auto;
            width: 90%;
            max-width: 650px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
            animation: modalSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            position: relative;
        }

        /* Creative decorative elements */
        #update-consultation-modal .modal-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(to right, var(--pastel-pink), var(--pastel-yellow), var(--pastel-green), var(--pastel-blue), var(--pastel-purple));
            z-index: 1;
        }

        #update-consultation-modal .modal-content::after {
            content: '';
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 80px;
            height: 80px;
            background-image: radial-gradient(circle, var(--accent-color) 2px, transparent 2px);
            background-size: 15px 15px;
            opacity: 0.1;
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        @keyframes modalSlideIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        #update-consultation-modal .modal-header {
            background-color: var(--bg-lighter);
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        #update-consultation-modal .modal-header h2 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #update-consultation-modal .modal-header h2::before {
            content: '';
            display: block;
            width: 4px;
            height: 24px;
            background-color: var(--accent-color);
            border-radius: 2px;
        }

        #update-consultation-modal .close-modal {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            font-size: 28px;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        #update-consultation-modal .close-modal:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
            transform: rotate(90deg);
        }

        #update-consultation-modal .modal-body {
            padding: 24px;
            max-height: calc(100vh - 200px);
            overflow-y: auto;
            position: relative;
            z-index: 2;
        }

        #update-consultation-modal .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        #update-consultation-modal .modal-body::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        #update-consultation-modal .modal-body::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        #update-consultation-modal .modal-body::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        #update-consultation-modal .form-group {
            margin-bottom: 20px;
            opacity: 0;
            animation: formElementFadeIn 0.5s ease forwards;
            position: relative;
        }

        #update-consultation-modal .form-group:nth-child(1) { animation-delay: 0.1s; }
        #update-consultation-modal .form-group:nth-child(2) { animation-delay: 0.15s; }
        #update-consultation-modal .form-group:nth-child(3) { animation-delay: 0.2s; }
        #update-consultation-modal .form-group:nth-child(4) { animation-delay: 0.25s; }
        #update-consultation-modal .form-group:nth-child(5) { animation-delay: 0.3s; }
        #update-consultation-modal .form-group:nth-child(6) { animation-delay: 0.35s; }

        @keyframes formElementFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        #update-consultation-modal .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 15px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            letter-spacing: 0.3px;
            position: relative;
            padding-left: 12px;
        }

        #update-consultation-modal .form-group label::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 16px;
            background-color: var(--accent-color);
            border-radius: 2px;
            opacity: 0.7;
        }

        #update-consultation-modal .form-group input,
        #update-consultation-modal .form-group select,
        #update-consultation-modal .form-group textarea {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 14px 16px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }

        #update-consultation-modal .form-group input:focus,
        #update-consultation-modal .form-group select:focus,
        #update-consultation-modal .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(227, 196, 58, 0.15);
            transform: translateY(-2px);
        }

        #update-consultation-modal .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='rgb(227, 196, 58)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }

        #update-consultation-modal .form-group textarea {
            resize: vertical;
            min-height: 140px;
            line-height: 1.6;
        }

        /* Styling for type options */
        #update-consultation-modal #consultation-type option[value="financing"] {
            background-color: #FFD6E0;
            color: #333;
        }

        #update-consultation-modal #consultation-type option[value="legal"] {
            background-color: #F3E8FF;
            color: #333;
        }

        #update-consultation-modal #consultation-type option[value="marketing"] {
            background-color: #FFEFB7;
            color: #333;
        }

        #update-consultation-modal #consultation-type option[value="technical"] {
            background-color: #DFF4FF;
            color: #333;
        }

        /* Styling for status options */
        #update-consultation-modal #consultation-status option[value="pending"] {
            background-color: #FFEFB7;
            color: #333;
        }

        #update-consultation-modal #consultation-status option[value="in-progress"] {
            background-color: #DFF4FF;
            color: #333;
        }

        #update-consultation-modal #consultation-status option[value="completed"] {
            background-color: #DCFCE7;
            color: #333;
        }

        #update-consultation-modal #consultation-status option[value="cancelled"] {
            background-color: #FEE2E2;
            color: #333;
        }

        /* Date input styling */
        #update-consultation-modal input[type="date"] {
            color-scheme: dark;
        }

        #update-consultation-modal input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1) brightness(0.8) sepia(100%) saturate(500%) hue-rotate(20deg);
            cursor: pointer;
        }

        /* Modal footer */
        #update-consultation-modal .modal-footer {
            padding: 20px 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            justify-content: flex-end;
            gap: 16px;
            background-color: rgba(45, 46, 54, 0.5);
            opacity: 0;
            animation: formElementFadeIn 0.5s ease forwards 0.4s;
            position: relative;
            z-index: 2;
        }

        /* Button styles */
        #update-consultation-modal .primary-btn,
        #update-consultation-modal .secondary-btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        #update-consultation-modal .primary-btn {
            background-color: var(--accent-color);
            color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(227, 196, 58, 0.2);
        }

        #update-consultation-modal .primary-btn:hover {
            background-color: rgba(227, 196, 58, 0.9);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(227, 196, 58, 0.3);
        }

        #update-consultation-modal .primary-btn:active {
            transform: translateY(-1px);
        }

        #update-consultation-modal .secondary-btn {
            background-color: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
        }

        #update-consultation-modal .secondary-btn:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-3px);
        }

        #update-consultation-modal .secondary-btn:active {
            transform: translateY(-1px);
        }

        /* Creative hover effect for buttons */
        #update-consultation-modal .primary-btn::after,
        #update-consultation-modal .secondary-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        #update-consultation-modal .primary-btn:hover::after,
        #update-consultation-modal .secondary-btn:hover::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }

        /* Alert styles */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            animation: slideIn 0.5s ease-out;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        /* Creative alert background pattern */
        .alert::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100%;
            background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.1) 50%, rgba(255, 255, 255, 0.1) 75%, transparent 75%, transparent);
            background-size: 10px 10px;
            opacity: 0.2;
            z-index: 0;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .alert-success {
            background-color: rgba(46, 160, 67, 0.15);
            border: 1px solid rgba(46, 160, 67, 0.3);
        }

        .alert-success svg {
            color: var(--success-color);
        }

        .alert-danger {
            background-color: rgba(248, 81, 73, 0.15);
            border: 1px solid rgba(248, 81, 73, 0.3);
        }

        .alert-danger svg {
            color: var(--danger-color);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            #update-consultation-modal .modal-content {
                width: 95%;
                max-width: none;
            }
            
            #update-consultation-modal .modal-body {
                padding: 16px;
                max-height: calc(100vh - 150px);
            }
            
            #update-consultation-modal .form-group {
                margin-bottom: 16px;
            }
            
            #update-consultation-modal .modal-footer {
                padding: 16px;
                flex-direction: column-reverse;
                gap: 12px;
            }
            
            #update-consultation-modal .primary-btn,
            #update-consultation-modal .secondary-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* General styles */
        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .btn-outline:hover {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }
    </style>
</body>
</html>
