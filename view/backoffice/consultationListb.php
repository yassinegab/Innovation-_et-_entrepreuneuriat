<?php 
include('../../controller/ConsultationController.php');
include('../../controller/reponseController.php');

$consultationC = new ConsultationController();
$reponseC = new ReponseController();



$column = 'id_consultation';
$order = 'ASC';

// Traitement de la recherche par ID
$searchResult = null;
if (isset($_GET['search_id']) && !empty($_GET['search_id'])) {
    $searchId = intval($_GET['search_id']);
    $searchResult = $consultationC->rechercherConsultationParId($searchId);
}

if (isset($_GET['sort'])) {
    switch ($_GET['sort']) {
        case 'id_asc':
            $column = 'id_consultation';
            $order = 'ASC';
            break;
        case 'id_desc':
            $column = 'id_consultation';
            $order = 'DESC';
            break;
        case 'titre_asc':
            $column = 'titre';
            $order = 'ASC';
            break;
        case 'titre_desc':
            $column = 'titre';
            $order = 'DESC';
            break;
    }
}

// Delete consultation
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $consultationC->supprimerConsultation($id);
    header("Location: consultationListb.php");
    exit();
}



// Add consultation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titre'])) {
    if (!empty($_POST['titre']) && !empty($_POST['type']) && !empty($_POST['description']) && !empty($_POST['date']) && !empty($_POST['id_utilisateur']) && !empty($_POST['statut'])) {
        $titre = htmlspecialchars(trim($_POST['titre']));
        $type = htmlspecialchars(trim($_POST['type']));
        $description = htmlspecialchars(trim($_POST['description']));
        $date = $_POST['date'];
        $id_utilisateur = htmlspecialchars(trim($_POST['id_utilisateur']));
        $statut = $_POST['statut'];

        try {
            $db = new PDO('mysql:host=localhost;dbname=projet', 'root', '');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query = "INSERT INTO consultations (titre, type, description, date_consultation, id_utilisateur, statut) VALUES (:titre, :type, :description, :date, :id_utilisateur, :statut)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':id_utilisateur', $id_utilisateur);
            $stmt->bindParam(':statut', $statut);

            if ($stmt->execute()) {
                header("Location: consultationListb.php?success=true");
                exit();
            } else {
                echo 'Erreur lors de linsertion.';
            }
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
}

// Add response
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_reponse'])) {
    if (!empty($_POST['contenu']) && !empty($_POST['id_consultation'])) {
        $contenu = htmlspecialchars(trim($_POST['contenu']));
        $id_consultation = intval($_POST['id_consultation']);
        $date_reponse = date('Y-m-d H:i:s');
        $id_utilisateur = 1; // ID de l'administrateur (à adapter selon votre système)

        // Créer une nouvelle réponse
        $reponse = new Reponse(null, $contenu, $date_reponse, $id_consultation, $id_utilisateur);
        
        // Ajouter la réponse
        $reponseC->ajouterReponse($reponse);
        
        // Mettre à jour le statut de la consultation si nécessaire
        $consultation = $consultationC->getConsultationById($id_consultation);
        if ($consultation['statut'] === 'pending') {
            $reponseC->updateConsultationStatus($id_consultation, 'in-progress');
        }
        
        // Rediriger pour éviter la soumission multiple du formulaire
        header("Location: consultationListb.php?response_success=true");
        exit;
    } else {
        $error_message = "Le contenu de la réponse ne peut pas être vide.";
    }
}

// Fetch all consultations
$liste = $consultationC->listeConsultations();

// Fetch all responses
$reponses = $reponseC->afficherReponsesBack();

$liste = $consultationC->listeConsultationsSorted($column, $order);

// Fonction pour formater la date
function formatDate($date) {
    return date('d/m/Y à H:i', strtotime($date));
}

// Fonction pour tronquer le texte
function tronquerTexte($texte, $longueur = 50) {
    if (strlen($texte) > $longueur) {
        return substr($texte, 0, $longueur) . '...';
    }
    return $texte;
}

// Statistiques pour le dashboard
$totalConsultations = count($liste);

// Initialiser les compteurs par statut
$consultationsEnCours = 0;
$consultationsValidees = 0;
$consultationsAnnulees = 0;
$consultationsEnAttente = 0;

