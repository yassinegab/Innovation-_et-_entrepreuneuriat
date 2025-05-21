<?php
include_once('../../controller/insccontroller.php');
require_once('../../controller/user_controller.php');
require_once('../../controller/propcontroller.php');
require_once('../../controller/collaborationscontroller.php');
require_once __DIR__ . '/../../controller/evenController.php';
require_once __DIR__ . '/../../model/evenmodel.php';
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';


session_start();
$inscriptioncontroller = new InscriptionController();
$userdhia=new User_controller();

$collab = new Collaborationscontroller();
$prop = new propcontroller();
$liste = $prop->afficher();
$evenementcontroller = new EvenementController();
$listevent = $evenementcontroller->listeven();
$listeInscriptions = $inscriptioncontroller->afficherInscriptionsAvecEvenement();
$id_utilisateur = 0;
if(isset($_SESSION['user_id'])){

$id_utilisateur = $_SESSION['user_id'];
$profile_user = $userdhia->load_user($id_utilisateur);

}

if (!is_array($listeInscriptions)) {
    if (is_object($listeInscriptions) && method_exists($listeInscriptions, 'fetchAll')) {
        $listeInscriptions = $listeInscriptions->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $listeInscriptions = [];
    }
}

function isUpcoming($date) {
    $eventDate = new DateTime($date);
    $today = new DateTime();
    return $eventDate >= $today;
}
if (isset($_SESSION['id']))
    $listnotif = $prop->affichernotifById($_SESSION['user_id']);

