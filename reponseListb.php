<?php
// Inclure les fichiers nécessaires
require_once('../../controller/ConsultationController.php');
require_once('../../controller/reponseController.php');
require_once('../../model/consultation.php');
require_once('../../model/reponse.php');

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si l'ID est fourni
if (!isset($_GET['id_consultation']) || empty($_GET['id_consultation'])) {
    header("Location: consultationListb.php");
    exit;
}

$id_consultation = intval($_GET['id_consultation']);

// Initialiser les contrôleurs
$consultationC = new ConsultationController();
$reponseC = new ReponseController();

// Récupérer la consultation
$consultation = $consultationC->getConsultationById($id_consultation);

// Vérifier si la consultation existe
if (!$consultation) {
    header("Location: consultationListb.php");
    exit;
}

// Récupérer les réponses pour cette consultation
$reponses = $reponseC->getReponsesByConsultationId($id_consultation);

// Traitement du formulaire d'ajout de réponse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_reponse'])) {
    if (!empty($_POST['contenu'])) {
        $contenu = htmlspecialchars(trim($_POST['contenu']));
        $date_reponse = date('Y-m-d H:i:s');
        $id_utilisateur = 1; // ID de l'administrateur (à adapter selon votre système)

        // Créer une nouvelle réponse
        $reponse = new Reponse(null, $contenu, $date_reponse, $id_consultation, $id_utilisateur);
        
        // Ajouter la réponse
        $reponseC->ajouterReponse($reponse);
        
        // Mettre à jour le statut de la consultation si nécessaire
        if ($consultation['statut'] === 'pending') {
            $reponseC->updateConsultationStatus($id_consultation, 'in-progress');
        }
        
        // Rediriger pour éviter la soumission multiple du formulaire
        header("Location: reponseListb.php?id_consultation=$id_consultation&success=true");
        exit;
    } else {
        $error_message = "Le contenu de la réponse ne peut pas être vide.";
    }
}

// Fonction pour formater la date
function formatDate($date) {
    return date('d/m/Y à H:i', strtotime($date));
}

// Fonction pour obtenir le libellé du type
function getTypeLabel($type) {
    $types = [
        'financing' => 'Financement',
        'legal' => 'Juridique',
        'marketing' => 'Marketing',
        'technical' => 'Technique'
    ];
    
    return isset($types[$type]) ? $types[$type] : $type;
}

// Fonction pour obtenir le libellé du statut
function getStatusLabel($statut) {
    $statuts = [
        'pending' => 'En attente',
        'in-progress' => 'En cours',
        'completed' => 'Terminée'
    ];
    
    return isset($statuts[$statut]) ? $statuts[$statut] : $statut;
}

