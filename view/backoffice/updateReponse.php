<?php
// Inclure les fichiers nécessaires
require_once('../../controller/reponseController.php');
require_once('../../controller/ConsultationController.php');
require_once('../../model/reponse.php');

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si l'ID est fourni
if (!isset($_GET['id_reponse']) || empty($_GET['id_reponse'])) {
    header("Location: consultationListb.php");
    exit;
}

$id_reponse = intval($_GET['id_reponse']);

// Initialiser les contrôleurs
$reponseC = new ReponseController();
$consultationC = new ConsultationController();

// Récupérer la réponse
$reponse = $reponseC->recupererReponse($id_reponse);

// Vérifier si la réponse existe
if (!$reponse) {
    header("Location: consultationListb.php");
    exit;
}

// Récupérer la consultation associée
$consultation = $consultationC->getConsultationById($reponse->getid_consultation());

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['contenu'])) {
        $contenu = htmlspecialchars(trim($_POST['contenu']));
        $date_reponse = date('Y-m-d H:i:s'); // Mettre à jour la date de la réponse
        
        // Mettre à jour l'objet réponse
        $reponse->setContenu($contenu);
        $reponse->setdate_reponse($date_reponse);
        
        // Mettre à jour la réponse dans la base de données
        $reponseC->modifierReponse($reponse);
        
        // Rediriger vers la page des détails de la consultation
        header("Location: reponseListb.php?id_consultation=" . $reponse->getid_consultation() . "&update_success=true");
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
        'completed' => 'Terminée',
        'cancelled' => 'Annulée'
    ];
    
    return isset($statuts[$statut]) ? $statuts[$statut] : $statut;
}

// Fonction pour obtenir la classe CSS du statut
function getStatusClass($statut) {
    $classes = [
        'pending' => 'warning',
        'in-progress' => 'info',
        'completed' => 'success',
        'cancelled' => 'danger'
    ];
    
    return isset($classes[$statut]) ? $classes[$statut] : '';
}

