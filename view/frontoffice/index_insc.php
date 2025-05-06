<?php
require_once __DIR__ . '/../../controller/evenController.php';
include_once('../../config.php');
include_once('../../controller/insccontroller.php');

// Récupération des événements
$inscriptioncontroller = new InscriptionController();
$eventcontroller = new EvenementController();
$db = config::getConnexion();

try {
    $query = $db->prepare("SELECT * FROM evenement");
    $query->execute();
    $listeEvenements = $query->fetchAll(PDO::FETCH_ASSOC);
    
    if (!is_array($listeEvenements)) {
        $listeEvenements = [];
    }
} catch (PDOException $e) {
    echo 'Erreur: ' . $e->getMessage();
    $listeEvenements = [];
}

// Récupération des inscriptions
$listeInscriptions = $inscriptioncontroller->afficherInscriptionsAvecEvenement();
// Assurez-vous que $listeInscriptions est bien un tableau
if (!is_array($listeInscriptions)) {
    if (is_object($listeInscriptions) && method_exists($listeInscriptions, 'fetchAll')) {
        $listeInscriptions = $listeInscriptions->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $listeInscriptions = [];
    }
}

// Traitement de l'inscription avec ID utilisateur fixé à 1
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inscription'])) {
    $id_evenement = $_POST['id_eve'] ?? null;
    $id_utilisateur = 1; // ID utilisateur fixé à 1
    $statut = 'En attente'; // Statut par défaut "En attente"
    $email = $_POST['email'] ?? ''; // Récupération de l'email

    // Débogage
    error_log("Données extraites : id_eve=$id_evenement, id_uti=$id_utilisateur, email=$email");

    // Validation des données
    if (empty($id_evenement)) {
        $message = "<div class='error-message'>Erreur: Aucun événement sélectionné.</div>";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='error-message'>Erreur: Veuillez fournir une adresse email valide.</div>";
    } else {
        try {
            // Débogage
            error_log("Création de l'objet Inscription");
            $inscri = new Inscription(null, $id_evenement, $id_utilisateur, $statut, $email);
            
            // Débogage - vérifiez que les getters fonctionnent
            error_log("Valeurs de l'objet : id_eve=" . $inscri->getIdEvenement() . 
                      ", id_uti=" . $inscri->getIdUtilisateur() . 
                      ", statut=" . $inscri->getStatut() . 
                      ", mail=" . $inscri->getMail());
            
            $result = $inscriptioncontroller->addInscription($inscri);
            
            // Débogage
            error_log("Résultat de addInscription : " . print_r($result, true));
            
            if ($result === "full") {
                $message = "<div class='error-message'>⚠ Désolé, cet événement a atteint sa capacité maximale.</div>";
            } elseif ($result === "already_registered") {
                $message = "<div class='error-message'>⚠ Vous êtes déjà inscrit à cet événement.</div>";
            } elseif ($result) {
                // Redirection après succès
                header("Location: index_insc.php?success=1");
                exit();
            } else {
                $message = "<div class='error-message'>Erreur lors de l'inscription. Veuillez réessayer. Si le problème persiste, contactez l'administrateur.</div>";
                error_log("Échec de l'inscription: ID événement=$id_evenement, ID utilisateur=$id_utilisateur, Email=$email");
            }
        } catch (PDOException $e) {
            $message = "<div class='error-message'>Erreur base de données: ".htmlspecialchars($e->getMessage())."</div>";
            error_log("PDOException dans index_insc.php: " . $e->getMessage());
        } catch (Exception $e) {
            $message = "<div class='error-message'>Une erreur est survenue. Veuillez réessayer.</div>";
            error_log("Exception dans index_insc.php: " . $e->getMessage());
        }
    }
}