if (isset($_POST['destroy'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
}
if (isset($_POST['id'])) {
    $_SESSION['user_id'] = $_POST['id'];
    header("Location: index.php");


}


if (isset($_POST['titre'])) {

    $propos = new Proposition(NULL, $_POST['titre'], $_POST['description'], $_POST['type'], $_POST['date_soumission'], 'En attente', $_SESSION['user_id']);
    $prop->ajouterproposition($propos);
    //echo 'cbnnnn';
    header("Location: index.php#filtrs");
}
if (isset($_GET['deleteid'])) {
    $prop->suppprop($_GET['deleteid']);
    header("Location: index.php");
}
if (isset($_POST['titre2'])) {
    $propos = new Proposition($_POST['idmodif'], $_POST['titre2'], $_POST['description2'], $_POST['type2'], $_POST['date_soumission2'], 'En attente', $_SESSION['user_id']);
    $prop->modify($propos);
    header("Location: index.php#filtrs");
}

if (isset($_POST['idproposs'])) {
    $prop->ajouterdemande($_SESSION['user_id'], $_POST['idproposs'], $_POST['role'], $_POST['date_debut'], $_POST['date_fin'], $_POST['typecollab']);
    $proposition = $prop->afficherById($_POST['idproposs']);
    $user= $userdhia->load_user($proposition['ID_Utilisateur']);
   // $user = $prop->afficheruser($proposition['ID_Utilisateur']);


    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // exemple pour Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'touil.moez25@gmail.com';
    $mail->Password = 'nyso etxh wfhh uunw';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('touil.moez25@gmail.com', 'touil');
    $mail->addAddress($user->getEmail());
    $mail->isHTML(true);
    $mail->Subject = ' Nouvelle proposition soumise – '.$proposition['Titre'];
    $mail->Body = '
<html>
<head>
  <style>
    body { font-family: Arial, sans-serif; color: #333; }
    .containereya { padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px; background-color: #f9f9f9; }
    h2 { color: #2c3e50; }
    .label { font-weight: bold; }
    .value { margin-bottom: 10px; }
  </style>
</head>
<body>
  <div class="containereya">
    <h2>Nouvelle proposition reçue</h2>
    <p>Bonjour,</p>
    <p>Nous vous informons que <strong>' . htmlspecialchars($user->getName()) . '</strong> a soumis une nouvelle proposition via notre plateforme.</p>
    
    <div class="details">
      <p><span class="label">Titre :</span> ' . htmlspecialchars($proposition['Titre']) . '</p>
      <p><span class="label">Description :</span> ' . htmlspecialchars($proposition['Description']) . '</p>
      <p><span class="label">Type :</span> ' . htmlspecialchars($proposition['Type']) . '</p>
      <p><span class="label">Date de soumission :</span> ' . htmlspecialchars($proposition['Date_Soumission']) . '</p>
      <p><span class="label">Statut actuel :</span> ' . htmlspecialchars($proposition['Statut']) . '</p>
      <p><span class="label">ID de la proposition :</span> ' . htmlspecialchars($proposition['ID_Proposition']) . '</p>
    </div>

    <p>Nous vous invitons à consulter cette proposition pour une éventuelle collaboration ou analyse.</p>

    <p>Bien cordialement,<br>
    <em>L\'équipe de gestion des propositions</em></p>
  </div>
</body>
</html>';

    if ($mail->send()) {
        //echo 'Email envoyé avec succès';
    } else {
        echo 'Erreur : ' . $mail->ErrorInfo;
    }







}
if (isset($_GET['idprop'])) {
    $proposs = $prop->afficherById($_GET['idprop']);
    $notifi = $prop->affichernotifByitsId($_GET['iddemande']);
    $collabo = new Collaboration(NULL, $proposs['ID_Proposition'], $_SESSION['user_id'], $notifi['role'], $notifi['date_debut'], $notifi['date_fin'], $notifi['type'], "En attente");
    $collab->ajouter($collabo);
    $prop->deleteDemande($_GET['iddemande']);
    header("Location: index.php");
}
if (isset($_GET['suppdemande'])) {
    $prop->deleteDemande($_GET['suppdemande']);
    header("Location: index.php");
}
if (isset($_POST['ajoutercollab2'])) {
    $proposs = $prop->afficherById($_POST['idpropp']);
    $notifi = $prop->affichernotifByitsId($_POST['iddemandee']);
    $collabo = new Collaboration(NULL, $proposs['ID_Proposition'], $_SESSION['user_id'], $notifi['role'], $notifi['date_debut'], $notifi['date_fin'], $notifi['type'], "En attente");
    $collab->ajouter($collabo);
    $prop->deleteDemande($_POST['iddemandee']);
    header("Location: index.php");
}
if (isset($_GET['generatedid'])) {
    $liste = $prop->afficherByIdfetchall($_GET['generatedid']);

}
function estDejaInscrit($id_evenement, $listeInscriptions, $id_utilisateur) {
    foreach ($listeInscriptions as $inscription) {
        if ($inscription['id_eve'] == $id_evenement && $inscription['id_uti'] == $id_utilisateur) {
            return true;
        }
    }
    return false;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inscription'])) {
    $id_evenement = $_POST['id_eve'] ?? null;
   //session_start();
   $id_utilisateur = $_SESSION['user_id'] ?? null;
   if (!$id_utilisateur) {
    $message = "<div class='error-message'>Erreur: Vous devez être connecté pour vous inscrire.</div>";
}


    $statut = 'En attente'; // Statut par défaut "En attente"
    $userrrrr=$userdhia->load_user($_SESSION['user_id']);
    $email = $userrrrr->getEmail() ?? ''; // Récupération de l'email

    // Débogage
    error_log("Données extraites : id_eve=$id_evenement, id_uti=$id_utilisateur, email=$email");

    // Validation des données
    {
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
                header("Location: index.php?success=1");
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

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EntrepreHub - Empowering Entrepreneurs</title>
    
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/theme-custom.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>

<body>
<div id="calendar"></div>

    <input type="hidden" id="room" >
    
<div id="overlaymoez" class="overlaymoez" style="display:none;">
    <div class="container-moer">
        <div id="nommeet" class="caller-name-moer">
            John Doe
        </div>
        <div class="video-area-moer">
            <div class="avatar-circle-moer">
                <i class="fas fa-user avatar-icon-moer"></i>
            </div>
            <div class="call-indicator-moer">
                <i class="fas fa-video"></i> Appel vidéo entrant...
            </div>
            <div class="call-text-moer">
                Voulez-vous accepter cet appel?
            </div>
        </div>
        <div class="buttons-area-moer">
            <button type="button" id="refusermeet"  class="decline-button-moer">
                <i class="fas fa-phone-slash button-icon-moer"></i>
            </button>
            <button type="button" id="acceptermeet" class="accept-button-moer">
                <i class="fas fa-phone button-icon-moer"></i>
            </button>
        </div>
    </div>
</div>

    <?php if(isset($_SESSION['user_id'])) {?>
    <input type="hidden" id="idreceiverr" value="<?= $_SESSION['user_id'] ?>">
    <?php }?>







    <div id="jitsi-meet" style="display: none;"></div>





    <button class="fluffyUnicorn" id="createCollabBtn" aria-label="Create New Collaboration">
        <svg class="ninjaTurtle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 5v14M5 12h14"></path>
        </svg>
        <span class="explodingRainbow">Create New Collaboration</span>
    </button>

    <!-- Modal Overlay for Collaboration Description -->
    <div class="alienInvasion" id="modalOverlay">
        <div class="zombieApocalypse">
            <div class="giantSquid">
                <h2 class="explodingPineapple">Describe Your Collaboration</h2>
            </div>
            <form id="collabForm">
                <div class="dancingTaco">

                    <div class="flyingToaster">
                        <label for="projectTitle" class="radioactivePotato">Project Title</label>
                        <input type="text" id="projectTitle" class="invisibleCheese"
                            placeholder="Enter a title for your project" required>
                    </div>
                    <div class="flyingToaster">
                        <label for="projectDescription" class="radioactivePotato">Project Description</label>
                        <textarea id="projectDescription" class="explodingWatermelon"
                            placeholder="Describe your project and collaboration needs in detail..."
                            required></textarea>
                    </div>
                    <div class="flyingToaster">
                        <label for="projectDuration" class="radioactivePotato">type</label>
                        <input type="text" id="prjecttype" class="invisibleCheese"
                            placeholder="Enter a type for your project" required>

                    </div>


                </div>
                <div class="robotDance">
                    <button type="button" class="crazyMonkey angryPotato" id="cancelBtn44">Cancel</button>
                    <button type="button" class="crazyMonkey happyBanana" id="submitBtn">Generate Proposal</button>
                </div>
            </form>
        </div>
    </div>






    <?php if (isset($_SESSION['user_id'])) { ?>
        <button id="btnmessagerie" data-idsender="<?= $_SESSION['user_id'] ?>" class="msg-button2" aria-label="Open Messages">
            <i class="fas fa-comments"></i>
        </button>
    <?php } ?>
    <button id="openChatbotButton" class="buttonchat">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
    </button>

    <div id="chatbotContainer">
        <div class="chat-wrapper">
            <div class="chat-header2">
                <div class="chat-header-left">
                    <div class="status-indicator"></div>
                    <div>
                        <div class="chat-header-title">AI Assistant</div>
                        <div class="chat-header-subtitle">Online</div>
                    </div>
                </div>
                <div class="chat-header-right">
                    <svg class="header-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="19" cy="12" r="1"></circle>
                        <circle cx="5" cy="12" r="1"></circle>
                    </svg>
                    <svg class="header-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </div>
            </div>

            <div class="chat-box" id="chatBox">
                <div class="date-separator">Today</div>
                <!-- Messages will appear here -->
            </div>

            <div class="chat-input">
                <div class="input-wrapper">
                    <div class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                    <textarea class="textareachat" id="inputMessage" placeholder="Type your message here..."
                        rows="1"></textarea>
                </div>
                <button id="sendMessageButton">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </div>
        </div>
    </div>



    <!-- Chat Interface -->
    <div class="chat-container" id="chatContainer">
        <div class="chat-header">
            <div id="chatttile" class="chat-title">
                <span class="status-indicator"></span>
                Support Chat
            </div>
            <button class="close-chat" id="closeChat" aria-label="Close Chat">×</button>
        </div>
        <div class="chat-messages" id="chatMessages">
            <!-- Messages will be populated here -->
        </div>
        <div class="chat-input">
            <input type="text" class="message-input" id="messageInput" placeholder="Type a message..."
                aria-label="Type a message">
            <button class="send-button" id="sendButton" aria-label="Send Message">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <div class="modal-overlay" id="modalOverlay2">
        <form action="index.php" method="POST">

            <div class="modal">
                <div class="modal-header">
                    <h2 class="modal-title">Collaboration Details</h2>
                </div>
                <div class="modal-body">
                    <div class="collab-data" id="collabData">
                        <!-- Collaboration data will be inserted here by JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn2 cancel-btn2" id="cancelBtncollab">Cancel</button>
                    <button name="ajoutercollab2" type="submit" class="btn2 confirm-btn2"
                        id="confirmBtn">accepter</button>
                </div>
            </div>
        </form>
    </div>

    <header class="header">
        <?php if(isset($_SESSION['user_id'])){?>
        <button class="notif-button" id="notifBtn">
            <i class="fas fa-bell"></i>
            <span class="notif-count" id="notifCount">3</span>
        </button>

        <div class="overlay" id="overlay"></div>

        <div class="notif-panel" id="notifPanel">
            <div class="panel-title">
                <h3>Notifications</h3>
            </div>
            <div class="notif-container" id="notifContainer">
                <?php
                foreach ($listnotif as $notif) {
                    $user=$userdhia->load_user($_SESSION['user_id']);
                   // $user = $prop->afficheruser($_SESSION['id']);
                    $propos = $prop->afficherById($notif['id_proposition']);
                    ?>

                    <!-- Messages directement intégrés dans le HTML -->
                    <div class="notif-card" data-id="1">
                        <div class="notif-author"><?= $user->getName(). ' ' . $user->getLastName() ?></div>
                        <div class="notif-date"><?= $propos['Titre'] ?></div>
                        <div class="notif-text"><?= $propos['Type'] ?></div>
                        <div class="notif-buttons">
                            <a href="index.php?idprop=<?= $propos['ID_Proposition'] ?>&iddemande=<?= $notif['id_demande'] ?>"
                                class="btn-approve" data-id="1">Accepter</a>
                            <a href="index.php?suppdemande=<?= $notif['id_demande'] ?>" class="btn-decline"
                                data-id="1">Refuser</a>
                            <button id="showCollabBtn" class="action-btn btn_affmodal" title="Voir les détails"
                                data-id="<?= $notif['id_demande'] ?>" data-id2="<?= $notif['id_proposition'] ?>"
                                data-nameuser="<?= $user->getName() . ' ' . $user->getLastName() ?>"
                                data-titreprop=" <?= htmlspecialchars($propos['Titre'], ENT_QUOTES, 'UTF-8') ?>" data-role="<?= $notif['role'] ?> "
                                data-date1="<?= $notif['date_debut'] ?>" data-date2="<?= $notif['date_fin'] ?>"
                                data-type="<?= $notif['type'] ?>">👁️</button>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
        <?php }?>
        <?php if(isset($_SESSION['user_id'])){?>
        <div class="profile-container">
            <div class="profile-icon" id="profileIcon">
                <img src="<?= htmlspecialchars($profile_user->getProfileImage()) ?>" alt="Profile Picture" >
                
            </div>
            <div class="profile-menu" id="profileMenu">
                <div class="profile-header">
                    <div class="name"><?= $profile_user->getName().' '.$profile_user->getLastName(); ?></div>
                    <div class="email"><?= $profile_user->getEmail() ?></div>
                </div>
                <a href="monprofil.php?id=<?= $_SESSION['user_id'] ?>" class="menu-item">
                    <i class="icon-profile"></i>
                    Mon profil
                </a>
                <a href="allUsers.php?id=<?= $_SESSION['user_id'] ?>" class="menu-item">
                    <i class="fas fa-users"></i> <!-- Icône pour plusieurs profils -->
                    Tous les profils
                </a>
                <a href="settings.html" class="menu-item">
                    <i class="icon-settings"></i>
                    Paramètres
                </a>
                <div class="menu-divider"></div>
                <a onclick="destroysession()" href="#" class="menu-item">
                    <i class="icon-logout"></i>
                    Déconnexion
                </a>
            </div>
        </div>
        <?php }?>
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
                        <li><a href="index.php" class="active">Accueil</a></li>
                        <li><a href="index2.php">Evenement</a></li>
                        <li><a href="indexyessine.php">Articles</a></li>
                        <li><a href="consultationList.php">Consultations</a></li>
                        <?php if (!isset($_SESSION['user_id'])) { ?>
                            <li><a href="login2.php"  class="btn btn-primary">Connecter</a></li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>

       

    





        <div id="prop" class="container2">
            <header>
                <h1>Propositions de Collaboration</h1>
                <p class="subtitle">Découvrez et gérez les propositions de collaboration</p>
            </header>

            <div class="filters" id="filtrs">
                <button id="tousbtn" class="filter-btn active" data-filter="tous">Tous</button>
                <button class="filter-btn" data-filter="Recherche">Recherche</button>
                <button class="filter-btn" data-filter="Développement">Développement</button>
                <button class="filter-btn" data-filter="Formation">Formation</button>
                <button class="filter-btn" data-filter="Conseil">Conseil</button>
            </div>

            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Rechercher une proposition..." onkeydown="rechercher(event)">
                
            </div>

            <div class="propositions" id="propositions-container">
                <?php
                foreach ($liste as $proposition) {
                        $user=$userdhia->load_user($proposition['ID_Utilisateur']);
                   // $user = $prop->afficheruser($proposition['ID_Utilisateur']);
                    $listecollab = $collab->selectcollabbyid($proposition['ID_Proposition']);
                    $today = new DateTime();
                    $submissionDate = new DateTime($proposition['Date_Soumission']);
                    $interval = $submissionDate->diff($today);
                    if ($interval->days > 30 && $submissionDate < $today && empty($listecollab)) {
                        $mail = new PHPMailer();
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com'; // exemple pour Gmail
                        $mail->SMTPAuth = true;
                        $mail->Username = 'touil.moez25@gmail.com';
                        $mail->Password = 'nyso etxh wfhh uunw';
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;

                        $mail->setFrom('touil.moez25@gmail.com', 'touil');
                        $mail->addAddress($user->getEmail());
                        $mail->Subject = 'proposition expire';
                        $mail->Body = 'ta proposition a expire veuilez poster une nouvelle fois';

                        if ($mail->send()) {
                            //echo 'Email envoyé avec succès';
                        } else {
                            echo 'Erreur : ' . $mail->ErrorInfo;
                        }

                        $prop->suppprop($proposition['ID_Proposition']);
                    } else {



                        ?>


                        <div class="proposition-card" data-type="<?= $proposition['Type']; ?>" data-name="<?= $proposition['Titre']?>">
                            <input type="hidden" class="hiddenid" value="<?= $proposition['ID_Proposition'] ?>">
                            <div class="card-header">
                                <h3 class="card-title"><?= $proposition['Titre']; ?></h3>
                                <span class="card-type"><?= $proposition['Type']; ?></span>
                            </div>
                            <div class="card-body">
                                <p class="card-description"><?= $proposition['Description']; ?></p>
                                <div class="card-meta">
                                    <div class="card-date">
                                        <i>📅</i>
                                        <span><?= $proposition['Date_Soumission']; ?></span>
                                    </div>
                                    <div class="card-user">
                                        <div class="user-avatar"><?= $user->getName()[0] . $user->getLastName()[0] ?></div>
                                        <span><?= $user->getName() ?></span><span><?= $user->getLastName() ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <?php if ($proposition['Statut'] == 'Approuvé') {
                                    echo '<span class="status status-approved">Approuvé</span>';
                                }
                                if ($proposition['Statut'] == 'En attente') {
                                    echo '<span class="status status-pending">En attente</span>';
                                }
                                if ($proposition['Statut'] == 'Refusé') {
                                    echo '<span class="status status-rejected">Refusé</span>';
                                } ?>

                                <div class="card-actions">

                                    <?php
                                    if (isset($_SESSION['user_id']) && $proposition['ID_Utilisateur'] == $_SESSION['user_id']) {
                                        echo '
                                 


                                    <button 
                                            class="action-btn btn-edit" 
                                            title="Modifier"
                                            data-id="' . $proposition['ID_Proposition'] . '"
                                            data-titre="' . htmlspecialchars($proposition['Titre']) . '"
                                            data-type="' . htmlspecialchars($proposition['Type']) . '"
                                            data-description="' . htmlspecialchars($proposition['Description']) . '"
                                            data-date="' . htmlspecialchars($proposition['Date_Soumission']) . '"
                                        >✏️</button>';
                                    }
                                    ?>


                                    <?php
                                    if (isset($_SESSION['user_id']) && $proposition['ID_Utilisateur'] == $_SESSION['user_id']) {
                                        echo '<a class="action-btn" title="Supprimer" href="index.php?deleteid=' . $proposition['ID_Proposition'] . '#filtrs">🗑️</a>';
                                    } else if (isset($_SESSION['user_id']))
                                        echo '
                                    <button data-nom="' . $user->getName() . '" data-idsender="' . $_SESSION['user_id'] . '" data-idreceiver="' . $proposition['ID_Utilisateur'] . '" class="msg-button" aria-label="Open Messages">
                                        <i class="fas fa-comments"></i>
                                    </button>
                                
                                    <div  class="video-call-main">
                                        <button data-idsender="' . $_SESSION['user_id'] . '" data-idreceiver="' . $proposition['ID_Utilisateur'] . '"  id="main-video-call-btn" class="btnmeeeet">
                                            <i class="fas fa-video"></i>
                                        </button>
                                    </div>
                                
                                    <input type="hidden" name="userid" value="2">
                                
                                    <button data-id="' . $proposition['ID_Proposition'] . '" data-userid="' . $_SESSION['user_id'] . '" class="main-video-call-btn btn_addcollab">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                ';

                                    ?>

                                </div>
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>





            <div class="pagination">
                <button class="page-btn">Précédent</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">Suivant</button>
            </div>

            <div class="add-btn-container">
                <button id="showFormBtn" class="add-btn">
                    <span class="add-btn-icon">+</span>
                    Ajouter une proposition
                </button>
            </div>


            <div class="overlaymoez2"></div>
            <div id="formContainer" class="form-container">
                <div class="form-header">
                    <h2 class="form-title">Nouvelle proposition de collaboration</h2>
                </div>
                <form id="propositionForm" action="index.php" method="POST">
                    <div class="form-body">
                        <div class="form-group">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" id="titre" name="titre" class="form-control"
                                placeholder="Entrez le titre de la proposition">
                            <button type="button" onclick="autotitre()" class="btn btnauto">auto</button>
                            <div style="color:red;" id="monLabel"></div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control"
                                placeholder="Décrivez votre proposition en détail"></textarea>
                            <button type="button" id="autodesc" class="btn btnauto">auto</button>
                            <div style="color:red;" id="monLabel2"></div>

                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="type" class="form-label">Type</label>
                                    <select id="type" name="type" class="form-control form-select">
                                        <option value="" disabled selected>Sélectionnez un type</option>
                                        <option value="Recherche">Recherche</option>
                                        <option value="Développement">Développement</option>
                                        <option value="Formation">Formation</option>
                                        <option value="Conseil">Conseil</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="date_soumission" class="form-label">Date de soumission</label>
                                    <input type="date" id="date_soumission" name="date_soumission" class="form-control">
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="form-footer">
                        <button type="button" id="cancelBtn" class="btn btn-cancel">Annuler</button>
                        <button type="submit" class="btn btn-submit">Soumettre la proposition</button>
                    </div>
                </form>
            </div>


            <div id="formContainer3" class="form-container">
                <div class="form-header">
                    <h2 class="form-title">Nouvelle collaboration</h2>
                </div>
                <form id="propositionForm3" action="index.php" method="POST">
                    <input type="hidden" name="idproposs" id="idproposs">
                    <div class="form-body">
                        <div class="form-group">
                            <label for="titre" class="form-label">Role</label>
                            <input type="text" id="role" name="role" class="form-control"
                                placeholder="Donner votre role">
                                <button type="button" onclick="autorole()" class="btn btnauto">auto</button>
                            <div style="color:red;" id="monLabelcollab"></div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Type d collaboration</label>
                            <textarea id="typecollab" name="typecollab" class="form-control"
                                placeholder="donner le type de collaboration"></textarea>
                                <button type="button" onclick="autotypee()" class="btn btnauto">auto</button>
                            <div style="color:red;" id="monLabel2collab"></div>

                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="date_soumission" class="form-label">Date de debut</label>
                                <input type="date" id="date_debut" name="date_debut" class="form-control">
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label for="date_soumission" class="form-label">Date de fin</label>
                                <input type="date" id="date_fin" name="date_fin" class="form-control">
                            </div>
                        </div>
                    </div>




                    <div class="form-footer">
                        <button type="button" id="cancelBtn3" class="btn btn-cancel">Annuler</button>
                        <button name="ajoutercollab" type="submit" class="btn btn-submit">demander
                            collaboration</button>
                    </div>
                </form>
            </div>



            <div id="formContainer2" class="form-container">
                <div class="form-header">
                    <h2 class="form-title">Nouvelle proposition de collaboration</h2>
                </div>
                <form id="propositionForm2" action="index.php" method="POST">
                    <input type="hidden" name="idmodif" id="idmodif">
                    <div class="form-body">
                        <div class="form-group">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" id="titre2" name="titre2" class="form-control"
                                placeholder="Entrez le titre de la proposition">
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description2" name="description2" class="form-control"
                                placeholder="Décrivez votre proposition en détail"></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="type" class="form-label">Type</label>
                                    <select id="type2" name="type2" class="form-control form-select">
                                        <option value="" disabled selected>Sélectionnez un type</option>
                                        <option value="Recherche">Recherche</option>
                                        <option value="Développement">Développement</option>
                                        <option value="Formation">Formation</option>
                                        <option value="Conseil">Conseil</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="date_soumission" class="form-label">Date de soumission</label>
                                    <input type="date" id="date_soumission2" name="date_soumission2"
                                        class="form-control">
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="form-footer">
                        <button type="button" id="cancelBtn2" class="btn btn-cancel">Annuler</button>
                        <button type="submit" class="btn btn-submit">modifier la proposition</button>
                    </div>
                </form>
            </div>




            <section id="about" class="about">
                <div class="container">
                    <div class="about-content">
                        <div class="about-text">
                            <div class="section-header">
                                <h2>À propos de nous</h2>
                                <p>Votre partenaire dans le voyage entrepreneurial</p>
                            </div>
                            <p>EntrepreHub a été fondé en 2020 avec une mission claire : démocratiser l'entrepreneuriat
                                et rendre les ressources d'affaires accessibles à tous. Notre plateforme combine
                                technologie, expertise et communauté pour aider les entrepreneurs à chaque étape de leur
                                parcours.</p>
                            <p>Que vous soyez au stade de l'idée ou que vous cherchiez à développer votre entreprise
                                existante, nous avons les outils et le soutien dont vous avez besoin pour réussir.</p>
                            <div class="about-features">
                                <div class="feature">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span>Plateforme tout-en-un</span>
                                </div>
                                <div class="feature">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span>Experts de l'industrie</span>
                                </div>
                                <div class="feature">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span>Communauté mondiale</span>
                                </div>
                                <div class="feature">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span>Ressources personnalisées</span>
                                </div>
                            </div>
                        </div>
                        <div class="about-image">
                            <div class="stats-card">
                                <div class="stats-header">
                                    <h3>Notre impact</h3>
                                </div>
                                <div class="stats-grid">
                                    <div class="stat-item">
                                        <div class="stat-value">10,000+</div>
                                        <div class="stat-label">Entrepreneurs</div>
                                        <div class="stat-bar">
                                            <div class="stat-bar-fill" style="width: 85%;"></div>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">5,000+</div>
                                        <div class="stat-label">Entreprises lancées</div>
                                        <div class="stat-bar">
                                            <div class="stat-bar-fill" style="width: 65%;"></div>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">€25M+</div>
                                        <div class="stat-label">Financement obtenu</div>
                                        <div class="stat-bar">
                                            <div class="stat-bar-fill" style="width: 75%;"></div>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-value">30+</div>
                                        <div class="stat-label">Pays</div>
                                        <div class="stat-bar">
                                            <div class="stat-bar-fill" style="width: 45%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="testimonials" class="testimonials">
                <div class="container">
                    <div class="section-header">
                        <h2>Ce que disent nos entrepreneurs</h2>
                        <p>Découvrez comment EntrepreHub a aidé des entrepreneurs comme vous</p>
                    </div>
                    <div class="testimonial-slider">
                        <div class="testimonial-track">
                            <div class="testimonial-card">
                                <div class="testimonial-content">
                                    <p>"EntrepreHub a complètement transformé mon parcours entrepreneurial. Les
                                        ressources et le mentorat que j'ai reçus ont été inestimables pour le lancement
                                        de ma startup."</p>
                                </div>
                                <div class="testimonial-author">
                                    <div class="author-avatar">
                                        <img src="/placeholder.svg?height=50&width=50" alt="Sophie Martin">
                                    </div>
                                    <div class="author-info">
                                        <h4>Sophie Martin</h4>
                                        <p>Fondatrice, TechSolutions</p>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial-card">
                                <div class="testimonial-content">
                                    <p>"Grâce à la communauté EntrepreHub, j'ai pu connecter avec des investisseurs qui
                                        croyaient en ma vision. Maintenant, mon entreprise est en pleine croissance."
                                    </p>
                                </div>
                                <div class="testimonial-author">
                                    <div class="author-avatar">
                                        <img src="/placeholder.svg?height=50&width=50" alt="Thomas Dubois">
                                    </div>
                                    <div class="author-info">
                                        <h4>Thomas Dubois</h4>
                                        <p>CEO, GreenInnovate</p>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial-card">
                                <div class="testimonial-content">
                                    <p>"Les ateliers et les cours en ligne d'EntrepreHub m'ont donné les compétences
                                        dont j'avais besoin pour transformer mon idée en une entreprise rentable."</p>
                                </div>
                                <div class="testimonial-author">
                                    <div class="author-avatar">
                                        <img src="/placeholder.svg?height=50&width=50" alt="Camille Bernard">
                                    </div>
                                    <div class="author-info">
                                        <h4>Camille Bernard</h4>
                                        <p>Fondatrice, ArtisanMarket</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-nav">
                            <button class="nav-prev" aria-label="Previous testimonial">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </button>
                            <div class="nav-dots">
                                <button class="nav-dot active" aria-label="Testimonial 1"></button>
                                <button class="nav-dot" aria-label="Testimonial 2"></button>
                                <button class="nav-dot" aria-label="Testimonial 3"></button>
                            </div>
                            <button class="nav-next" aria-label="Next testimonial">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <section id="contact" class="contact">
                <div class="container">
                    <div class="contact-content">
                        <div class="contact-info">
                            <div class="section-header">
                                <h2>Contactez-nous</h2>
                                <p>Prêt à commencer votre voyage entrepreneurial?</p>
                            </div>
                            <p>Que vous ayez des questions sur nos services ou que vous souhaitiez en savoir plus sur la
                                façon dont nous pouvons vous aider, notre équipe est là pour vous.</p>
                            <div class="contact-methods">
                                <div class="contact-method">
                                    <div class="method-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                            </path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                    </div>
                                    <div class="method-details">
                                        <h4>Email</h4>
                                        <p>contact@entreprehub.com</p>
                                    </div>
                                </div>
                                <div class="contact-method">
                                    <div class="method-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="method-details">
                                        <h4>Téléphone</h4>
                                        <p>+33 1 23 45 67 89</p>
                                    </div>
                                </div>
                                <div class="contact-method">
                                    <div class="method-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                    </div>
                                    <div class="method-details">
                                        <h4>Adresse</h4>
                                        <p>123 Avenue de l'Innovation, 75001 Paris</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="contact-form-container">
                            <form class="contact-form">
                                <div class="form-group">
                                    <label for="name">Nom</label>
                                    <input type="text" id="name" name="name">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="subject">Sujet</label>
                                    <select id="subject" name="subject">
                                        <option value="general">Question générale</option>
                                        <option value="mentorship">Mentorat</option>
                                        <option value="funding">Financement</option>
                                        <option value="partnership">Partenariat</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea id="message" name="message" rows="5"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Envoyer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <section class="cta">
                <div class="container">
                    <div class="cta-content">
                        <h2>Prêt à transformer votre vision en réalité?</h2>
                        <p>Rejoignez des milliers d'entrepreneurs qui ont déjà commencé leur voyage avec EntrepreHub.
                        </p>
                        <a href="#" class="btn btn-primary">Commencer gratuitement</a>
                    </div>
                </div>
            </section>
            

            <main>
      <div class="chart-card-moez2">
        <div class="chart-header-moez2">
          <h2 class="chart-title-moez2">Répartition par Type</h2>
          <div class="chart-controls-moez2">
            <button id="animateChart-moez2" class="btn-moez2">Animer</button>
          </div>
        </div>
        <div class="chart-container-moez2">
          <canvas id="pieChart-moez2"></canvas>
          <div class="percentage-container-moez2" id="percentageDisplay-moez2">
            <div class="percentage-value-moez2">-</div>
            <div class="percentage-label-moez2">Sélectionnez un segment</div>
          </div>
        </div>
        <div class="chart-legend-moez2" id="customLegend-moez2"></div>
      </div>
      
      <div class="stats-summary-moez2">
        <div class="stat-card-moez2">
          <h3>Total</h3>
          <p class="stat-value-moez2" id="totalValue-moez2">22</p>
        </div>
        <div class="stat-card-moez2">
          <h3>Maximum</h3>
          <p class="stat-value-moez2" id="maxValue-moez2">8</p>
          <p class="stat-label-moez2" id="maxLabel-moez2">Développement</p>
        </div>
        <div class="stat-card-moez2">
          <h3>Pourcentage</h3>
          <p class="stat-value-moez2" id="percentValue-moez2">36%</p>
          <p class="stat-label-moez2">du total</p>
        </div>
      </div>
    </main>
    
    <footer class="footer-moez2">
      <p>© 2025 Statistiques des Types</p>
    </footer>
  </div>
    
    </main>

    

    <script src="assets/script.js"></script>
    <script src='https://meet.jit.si/external_api.js'></script>
    <script type="text/javascript">
    async function loadMessages() {
        try {
            const response = await fetch('getnotifmeet.php');
            const data = await response.json();
            console.log(data.data.idreceiver);

            if (data.success && data.data.idreceiver == document.getElementById('idreceiverr').value) {
                // Supprimer la notif
                await fetch('supprimermeet.php');

                // Afficher la fenêtre de réunion
                document.getElementById('overlaymoez').style.display = 'block';
                document.getElementById('nommeet').innerHTML = 'moeztouil';
                document.getElementById('room').value = data.data.roomurl;

                // Créer la salle Jitsi
                
               // document.getElementById('jitsi-meet').style.display = 'block';
            }
        } catch (err) {
            console.error("Erreur lors du chargement des messages :", err);
        }
    }

    // Lancer la détection de messages toutes les 2 secondes
    loadMessages();
    setInterval(loadMessages, 2000);

    // Clic sur le bouton accepter
    document.getElementById('refusermeet').addEventListener('click', () => {
        document.getElementById('overlaymoez').style.display = 'none';
        
        // Tu peux aussi gérer ici autre chose si besoin
    });
    document.getElementById('acceptermeet').addEventListener('click', () => {
        document.getElementById('overlaymoez').style.display = 'none';
        document.getElementById('jitsi-meet').style.display = 'block';
        const domain = "meet.jit.si";
                const options = {
                    roomName: document.getElementById('room').value,
                    width: 1100,
                    height: 600,
                    parentNode: document.querySelector('#jitsi-meet')
                };
                const api = new JitsiMeetExternalAPI(domain, options);

        
        // Tu peux aussi gérer ici autre chose si besoin
    });

    // Gestion des boutons "appeler"
    const meetingbtn = document.querySelectorAll('.btnmeeeet');
    meetingbtn.forEach(button => {
        button.addEventListener('click', () => {
            const domain = "meet.jit.si";
            const roomName = "TestRoom123"; // Génère dynamiquement si besoin
            const options = {
                roomName: roomName,
                width: 1100,
                height: 600,
                parentNode: document.querySelector('#jitsi-meet')
            };

            
            const senderId = button.dataset.idsender;
            const receiverId = button.dataset.idreceiver;

            // Envoie au backend
            fetch("insert_notif.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    sender: senderId,
                    receiver: receiverId,
                    content: roomName
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log("Message enregistré !");
                    } else {
                        console.error("Erreur:", data.error);
                    }
                });

            // Lancer la salle immédiatement pour l'appelant
            const api = new JitsiMeetExternalAPI(domain, options);
            document.getElementById('jitsi-meet').style.display = 'block';

        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        let rech=0;
        let dev=0;
        let conseil=0;
        let formation=0;
        const propositionCards = document.querySelectorAll('.proposition-card');
  propositionCards.forEach(card => {
    
        const cardType = card.getAttribute('data-type').toLowerCase();
        if (cardType === 'recherche') {
    rech++;
} else if (cardType === 'développement') {
    dev++;
} else if (cardType === 'conseil') {
    conseil++;
} else if (cardType === 'formation') {
    formation++;
}
    })

      // Données du graphique
      const types = ['Recherche', 'Développement', 'Conseil', 'Formation'];
      const counts = [rech, dev, conseil, formation];
      
      // Calculer les statistiques
      const total = counts.reduce((sum, count) => sum + count, 0);
      const max = Math.max(...counts);
      const maxIndex = counts.indexOf(max);
      const maxPercentage = Math.round((max / total) * 100);
      
      // Mettre à jour les statistiques dans le DOM
      document.getElementById('totalValue-moez2').textContent = total;
      document.getElementById('maxValue-moez2').textContent = max;
      document.getElementById('maxLabel-moez2').textContent = types[maxIndex];
      document.getElementById('percentValue-moez2').textContent = maxPercentage + '%';
      
      // Couleurs sombres pour le graphique
      const darkColors = [
        'rgba(255, 220, 65, 0.9)',    // Jaune principal
        'rgba(45, 55, 72, 0.9)',      // Bleu foncé
        'rgba(113, 128, 150, 0.9)',   // Gris
        'rgba(74, 85, 104, 0.9)'      // Bleu-gris
      ];
      
      const hoverColors = [
        'rgba(255, 220, 65, 1)',      // Jaune principal (plus vif)
        'rgba(56, 66, 87, 1)',        // Bleu foncé (plus vif)
        'rgba(129, 144, 165, 1)',     // Gris (plus vif)
        'rgba(90, 103, 125, 1)'       // Bleu-gris (plus vif)
      ];
      
      // Configuration du graphique
      const ctx = document.getElementById('pieChart-moez2').getContext('2d');
      
      // Créer le graphique
      const pieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: types,
          datasets: [{
            data: counts,
            backgroundColor: darkColors,
            borderColor: 'rgba(30, 30, 30, 1)',
            borderWidth: 2,
            hoverBackgroundColor: hoverColors,
            hoverBorderColor: 'rgba(255, 220, 65, 1)',
            hoverBorderWidth: 3,
            cutout: '60%'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.8)',
              titleFont: {
                size: 16,
                weight: 'bold'
              },
              bodyFont: {
                size: 14
              },
              padding: 12,
              displayColors: true,
              callbacks: {
                label: function(context) {
                  const value = context.raw;
                  const percentage = Math.round((value / total) * 100);
                  return `${value} (${percentage}%)`;
                }
              }
            }
          },
          animation: {
            animateRotate: true,
            animateScale: true,
            duration: 1500,
            easing: 'easeOutQuart'
          }
        }
      });
      
      // Créer une légende personnalisée
      const customLegend = document.getElementById('customLegend-moez2');
      const percentageDisplay = document.getElementById('percentageDisplay-moez2');
      const percentageValue = percentageDisplay.querySelector('.percentage-value-moez2');
      const percentageLabel = percentageDisplay.querySelector('.percentage-label-moez2');
      
      types.forEach((type, index) => {
        const legendItem = document.createElement('div');
        legendItem.classList.add('legend-item-moez2');
        
        const colorBox = document.createElement('span');
        colorBox.classList.add('legend-color-moez2');
        colorBox.style.backgroundColor = darkColors[index];
        
        const label = document.createElement('span');
        const percentage = Math.round((counts[index] / total) * 100);
        label.textContent = `${type} (${counts[index]} - ${percentage}%)`;
        
        legendItem.appendChild(colorBox);
        legendItem.appendChild(label);
        customLegend.appendChild(legendItem);
        
        // Ajouter un événement pour mettre en évidence le segment correspondant
        legendItem.addEventListener('mouseenter', () => {
          // Mettre à jour l'affichage du pourcentage
          percentageValue.textContent = `${percentage}%`;
          percentageLabel.textContent = type;
          
          // Mettre en évidence le segment
          const activeSegments = Array(types.length).fill(0);
          activeSegments[index] = 0.1; // Valeur pour l'offset
          pieChart.setActiveElements([{datasetIndex: 0, index: index}]);
          pieChart.update();
        });
        
        legendItem.addEventListener('mouseleave', () => {
          // Réinitialiser l'affichage du pourcentage
          percentageValue.textContent = '-';
          percentageLabel.textContent = 'Sélectionnez un segment';
          
          // Réinitialiser le graphique
          pieChart.setActiveElements([]);
          pieChart.update();
        });
      });
      
      // Bouton d'animation
      const animateButton = document.getElementById('animateChart-moez2');
      animateButton.addEventListener('click', () => {
        // Réinitialiser et animer le graphique
        pieChart.reset();
        pieChart.update();
        
        // Animation personnalisée
        setTimeout(() => {
          pieChart.options.animation.animateRotate = true;
          pieChart.options.animation.animateScale = true;
          pieChart.update();
        }, 50);
      });
      
      // Interaction avec le graphique
      document.getElementById('pieChart-moez2').addEventListener('mousemove', (e) => {
        const activePoints = pieChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, false);
        
        if (activePoints.length > 0) {
          const index = activePoints[0].index;
          const value = counts[index];
          const percentage = Math.round((value / total) * 100);
          
          // Mettre à jour l'affichage du pourcentage
          percentageValue.textContent = `${percentage}%`;
          percentageLabel.textContent = types[index];
        } else {
          // Réinitialiser l'affichage du pourcentage
          percentageValue.textContent = '-';
          percentageLabel.textContent = 'Sélectionnez un segment';
        }
      });
      
      // Réinitialiser lorsque la souris quitte le graphique
      document.getElementById('pieChart-moez2').addEventListener('mouseleave', () => {
        percentageValue.textContent = '-';
        percentageLabel.textContent = 'Sélectionnez un segment';
      });
    });
  </script>


</body>

</html>