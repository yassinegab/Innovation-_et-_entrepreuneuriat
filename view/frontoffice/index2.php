<?php
include_once('../../controller/insccontroller.php');
require_once('../../controller/user_controller.php');
require_once('../../controller/user_controller.php');
require_once('../../controller/propcontroller.php');
require_once('../../controller/collaborationscontroller.php');
require_once __DIR__ . '/../../controller/evenController.php';
require_once __DIR__ . '/../../model/evenmodel.php';
/*use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';*/
$userdhia=new User_controller();
session_start();

if(isset($_SESSION['user_id'])){

$id_utilisateur = $_SESSION['user_id'];
$profile_user = $userdhia->load_user($id_utilisateur);

}

$inscriptioncontroller = new InscriptionController();
$userdhia=new User_controller();
$collab = new Collaborationscontroller();
$prop = new propcontroller();
$liste = $prop->afficher();
$evenementcontroller = new EvenementController();
$listevent = $evenementcontroller->listeven();
$listeInscriptions = $inscriptioncontroller->afficherInscriptionsAvecEvenement();
if(isset($_SESSION['user_id']))
$id_utilisateur = $_SESSION['user_id'];
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
    <title>STELLIFEROUS - Empowering Entrepreneurs</title>
    
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


    <input type="hidden" id="idreceiverr" value="<?= $_SESSION['user_id'] ?>">







    <div id="jitsi-meet" style="display: none;"></div>





    






    


    

    

    <header class="header">
        
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
        <div class="container">

            <div class="header-content">
                <div class="logo">
                    <h1>STELLIFEROUS</h1>
                </div>
                <nav class="nav">
                    <button class="nav-toggle" aria-label="Toggle navigation">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <ul class="nav-menu">
                        <li><a href="index.php" >Accueil</a></li>
                        <li><a href="index2.php" class="active">Evenement</a></li>
                         <li><a href="indexyessine.php">Articles</a></li>
                        <li><a href="consultationList.php">Consultations</a></li>
                        <?php if (!isset($_SESSION['user_id'])) { ?>
                            <li><a href="login.php"  class="btn btn-primary">Connecter</a></li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>

        

    <div class="containerevent">
        <section class="events">
            <div class="events-header">
                <h2 class="events-title">Événements à venir</h2>
                <span class="events-count" id="events-count">  <?php echo count($listevent); ?> événement<?php echo count($listevent) > 1 ? 's' : ''; ?> disponible<?php echo count($listevent) > 1 ? 's' : ''; ?></span>
            </div>

             <div class="search-bar">
                <input type="text" class="search-input" placeholder="Rechercher un evenement..." >
                
            </div>
            
           <div id="events-grid" class="events-grid">
           <?php foreach($listevent as $even) {
              $estInscrit = estDejaInscrit($even['id_ev'], $listeInscriptions, $id_utilisateur);
               $imageData = $even['image']; // BLOB depuis la BDD
            $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageData);

            $statut = "";
            

        if ($estInscrit) {
    foreach ($listeInscriptions as $inscription) {
        if ($inscription['id_eve'] == $even['id_ev'] && $inscription['id_uti'] == $id_utilisateur) {
            $statut = $inscription['statut'];
            break;
        }
    }
}
            
            ?>
             <?php $isUpcoming = isUpcoming($even['date']); ?>
                
                <div class="event-card" data-category="Festival" data-location="Paris" data-date="2025-06-15" data-price="75" data-smoking="true" data-companion="true">
                                <?php if (!$isUpcoming): ?>
                        <div class="past-event-overlay">
                            <span class="past-event-label">Événement passé</span>
                        </div>
                    <?php endif; ?>
                    <div class="event-image" style="background-image: url('<?= $base64Image ?>')">
                        <div class="event-date"><?= $even['date'] ?></div>
                            <div class="event-category">Festival</div>
                         <?php if ($isUpcoming): ?>
                            <?php if ($estInscrit): ?>
                        <div class="event-registration registered">
                            <i class="fas fa-check-circle"></i> Déjà inscrit
                        </div>
                    <?php else: ?>
                        <div class="event-registration">
                            <form action="index.php" method="POST">
                                <input type="hidden" id="id_eve" name="id_eve" value="<?=$even['id_ev']?>">
                                <input type="hidden" name="id_uti" value="1">
                                <input type="hidden" name="inscription" value="1">
                            <button  type="submit" class="register-btn" >
                                
                                <i class="fas fa-plus-circle"></i> S'inscrire
                            </button>
                            </form>
                        </div>
                    <?php endif; ?>
                     <?php endif; ?>
                    </div>
                    <div class="event-content">
                        <h3 class="event-name"><?= htmlspecialchars($even['nom']); ?></h3>
                        <div class="event-info">
                            <div class="event-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= htmlspecialchars($even['lieu']); ?></span>
                            </div>
                            <div class="event-info-item">
                                <i class="fas fa-users"></i>
                                <span>Capacité: <?= htmlspecialchars($even['capacite']); ?> personnes</span>
                            </div>
                        </div>
                                            <div class="event-feature">
                            <div class="feature-icon <?= (isset($even['espace_fumeur']) && $even['espace_fumeur']) ? 'feature-yes' : 'feature-no' ?>">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>Espace fumeur</span>
                        </div>
                        <div class="event-feature">
                            <div class="feature-icon <?= (isset($even['accompagnateur_autorise']) && $even['accompagnateur_autorise']) ? 'feature-yes' : 'feature-no' ?>">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>Accompagnateur</span>
                        </div>

                        <div class="event-price"><?= $even['prix']?> €</div>
                    </div>
                </div>
                <?php }?>  
             </div> 
             
        </section>     

    </div>






        


            


            


                   





            <section id="about" class="about">
                <div class="container">
                    <div class="about-content">
                        <div class="about-text">
                            <div class="section-header">
                                <h2>À propos de nous</h2>
                                <p>Votre partenaire dans le voyage entrepreneurial</p>
                            </div>
                            <p>STELLIFEROUS a été fondé en 2020 avec une mission claire : démocratiser l'entrepreneuriat
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
                        <p>Découvrez comment STELLIFEROUS a aidé des entrepreneurs comme vous</p>
                    </div>
                    <div class="testimonial-slider">
                        <div class="testimonial-track">
                            <div class="testimonial-card">
                                <div class="testimonial-content">
                                    <p>"STELLIFEROUS a complètement transformé mon parcours entrepreneurial. Les
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
                                    <p>"Grâce à la communauté STELLIFEROUS, j'ai pu connecter avec des investisseurs qui
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
                                    <p>"Les ateliers et les cours en ligne d'STELLIFEROUS m'ont donné les compétences
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
                                        <p>contact@STELLIFEROUS.com</p>
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
                        <p>Rejoignez des milliers d'entrepreneurs qui ont déjà commencé leur voyage avec STELLIFEROUS.
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

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h2>STELLIFEROUS</h2>
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
                <p>&copy; 2023 STELLIFEROUS. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    
    <script src='https://meet.jit.si/external_api.js'></script>
    