// Données pour le graphique par mois
$consultationsByMonth = array_fill(0, 12, 0);
$currentYear = date('Y');

// Données pour le camembert par statut
$statsByStatus = [
    'pending' => 0,
    'in-progress' => 0,
    'completed' => 0,
    'cancelled' => 0
];

// Données pour le graphique par type
$statsByType = [];

// Parcourir toutes les consultations pour calculer les statistiques
foreach ($liste as $consultation) {
    // Compter par statut
    switch ($consultation['statut']) {
        case 'pending':
            $consultationsEnAttente++;
            $statsByStatus['pending']++;
            break;
        case 'in-progress':
            $consultationsEnCours++;
            $statsByStatus['in-progress']++;
            break;
        case 'completed':
            $consultationsValidees++;
            $statsByStatus['completed']++;
            break;
        case 'cancelled':
            $consultationsAnnulees++;
            $statsByStatus['cancelled']++;
            break;
    }
    
    // Compter par type
    $type = $consultation['type'];
    if (!isset($statsByType[$type])) {
        $statsByType[$type] = 0;
    }
    $statsByType[$type]++;
    
    // Compter par mois (pour l'année en cours)
    $consultDate = new DateTime($consultation['date_consultation']);
    if ($consultDate->format('Y') == $currentYear) {
        $month = intval($consultDate->format('m')) - 1; // 0-indexed
        $consultationsByMonth[$month]++;
    }
}

// Convertir les données pour les graphiques
$statusLabels = json_encode(['En attente', 'En cours', 'Validées', 'Annulées']);
$statusData = json_encode([
    $statsByStatus['pending'], 
    $statsByStatus['in-progress'], 
    $statsByStatus['completed'], 
    $statsByStatus['cancelled']
]);

$monthLabels = json_encode(['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc']);
$monthData = json_encode($consultationsByMonth);

// Calculer le taux de réponse (nombre de consultations avec au moins une réponse)
$consultationsAvecReponse = 0;
foreach ($liste as $consultation) {
    $id_consultation = $consultation['id_consultation'];
    $hasResponse = false;
    
    foreach ($reponses as $reponse) {
        if ($reponse->getid_consultation() == $id_consultation) {
            $hasResponse = true;
            break;
        }
    }
    
    if ($hasResponse) {
        $consultationsAvecReponse++;
    }
}

$tauxReponse = ($totalConsultations > 0) ? round(($consultationsAvecReponse / $totalConsultations) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - EntrepreHub</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="theme-custom.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/back.css">
    <!-- Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- CountUp.js pour les animations de compteurs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.0.8/countUp.min.js"></script>
</head>
<body class="admin-body">
    <div class="admin-container">
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
                        <span>Tableau de bord</span>
                    </div>
                </div>
                
            </header>
            
            <!-- Dashboard Content -->
            <div class="admin-content">
                <div class="dashboard-header">
                    <h1>Statistiques des Consultations</h1>
                    <div class="date-filter">
                        <button class="btn btn-secondary">
                            <span>Derniers 30 jours</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Début de la section statistiques -->
                <div class="stats-dashboard fade-in">

    <!-- Cartes résumées -->
    <div class="stats-cards">
        <div class="stats-card" style="--card-color: #FFD6E0;">
            <div class="stats-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </div>
            <div class="stats-card-content">
                <h3>Total Consultations</h3>
                <div class="stats-card-value" id="total-consultations"><?= $totalConsultations ?></div>
                <div class="stats-card-info">Toutes consultations confondues</div>
            </div>
        </div>
        
        <div class="stats-card" style="--card-color: #FFEFB7;">
            <div class="stats-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
            </div>
            <div class="stats-card-content">
                <h3>Consultations en cours</h3>
                <div class="stats-card-value" id="consultations-en-cours"><?= $consultationsEnCours ?></div>
                <div class="stats-card-info">Statut: "in-progress"</div>
            </div>
        </div>
        
        <div class="stats-card" style="--card-color: #DCFCE7;">
            <div class="stats-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div class="stats-card-content">
                <h3>Consultations validées</h3>
                <div class="stats-card-value" id="consultations-validees"><?= $consultationsValidees ?></div>
                <div class="stats-card-info">Statut: "completed"</div>
            </div>
        </div>
        
        <div class="stats-card" style="--card-color: #FFE2DD;">
            <div class="stats-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
            </div>
            <div class="stats-card-content">
                <h3>Consultations annulées</h3>
                <div class="stats-card-value" id="consultations-annulees"><?= $consultationsAnnulees ?></div>
                <div class="stats-card-info">Statut: "cancelled"</div>
            </div>
        </div>
                </div>
                   <!-- Graphiques -->
                   <div class="stats-charts">
                        <div class="stats-chart-container fade-in" style="--delay: 0.3s">
                            <div class="stats-chart-header">
                                <h3>Répartition par statut</h3>
                                <div class="stats-chart-info">
                                    <span class="stats-chart-percentage"><?= $tauxReponse ?>%</span>
                                    <span class="stats-chart-label">Taux de réponse</span>
                                </div>
                            </div>
                            <div class="stats-chart-body">
                                <canvas id="status-chart"></canvas>
                            </div>
                        </div>
                        
                        <div class="stats-chart-container fade-in" style="--delay: 0.5s">
                            <div class="stats-chart-header">
                                <h3>Évolution mensuelle <?= $currentYear ?></h3>
                                <div class="stats-chart-info">
                                    <span class="stats-chart-label">Consultations par mois</span>
                                </div>
                            </div>
                            <div class="stats-chart-body">
                                <canvas id="monthly-chart"></canvas>
                            </div>
                        </div>
                    </div>
                <!-- Fin de la section statistiques -->
                
                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>La consultation a été ajoutée avec succès.</span>
                </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['response_success'])): ?>
                <div class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>Votre réponse a été ajoutée avec succès.</span>
                </div>
                <?php endif; ?>
                
                <!-- Add this code right after the alerts and before the table-section div -->
