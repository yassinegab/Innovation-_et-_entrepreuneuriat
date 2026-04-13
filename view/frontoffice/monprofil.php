<?php
session_start();
require_once(__DIR__ . '/../../controller/user_controller.php');
require_once __DIR__ . '/../../controller/evenController.php';
include_once('../../config.php');
include_once('../../controller/insccontroller.php');


$inscriptioncontroller = new InscriptionController();

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the profile user ID from URL parameter
$profile_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If no ID provided or invalid ID, redirect to users page
if ($profile_id <= 0) {
    header('Location: allUsers.php');
    exit();
}

$userC = new User_controller();
$current_user_id = $_SESSION['user_id'];



// Fetch the profile user object
$profile_user = $userC->load_user($profile_id);

// If user not found, redirect to users page
if (!$profile_user) {
    header('Location: allUsers.php');
    exit();
}

// Check if the viewed profile is the current user's profile
$is_own_profile = ($current_user_id == $profile_id);

// Get connection status (placeholder - implement your own logic)
// Possible values: none, pending_sent, pending_received, connected, blocked
$connection_status = "none";
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


$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inscription'])) {
    $id_evenement = $_POST['id_eve'] ?? null;
   session_start();
   $id_utilisateur = $_SESSION['user_id'] ?? null;
   if (!$id_utilisateur) {
    $message = "<div class='error-message'>Erreur: Vous devez être connecté pour vous inscrire.</div>";
}


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

