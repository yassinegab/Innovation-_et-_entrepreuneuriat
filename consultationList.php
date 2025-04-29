<?php 
include_once ('../../controller/ConsultationController.php');
//include_once('reponseList.php');
$controller = new ConsultationController();
$consultationC = new ConsultationController();



// Add consultation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                header("Location: consultationList.php?success=true");
                exit();
            } else {
                echo 'Erreur lors de l’insertion.';
            }
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
}

// Fetch all consultations
$liste = $consultationC->listeConsultations();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConsultPro - Liste des consultations</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>   
<style>
    
/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    z-index: 1000;
    overflow: auto;
}

.modal-content {
    background-color: var(--bg-light);
    margin: 5% auto;
    padding: 30px; /* Increased padding */
    border-radius: 8px;
    width: 85%; /* Slightly increased width */
    max-width: 900px; /* Increased max-width */
    max-height: 85vh; /* Increased max-height for better space utilization */
    overflow-y: auto;
    position: relative;
}


.close-modal {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 24px;
    cursor: pointer;
    color: var(--text-muted);
}
/* MODAL FIXES */

        /* MODAL FIXES */
        #details-modal {
    display: none; /* Start hidden */
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    justify-content: center;
    align-items: center;
    background-color: rgba(0,0,0,0.6);
    z-index: 9999;
}


        #details-modal.modal-active {  /* Changed from '.active' to avoid conflicts */
            display: flex !important; /* Show as flex when active */
        }

        #details-modal .modal-content {
            background: var(--bg-light);
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto; /* Scroll if content is long */
            border-radius: 8px;
            padding: 20px;
            position: relative;
        }

        #details-modal .close-modal {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            color: var(--primary-color);
        }
        .modal.modal-active {
    display: flex !important;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.6);
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
/* Main Content */
.main-content {
    flex: 1;
    margin-left: 0; /* Removed margin to avoid unnecessary space */
    padding: 20px;  /* Added padding for better content spacing */
    position: relative;
    overflow: auto; /* Ensures content doesn't overflow */
}
/* New Consultation Modal Styles */
#new-consultation-modal {
    display: none;
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
}

#new-consultation-modal.active {
    display: flex;
    animation: fadeIn 0.3s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

#new-consultation-modal .modal-content {
    background-color: rgb(36, 37, 43);
    margin: auto;
    width: 90%;
    max-width: 550px;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.08);
    overflow: hidden;
    animation: modalSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes modalSlideIn {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}

#new-consultation-modal .modal-header {
    background-color: rgb(45, 46, 54);
    padding: 18px 24px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

#new-consultation-modal .modal-header h2 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.95);
    letter-spacing: -0.01em;
}

#new-consultation-modal .close-modal {
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
}

#new-consultation-modal .close-modal:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.9);
}

#new-consultation-modal .modal-body {
    padding: 24px;
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}

#new-consultation-modal .modal-body::-webkit-scrollbar {
    width: 8px;
}

#new-consultation-modal .modal-body::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}

#new-consultation-modal .modal-body::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}

#new-consultation-modal .modal-body::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

#new-consultation-modal .form-group {
    margin-bottom: 20px;
    opacity: 0;
    animation: formElementFadeIn 0.5s ease forwards;
}

#new-consultation-modal .form-group:nth-child(1) { animation-delay: 0.1s; }
#new-consultation-modal .form-group:nth-child(2) { animation-delay: 0.15s; }
#new-consultation-modal .form-group:nth-child(3) { animation-delay: 0.2s; }
#new-consultation-modal .form-group:nth-child(4) { animation-delay: 0.25s; }
#new-consultation-modal .form-group:nth-child(5) { animation-delay: 0.3s; }
#new-consultation-modal .form-group:nth-child(6) { animation-delay: 0.35s; }

@keyframes formElementFadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

#new-consultation-modal .form-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.8);
}

#new-consultation-modal .form-group input,
#new-consultation-modal .form-group select,
#new-consultation-modal .form-group textarea {
    width: 100%;
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 12px 16px;
    color: rgba(255, 255, 255, 0.9);
    font-size: 15px;
    font-family: 'Inter', sans-serif;
    transition: all 0.2s ease;
}