// Fonction pour obtenir la classe CSS du statut
function getStatusClass($statut) {
    $classes = [
        'pending' => 'warning',
        'in-progress' => 'info',
        'completed' => 'success'
    ];
    
    return isset($classes[$statut]) ? $classes[$statut] : '';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la consultation - EntrepreHub</title>
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
                        <span>Détails</span>
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
            
            <!-- Consultation Details Content -->
            <div class="admin-content">
                <div class="dashboard-header">
                    <div class="flex-between">
                        <h1>Détails de la consultation #<?php echo $consultation['id_consultation']; ?></h1>
                        <div class="action-buttons">
                            <a href="updateConsultationb.php?id_consultation=<?php echo $consultation['id_consultation']; ?>" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Modifier
                            </a>
                            <a href="consultationListb.php" class="btn btn-outline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="19" y1="12" x2="5" y2="12"></line>
                                    <polyline points="12 19 5 12 12 5"></polyline>
                                </svg>
                                Retour
                            </a>
                        </div>
                    </div>
                </div>
                
                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>Votre réponse a été ajoutée avec succès.</span>
                </div>
                <?php endif; ?>
                
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
                
                <div class="consultation-details-container">
                    <div class="consultation-info-card">
                        <div class="card-header">
                            <h2><?php echo htmlspecialchars($consultation['titre']); ?></h2>
                            <div class="consultation-meta">
                                <span class="consultation-type"><?php echo getTypeLabel($consultation['type']); ?></span>
                                <span class="consultation-status <?php echo getStatusClass($consultation['statut']); ?>">
                                    <?php echo getStatusLabel($consultation['statut']); ?>
                                </span>
                                <span class="consultation-date">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <?php echo formatDate($consultation['date_consultation']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="detail-section">
                                <h3>Description</h3>
                                <div class="description-content">
                                    <?php echo nl2br(htmlspecialchars($consultation['description'])); ?>
                                </div>
                            </div>
                            
                            <div class="detail-section">
                                <h3>Informations utilisateur</h3>
                                <div class="user-info-content">
                                    <p><strong>ID Utilisateur:</strong> <?php echo htmlspecialchars($consultation['id_utilisateur']); ?></p>
                                    <!-- Vous pouvez ajouter plus d'informations sur l'utilisateur ici si disponibles -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="responses-section">
                        <h3>Réponses (<?php echo count($reponses); ?>)</h3>
                        
                        <?php if (empty($reponses)): ?>
                        <div class="no-responses">
                            <p>Aucune réponse pour cette consultation.</p>
                        </div>
                        <?php else: ?>
                            <?php foreach ($reponses as $reponse): ?>
                            <div class="response-card">
                                <div class="response-header">

                                    <div class="response-meta">
                                        <span class="response-author">Admin</span>
                                        <span class="response-date"><?php echo formatDate($reponse->getdate_reponse()); ?></span>
                                    </div>
                                    <div class="response-actions">
                                        <a href="updateReponse.php?id_reponse=<?php echo $reponse->getid_reponse(); ?>" class="btn-icon" title="Modifier">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                        <a href="deleteReponseb.php?id_reponse=<?php echo $reponse->getid_reponse(); ?>&id_consultation=<?php echo $id_consultation; ?>" class="btn-icon" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cette réponse ?');">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="response-content">
                                    <?php echo nl2br(htmlspecialchars($reponse->getcontenu())); ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <!-- Formulaire pour ajouter une réponse -->
                        <div class="add-response-section">
                            <h3>Ajouter une réponse</h3>
                            <form method="POST" action="">
                                <div class="form-group">
                                    <textarea name="contenu" rows="5" placeholder="Écrivez votre réponse ici..." required></textarea>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="ajouter_reponse" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="22" y1="2" x2="11" y2="13"></line>
                                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                        </svg>
                                        Envoyer la réponse
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
        /* Enhanced Styles for Consultation Details Page */
.consultation-details-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (min-width: 992px) {
    .consultation-details-container {
        grid-template-columns: 1.2fr 0.8fr;
    }
}

/* Consultation Info Card */
.consultation-info-card {
    background-color: var(--bg-light);
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid var(--border-color);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.consultation-info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.consultation-info-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 6px;
    background: linear-gradient(to right, #FFD6E0, #FFEFB7, #DCFCE7, #DFF4FF);
}

.card-header {
    padding: 24px;
    border-bottom: 1px solid var(--border-color);
    background-color: rgba(45, 46, 54, 0.5);
    position: relative;
}

.card-header h2 {
    margin-bottom: 16px;
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -0.02em;
    line-height: 1.3;
}

.consultation-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
}

.consultation-type {
    background-color:rgb(224, 144, 24);
    color: var(--primary-color);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    box-shadow: 0 2px 10px rgba(227, 230, 233, 0.2);
}

.consultation-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.consultation-status::before {
    content: '';
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.consultation-status.warning {
    background-color: #FFEFB7;
    color: var(--primary-color);
}

.consultation-status.warning::before {
    background-color: #d29922;
}

.consultation-status.info {
    background-color: #DFF4FF;
    color: var(--primary-color);
}

.consultation-status.info::before {
    background-color: #58a6ff;
}

.consultation-status.success {
    background-color: #DCFCE7;
    color: var(--primary-color);
}

.consultation-status.success::before {
    background-color: #2ea043;
}

.consultation-date {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-muted);
    font-size: 0.85rem;
    background-color: rgba(255, 255, 255, 0.05);
    padding: 6px 12px;
    border-radius: 20px;
}

.card-body {
    padding: 24px;
}

.detail-section {
    margin-bottom: 28px;
    animation: slideUp 0.5s ease-out forwards;
    opacity: 0;
}

.detail-section:nth-child(1) { animation-delay: 0.1s; }
.detail-section:nth-child(2) { animation-delay: 0.2s; }

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.detail-section h3 {
    font-size: 1.1rem;
    margin-bottom: 16px;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.detail-section h3::before {
    content: '';
    display: block;
    width: 4px;
    height: 18px;
    background-color: var(--accent-color);
    border-radius: 2px;
}

.description-content {
    background-color: var(--bg-lighter);
    padding: 20px;
    border-radius: 12px;
    line-height: 1.7;
    font-size: 1.05rem;
    border-left: 4px solid rgba(227, 196, 58, 0.5);
}

.user-info-content {
    background-color: var(--bg-lighter);
    padding: 20px;
    border-radius: 12px;
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}

.user-info-content p {
    margin: 0;
    padding: 8px 16px;
    background-color: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.user-info-content p strong {
    color: var(--text-secondary);
}

/* Responses Section */
.responses-section {
    background-color: var(--bg-light);
    border-radius: 16px;
    padding: 24px;
    border: 1px solid var(--border-color);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    max-height: 800px;
}

.responses-section h3 {
    margin-bottom: 20px;
    font-size: 1.3rem;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border-color);
}

.responses-section h3::before {
    content: '';
    display: block;
    width: 4px;
    height: 20px;
    background-color: var(--accent-color);
    border-radius: 2px;
}

.no-responses {
    background-color: var(--bg-lighter);
    padding: 24px;
    border-radius: 12px;
    text-align: center;
    color: var(--text-muted);
    font-size: 1.1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}

.no-responses::before {
    content: '';
    display: block;
    width: 60px;
    height: 60px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 24 24' fill='none' stroke='rgba(255, 255, 255, 0.2)' stroke-width='1' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: center;
}

.response-cards-container {
    overflow-y: auto;
    padding-right: 8px;
    margin-bottom: 20px;
    flex: 1;
}

.response-cards-container::-webkit-scrollbar {
    width: 6px;
}

.response-cards-container::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}

.response-cards-container::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}

.response-cards-container::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

.response-card {
    background-color: var(--bg-lighter);
    border-radius: 12px;
    margin-bottom: 16px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    animation: fadeIn 0.5s ease-out forwards;
    opacity: 0;
}

.response-card:nth-child(1) { animation-delay: 0.1s; }
.response-card:nth-child(2) { animation-delay: 0.2s; }
.response-card:nth-child(3) { animation-delay: 0.3s; }
.response-card:nth-child(4) { animation-delay: 0.4s; }
.response-card:nth-child(5) { animation-delay: 0.5s; }

.response-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    border-color: rgba(255, 255, 255, 0.1);
}