function estDejaInscrit($id_evenement, $listeInscriptions, $id_utilisateur) {
    foreach ($listeInscriptions as $inscription) {
        if ($inscription['id_eve'] == $id_evenement && $inscription['id_uti'] == $id_utilisateur) {
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
    <title>Mon Profil</title>
    <link rel="stylesheet" href="assets/monprofil.css">
    <link rel="stylesheet" href="assets/collaborations.css">
    <style>
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .status-active {
            background-color: rgba(76, 209, 55, 0.2);
            color: var(--success-color);
        }

        .status-inactive {
            background-color: rgba(255, 82, 82, 0.2);
            color: var(--danger-color);
        }

    </style>
    </head>
<body>
    <header>
        <div class="container">
            <nav>
                <a href="#" class="logo">ProfilApp</a>
                <div class="nav-links">
                    <a href="index.php">Accueil</a>
                    
                </div>
                <div class="profile-icon">
                     <?php if ($profile_user->getProfileImage()): ?>
                    <img src="<?= htmlspecialchars($profile_user->getProfileImage()) ?>" alt="Profile Picture" >
                    <?php else: ?>
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($profile_user->getName()) ?>&background=2d2d2d&color=FFD700" alt="Profile Picture" >
                    <?php endif; ?>
                </div>
                <div class="mobile-menu-btn">☰</div>
            </nav>
        </div>
    </header>

    <section class="profile-header">
        <div class="container">
            <div class="profile-container">
                <div class="profile-image fade-in">
                    <?php if ($profile_user->getProfileImage()): ?>
                    <img src="<?= htmlspecialchars($profile_user->getProfileImage()) ?>" alt="Profile Picture" >
                <?php else: ?>
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($profile_user->getName()) ?>&background=2d2d2d&color=FFD700" alt="Profile Picture" >
                <?php endif; ?>
                </div>
                <h1 class="profile-name fade-in delay-1"><?php echo htmlspecialchars($profile_user->getName() . ' ' . $profile_user->getLastName()); ?></h1>
                
                
                <div class="profile-stats fade-in delay-2">
                    <div class="stat">
                        <div class="stat-value">125</div>
                        <div class="stat-label">Publications</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value">1.2K</div>
                        <div class="stat-label">Abonnés</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value">348</div>
                        <div class="stat-label">Abonnements</div>
                    </div>
                </div>
                
                <div class="profile-actions fade-in delay-3">
                    <?php if ($is_own_profile){?>
                    <a href="user_profile.php" class="btn btn-primary"><span class="btn-icon">✏️</span>Modifier le profil</a>
                    <a href="collaborations.php" class="btn btn-outline"><span class="btn-icon">👥</span>Mes collaborations</a>
                    <?php }?>
                    <a href="#" class="btn btn-secondary"><span class="btn-icon">🔗</span>Partager</a>
                </div>
            </div>
        </div>
    </section>

    <main class="main-content container">
        <section class="section fade-in delay-3">
            <h2 class="section-title">À propos</h2>
            <div class="about-grid">
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo htmlspecialchars($profile_user->getEmail()); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">date de naissance</div>
                    <div class="info-value"> <?php 
                                $birthdate = sprintf('%04d-%02d-%02d', 
                                    $profile_user->getBirthYear(), 
                                    $profile_user->getBirthMonth(), 
                                    $profile_user->getBirthDay()
                                );
                                echo htmlspecialchars($birthdate);
                            ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">status</div>
                    <div class="info-value"> <?php if ($profile_user->getActiveAccount() == 1): ?>
                            <span class="status-badge status-active">
                                <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px;"></i> Active
                            </span>
                        <?php else: ?>
                            <span class="status-badge status-inactive">
                                <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px;"></i> Inactive
                            </span>
                        <?php endif; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Last Login</div>
                    <div class="info-value"><?php 
                                        // Format last login time
                                        $last_login=null;
                                        $last_loginday=random_int(0, 31);
                                        $last_loginhour=random_int(0, 23);
                                        $last_loginminute=random_int(0, 59);
                                        if ($last_login==null) {
                                            $login_time = new DateTime($last_login);
                                            $now = new DateTime();
                                            $interval = $now->diff($login_time);
                                            
                                            if ($interval->days > 0) {
                                                echo $interval->days . " days ago";
                                            } elseif ($interval->h > 0) {
                                                echo $interval->h . " hours ago";
                                            } elseif ($interval->i > 0) {
                                                echo $interval->i . " minutes ago";
                                            } else {
                                                echo "Just now";
                                            }
                                        } 
                                    ?></div>
                </div>
            </div>
     </section>
    <section class="page-header-eya2">
    <div class="container-eya2">
        <h1 class="page-title-eya2">Mes Inscriptions</h1>
        <div class="header-decoration-eya2"></div>
    </div>
</section>

<div class="table-container-eya2">
    <?php
    // Utilisateur connecté
    $id_utilisateur = $_SESSION['user_id'] ?? null; // adapte "user_id" si différent

    $mesInscriptions = [];
    if ($id_utilisateur !== null) {
        foreach ($listeInscriptions as $inscription) {
            if ($inscription['id_uti'] == $id_utilisateur) {
                $mesInscriptions[] = $inscription;
            }
        }
    }
    
    if (empty($mesInscriptions)): 
    ?>
        <div class="empty-state-eya2">
            <div class="empty-icon-eya2">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h3>Aucun événement</h3>
            <p>Vous n'êtes inscrit à aucun événement pour le moment.</p>
            <a href="index.php" class="btn-primary-eya2">Découvrir les événements</a>
        </div>
    <?php else: ?>
        <div class="table-responsive-eya2">
            <table class="events-table-eya2">
                <thead>
                    <tr>
                        <th>Événement</th>
                        <th>Date</th>
                        <th>Lieu</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($mesInscriptions as $inscription): ?>
                    <tr class="event-row-eya2">
                        <td class="event-name-eya2"><?= htmlspecialchars($inscription['nom']) ?></td>
                        <td class="event-date-eya2">
                            <i class="fas fa-calendar-alt"></i>
                            <?= date('d/m/Y', strtotime($inscription['date'])) ?>
                        </td>
                        <td class="event-location-eya2">
                            <i class="fas fa-map-marker-alt"></i>
                            <?= htmlspecialchars($inscription['lieu']) ?>
                        </td>
                        <td class="event-status-eya2">
                            <?php if($inscription['statut'] == 'Confirmée'): ?>
                                <span class="status-badge-eya2 status-confirmed-eya2">
                                    <i class="fas fa-check-circle"></i> Confirmée
                                </span>
                            <?php elseif($inscription['statut'] == 'En attente'): ?>
                                <span class="status-badge-eya2 status-pending-eya2">
                                    <i class="fas fa-clock"></i> En attente
                                </span>
                            <?php elseif($inscription['statut'] == 'Refusée'): ?>
                                <span class="status-badge-eya2 status-refused-eya2">
                                    <i class="fas fa-times-circle"></i> Refusée
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
       
    <footer>
        <div class="container">
            <div class="social-links">
                <a href="#" class="social-link">𝕏</a>
                <a href="#" class="social-link">in</a>
                <a href="#" class="social-link">f</a>
                <a href="#" class="social-link">🐙</a>
            </div>
            <div class="footer-links">
                <a href="#">Conditions d'utilisation</a>
                <a href="#">Politique de confidentialité</a>
                <a href="#">Aide</a>
                <a href="#">Contact</a>
            </div>
            <div class="copyright">
                &copy; 2025 ProfilApp. Tous droits réservés.
            </div>
        </div>
    </footer>
    <script src="assets/monprofil.js"></script>
</body>
</html>    