#new-consultation-modal .form-group input:focus,
#new-consultation-modal .form-group select:focus,
#new-consultation-modal .form-group textarea:focus {
    outline: none;
    border-color: rgb(227, 196, 58);
    box-shadow: 0 0 0 3px rgba(227, 196, 58, 0.15);
}

#new-consultation-modal .form-group select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='rgb(227, 196, 58)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 40px;
}

#new-consultation-modal .form-group textarea {
    resize: vertical;
    min-height: 120px;
}








/* Styling for type options */
#new-consultation-modal #consultation-type option[value="financing"] {
            background-color: #FFD6E0;
            color: #333;
        }

#new-consultation-modal #consultation-type option[value="legal"] {
    background-color: #F3E8FF;
    color: #333;
}

#new-consultation-modal #consultation-type option[value="marketing"] {
    background-color: #FFEFB7;
    color: #333;
}

#new-consultation-modal #consultation-type option[value="technical"] {
    background-color: #DFF4FF;
    color: #333;
}

/* Styling for status options */
#new-consultation-modal #consultation-status option[value="pending"] {
    background-color: #FFEFB7;
    color: #333;
}

#new-consultation-modal #consultation-status option[value="in-progress"] {
    background-color: #DFF4FF;
    color: #333;
}

#new-consultation-modal #consultation-status option[value="completed"] {
    background-color: #DCFCE7;
    color: #333;
}
#new-consultation-modal #consultation-status option[value="cancelled"] {
    background-color: #FEE2E2; /* soft red */
    color: #333;
        }

/* Date input styling */
#new-consultation-modal input[type="date"] {
    color-scheme: dark;
}

#new-consultation-modal input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(0.8) sepia(100%) saturate(500%) hue-rotate(20deg);
    cursor: pointer;
}

/* Modal footer */
#new-consultation-modal .modal-footer {
    padding: 16px 24px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background-color: rgba(45, 46, 54, 0.5);
    opacity: 0;
    animation: formElementFadeIn 0.5s ease forwards 0.4s;
}

/* Button styles */
#new-consultation-modal .primary-btn,
#new-consultation-modal .secondary-btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    font-family: 'Inter', sans-serif;
}

#new-consultation-modal .primary-btn {
    background-color: rgb(227, 196, 58);
    color: rgb(29, 30, 35);
}

#new-consultation-modal .primary-btn:hover {
    background-color: rgba(227, 196, 58, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(227, 196, 58, 0.2);
}

#new-consultation-modal .primary-btn:active {
    transform: translateY(0);
}

#new-consultation-modal .secondary-btn {
    background-color: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.9);
}

#new-consultation-modal .secondary-btn:hover {
    background-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px);
}

#new-consultation-modal .secondary-btn:active {
    transform: translateY(0);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #new-consultation-modal .modal-content {
        width: 95%;
        max-width: none;
    }
    
    #new-consultation-modal .modal-body {
        padding: 16px;
        max-height: calc(100vh - 150px);
    }
    
    #new-consultation-modal .form-group {
        margin-bottom: 16px;
    }
    
    #new-consultation-modal .modal-footer {
        padding: 12px 16px;
        flex-direction: column-reverse;
    }
    
    #new-consultation-modal .primary-btn,
    #new-consultation-modal .secondary-btn {
        width: 100%;
        text-align: center;
    }
}
</style>

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
                <button class="new-consultation-btn" onclick="document.getElementById('new-consultation-modal').style.display='block'">
                    <i class="fas fa-plus"></i> Nouvelle consultation
                </button>
            </div>
        </header>

        <div class="content-wrapper">
            <div class="page-header">
                <h1>Gestion des Consultations</h1>
                <div class="filters">
                    <select id="status-filter">
                        <option value="all">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="in-progress">En cours</option>
                        <option value="cancelled">annullée</option>
                        <option value="completed">Terminées</option>
                    </select>
                    <select id="type-filter">
                        <option value="all">Tous les types</option>
                        <option value="financing">financement</option>
                        <option value="legal">juridique</option>
                        <option value="marketing">marketing</option>
                        <option value="technical">technique</option>
                    </select>
                </div>
            </div>

            <div class="consultations-list">
                <?php foreach ($liste as $consultation): ?>
                <div class="consultation-card" data-id="<?= intval($consultation['id_consultation']); ?>">
                    <div class="card-header">
                        <div class="consultation-info">
                            <h3><?= htmlspecialchars($consultation['titre']); ?></h3>
                            <span class="consultation-type"><?= htmlspecialchars($consultation['type']); ?></span>
                            <span class="consultation-status <?= strtolower(htmlspecialchars($consultation['statut'])); ?>">
                                <?= htmlspecialchars($consultation['statut']); ?>
                            </span>
                        </div>
                        <div class="consultation-date">
                            <i class="far fa-calendar-alt"></i> <?= htmlspecialchars($consultation['date_consultation']); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <p><?= htmlspecialchars($consultation['description']); ?></p>
                    </div>
                    <div class="card-footer">
                        <div class="id_utilisateur-info">
                            <img src="https://via.placeholder.com/24" alt="id_utilisateur" class="id_utilisateur-avatar">
                            <span><?= htmlspecialchars($consultation['id_utilisateur']); ?></span>
                        </div>

                        <!-- Voir détails button -->
                        <button 
    class="view-details-btn fancy-details-button"
    data-id="<?= intval($consultation['id_consultation']); ?>">
    <i class="fas fa-eye"></i> Voir détails