.response-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: rgba(45, 46, 54, 0.5);
}

.response-meta {
    display: flex;
    gap: 12px;
    align-items: center;
}

.response-author {
    font-weight: 600;
    background-color: rgba(227, 196, 58, 0.2);
    color: var(--accent-color);
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.85rem;
}

.response-date {
    color: var(--text-muted);
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 6px;
}

.response-date::before {
    content: '';
    display: inline-block;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background-color: var(--text-muted);
}

.response-actions {
    display: flex;
    gap: 8px;
}

.btn-icon {
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-icon:hover {
    color: var(--text-primary);
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

.response-content {
    padding: 20px;
    line-height: 1.7;
    font-size: 1.05rem;
    position: relative;
}

.response-content::before {
    content: '"';
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: 3rem;
    color: rgba(255, 255, 255, 0.05);
    font-family: Georgia, serif;
    line-height: 1;
}

/* Add Response Section */
.add-response-section {
    margin-top: auto;
    background-color: var(--bg-lighter);
    padding: 20px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.05);
    animation: fadeIn 0.5s ease-out 0.6s forwards;
    opacity: 0;
}

.add-response-section h3 {
    margin-bottom: 16px;
    font-size: 1.2rem;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border-color);
}

.add-response-section h3::before {
    content: '';
    display: block;
    width: 4px;
    height: 18px;
    background-color: var(--accent-color);
    border-radius: 2px;
}

.form-group {
    margin-bottom: 16px;
}

.form-group textarea {
    width: 100%;
    padding: 16px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    background-color: var(--primary-color);
    color: var(--text-primary);
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
    font-size: 1rem;
    line-height: 1.6;
    transition: all 0.3s ease;
}

.form-group textarea:focus {
    outline: none;
    border-color: var(--accent-color);
    box-shadow: 0 0 0 3px rgba(227, 196, 58, 0.15);
}

.form-group textarea::placeholder {
    color: rgba(255, 255, 255, 0.3);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
}

/* Button Styles */
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
}

.btn::after {
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

.btn:hover::after {
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

.btn-primary {
    background-color: var(--accent-color);
    color: var(--primary-color);
    box-shadow: 0 4px 15px rgba(227, 196, 58, 0.3);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(227, 196, 58, 0.4);
}

.btn-primary:active {
    transform: translateY(-1px);
}

.btn-secondary {
    background-color: var(--info-color);
    color: var(--primary-color);
    box-shadow: 0 4px 15px rgba(88, 166, 255, 0.3);
}

.btn-secondary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(88, 166, 255, 0.4);
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

.flex-between {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
}

.action-buttons {
    display: flex;
    gap: 12px;
}

/* Alert Styles */
.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    animation: slideIn 0.5s ease-out;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
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

/* Add this to your HTML to wrap response cards */
.response-cards-container {
    max-height: 400px;
    overflow-y: auto;
}
    </style>
</body>
</html>