// Afficher message de succès après redirection
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "<div class='success-message'>Inscription réussie ! Votre demande est en attente de confirmation par l'administrateur.</div>";
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
       .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .event-card {
            border: 1px solid rgb(5, 5, 5);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            background-color: white;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
        }

        .event-header {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .event-title {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
            color: #1e293b;
        }

        .event-content {
            padding: 15px;
        }

        .event-info {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            font-size: 1rem;
            color: #334155;
        }

        .event-info svg {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            color: #10b981;
        }

        /* Boutons */
        .event-footer {
            padding: 15px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
        }

        .btn-register {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .btn-register:hover {
            background-color: #059669;
        }

        .btn-register:disabled {
            background-color: #94a3b8;
            cursor: not-allowed;
        }

        /* Tableau des inscriptions */
        .inscriptions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .inscriptions-table th, 
        .inscriptions-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .inscriptions-table th {
            background-color: #f8fafc;
            font-weight: 600;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-confirmed {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background-color: #fef9c3;
            color: #854d0e;
        }

        .status-refused {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .no-inscriptions {
            text-align: center;
            padding: 30px;
            background-color: #f8fafc;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        /* Style pour le modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .modal-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #64748b;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .success-message {
            background-color: #dcfce7;
            color: #166534;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .error-message {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        /* Style pour le champ email dans le modal */
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }
        
        .no-events {
            text-align: center;
            padding: 30px;
            background-color: #f8fafc;
            border-radius: 8px;
            margin-top: 20px;
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
                    <?php if (!empty($listeEvenements) && (is_array($listeEvenements) || is_object($listeEvenements))): ?>
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
                                    <button onclick="openInscriptionModal(<?= $evenement['id_ev']; ?>, '<?= htmlspecialchars($evenement['nom']); ?>')" class="btn-register">S'inscrire</button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-events">
                            <p>Aucun événement n'est disponible pour le moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Modal d'inscription -->
                <div id="inscriptionModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">S'inscrire à l'événement</h3>
                            <button class="close-modal" onclick="closeInscriptionModal()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p id="eventName"></p>
                            <form id="inscriptionForm" action="index_insc.php" method="POST">
                                <input type="hidden" id="id_eve" name="id_eve" value="">
                                <input type="hidden" name="id_uti" value="1">
                                <input type="hidden" name="inscription" value="1">
                                
                                <div class="form-group">
                                    <label for="email">Votre adresse email *</label>
                                    <input type="email" id="email" name="email" required placeholder="exemple@email.com">
                                </div>
                                
                                <div class="modal-footer">
                                    <button type="button" class="btn" onclick="closeInscriptionModal()">Annuler</button>
                                    <button type="submit" class="btn-register">Confirmer l'inscription</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="section-header" style="margin-top: 40px;">
                    <h2>Mes Inscriptions</h2>
                    <p>Consultez vos inscriptions aux événements</p>
                </div>

                <?php
                // Filtrer les inscriptions pour n'afficher que celles de l'utilisateur 1
                $mesInscriptions = [];
                foreach ($listeInscriptions as $inscription) {
                    if ($inscription['id_uti'] == 1) {
                        $mesInscriptions[] = $inscription;
                    }
                }
                ?>

                <?php if (empty($mesInscriptions)): ?>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mesInscriptions as $inscription): 
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
                                <td><?= htmlspecialchars($inscription['nom']) ?></td>
                                <td><?= htmlspecialchars($inscription['date']) ?></td>
                                <td><?= htmlspecialchars($inscription['lieu']) ?></td>
                                <td>
                                    <span class="status-badge <?= $statusClass; ?>">
                                        <?= htmlspecialchars($inscription['statut']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
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

    <script>
        // Fonctions pour le modal d'inscription
        function openInscriptionModal(eventId, eventName) {
            document.getElementById('eventName').textContent = 'Événement: ' + eventName;
            document.getElementById('id_eve').value = eventId;
            document.getElementById('inscriptionModal').style.display = 'flex';
        }
        
        function closeInscriptionModal() {
            document.getElementById('inscriptionModal').style.display = 'none';
        }
        
        // Fermer le modal si l'utilisateur clique en dehors
        window.onclick = function(event) {
            const inscriptionModal = document.getElementById('inscriptionModal');
            
            if (event.target === inscriptionModal) {
                closeInscriptionModal();
            }
        }
    </script>
</body>
</html>