// Fonction pour obtenir la couleur du type
function getTypeColor($type) {
    $colors = [
        'financing' => 'var(--pastel-pink)',
        'legal' => 'var(--pastel-purple)',
        'marketing' => 'var(--pastel-yellow)',
        'technical' => 'var(--pastel-blue)'
    ];
    
    return isset($colors[$type]) ? $colors[$type] : 'var(--accent-color)';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la réponse - EntrepreHub</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <main class="main-content">
        <header class="main-header">
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Rechercher...">
            </div>
            <div class="header-actions">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">2</span>
                </button>
                <a href="reponseListb.php?id_consultation=<?php echo $reponse->getid_consultation(); ?>" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </header>

        <!-- Update Response Modal -->
        <div id="update-response-modal" class="modal modal-active">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Modifier la réponse</h2>
                    <a href="reponseListb.php?id_consultation=<?php echo $reponse->getid_consultation(); ?>" class="close-modal">&times;</a>
                </div>
                <div class="modal-body">
                    <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo $error_message; ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="consultation-preview">
                        <div class="consultation-badge" style="background-color: <?php echo getTypeColor($consultation['type']); ?>">
                            <i class="fas <?php 
                                switch($consultation['type']) {
                                    case 'financing': echo 'fa-coins'; break;
                                    case 'legal': echo 'fa-gavel'; break;
                                    case 'marketing': echo 'fa-bullhorn'; break;
                                    case 'technical': echo 'fa-code'; break;
                                    default: echo 'fa-question-circle';
                                }
                            ?>"></i>
                        </div>
                        <div class="consultation-info">
                            <h3><?php echo htmlspecialchars($consultation['titre']); ?></h3>
                            <div class="consultation-meta">
                                <span class="consultation-type" style="background-color: <?php echo getTypeColor($consultation['type']); ?>">
                                    <?php echo getTypeLabel($consultation['type']); ?>
                                </span>
                                <span class="consultation-status status-<?php echo $consultation['statut']; ?>">
                                    <?php echo getStatusLabel($consultation['statut']); ?>
                                </span>
                                <span class="consultation-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?php echo formatDate($consultation['date_consultation']); ?>
                                </span>
                            </div>
                            <div class="consultation-description">
                                <p><?php echo nl2br(htmlspecialchars($consultation['description'])); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <form id="update-response-form" method="POST" action="">
                        <div class="form-group">
                            <label for="contenu">
                                <i class="fas fa-comment-dots"></i>
                                Contenu de la réponse
                            </label>
                            <div class="textarea-container">
                                <textarea id="contenu" name="contenu" rows="8" required><?php echo htmlspecialchars($reponse->getContenu()); ?></textarea>
                                <div class="textarea-decoration"></div>
                            </div>
                            <div class="response-meta">
                                <span class="response-date">
                                    <i class="far fa-clock"></i>
                                    Dernière modification: <?php echo formatDate($reponse->getdate_reponse()); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <a href="reponseListb.php?id_consultation=<?php echo $reponse->getid_consultation(); ?>" class="secondary-btn">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="primary-btn">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
            background-color: var(--primary-color);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
            position: relative;
            overflow: auto;
        }
        
        /* Header */
        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .search-container {
            display: flex;
            align-items: center;
            background-color: var(--bg-lighter);
            border-radius: 8px;
            padding: 8px 15px;
            width: 300px;
        }
        
        .search-container i {
            color: var(--text-muted);
            margin-right: 10px;
        }
        
        .search-container input {
            background: transparent;
            border: none;
            color: var(--text-primary);
            width: 100%;
            outline: none;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .notification-btn {
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 18px;
            cursor: pointer;
            position: relative;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--accent-color);
            color: var(--primary-color);
            font-size: 10px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .back-btn:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
        
        /* Update Response Modal Styles */
        #update-response-modal {
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
        
        #update-response-modal .modal-content {
            background-color: rgb(36, 37, 43);
            margin: auto;
            width: 90%;
            max-width: 700px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
            animation: modalSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            position: relative;
        }
        
        /* Creative decorative elements */
        #update-response-modal .modal-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, var(--accent-color), var(--pastel-pink), var(--pastel-blue));
            z-index: 1;
        }
        
        #update-response-modal .modal-content::after {
            content: '';
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 100px;
            height: 100px;
            background-image: radial-gradient(circle, var(--accent-color) 2px, transparent 2px);
            background-size: 15px 15px;
            opacity: 0.1;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        
        @keyframes modalSlideIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        #update-response-modal .modal-header {
            background-color: rgb(45, 46, 54);
            padding: 18px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        
        #update-response-modal .modal-header h2 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.95);
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        #update-response-modal .modal-header h2::before {
            content: '';
            display: inline-block;
            width: 3px;
            height: 20px;
            background-color: var(--accent-color);
            border-radius: 3px;
            margin-right: 10px;
        }
        
        #update-response-modal .close-modal {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        #update-response-modal .close-modal:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
            transform: rotate(90deg);
        }
        
        #update-response-modal .modal-body {
            padding: 24px;
            max-height: calc(100vh - 200px);
            overflow-y: auto;
            position: relative;
            z-index: 2;
        }
        
        #update-response-modal .modal-body::-webkit-scrollbar {
            width: 8px;
        }
        
        #update-response-modal .modal-body::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }
        
        #update-response-modal .modal-body::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        
        #update-response-modal .modal-body::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        /* Consultation Preview */
        .consultation-preview {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            gap: 16px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.5s ease forwards;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .consultation-preview::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.03) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .consultation-badge {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--primary-color);
            flex-shrink: 0;
        }
        
        .consultation-info {
            flex: 1;
        }
        
        .consultation-info h3 {
            margin-bottom: 8px;
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .consultation-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }
        
        .consultation-type {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .consultation-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-pending {
            background-color: var(--pastel-yellow);
            color: var(--primary-color);
        }
        
        .status-in-progress {
            background-color: var(--pastel-blue);
            color: var(--primary-color);
        }
        
        .status-completed {
            background-color: var(--pastel-green);
            color: var(--primary-color);
        }
        
        .status-cancelled {
            background-color: var(--pastel-red);
            color: var(--primary-color);
        }
        
        .consultation-date {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: var(--text-muted);
        }
        
        .consultation-description {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
            color: var(--text-secondary);
            max-height: 100px;
            overflow-y: auto;
            position: relative;
        }
        
        .consultation-description::-webkit-scrollbar {
            width: 4px;
        }
        
        .consultation-description::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .consultation-description::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
            opacity: 0;
            animation: formElementFadeIn 0.5s ease forwards 0.3s;
        }
        
        @keyframes formElementFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .textarea-container {
            position: relative;
            margin-bottom: 10px;
        }
        
        .textarea-container textarea {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            resize: vertical;
            min-height: 180px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .textarea-container textarea:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(227, 196, 58, 0.15);
            transform: translateY(-2px);
        }
        
        .textarea-decoration {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 80px;
            height: 80px;
            background-image: radial-gradient(circle, var(--accent-color) 1px, transparent 1px);
            background-size: 10px 10px;
            opacity: 0.1;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .response-meta {
            display: flex;
            justify-content: flex-end;
            font-size: 12px;
            color: var(--text-muted);
        }
        
        .response-date {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* Modal footer */
        #update-response-modal .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background-color: rgba(45, 46, 54, 0.5);
            opacity: 0;
            animation: formElementFadeIn 0.5s ease forwards 0.4s;
            position: relative;
            z-index: 2;
        }
        
        /* Button styles */
        #update-response-modal .primary-btn,
        #update-response-modal .secondary-btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        #update-response-modal .primary-btn {
            background-color: rgb(227, 196, 58);
            color: rgb(29, 30, 35);
        }
        
        #update-response-modal .primary-btn:hover {
            background-color: rgba(227, 196, 58, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(227, 196, 58, 0.2);
        }
        
        #update-response-modal .primary-btn:active {
            transform: translateY(0);
        }
        
        #update-response-modal .secondary-btn {
            background-color: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
        }
        
        #update-response-modal .secondary-btn:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
        
        #update-response-modal .secondary-btn:active {
            transform: translateY(0);
        }
        
        /* Alert styles */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }
        
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
        
        .alert-danger {
            background-color: rgba(248, 81, 73, 0.15);
            border-left: 4px solid var(--danger-color);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            #update-response-modal .modal-content {
                width: 95%;
                max-width: none;
                margin: 20px;
            }
            
            #update-response-modal .modal-body {
                padding: 16px;
                max-height: calc(100vh - 150px);
            }
            
            .consultation-preview {
                fle