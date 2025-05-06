<?php
require_once __DIR__ . '/../../controller/evenController.php';
include_once('../../config.php');
include_once('../../controller/insccontroller.php');

// Initialisation des contrôleurs
$inscriptioncontroller = new InscriptionController();
$eventcontroller = new EvenementController();

// Récupération des événements
$db = config::getConnexion();
$query = $db->prepare("SELECT * FROM evenement");
$query->execute();
$listeEvenements = $query->fetchAll(PDO::FETCH_ASSOC);

// Récupération des inscriptions
try {
    $pdo = config::getConnexion();
    $query = $pdo->prepare("
        SELECT i.*, e.nom, e.date, e.lieu, e.capacite 
        FROM inscription i 
        JOIN evenement e ON i.id_eve = e.id_ev
    ");
    $query->execute();
    $listeInscriptions = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erreur: ' . $e->getMessage();
    $listeInscriptions = [];
}

// Traitement de la suppression d'inscription
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_inscription'])) {
    $id_inscription = $_POST['id_inscription'];
    $inscriptioncontroller->deleteinsc($id_inscription);
    $message = "<div class='success-message'>Inscription supprimée avec succès.</div>";
    
    // Recharger les inscriptions après suppression
    try {
        $query = $pdo->prepare("
            SELECT i.*, e.nom, e.date, e.lieu, e.capacite 
            FROM inscription i 
            JOIN evenement e ON i.id_eve = e.id_ev
        ");
        $query->execute();
        $listeInscriptions = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erreur: ' . $e->getMessage();
        $listeInscriptions = [];
    }
}

// Traitement de la confirmation d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_inscription'])) {
    $id_inscription = $_POST['id_inscription'];
    
    // Utiliser la nouvelle méthode qui gère à la fois la mise à jour du statut et l'envoi d'email
    $result = $inscriptioncontroller->confirmerInscription($id_inscription);
    
    if ($result) {
        $message = "<div class='success-message'>Inscription confirmée avec succès. Un email de confirmation a été envoyé à l'utilisateur.</div>";
    } else {
        $message = "<div class='success-message'>Inscription confirmée avec succès, mais l'email n'a pas pu être envoyé.</div>";
    }
    
    // Recharger les inscriptions après mise à jour
    try {
        $query = $pdo->prepare("
            SELECT i.*, e.nom, e.date, e.lieu, e.capacite 
            FROM inscription i 
            JOIN evenement e ON i.id_eve = e.id_ev
        ");
        $query->execute();
        $listeInscriptions = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erreur: ' . $e->getMessage();
        $listeInscriptions = [];
    }
}

// Traitement du refus d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['refuse_inscription'])) {
    $id_inscription = $_POST['id_inscription'];
    
    // Utiliser la nouvelle méthode qui gère à la fois la mise à jour du statut et l'envoi d'email
    $result = $inscriptioncontroller->refuserInscription($id_inscription);
    
    if ($result) {
        $message = "<div class='success-message'>Inscription refusée. Un email d'information a été envoyé à l'utilisateur.</div>";
    } else {
        $message = "<div class='success-message'>Inscription refusée, mais l'email n'a pas pu être envoyé.</div>";
    }
    
    // Recharger les inscriptions après mise à jour
    try {
        $query = $pdo->prepare("
            SELECT i.*, e.nom, e.date, e.lieu, e.capacite 
            FROM inscription i 
            JOIN evenement e ON i.id_eve = e.id_ev
        ");
        $query->execute();
        $listeInscriptions = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erreur: ' . $e->getMessage();
        $listeInscriptions = [];
    }
}

// Le reste du code reste inchangé...


// Filtrer par événement si un ID est spécifié
$evenement_selectionne = isset($_GET['event_id']) ? $_GET['event_id'] : null;

// Organiser les inscriptions par événement
$inscriptionsParEvenement = [];
foreach ($listeEvenements as $evenement) {
    $inscriptionsParEvenement[$evenement['id_ev']] = [
        'evenement' => $evenement,
        'inscriptions' => []
    ];
}

foreach ($listeInscriptions as $inscription) {
    if (isset($inscriptionsParEvenement[$inscription['id_eve']])) {
        $inscriptionsParEvenement[$inscription['id_eve']]['inscriptions'][] = $inscription;
    }
}

// Calculer les statistiques pour le tableau de bord
$totalEvenements = count($listeEvenements);
$totalParticipants = 0;
$totalInscriptions = count($listeInscriptions);
$totalRevenu = 0;



// Compter les participants (supposons que chaque inscription = 1 participant)
$totalParticipants = $totalInscriptions;

// Préparer les données pour les graphiques
$dataInscriptionsParEvenement = [];
$dataRepartitionEvenements = [
    'Conférences' => 0,
    'Ateliers' => 0,
    'Réseautage' => 0,
    'Formations' => 0
];

// Catégoriser les événements (exemple simplifié)
/*foreach ($listeEvenements as $index => $evenement) {
    $categorie = $index % 4;
    switch ($categorie) {
        case 0:
            $dataRepartitionEvenements['Conférences']++;
            break;
        case 1:
            $dataRepartitionEvenements['Ateliers']++;
            break;
        case 2:
            $dataRepartitionEvenements['Réseautage']++;
            break;
        case 3:
            $dataRepartitionEvenements['Formations']++;
            break;
    }
}*/

// Préparer les données pour le graphique des inscriptions
foreach ($inscriptionsParEvenement as $id => $data) {
    if (count($data['inscriptions']) > 0) {
        $dataInscriptionsParEvenement[$data['evenement']['nom']] = count($data['inscriptions']);
    }
}

// Trier par nombre d'inscriptions et prendre les 7 premiers
arsort($dataInscriptionsParEvenement);
$dataInscriptionsParEvenement = array_slice($dataInscriptionsParEvenement, 0, 7, true);

// Convertir les données en format JSON pour les graphiques
$jsonDataInscriptions = json_encode($dataInscriptionsParEvenement);
$jsonDataRepartition = json_encode(array_values($dataRepartitionEvenements));
$jsonLabelsRepartition = json_encode(array_keys($dataRepartitionEvenements));

// Récupérer les événements récents (les 5 derniers)
$evenementsRecents = array_slice($listeEvenements, 0, 5);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - EntrepreHub</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg-sidebar: #1a1c23;
            --bg-main: #121317;
            --bg-card: #1e2029;
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --accent: #d4b106;
            --accent-hover: #e9c307;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --border: #2d3748;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-primary);
            line-height: 1.5;
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 260px;
            background-color: var(--bg-sidebar);
            padding: 1.5rem 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar-brand {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        
        .sidebar-brand h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        
        .sidebar-nav {
            padding: 1.5rem 0;
            flex: 1;
        }
        
        .sidebar-nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s;
            margin-bottom: 0.25rem;
        }
        
        .sidebar-nav-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border-left: 3px solid var(--accent);
        }
        
        .sidebar-nav-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
        }
        
        .sidebar-nav-item svg {
            width: 20px;
            height: 20px;
            margin-right: 0.75rem;
        }
        
        .sidebar-nav-item .badge {
            margin-left: auto;
            background-color: var(--accent);
            color: var(--bg-sidebar);
            border-radius: 9999px;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 1.5rem;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }
        
        .header-title {
            display: flex;
            align-items: center;
        }
        
        .header-title h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-right: 0.5rem;
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        .breadcrumb-item:not(:last-child)::after {
            content: "›";
            margin: 0 0.5rem;
        }
        
        .search-bar {
            position: relative;
            width: 300px;
        }
        
        .search-bar input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border-radius: 9999px;
            border: none;
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
        }
        
        .search-bar svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: var(--text-secondary);
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .notification-bell {
            position: relative;
            cursor: pointer;
        }
        
        .notification-bell svg {
            width: 20px;
            height: 20px;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 16px;
            height: 16px;
            background-color: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.625rem;
            font-weight: 600;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-card {
            background-color: var(--bg-card);
            border-radius: 0.5rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }
        
        .stat-icon.events {
            background-color: rgba(212, 177, 6, 0.2);
            color: var(--accent);
        }
        
        .stat-icon.participants {
            background-color: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }
        
        .stat-icon.inscriptions {
            background-color: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }
        
        .stat-icon.revenue {
            background-color: rgba(239, 68, 68, 0.2);
            color: var(--danger);
        }
        
        .stat-icon svg {
            width: 24px;
            height: 24px;
        }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-title {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .stat-change {
            display: flex;
            align-items: center;
            font-size: 0.75rem;
        }
        
        .stat-change.positive {
            color: var(--success);
        }
        
        .stat-change.negative {
            color: var(--danger);
        }
        
        .chart-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .chart-card {
            background-color: var(--bg-card);
            border-radius: 0.5rem;
            padding: 1.5rem;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .chart-title {
            font-size: 1rem;
            font-weight: 600;
        }
        
        .chart-tabs {
            display: flex;
            gap: 0.5rem;
        }
        
        .chart-tab {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            cursor: pointer;
            background-color: transparent;
            color: var(--text-secondary);
            border: none;
        }
        
        .chart-tab.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
        }
        
        .chart-container {
            height: 300px;
            position: relative;
        }
        
        .event-section {
            background-color: var(--bg-card);
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        
        .event-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .event-title {
            font-size: 1.125rem;
            font-weight: 600;
        }
        
        .event-meta {
            display: flex;
            gap: 1rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        .event-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .event-meta-item svg {
            width: 16px;
            height: 16px;
        }
        
        .inscriptions-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .inscriptions-table th {
            text-align: left;
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border);
        }
        
        .inscriptions-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        
        .inscriptions-table tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-confirmed {
            background-color: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }
        
        .status-pending {
            background-color: rgba(245, 158, 11, 0.2);
            color: var(--warning);
        }
        
        .status-refused {
            background-color: rgba(239, 68, 68, 0.2);
            color: var(--danger);
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .btn-confirm {
            background-color: var(--success);
            color: white;
        }
        
        .btn-confirm:hover {
            background-color: #0d9669;
        }
        
        .btn-refuse {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-refuse:hover {
            background-color: #dc2626;
        }
        
        .btn-delete {
            background-color: rgba(239, 68, 68, 0.2);
            color: var(--danger);
        }
        
        .btn-delete:hover {
            background-color: rgba(239, 68, 68, 0.3);
        }
        
        .no-inscriptions {
            padding: 2rem;
            text-align: center;
            color: var(--text-secondary);
        }
        
        .event-filter {
            margin-bottom: 1.5rem;
            display: flex;
            gap: 1rem;
        }
        
        .event-filter select {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            background-color: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border);
            font-size: 0.875rem;
        }
        
        .success-message {
            background-color: rgba(16, 185, 129, 0.2);
            border-left: 4px solid var(--success);
            color: var(--success);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 0.25rem;
        }
        
        .error-message {
            background-color: rgba(239, 68, 68, 0.2);
            border-left: 4px solid var(--danger);
            color: var(--danger);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 0.25rem;
        }
        
        .tab-container {
            margin-bottom: 1.5rem;
        }
        
        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }
        
        .tab {
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            font-weight: 500;
            color: var(--text-secondary);
            border-bottom: 2px solid transparent;
        }
        
        .tab.active {
            color: var(--accent);
            border-bottom: 2px solid var(--accent);
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .chart-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
                padding: 1rem 0;
            }
            
            .sidebar-brand {
                padding: 0 1rem 1rem;
                display: flex;
                justify-content: center;
            }
            
            .sidebar-brand h1 {
                display: none;
            }
            
            .sidebar-nav-item {
                padding: 0.75rem 1rem;
                justify-content: center;
            }
            
            .sidebar-nav-item span {
                display: none;
            }
            
            .sidebar-nav-item svg {
                margin-right: 0;
            }
            
            .main-content {
                margin-left: 80px;
            }
        }
        
        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .search-bar {
                width: 100%;
            }
            
            .user-menu {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h1>EntrepreHub</h1>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="sidebar-nav-item active">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span>Tableau de bord</span>
            </a>
            <a href="#" class="sidebar-nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Événements</span>
            </a>
            <a href="#" class="sidebar-nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Utilisateurs</span>
            </a>
            <a href="#" class="sidebar-nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Intervenants</span>
            </a>
            <a href="#" class="sidebar-nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Analytiques</span>
            </a>
            <a href="#" class="sidebar-nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                <span>Messages</span>
                <span class="badge">5</span>
            </a>
            <a href="#" class="sidebar-nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Paramètres</span>
            </a>
            <a href="#" class="sidebar-nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-1.893 3.772-1.893 2.552 0 4.5 2.243 4.5 5s-1.753 5-4.5 5c-1.742 0-3.223-.728-3.772-1.893" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-2-9-9-2 2 9z" />
                </svg>
                <span>Aide</span>
            </a>
        </nav>
    </aside>
    
    <main class="main-content">
        <header class="header">
            <div class="header-title">
                <h1>Administration</h1>
                <div class="breadcrumb">
                    <span class="breadcrumb-item">Administration</span>
                    <span class="breadcrumb-item">Tableau de bord</span>
                </div>
            </div>
            <div class="search-bar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Rechercher...">
            </div>
            <div class="user-menu">
                <div class="notification-bell">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="notification-badge">3</span>
                </div>
                <div class="user-profile">
                    <div class="user-avatar">SM</div>
                    <div class="user-info">
                        <span class="user-name">Sophie Martin</span>
                        <span class="user-role">Administrateur</span>
                    </div>
                </div>
            </div>
        </header>
        
        <?php if (!empty($message)) echo $message; ?>
        
        <div class="tab-container">
            <div class="tabs">
                <div class="tab active" onclick="showTab('dashboard')">Tableau de bord</div>
                <div class="tab" onclick="showTab('inscriptions')">Gestion des inscriptions</div>
            </div>
        </div>
        
        <div id="dashboard-tab">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon events">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-title">Événements</div>
                        <div class="stat-value"><?= $totalEvenements ?></div>
                        <div class="stat-change positive">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            +12.5%
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon participants">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-title">Participants</div>
                        <div class="stat-value"><?= $totalParticipants ?></div>
                        <div class="stat-change positive">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            +18.2%
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon inscriptions">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-title">Inscriptions</div>
                        <div class="stat-value"><?= $totalInscriptions ?></div>
                        <div class="stat-change positive">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            +5.3%
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-title">Revenus</div>
                        <div class="stat-value">€<?= number_format($totalRevenu, 0, ',', ' ') ?></div>
                        <div class="stat-change negative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                            -2.4%
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="chart-grid">
                <div class="chart-card">
                    <div class="chart-header">
                        <h2 class="chart-title">Inscriptions aux événements</h2>
                        <div class="chart-tabs">
                            <button class="chart-tab active">Jour</button>
                            <button class="chart-tab">Semaine</button>
                            <button class="chart-tab">Mois</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="inscriptionsChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-header">
                        <h2 class="chart-title">Répartition des événements</h2>
                        <div class="chart-tabs">
                            <button class="chart-tab active">Tous</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="repartitionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="inscriptions-tab" style="display: none;">
            <div class="event-filter">
                <select id="event-selector" onchange="window.location.href='?event_id='+this.value">
                    <option value="">Tous les événements</option>
                    <?php foreach ($listeEvenements as $evenement): ?>
                        <option value="<?= $evenement['id_ev'] ?>" <?= $evenement_selectionne == $evenement['id_ev'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($evenement['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <?php 
            // Si un événement est sélectionné, n'afficher que cet événement
            if ($evenement_selectionne) {
                if (isset($inscriptionsParEvenement[$evenement_selectionne])) {
                    $eventData = $inscriptionsParEvenement[$evenement_selectionne];
                    displayEventSection($eventData['evenement'], $eventData['inscriptions']);
                }
            } else {
                // Sinon, afficher tous les événements
                foreach ($inscriptionsParEvenement as $eventData) {
                    displayEventSection($eventData['evenement'], $eventData['inscriptions']);
                }
            }
            
            // Fonction pour afficher une section d'événement
            function displayEventSection($evenement, $inscriptions) {
            ?>
                <div class="event-section">
                    <div class="event-header">
                        <h2 class="event-title"><?= htmlspecialchars($evenement['nom']) ?></h2>
                        <div class="event-meta">
                            <div class="event-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span><?= htmlspecialchars($evenement['date']) ?></span>
                            </div>
                            <div class="event-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span><?= htmlspecialchars($evenement['lieu']) ?></span>
                            </div>
                            <div class="event-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Inscriptions: <?= count($inscriptions) ?> / <?= htmlspecialchars($evenement['capacite']) ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (empty($inscriptions)): ?>
                        <div class="no-inscriptions">
                            <p>Aucune inscription pour cet événement.</p>
                        </div>
                    <?php else: ?>
                        <table class="inscriptions-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Utilisateur</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inscriptions as $inscription): 
                                    $statusClass = '';
                                    switch($inscription['statut']) {
                                        case 'Confirmée':
                                            $statusClass = 'status-confirmed';
                                            break;
                                        case 'Refusée':
                                            $statusClass = 'status-refused';
                                            break;
                                        default:
                                            $statusClass = 'status-pending';
                                    }
                                ?>
                                <tr>
                                    <td><?= $inscription['id_inscription'] ?></td>
                                    <td>Utilisateur #<?= $inscription['id_uti'] ?></td>
                                    <td>
                                        <span class="status-badge <?= $statusClass; ?>">
                                            <?= htmlspecialchars($inscription['statut']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if ($inscription['statut'] === 'En attente'): ?>
                                                <form method="POST" action="" style="display: inline;">
                                                    <input type="hidden" name="id_inscription" value="<?= $inscription['id_inscription'] ?>">
                                                    <button type="submit" name="confirm_inscription" class="btn btn-confirm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Confirmer
                                                    </button>
                                                </form>
                                                <form method="POST" action="" style="display: inline;">
                                                    <input type="hidden" name="id_inscription" value="<?= $inscription['id_inscription'] ?>">
                                                    <button type="submit" name="refuse_inscription" class="btn btn-refuse">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Refuser
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <form method="POST" action="" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?');">
                                                <input type="hidden" name="id_inscription" value="<?= $inscription['id_inscription'] ?>">
                                                <button type="submit" name="delete_inscription" class="btn btn-delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php
            }
            ?>
        </div>
    </main>
    
    <script>
        // Configuration des graphiques
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique des inscriptions
            const inscriptionsCtx = document.getElementById('inscriptionsChart').getContext('2d');
            const inscriptionsChart = new Chart(inscriptionsCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_keys($dataInscriptionsParEvenement)) ?>,
                    datasets: [{
                        label: 'Nombre d\'inscriptions',
                        data: <?= json_encode(array_values($dataInscriptionsParEvenement)) ?>,
                        backgroundColor: '#d4b106',
                        borderColor: '#d4b106',
                        borderWidth: 1,
                        borderRadius: 4,
                        barThickness: 20,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#94a3b8'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#94a3b8'
                            }
                        }
                    }
                }
            });
            
            // Graphique de répartition
            const repartitionCtx = document.getElementById('repartitionChart').getContext('2d');
            const repartitionChart = new Chart(repartitionCtx, {
                type: 'doughnut',
                data: {
                    labels: <?= $jsonLabelsRepartition ?>,
                    datasets: [{
                        data: <?= $jsonDataRepartition ?>,
                        backgroundColor: [
                            '#d4b106', // Jaune
                            '#10b981', // Vert
                            '#3b82f6', // Bleu
                            '#8b5cf6'  // Violet
                        ],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                color: '#e2e8f0',
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        });
        
        // Gestion des onglets
        function showTab(tabName) {
            // Cacher tous les onglets
            document.getElementById('dashboard-tab').style.display = 'none';
            document.getElementById('inscriptions-tab').style.display = 'none';
            
            // Afficher l'onglet sélectionné
            document.getElementById(tabName + '-tab').style.display = 'block';
            
            // Mettre à jour les classes actives
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Trouver l'onglet correspondant et le marquer comme actif
            if (tabName === 'dashboard') {
                tabs[0].classList.add('active');
            } else if (tabName === 'inscriptions') {
                tabs[1].classList.add('active');
            }
        }
    </script>
</body>
</html>
