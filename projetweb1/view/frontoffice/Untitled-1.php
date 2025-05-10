<?php
require_once __DIR__ . '/../../controller/evenController.php';
include_once('../../config.php');
include_once('../../controller/insccontroller.php');

// Récupération des événements
$evenementcontroller = new InscriptionController();
$eventcontroller = new EvenementController();
$db = config::getConnexion();
$query = $db->prepare("SELECT * FROM evenement");
$query->execute();
$listeEvenements = $query->fetchAll(PDO::FETCH_ASSOC);

// Récupération des inscriptions
$inscriptioncontroller = new InscriptionController();
$listeInscriptions = $inscriptioncontroller->afficherInscriptionsAvecEvenement();
          
// Traitement de l'inscription avec ID utilisateur fixé à 1
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inscription'])) {
    $id_evenement = $_POST['id_eve'] ?? null;
    $id_utilisateur = 1; // ID utilisateur fixé à 1 comme demandé
    $statut = 'En attente';

    $inscri = new Inscription(null, $id_evenement, $id_utilisateur, $statut);
    $result = $inscriptioncontroller->addInscription($inscri);
    
    if ($result === "full") {
        $message = "<div class='error-message'>⚠ Désolé, cet événement a atteint sa capacité maximale.</div>";
    } elseif ($result) {
        $message = "<div class='success-message'>Inscription réussie ! Numéro : $result</div>";
        header("Location: index_insc.php");
    } else {
        $message = "<div class='error-message'>Erreur lors de l'inscription. Veuillez réessayer.</div>";
    }
}

// Traitement de la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_inscription'])) {
    $id_inscription = $_POST['id_inscription'];
    $inscriptioncontroller->deleteinsc($id_inscription);
    header("Location: index_insc.php");
    exit();
}