<?php if (isset($_GET['search_id']) && !empty($_GET['search_id'])): ?>
    <?php if ($searchResult): ?>
        <div class="search-result">
            <h3>Résultat de la recherche pour ID: <?= htmlspecialchars($_GET['search_id']) ?></h3>
            <div class="search-result-details">
                <div class="search-result-field">
                    <strong>ID:</strong> <?= htmlspecialchars($searchResult['id_consultation']) ?>
                </div>
                <div class="search-result-field">
                    <strong>Titre:</strong> <?= htmlspecialchars($searchResult['titre']) ?>
                </div>
                <div class="search-result-field">
                    <strong>Type:</strong> <?= htmlspecialchars($searchResult['type']) ?>
                </div>
                <div class="search-result-field">
                    <strong>Date:</strong> <?= htmlspecialchars($searchResult['date_consultation']) ?>
                </div>
                <div class="search-result-field">
                    <strong>Statut:</strong> 
                    <span class="table-badge status <?= strtolower($searchResult['statut']) ?>">
                        <?= htmlspecialchars($searchResult['statut']) ?>
                    </span>
                </div>
                <div class="search-result-field">
                    <strong>ID Utilisateur:</strong> <?= htmlspecialchars($searchResult['id_utilisateur']) ?>
                </div>
            </div>
            <div class="search-result-field" style="grid-column: 1 / -1;">
                <strong>Description:</strong> 
                <p><?= htmlspecialchars($searchResult['description']) ?></p>
            </div>
            <div style="margin-top: 16px;">
                <a href="reponseListb.php?id_consultation=<?= $searchResult['id_consultation'] ?>" class="btn btn-primary">
                    Voir les détails
                </a>
                <a href="updateConsultationb.php?id_consultation=<?= $searchResult['id_consultation'] ?>" class="btn btn-secondary">
                    Modifier
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="no-results">
            Aucune consultation trouvée avec l'ID: <?= htmlspecialchars($_GET['search_id']) ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

                
                <!-- Tableau des Consultations -->
 <div class="table-section">
    
            <div class="table-header-top">
                    <h2>Consultations récentes</h2>
                    <button class="add-btn" onclick="document.getElementById('new-consultation-modal').classList.add('active')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Ajouter consultation
                    </button>
            </div>
                    
                
            
            <div class="table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>ID Utilisateur</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($liste as $consultation): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($consultation['id_consultation']) ?></td>
                                        <td><?= htmlspecialchars($consultation['titre']) ?></td>
                                        <td><?= htmlspecialchars(substr($consultation['description'], 0, 50)) ?>...</td>
                                        <td><?= htmlspecialchars($consultation['date_consultation']) ?></td>
                                        <td><?= htmlspecialchars($consultation['type']) ?></td>
                                        <td>
                                            <span class="table-badge status <?= strtolower($consultation['statut']) ?>">
                                                <?= htmlspecialchars($consultation['statut']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($consultation['id_utilisateur']) ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="dropdown-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="12" cy="12" r="1"></circle>
                                                        <circle cx="19" cy="12" r="1"></circle>
                                                        <circle cx="5" cy="12" r="1"></circle>
                                                    </svg>
                                                </button>
                                                <div class="dropdown-content">
                                                    <a href="reponseListb.php?id_consultation=<?= $consultation['id_consultation'] ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                        Voir détails
                                                    </a>
                                                    <a href="updateConsultationb.php?id_consultation=<?= $consultation['id_consultation'] ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                        </svg>
                                                        Modifier
                                                    </a>
                                                    <a href="consultationListb.php?delete_id=<?= $consultation['id_consultation'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette consultation ?');">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        </svg>
                                                        Supprimer
                                                    </a>
                                                    <a href="#" onclick="openRepondreModal(<?= $consultation['id_consultation'] ?>, '<?= htmlspecialchars($consultation['titre'], ENT_QUOTES) ?>')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                                        </svg>
                                                        Répondre
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
             <!-- Responses Content -->
             <div class="admin-content">
                <div class="dashboard-header">
                    <h1>Gestion des Réponses</h1>
                    <div class="date-filter">
                        <button class="btn btn-secondary">
                            <span>Derniers 30 jours</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <?php if (isset($_GET['delete_success'])): ?>
                <div class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>La réponse a été supprimée avec succès.</span>
                </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['update_success'])): ?>
                <div class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>La réponse a été mise à jour avec succès.</span>
                </div>
                <?php endif; ?>
                
                <!-- Tableau des Réponses -->
                
                    <div class="table-header">
                        <h2>Liste des réponses</h2>
                       
                    </div>

                    <div class="table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Contenu</th>
                                    <th>Date</th>
                                    <th>ID Consultation</th>
                                    <th>ID Utilisateur</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($reponses)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Aucune réponse trouvée</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($reponses as $reponse): ?>
                                        <?php 
                                            // Récupérer la consultation associée
                                            $consultation = $consultationC->getConsultationById($reponse->getid_consultation());
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($reponse->getid_reponse()) ?></td>
                                            <td><?= htmlspecialchars(tronquerTexte($reponse->getContenu(), 50)) ?></td>
                                            <td><?= formatDate($reponse->getdate_reponse()) ?></td>
                                            <td>
                                                <a href="reponseListb.php?id_consultation=<?= $reponse->getid_consultation() ?>" class="consultation-link">
                                                    <?= htmlspecialchars($reponse->getid_consultation()) ?>
                                                    <?php if ($consultation): ?>
                                                        <span class="tooltip"><?= htmlspecialchars($consultation['titre']) ?></span>
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                            <td><?= htmlspecialchars($reponse->getid_utilisateur()) ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="dropdown-btn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <circle cx="12" cy="12" r="1"></circle>
                                                            <circle cx="19" cy="12" r="1"></circle>
                                                            <circle cx="5" cy="12" r="1"></circle>
                                                        </svg>
                                                    </button>
                                                    <div class="dropdown-content">
                                                        <a href="reponseListb.php?id_consultation=<?= $reponse->getid_consultation() ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                            Voir détails
                                                        </a>
                                                        <a href="updateReponse.php?id_reponse=<?= $reponse->getid_reponse() ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                            </svg>
                                                            Modifier
                                                        </a>
                                                        <a href="deleteReponseb.php?id_reponse=<?= $reponse->getid_reponse() ?>&id_consultation=<?= $reponse->getid_consultation() ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette réponse ?');">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                            Supprimer
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                
            </div>
           
        </main>
    </div>

    <!-- Modal pour ajouter une nouvelle consultation -->
    <div id="new-consultation-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Nouvelle consultation</h2>
                <button class="close-modal" onclick="document.getElementById('new-consultation-modal').style.display='none'">&times;</button>
            </div>
            <div class="modal-body">
                <form id="consultation-form" method="POST" action="consultationListb.php">
                    <div class="form-group">
                        <label for="consultation-subject">Sujet</label>
                        <input type="text" id="consultation-subject" name="titre" required>
                    </div>
                    <div class="form-group">
                        <label for="consultation-type">Type</label>
                        <select id="consultation-type" name="type" required>
                            <option value="">Sélectionnez un type</option>
                            <option value="financing">Financement</option>
                            <option value="legal">Juridique</option>
                            <option value="marketing">Marketing</option>
                            <option value="technical">Technique</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="consultation-description">Description</label>
                        <textarea id="consultation-description" name="description" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="consultation-date">Date</label>
                        <input type="date" id="consultation-date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="consultation-id_utilisateur">ID Utilisateur</label>
                        <input type="text" id="consultation-id_utilisateur" name="id_utilisateur" required>
                    </div>
                    <div class="form-group">
                        <label for="consultation-status">Statut</label>
                        <select id="consultation-status" name="statut" required>
                            <option value="pending">En attente</option>
                            <option value="in-progress">En cours</option>
                            <option value="completed">Terminée</option>
                            <option value="cancelled">Annulée</option>
                            
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="secondary-btn" type="button" onclick="document.getElementById('new-consultation-modal').style.display='none'">Annuler</button>
                        <button class="primary-btn" type="submit">Soumettre</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Modal pour répondre à une consultation -->
    <div id="repondre-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Répondre à la consultation: <span id="consultation-title"></span></h2>
                <button class="close-modal" onclick="document.getElementById('repondre-modal').style.display='none'">&times;</button>
            </div>
            <div class="modal-body">
                <form id="reponse-form" method="POST" action="consultationListb.php">
                    <input type="hidden" id="id_consultation" name="id_consultation">
                    <div class="form-group">
                        <label for="reponse-contenu">Votre réponse</label>
                        <textarea id="reponse-contenu" name="contenu" rows="5" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="secondary-btn" type="button" onclick="document.getElementById('repondre-modal').style.display='none'">Annuler</button>
                        <button class="primary-btn" type="submit" name="ajouter_reponse">Envoyer la réponse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 

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

        /* Animation de fade-in */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
            opacity: 0;
            animation-delay: calc(var(--delay, 0) * 1s);
        }

        /* Styles pour la section statistiques */
        .stats-dashboard {
            margin-bottom: 40px;
            background: linear-gradient(to bottom right, rgba(45, 46, 54, 0.5), rgba(36, 37, 43, 0.5));
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Cartes statistiques */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stats-card {
            background-color: var(--bg-light);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--card-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .stats-card-icon {
            background-color: var(--card-color);
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stats-card-icon svg {
            color: var(--primary-color);
        }

        .stats-card-content {
            flex: 1;
        }

        .stats-card-content h3 {
            margin: 0 0 8px 0;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .stats-card-value {
            font-size: 28px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .stats-card-info {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Graphiques */
        .stats-charts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
        }

        @media (max-width: 900px) {
            .stats-charts {
                grid-template-columns: 1fr;
            }
        }

        .stats-chart-container {
            background-color: var(--bg-light);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stats-chart-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stats-chart-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 500;
        }

        .stats-chart-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .stats-chart-percentage {
            font-size: 18px;
            font-weight: 600;
            color: var(--accent-color);
        }

        .stats-chart-label {
            font-size: 12px;
            color: var(--text-muted);
        }

        .stats-chart-body {
            padding: 20px;
            height: 300px;
            position: relative;
        }

        /* Dropdown styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
        }

        .dropdown-btn:hover {
            background-color: var(--bg-lighter);
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: var(--bg-light);
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 4px;
            border: 1px solid var(--border-color);
        }

        .dropdown-content.show {
            display: block;
        }

        .dropdown-content a {
            color: var(--text-primary);
            padding: 8px 12px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dropdown-content a:hover {
            background-color: var(--bg-lighter);
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background-color: var(--bg-light);
            margin: auto;
            padding: 0;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: modalopen 0.3s;
        }

        @keyframes modalopen {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        .modal-header {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.25rem;
        }

        .close-modal {
            color: var(--text-muted);
            background: none;
            border: none;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-modal:hover {
            color: var(--text-primary);
        }

        .modal-body {
            padding: 16px;
        }

        .modal-footer {
            padding: 16px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        /* Alert styles */
        .alert {
            padding: 12px 16px;
            margin-bottom: 16px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background-color: rgba(46, 160, 67, 0.2);
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }

        .alert-danger {
            background-color: rgba(248, 81, 73, 0.2);
            border: 1px solid var(--danger-color);
            color: var(--danger-color);
        }

        /* Table styles */
        .table-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .table-header-top h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }
        
        .table-section {
            margin-top: 24px;
        }

        .table-container {
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th,
        .admin-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .admin-table th {
            background-color: var(--bg-lighter);
            font-weight: 600;
        }

        .admin-table tr:hover {
            background-color: var(--bg-lighter);
        }

        .table-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status.pending {
            background-color: var(--warning-color);
            color: var(--primary-color);
        }

        .status.in-progress {
            background-color: var(--info-color);
            color: var(--primary-color);
        }

        .status.completed {
            background-color: var(--success-color);
            color: var(--primary-color);
        }

        .status.cancelled {
            background-color: var(--danger-color);
            color: var(--primary-color);
        }
        
        /* Add Button */
        .add-btn {
            background-color: var(--accent-color);
            color: var(--primary-color);
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .add-btn:hover {
            background-color: rgba(227, 196, 58, 0.9);
            transform: translateY(-1px);
        }

        .add-btn:active {
            transform: translateY(0);
        }
        
        /* Search and Sort Controls */
        .controls-wrapper {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
        }

        @media (max-width: 768px) {
            .controls-wrapper {
                flex-direction: column;
            }
        }

        .control-item {
            flex: 1;
            background-color: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .control-item:hover {
            border-color: rgba(255, 255, 255, 0.03);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .control-header {
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .control-header svg {
            color: var(--accent-color);
        }

        .control-header h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .control-body {
            padding: 16px;
        }

        /* Form Elements */
        .search-form {
            display: flex;
            gap: 8px;
        }

        .search-input {
            flex: 1;
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 8px 12px;
            color: var(--text-primary);
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(227, 196, 58, 0.1);
        }

        .sort-select {
            width: 100%;
            background-color: rgba(36, 34, 34, 0.03);
            border: 1px solid rgba(19, 18, 18, 0.1);
            border-radius: 8px;
            padding: 8px 12px;
            color: var(--text-primary);
            font-size: 14px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='rgb(227, 196, 58)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 30px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .sort-select:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(227, 196, 58, 0.1);
        }

        /* Button styles */
        .btn {
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
        }

        .btn-primary {
            background-color: var(--accent-color);
            color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: rgba(227, 196, 58, 0.9);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        /* Form styles */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 10px 12px;
            color: var(--text-primary);
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(227, 196, 58, 0.1);
        }

        /* Button styles for modals */
        .primary-btn,
        .secondary-btn {
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .primary-btn {
            background-color: var(--accent-color);
            color: var(--primary-color);
        }

        .primary-btn:hover {
            background-color: rgba(227, 196, 58, 0.9);
        }

        .secondary-btn {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
        }

        .secondary-btn:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
    </style>
     <script src="assets/back.js"></script>
    <script>
        // Script pour le toggle du menu sur mobile
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('active');
        });

        // Script pour ouvrir le modal de réponse
        function openRepondreModal(id_consultation, titre) {
            document.getElementById('id_consultation').value = id_consultation;
            document.getElementById('consultation-title').textContent = titre;
            document.getElementById('repondre-modal').style.display = 'block';
        }

        // Script pour les dropdowns dans le tableau
        document.addEventListener('DOMContentLoaded', function() {
            var dropdowns = document.querySelectorAll('.dropdown');
            
            dropdowns.forEach(function(dropdown) {
                dropdown.querySelector('.dropdown-btn').addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeAllDropdowns();
                    dropdown.querySelector('.dropdown-content').classList.toggle('show');
                });
            });
            
            // Fermer les dropdowns quand on clique ailleurs
            document.addEventListener('click', function() {
                closeAllDropdowns();
            });
            
            function closeAllDropdowns() {
                document.querySelectorAll('.dropdown-content').forEach(function(content) {
                    content.classList.remove('show');
                });
            }
            
            // Initialiser les graphiques
            initCharts();
            
            // Initialiser les compteurs animés
           // Initialisation des compteurs animés
function initCounters() {
    const options = {
        duration: 2,
        useEasing: true,
        useGrouping: true,
        separator: ' ',
        decimal: ',',
    };
    
    // Get the current values from the HTML
    const totalValue = parseInt(document.getElementById('total-consultations').textContent) || 0;
    const enCoursValue = parseInt(document.getElementById('consultations-en-cours').textContent) || 0;
    const valideesValue = parseInt(document.getElementById('consultations-validees').textContent) || 0;
    const annuleesValue = parseInt(document.getElementById('consultations-annulees').textContent) || 0;
    
    // Compteur total consultations
    const totalCounter = new CountUp('total-consultations', 0, totalValue, 0, 2.5, options);
    if (!totalCounter.error) {
        totalCounter.start();
    }
    
    // Compteur consultations en cours
    const enCoursCounter = new CountUp('consultations-en-cours', 0, enCoursValue, 0, 2.5, options);
    if (!enCoursCounter.error) {
        enCoursCounter.start();
    }
    
    // Compteur consultations validées
    const valideesCounter = new CountUp('consultations-validees', 0, valideesValue, 0, 2.5, options);
    if (!valideesCounter.error) {
        valideesCounter.start();
    }
    
    // Compteur consultations annulées
    const annuleesCounter = new CountUp('consultations-annulees', 0, annuleesValue, 0, 2.5, options);
    if (!annuleesCounter.error) {
        annuleesCounter.start();
    }
}
        });
        
        // Initialisation des graphiques
        function initCharts() {
            // Graphique camembert par statut
            const statusCtx = document.getElementById('status-chart').getContext('2d');
            const statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: <?= $statusLabels ?>,
                    datasets: [{
                        data: <?= $statusData ?>,
                        backgroundColor: [
                            '#FFEFB7', // En attente - jaune pastel
                            '#DFF4FF', // En cours - bleu pastel
                            '#DCFCE7', // Validées - vert pastel
                            '#FFE2DD'  // Annulées - rouge pastel
                        ],
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: 'rgba(255, 255, 255, 0.7)',
                                font: {
                                    family: 'Inter',
                                    size: 12
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(45, 46, 54, 0.9)',
                            titleColor: 'rgba(255, 255, 255, 0.9)',
                            bodyColor: 'rgba(255, 255, 255, 0.7)',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                }
            });
            
            // Graphique à barres par mois
            const monthlyCtx = document.getElementById('monthly-chart').getContext('2d');
            const monthlyChart = new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: <?= $monthLabels ?>,
                    datasets: [{
                        label: 'Consultations',
                        data: <?= $monthData ?>,
                        backgroundColor: 'rgba(227, 196, 58, 0.7)',
                        borderColor: 'rgba(227, 196, 58, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                        hoverBackgroundColor: 'rgba(227, 196, 58, 0.9)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)'
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.7)',
                                font: {
                                    family: 'Inter'
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.7)',
                                font: {
                                    family: 'Inter'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(45, 46, 54, 0.9)',
                            titleColor: 'rgba(255, 255, 255, 0.9)',
                            bodyColor: 'rgba(255, 255, 255, 0.7)',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            padding: 12
                        }
                    },
                    animation: {
                        delay: function(context) {
                            return context.dataIndex * 100;
                        },
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }
        
        // Initialisation des compteurs animés
        function initCounters() {
            const options = {
                duration: 2,
                useEasing: true,
                useGrouping: true,
                separator: ' ',
                decimal: ',',
            };
            
            // Compteur total consultations
            const totalCounter = new CountUp('total-consultations', 0, <?= $totalConsultations ?>, 0, 2.5, options);
            if (!totalCounter.error) {
                totalCounter.start();
            }
            
            // Compteur consultations en cours
            const enCoursCounter = new CountUp('consultations-en-cours', 0, <?= $consultationsEnCours ?>, 0, 2.5, options);
            if (!enCoursCounter.error) {
                enCoursCounter.start();
            }
            
            // Compteur consultations validées
            const valideesCounter = new CountUp('consultations-validees', 0, <?= $consultationsValidees ?>, 0, 2.5, options);
            if (!valideesCounter.error) {
                valideesCounter.start();
            }
            
            // Compteur consultations annulées
            const annuleesCounter = new CountUp('consultations-annulees', 0, <?= $consultationsAnnulees ?>, 0, 2.5, options);
            if (!annuleesCounter.error) {
                annuleesCounter.start();
            }
        }
    </script>
</body>
</html>
