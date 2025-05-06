<?php
require_once('../../controller/propcontroller.php');
require_once('../../controller/collaborationscontroller.php');
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

session_start();

echo '<pre style="z-index: 4000;">';
print_r($_SESSION['id']);
echo '</pre>';
$collab = new Collaborationscontroller();
$prop = new propcontroller();
$liste = $prop->afficher();
if (isset($_SESSION['id']))
    $listnotif = $prop->affichernotifById($_SESSION['id']);

if (isset($_POST['destroy'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
}
if (isset($_POST['id'])) {
    $_SESSION['id'] = $_POST['id'];
    header("Location: index.php");


}


if (isset($_POST['titre'])) {

    $propos = new Proposition(NULL, $_POST['titre'], $_POST['description'], $_POST['type'], $_POST['date_soumission'], 'En attente', $_SESSION['id']);
    $prop->ajouterproposition($propos);
    //echo 'cbnnnn';
    header("Location: index.php#filtrs");
}
if (isset($_GET['deleteid'])) {
    $prop->suppprop($_GET['deleteid']);
    header("Location: index.php");
}
if (isset($_POST['titre2'])) {
    $propos = new Proposition($_POST['idmodif'], $_POST['titre2'], $_POST['description2'], $_POST['type2'], $_POST['date_soumission2'], 'En attente', $_SESSION['id']);
    $prop->modify($propos);
    header("Location: index.php#filtrs");
}

if (isset($_POST['idproposs'])) {
    $prop->ajouterdemande($_SESSION['id'], $_POST['idproposs'], $_POST['role'], $_POST['date_debut'], $_POST['date_fin'], $_POST['typecollab']);
    $proposition = $prop->afficherById($_POST['idproposs']);
    $user = $prop->afficheruser($proposition['ID_Utilisateur']);


    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // exemple pour Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'touil.moez25@gmail.com';
    $mail->Password = 'nyso etxh wfhh uunw';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('touil.moez25@gmail.com', 'touil');
    $mail->addAddress($user['mail']);
    $mail->isHTML(true);
    $mail->Subject = ' Nouvelle proposition soumise – '.$proposition['Titre'];
    $mail->Body = '
<html>
<head>
  <style>
    body { font-family: Arial, sans-serif; color: #333; }
    .container { padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px; background-color: #f9f9f9; }
    h2 { color: #2c3e50; }
    .label { font-weight: bold; }
    .value { margin-bottom: 10px; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Nouvelle proposition reçue</h2>
    <p>Bonjour,</p>
    <p>Nous vous informons que <strong>' . htmlspecialchars($user['nomuser']) . '</strong> a soumis une nouvelle proposition via notre plateforme.</p>
    
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
    $collabo = new Collaboration(NULL, $proposs['ID_Proposition'], $_SESSION['id'], $notifi['role'], $notifi['date_debut'], $notifi['date_fin'], $notifi['type'], "En attente");
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
    $collabo = new Collaboration(NULL, $proposs['ID_Proposition'], $_SESSION['id'], $notifi['role'], $notifi['date_debut'], $notifi['date_fin'], $notifi['type'], "En attente");
    $collab->ajouter($collabo);
    $prop->deleteDemande($_POST['iddemandee']);
    header("Location: index.php");
}
if (isset($_GET['generatedid'])) {
    $liste = $prop->afficherByIdfetchall($_GET['generatedid']);

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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">


</head>

<body>
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


    <input type="hidden" id="idreceiverr" value="<?= $_SESSION['id'] ?>">







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






    <?php if (isset($_SESSION['id'])) { ?>
        <button id="btnmessagerie" data-idsender="<?= $_SESSION['id'] ?>" class="msg-button2" aria-label="Open Messages">
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
                    $user = $prop->afficheruser($_SESSION['id']);
                    $propos = $prop->afficherById($notif['id_proposition']);
                    ?>

                    <!-- Messages directement intégrés dans le HTML -->
                    <div class="notif-card" data-id="1">
                        <div class="notif-author"><?= $user['nomuser'] . ' ' . $user['prenomuser'] ?></div>
                        <div class="notif-date"><?= $propos['Titre'] ?></div>
                        <div class="notif-text"><?= $propos['Type'] ?></div>
                        <div class="notif-buttons">
                            <a href="index.php?idprop=<?= $propos['ID_Proposition'] ?>&iddemande=<?= $notif['id_demande'] ?>"
                                class="btn-approve" data-id="1">Accepter</a>
                            <a href="index.php?suppdemande=<?= $notif['id_demande'] ?>" class="btn-decline"
                                data-id="1">Refuser</a>
                            <button id="showCollabBtn" class="action-btn btn_affmodal" title="Voir les détails"
                                data-id="<?= $notif['id_demande'] ?>" data-id2="<?= $notif['id_proposition'] ?>"
                                data-nameuser="<?= $user['nomuser'] . ' ' . $user['prenomuser'] ?>"
                                data-titreprop=" <?= htmlspecialchars($propos['Titre'], ENT_QUOTES, 'UTF-8') ?>" data-role="<?= $notif['role'] ?> "
                                data-date1="<?= $notif['date_debut'] ?>" data-date2="<?= $notif['date_fin'] ?>"
                                data-type="<?= $notif['type'] ?>">👁️</button>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
        <div class="profile-container">
            <div class="profile-icon" id="profileIcon">
                <!-- L'image sera chargée ici via JavaScript -->
                <div class="initials">JD</div>
            </div>
            <div class="profile-menu" id="profileMenu">
                <div class="profile-header">
                    <div class="name">John Doe</div>
                    <div class="email">john.doe@example.com</div>
                </div>
                <a href="monprofil.php" class="menu-item">
                    <i class="icon-profile"></i>
                    Mon profil
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
                        <li><a href="../backoffice/back.php">page admin</a></li>
                        <li><a href="#home" class="active">Accueil</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#about">À propos</a></li>
                        <li><a href="#testimonials">Mes collaborations</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <?php if (!isset($_SESSION['id'])) { ?>
                            <li><button href="#" onclick="sendId()" class="btn btn-primary">Connecter</button></li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>

        <section id="home" class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1>Transformez vos idées en entreprises prospères</h1>
                    <p>Plateforme complète pour les entrepreneurs avec des ressources, des outils et une communauté pour
                        vous aider à réussir.</p>
                    <div class="hero-buttons">
                        <a href="#" class="btn btn-primary">Commencer gratuitement</a>
                        <a href="#" class="btn btn-secondary">En savoir plus</a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat">
                            <span class="stat-number">10k+</span>
                            <span class="stat-label">Entrepreneurs</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">5k+</span>
                            <span class="stat-label">Startups lancées</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">95%</span>
                            <span class="stat-label">Taux de satisfaction</span>
                        </div>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="code-window">
                        <div class="code-header">
                            <span class="dot red"></span>
                            <span class="dot yellow"></span>
                            <span class="dot green"></span>
                            <span class="code-title">success.js</span>
                        </div>
                        <div class="code-content">
                            <pre><code>class Entrepreneur {
  constructor(vision, passion) {
    this.vision = vision;
    this.passion = passion;
    this.resources = [];
  }

  addResource(resource) {
    this.resources.push(resource);
    console.log("Growing stronger!");
  }

  launch() {
    if (this.resources.length > 0) {
      return "Success!";
    }
    return "Need more resources";
  }
}

// Create your success story
const myBusiness = new Entrepreneur(
  "Change the world",
  "Unlimited"
);

// EntrepreHub provides resources
myBusiness.addResource("Mentorship");
myBusiness.addResource("Funding");
myBusiness.addResource("Network");

// Launch your business
console.log(myBusiness.launch());</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="services" class="services">
            <div class="container">
                <div class="section-header">
                    <h2>Nos Services</h2>
                    <p>Tout ce dont vous avez besoin pour développer votre entreprise</p>
                </div>
                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                <path d="M2 17l10 5 10-5"></path>
                                <path d="M2 12l10 5 10-5"></path>
                            </svg>
                        </div>
                        <h3>Formation d'entreprise</h3>
                        <p>Apprenez les bases de la création d'entreprise avec nos cours en ligne et nos ateliers
                            interactifs.</p>
                        <a href="#" class="link-arrow">En savoir plus</a>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                <line x1="15" y1="9" x2="15.01" y2="9"></line>
                            </svg>
                        </div>
                        <h3>Mentorat</h3>
                        <p>Connectez-vous avec des entrepreneurs expérimentés qui vous guideront dans votre parcours.
                        </p>
                        <a href="#" class="link-arrow">En savoir plus</a>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3>Réseautage</h3>
                        <p>Rejoignez notre communauté d'entrepreneurs et établissez des connexions précieuses.</p>
                        <a href="#" class="link-arrow">En savoir plus</a>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <h3>Financement</h3>
                        <p>Accédez à des opportunités de financement et connectez-vous avec des investisseurs
                            potentiels.</p>
                        <a href="#" class="link-arrow">En savoir plus</a>
                    </div>
                </div>
            </div>
        </section>








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
                <input type="text" class="search-input" placeholder="Rechercher une proposition...">
                <button class="search-btn">Rechercher</button>
            </div>

            <div class="propositions" id="propositions-container">
                <?php
                foreach ($liste as $proposition) {
                    $user = $prop->afficheruser($proposition['ID_Utilisateur']);
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
                        $mail->addAddress($user['mail']);
                        $mail->Subject = 'proposition';
                        $mail->Body = 'ta proposition a expire veuilez poster une nouvelle fois';

                        if ($mail->send()) {
                            //echo 'Email envoyé avec succès';
                        } else {
                            echo 'Erreur : ' . $mail->ErrorInfo;
                        }

                        $prop->suppprop($proposition['ID_Proposition']);
                    } else {



                        ?>


                        <div class="proposition-card" data-type="<?= $proposition['Type']; ?>">
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
                                        <div class="user-avatar"><?= $user['nomuser'][0] . $user['prenomuser'][0] ?></div>
                                        <span><?= $user['nomuser'] ?></span><span><?= $user['prenomuser'] ?></span>
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
                                    if (isset($_SESSION['id']) && $proposition['ID_Utilisateur'] == $_SESSION['id']) {
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
                                    if (isset($_SESSION['id']) && $proposition['ID_Utilisateur'] == $_SESSION['id']) {
                                        echo '<a class="action-btn" title="Supprimer" href="index.php?deleteid=' . $proposition['ID_Proposition'] . '#filtrs">🗑️</a>';
                                    } else if (isset($_SESSION['id']))
                                        echo '
                                    <button data-nom="' . $user['nomuser'] . '" data-idsender="' . $_SESSION['id'] . '" data-idreceiver="' . $proposition['ID_Utilisateur'] . '" class="msg-button" aria-label="Open Messages">
                                        <i class="fas fa-comments"></i>
                                    </button>
                                
                                    <div  class="video-call-main">
                                        <button data-idsender="' . $_SESSION['id'] . '" data-idreceiver="' . $proposition['ID_Utilisateur'] . '"  id="main-video-call-btn" class="btnmeeeet">
                                            <i class="fas fa-video"></i>
                                        </button>
                                    </div>
                                
                                    <input type="hidden" name="userid" value="2">
                                
                                    <button data-id="' . $proposition['ID_Proposition'] . '" data-userid="' . $_SESSION['id'] . '" class="main-video-call-btn btn_addcollab">
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
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h2>EntrepreHub</h2>
                    <p>Votre partenaire dans le voyage entrepreneurial</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                        <a href="#" aria-label="Twitter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
                                </path>
                            </svg>
                        </a>
                        <a href="#" aria-label="LinkedIn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z">
                                </path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        </a>
                        <a href="#" aria-label="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
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

</body>

</html>