// Vérifier si un utilisateur est déjà inscrit à un événement
function estDejaInscrit($id_evenement, $listeInscriptions) {
    foreach ($listeInscriptions as $inscription) {
        if ($inscription['id_eve'] == $id_evenement && $inscription['id_uti'] == 1) {
            return true;
        }
    }
    return false;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscriptions aux Événements - EntrepreHub</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="theme-custom.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <style>
        /* Styles supplémentaires pour l'affichage des événements */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .event-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            background-color: white;
        }
        
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .event-header {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        
        .event-title {
            margin: 0;
            font-size: 1.25rem;
            color: #1e293b;
        }
        
        .event-content {
            padding: 15px;
        }
        
        .event-info {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .event-info svg {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            color: #64748b;
        }
        
        .event-footer {
            padding: 15px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn-register {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        
        .btn-register:hover {
            background-color: #059669;
        }
        
        .btn-register:disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .status-confirmed {
            background-color: #10b981;
            color: white;
        }
        
        .status-pending {
            background-color: #f59e0b;
            color: white;
        }
        
        .status-cancelled {
            background-color: #ef4444;
            color: white;
        }
        
        .inscriptions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .inscriptions-table th, 
        .inscriptions-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .inscriptions-table th {
            background-color: #f8fafc;
            font-weight: 600;
        }
        
        .inscriptions-table tr:hover {
            background-color: #f1f5f9;
        }
        
        .delete-btn {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .delete-btn:hover {
            background-color: #dc2626;
        }
        
        .success-message {
            background-color: #d1fae5;
            border-left: 4px solid #10b981;
            color: #065f46;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .error-message {
            background-color: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>EntrepreHub</h1>
                </div>
                <nav class="nav">
                    <button class="nav-toggle" aria-label="Toggle navigation">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <ul class="nav-menu">
                        <li><a href="index_insc.php">Accueil</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#about">À propos</a></li>
                        <li><a href="#testimonials">Témoignages</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="#" class="btn btn-primary">Commencer</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <section class="hero small-hero">
            <div class="container">
                <div class="hero-content">
                    <h1>Inscriptions aux Événements</h1>
                    <p>Inscrivez-vous à nos événements pour développer votre réseau et vos compétences entrepreneuriales</p>
                </div>
            </div>
        </section>

        <section class="inscription-section">
            <div class="container">
                <div class="section-header">
                    <h2>Nos Événements</h2>
                    <p>Découvrez et inscrivez-vous à nos prochains événements</p>
                </div>
                
                <?php if (!empty($message)) echo $message; ?>
                
                <div class="events-grid">
                    <?php foreach ($listeEvenements as $evenement): 
                        $estInscrit = estDejaInscrit($evenement['id_ev'], $listeInscriptions);
                        $statut = "";
                        
                        // Trouver le statut si inscrit
                        if ($estInscrit) {
                            foreach ($listeInscriptions as $inscription) {
                                if ($inscription['id_eve'] == $evenement['id_ev'] && $inscription['id_uti'] == 1) {
                                    $statut = $inscription['statut'];
                                    break;
                                }
                            }
                        }
                    ?>
                    <div class="event-card">
                        <div class="event-header">
                            <h3 class="event-title"><?= htmlspecialchars($evenement['nom']); ?></h3>
                        </div>
                        <div class="event-content">
                            <div class="event-info">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <span><?= htmlspecialchars($evenement['date']); ?></span>
                            </div>
                            <div class="event-info">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <span><?= htmlspecialchars($evenement['lieu']); ?></span>
                            </div>
                            <div class="event-info">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span>Capacité: <?= htmlspecialchars($evenement['capacite']); ?> personnes</span>
                            </div>
                            
                            <?php if ($estInscrit): ?>
                            <div class="event-info" style="margin-top: 15px;">
                                <?php
                                $statusClass = '';
                                switch($statut) {
                                    case 'Confirmé':
                                        $statusClass = 'status-confirmed';
                                        break;
                                    case 'Annulé':
                                        $statusClass = 'status-cancelled';
                                        break;
                                    default:
                                        $statusClass = 'status-pending';
                                }
                                ?>
                                <span class="status-badge <?= $statusClass; ?>">
                                    <?= htmlspecialchars($statut); ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="event-footer">
                            <?php if ($estInscrit): ?>
                                <button class="btn-register" disabled>Déjà inscrit</button>
                            <?php else: ?>
                                <form action="index_insc.php" method="POST">
                                    <input type="hidden" name="id_eve" value="<?= $evenement['id_ev']; ?>">
                                    <input type="hidden" name="id_uti" value="1">
                                    <button type="submit" name="inscription" class="btn-register">S'inscrire</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="section-header" style="margin-top: 40px;">
                    <h2>Mes Inscriptions</h2>
                    <p>Consultez vos inscriptions aux événements</p>
                </div>
                
                <?php if (empty($listeInscriptions)): ?>
                    <div class="no-inscriptions">
                        <p>Aucune inscription n'a été enregistrée pour le moment.</p>
                    </div>
                <?php else: ?>
                    <table class="inscriptions-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Événement</th>
                                <th>Date</th>
                                <th>Lieu</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listeInscriptions as $inscription): 
                                if ($inscription['id_uti'] == 1): // Afficher uniquement les inscriptions de l'utilisateur 1
                                    $statusClass = '';
                                    switch($inscription['statut']) {
                                        case 'Confirmé':
                                            $statusClass = 'status-confirmed';
                                            break;
                                        case 'Annulé':
                                            $statusClass = 'status-cancelled';
                                            break;
                                        default:
                                            $statusClass = 'status-pending';
                                    }
                            ?>
                            <tr>
                                <td><?= $inscription['id_inscription'] ?></td>
                                <td><?= htmlspecialchars($inscription['nom']) ?></td>
                                <td><?= htmlspecialchars($inscription['date']) ?></td>
                                <td><?= htmlspecialchars($inscription['lieu']) ?></td>
                                <td>
                                    <span class="status-badge <?= $statusClass; ?>">
                                        <?= htmlspecialchars($inscription['statut']); ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="index_insc.php" onsubmit="return confirm('Supprimer cette inscription ?');">
                                        <input type="hidden" name="id_inscription" value="<?= $inscription['id_inscription'] ?>">
                                        <button type="submit" class="delete-btn">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endif; endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </section>
        
        <section class="cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Prêt à participer à nos événements?</h2>
                    <p>Rejoignez des milliers d'entrepreneurs qui ont déjà commencé leur voyage avec EntrepreHub.</p>
                    <a href="#" class="btn btn-primary">Voir tous les événements</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h2>EntrepreHub</h2>
                    <p>Votre partenaire dans le voyage entrepreneurial</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                        <a href="#" aria-label="Twitter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                            </svg>
                        </a>
                        <a href="#" aria-label="LinkedIn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        </a>
                        <a href="#" aria-label="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="footer-links">
                    <div class="footer-links-column">
                        <h3>Services</h3>
                        <ul>
                            <li><a href="#">Formation d'entreprise</a></li>
                            <li><a href="#">Mentorat</a></li>
                            <li><a href="#">Réseautage</a></li>
                            <li><a href="#">Financement</a></li>
                        </ul>
                    </div>
                    <div class="footer-links-column">
                        <h3>Entreprise</h3>
                        <ul>
                            <li><a href="#">À propos</a></li>
                            <li><a href="#">Équipe</a></li>
                            <li><a href="#">Carrières</a></li>
                            <li><a href="#">Partenaires</a></li>
                        </ul>
                    </div>
                    <div class="footer-links-column">
                        <h3>Ressources</h3>
                        <ul>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">Guides</a></li>
                            <li><a href="#">Événements</a></li>
                            <li><a href="#">FAQ</a></li>
                        </ul>
                    </div>
                    <div class="footer-links-column">
                        <h3>Légal</h3>
                        <ul>
                            <li><a href="#">Conditions d'utilisation</a></li>
                            <li><a href="#">Politique de confidentialité</a></li>
                            <li><a href="#">Cookies</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 EntrepreHub. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>