<script>
    const profileIcon = document.getElementById('profileIcon');
const profileMenu = document.getElementById('profileMenu');
const initialsElement = profileIcon.querySelector('.initials');

// Fonction pour charger l'image de profil
function loadProfileImage(imageUrl, userName) {
    // Créer l'élément image
    const img = new Image();
    
    // Définir l'URL de l'image
    img.src = imageUrl;
    
    // Gérer le chargement réussi de l'image
    img.onload = function() {
        // Supprimer les initiales
        if (initialsElement) {
            initialsElement.remove();
        }
        
        // Ajouter l'image au conteneur
        profileIcon.appendChild(img);
    };
    
    // Gérer l'erreur de chargement de l'image
    img.onerror = function() {
        console.log("Erreur de chargement de l'image, utilisation des initiales à la place");
        // S'assurer que les initiales sont correctes
        setUserInitials(userName);
    };
}

// Fonction pour définir les initiales de l'utilisateur
function setUserInitials(name) {
    const nameParts = name.split(' ');
    let initials = '';
    
    if (nameParts.length >= 2) {
        initials = nameParts[0].charAt(0) + nameParts[1].charAt(0);
    } else {
        initials = nameParts[0].charAt(0);
    }
    
    initialsElement.textContent = initials.toUpperCase();
}

// Ajouter un événement de clic à l'icône de profil
profileIcon.addEventListener('click', function() {
    profileMenu.classList.toggle('active');
});

// Fermer le menu si on clique ailleurs sur la page
document.addEventListener('click', function(event) {
    if (!profileIcon.contains(event.target) && !profileMenu.contains(event.target)) {
        profileMenu.classList.remove('active');
    }
});

// Exemple d'utilisation - remplacez par les informations de l'utilisateur connecté
const userName = "moez touil";
const userEmail = "moez.touil@esprit.tn";
const profileImageUrl = "assets/398582438_2615487838627907_5927319269485945046_n.jpg"; // URL d'exemple

// Mettre à jour les informations dans le menu
document.querySelector('.profile-header .name').textContent = userName;
document.querySelector('.profile-header .email').textContent = userEmail;

// Charger l'image de profil
//loadProfileImage(profileImageUrl, userName);
</script>

</body>

</html>