</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        
<!-- Details Modal -->


<div id="details-modal" class="modal" style="display:none;">

    <div class="modal-content" id="details-content">
        <div class="modal-content">
            <div class="modal-header">
                <h2>détails de consultation</h2>
        <!-- Consultation details will be loaded here -->
            </div>
    </div>
    </div>
    <span class="close-modal">X</span>
</div>


        <!-- MODAL Ajouter Consultation -->
        <div id="new-consultation-modal" class="modal" style="display:none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Nouvelle consultation</h2>
                    <button class="close-modal" onclick="document.getElementById('new-consultation-modal').style.display='none'">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="consultation-form" method="POST" action="consultationList.php">
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
                            <label for="consultation-id_utilisateur">id_utilisateur</label>
                            <input type="text" id="consultation-id_utilisateur" name="id_utilisateur" required>
                        </div>
                        <!-- Statut field (hidden and always pending) -->
                        <input type="hidden" name="statut" value="pending">

                        <div class="modal-footer">
                            <button class="secondary-btn" type="button" onclick="document.getElementById('new-consultation-modal').style.display='none'">Annuler</button>
                            <button class="primary-btn" type="submit">Soumettre</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

<div id="details-modal" class="modal" style="display: none;">

  <div class="modal-content" id="details-content">
    <span class="close-modal" style="cursor:pointer; float:right; margin: 10px;">&times;</span>
  </div>
</div>

<style>
.modal {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}
.modal-content {
    background: white;
    color: black;
    padding: 20px;
    max-width: 800px;
    max-height: 80vh;
    overflow-y: auto;
    border-radius: 10px;
}
.modal-active {
    display: flex !important;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById('details-modal');
    const modalContent = document.getElementById('details-content');
    const closeButton = document.querySelector('.close-modal');

    function openModal() {
        modal.classList.add('modal-active');
        modal.style.display = 'flex';
    }

    function closeModal() {
        modal.classList.remove('modal-active');
        modal.style.display = 'none';
    }

    function loadConsultationDetails(consultationId) {
        modalContent.innerHTML = '<p>Chargement...</p>';
        openModal();

        fetch('./reponseList.php?id_consultation=' + consultationId)
            .then(response => {
                if (!response.ok) throw new Error('Erreur réseau');
                return response.text();
            })
            .then(data => {
                modalContent.innerHTML = data;
            })
            .catch(error => {
                modalContent.innerHTML = '<p>Erreur lors du chargement.</p>';
                console.error('Erreur:', error);
            });
    }

    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const consultationId = this.getAttribute('data-id');
            if (consultationId) {
                loadConsultationDetails(consultationId);
            }
        });
    });

    if (closeButton) closeButton.addEventListener('click', closeModal);

    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });
});

</script>



</body>
</html>
