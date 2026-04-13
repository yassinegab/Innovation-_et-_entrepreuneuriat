<?php
include_once('../../controller/ConsultationController.php');
require_once('../../controller/user_controller.php');
//include_once('reponseList.php');
session_start();
$controller = new ConsultationController();
$consultationC = new ConsultationController();
$userdhia=new User_controller();

if(isset($_SESSION['user_id'])){

$id_utilisateur = $_SESSION['user_id'];
$profile_user = $userdhia->load_user($id_utilisateur);

}
session_start();
if (isset($_POST['destroy'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
}
if (isset($_POST['id'])) {
    $_SESSION['user_id'] = $_POST['id'];
    header("Location: index.php");


}

// Update consultation status
if (isset($_POST['action']) && $_POST['action'] === 'complete_consultation') {
  if (isset($_POST['consultation_id']) && !empty($_POST['consultation_id'])) {
    $id_consultation = (int)$_POST['consultation_id'];

    try {
      $db = new PDO('mysql:host=localhost;dbname=mydbname', 'root', '');
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $query = "UPDATE consultations SET statut = 'completed' WHERE id_consultation = :id_consultation";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':id_consultation', $id_consultation);

      if ($stmt->execute()) {
        // Retourner une réponse JSON pour les requêtes AJAX
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
          header('Content-Type: application/json');
          echo json_encode(['success' => true]);
          exit;
        }

        // Redirection normale pour les soumissions de formulaire standard
        header("Location: consultationList.php?success=update_status");
        exit();
      } else {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
          header('Content-Type: application/json');
          echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour du statut.']);
          exit;
        }
        echo 'Erreur lors de la mise à jour du statut.';
      }
    } catch (PDOException $e) {
      if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
        exit;
      }
      echo 'Erreur : ' . $e->getMessage();
    }
  }
}

// Add consultation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
  if (!empty($_POST['titre']) && !empty($_POST['type']) && !empty($_POST['description']) && !empty($_POST['date']) && !empty($_POST['id_utilisateur']) && !empty($_POST['statut'])) {
    $titre = htmlspecialchars(trim($_POST['titre']));
    $type = htmlspecialchars(trim($_POST['type']));
    $description = htmlspecialchars(trim($_POST['description']));
    $date = $_POST['date'];
    $id_utilisateur = htmlspecialchars(trim($_POST['id_utilisateur']));
    $statut = $_POST['statut'];

    try {
      $db = new PDO('mysql:host=localhost;dbname=mydbname', 'root', '');
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
        echo 'Erreur lors de l\'insertion.';
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
  <link rel="stylesheet" href="assets/styletakoua.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/theme-custom.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</head>
<style>
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
  }

  /* modaltakoua Styles - Adding blur effect and improving aesthetics */
  .modaltakoua {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    
    overflow: auto;
    z-index: 999999;
  }

  .modaltakoua-content {
    background-color: var(--bg-light, #24252b);
    margin: 5% auto;
    padding: 30px;
    border-radius: 16px;
    width: 85%;
    max-width: 900px;
    height: 90%;
    overflow-y: auto;
    position: relative;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transform: translateY(0);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
     z-index: 999999;
    
  }

  .close-modaltakoua {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 24px;
    cursor: pointer;
    color: #e3c43a;
    /* Changed to orange/gold color */
    transition: color 0.2s ease, transform 0.2s ease;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
  }

  .close-modaltakoua:hover {
    color: #e3c43a;
    background-color: rgba(227, 196, 58, 0.1);
    transform: rotate(90deg);
  }

  /* modaltakoua FIXES */
  #details-modaltakoua {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 999999;
  }

  #details-modaltakoua.modaltakoua-active {
    display: flex !important;
    animation: modaltakouaFadeIn 0.3s forwards;
     z-index: 999999;
  }

  @keyframes modaltakouaFadeIn {
    from {
      opacity: 0;
    }

    to {
      opacity: 1;
    }
  }

  #details-modaltakoua .modaltakoua-content {
    background: var(--bg-light, #24252b);
    width: 90%;
    max-width: 800px;
    height: 90%;
    overflow-y: auto;
    border-radius: 16px;
    padding: 24px;
    position: relative;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
    animation: modaltakouaSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
     z-index: 999999;
  }

  /* Style for centered text at the beginning of details modaltakoua */
  #details-modaltakoua .modaltakoua-header-text {
    text-align: center;
    color: white;
    margin-bottom: 20px;
    font-size: 1.5rem;
    font-weight: 600;
    padding-bottom: 15px;
    border-bottom: 1px solid rgba(227, 196, 58, 0.3);
  }

  @keyframes modaltakouaSlideUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  #details-modaltakoua .close-modaltakoua {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 24px;
    cursor: pointer;
    color: #e3c43a;
    transition: all 0.2s ease;
  }

  .modaltakoua.modaltakoua-active {
    display: flex !important;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    align-items: center;
    justify-content: center;
    z-index: 99999;
    animation: backdropFadeIn 0.3s ease forwards;
  }

  @keyframes backdropFadeIn {
    from {
      background: rgba(0, 0, 0, 0);
      backdrop-filter: blur(0px);
    }

    to {
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(8px);
    }
  }

  /* Main Content */
  .main-content {
    flex: 1;
    margin-left: 0;
    padding: 20px;
    position: relative;
    overflow: auto;
  }

  /* New Consultation modaltakoua Styles */
  #new-consultation-modaltakoua {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    align-items: center;
    justify-content: center;
  }

  #new-consultation-modaltakoua.active {
    display: flex;
    animation: fadeIn 0.3s ease forwards;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
    }

    to {
      opacity: 1;
    }
  }

  #new-consultation-modaltakoua .modaltakoua-content {
    background-color: rgb(36, 37, 43);
    margin: auto;
    width: 90%;
    max-width: 550px;
    height: 90%;
    border-radius: 16px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
    border: 1px solid rgba(227, 196, 58, 0.2);
    /* Added orange border */
    overflow: hidden;
    animation: modaltakouaSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }

  @keyframes modaltakouaSlideIn {
    from {
      opacity: 0;
      transform: translateY(-30px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  #new-consultation-modaltakoua .modaltakoua-header {
    background-color: rgb(45, 46, 54);
    background-image: linear-gradient(to right, rgba(227, 196, 58, 0.2), transparent);
    padding: 18px 24px;
    border-bottom: 1px solid rgba(227, 196, 58, 0.2);
    /* Changed to orange */
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  #new-consultation-modaltakoua .modaltakoua-header h2 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #e3c43a;
    /* Changed to orange */
    letter-spacing: -0.01em;
  }

  #new-consultation-modaltakoua .close-modaltakoua {
    background: none;
    border: none;
    color: #e3c43a;
    /* Changed to orange */
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

  #new-consultation-modaltakoua .close-modaltakoua:hover {
    background-color: rgba(227, 196, 58, 0.1);
    color: #e3c43a;
    transform: rotate(90deg);
  }

  #new-consultation-modaltakoua .modaltakoua-body {
    padding: 24px;
    max-height: 100%
    overflow-y: auto;
  }

  #new-consultation-modaltakoua .modaltakoua-body::-webkit-scrollbar {
    width: 8px;
  }

  #new-consultation-modaltakoua .modaltakoua-body::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
  }

  #new-consultation-modaltakoua .modaltakoua-body::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
  }

  #new-consultation-modaltakoua .modaltakoua-body::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
  }

  #new-consultation-modaltakoua .form-group {
    margin-bottom: 20px;
    opacity: 0;
    animation: formElementFadeIn 0.5s ease forwards;
  }

  #new-consultation-modaltakoua .form-group:nth-child(1) {
    animation-delay: 0.1s;
  }

  #new-consultation-modaltakoua .form-group:nth-child(2) {
    animation-delay: 0.15s;
  }

  #new-consultation-modaltakoua .form-group:nth-child(3) {
    animation-delay: 0.2s;
  }

  #new-consultation-modaltakoua .form-group:nth-child(4) {
    animation-delay: 0.25s;
  }

  #new-consultation-modaltakoua .form-group:nth-child(5) {
    animation-delay: 0.3s;
  }

  #new-consultation-modaltakoua .form-group:nth-child(6) {
    animation-delay: 0.35s;
  }

  @keyframes formElementFadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  #new-consultation-modaltakoua .form-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.8);
  }

  #new-consultation-modaltakoua .form-group input,
  #new-consultation-modaltakoua .form-group select,
  #new-consultation-modaltakoua .form-group textarea {
    width: 100%;
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(227, 196, 58, 0.2);
    /* Changed to orange */
    border-radius: 10px;
    padding: 12px 16px;
    color: rgba(255, 255, 255, 0.9);
    font-size: 15px;
    font-family: "Inter", sans-serif;
    transition: all 0.2s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1) inset;
  }

  #new-consultation-modaltakoua .form-group input:focus,
  #new-consultation-modaltakoua .form-group select:focus,
  #new-consultation-modaltakoua .form-group textarea:focus {
    outline: none;
    border-color: #e3c43a;
    box-shadow: 0 0 0 3px rgba(227, 196, 58, 0.15), 0 2px 5px rgba(0, 0, 0, 0.1) inset;
  }

  #new-consultation-modaltakoua .form-group select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='rgb(227, 196, 58)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 40px;
  }

  .consultation-type {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
    text-transform: capitalize;
  }

  .consultation-status {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
    color: white;
    text-transform: capitalize;
  }


  #new-consultation-modaltakoua .form-group textarea {
    resize: vertical;
    min-height: 120px;
  }


  /* Styling for status options - ensuring text is black */
  #new-consultation-modaltakoua #consultation-status option[value="pending"] {
    background-color: rgb(212, 126, 27);
    color: #333 !important;
  }

  #new-consultation-modaltakoua #consultation-status option[value="in-progress"] {
    background-color: rgb(45, 135, 184);
    color: #333 !important;
  }

  #new-consultation-modaltakoua #consultation-status option[value="completed"] {
    background-color: rgb(25, 128, 61);
    color: #333 !important;
  }

  #new-consultation-modaltakoua #consultation-status option[value="cancelled"] {
    background-color: rgb(160, 35, 35);
    color: #333 !important;
  }

  /* Date input styling */
  #new-consultation-modaltakoua input[type="date"] {
    color-scheme: dark;
  }

  #new-consultation-modaltakoua input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(0.8) sepia(100%) saturate(500%) hue-rotate(20deg);
    cursor: pointer;
  }

  /* modaltakoua footer */
  #new-consultation-modaltakoua .modaltakoua-footer {
    padding: 16px 24px;
    border-top: 1px solid rgba(227, 196, 58, 0.2);
    /* Changed to orange */
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background-color: rgba(45, 46, 54, 0.5);
    opacity: 0;
    animation: formElementFadeIn 0.5s ease forwards 0.4s;
  }

  /* Button styles */
  #new-consultation-modaltakoua .primary-btn,
  #new-consultation-modaltakoua .secondary-btn {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    font-family: "Inter", sans-serif;
  }

  #new-consultation-modaltakoua .primary-btn {
    background: linear-gradient(135deg, #e3c43a, #d4b52f);
    color: rgb(29, 30, 35);
    box-shadow: 0 4px 10px rgba(227, 196, 58, 0.3);
  }

  #new-consultation-modaltakoua .primary-btn:hover {
    background: linear-gradient(135deg, #f3d44a, #e4c53f);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(227, 196, 58, 0.4);
  }

  #new-consultation-modaltakoua .primary-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 5px rgba(227, 196, 58, 0.3);
  }

  #new-consultation-modaltakoua .secondary-btn {
    background-color: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.2);
  }

  #new-consultation-modaltakoua .secondary-btn:hover {
    background-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  }

  #new-consultation-modaltakoua .secondary-btn:active {
    transform: translateY(0);
    box-shadow: none;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    #new-consultation-modaltakoua .modaltakoua-content {
      width: 95%;
      max-width: none;
    }

    #new-consultation-modaltakoua .modaltakoua-body {
      padding: 16px;
      max-height: calc(100vh - 150px);
    }

    #new-consultation-modaltakoua .form-group {
      margin-bottom: 16px;
    }

    #new-consultation-modaltakoua .modaltakoua-footer {
      padding: 12px 16px;
      flex-direction: column-reverse;
    }

    #new-consultation-modaltakoua .primary-btn,
    #new-consultation-modaltakoua .secondary-btn {
      width: 100%;
      text-align: center;
    }
  }

  .modaltakoua {
    position: relative;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 999999;
  }

  .modaltakoua-content {
    background: white;
    color: black;
    padding: 20px;
    max-width: 800px;
    height: 100%;
    overflow-y: auto;
    border-radius: 16px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    transform: scale(0.95);
    opacity: 0;
    transition: transform 0.3s ease, opacity 0.3s ease;
  }

  .modaltakoua-active {
    display: flex !important;
  }

  .modaltakoua-active .modaltakoua-content {
    transform: scale(1);
    opacity: 1;
  }

  /* Styles pour l'overlay */
  .overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    z-index: 80;
    transition: opacity 0.3s ease;
    opacity: 0;
  }

  .overlay.active {
    display: block;
    opacity: 1;
  }

  /* Enhanced Fermer Button Style */
  .fermer-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: linear-gradient(135deg, #e3c43a, #d4b52f);
    color: rgb(29, 30, 35) !important;
    border: none;
    border-radius: 10px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-align: center;
    margin-top: 20px;
    text-decoration: none;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(227, 196, 58, 0.3);
    z-index: 1;
  }

  .fermer-btn:before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0));
    z-index: -1;
    transition: all 0.6s ease;
  }

  .fermer-btn:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 7px 20px rgba(227, 196, 58, 0.4);
  }

  .fermer-btn:hover:before {
    left: 100%;
  }

  .fermer-btn:active {
    transform: translateY(1px);
    box-shadow: 0 2px 5px rgba(227, 196, 58, 0.3);
  }

  .fermer-btn i {
    font-size: 16px;
    transition: transform 0.3s ease;
  }

  .fermer-btn:hover i {
    transform: translateX(3px);
  }

  /* Evaluation section styles */
  .evaluation-section {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid rgba(227, 196, 58, 0.2);
  }

  .evaluation-section h3 {
    color: #e3c43a;
    margin-bottom: 15px;
    font-size: 1.1rem;
  }

  .evaluation-stars {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
  }

  .star-btn {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.3);
    font-size: 24px;
    cursor: pointer;
    transition: color 0.2s ease, transform 0.2s ease;
  }

  .star-btn:hover,
  .star-btn.active {
    color: #e3c43a;
    transform: scale(1.2);
  }

  .evaluation-comment {
    margin-bottom: 20px;
  }

  .evaluation-comment textarea {
    width: 100%;
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(227, 196, 58, 0.2);
    border-radius: 10px;
    padding: 12px 16px;
    color: rgba(255, 255, 255, 0.9);
    font-size: 15px;
    font-family: "Inter", sans-serif;
    min-height: 100px;
    resize: vertical;
  }

  .complete-btn {
    background: linear-gradient(135deg, #e3c43a, #d4b52f);
    color: rgb(29, 30, 35);
    border: none;
    border-radius: 10px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 10px rgba(227, 196, 58, 0.3);
  }

  .complete-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(227, 196, 58, 0.4);
  }

  .complete-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 5px rgba(227, 196, 58, 0.3);
  }

  /* Confirmation modaltakoua styles */
  #confirmation-modaltakoua {
    display: none;
    position: fixed;
    z-index: 9999;
    /* Augmenté pour être au-dessus du modaltakoua de détails */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    align-items: center;
    justify-content: center;
  }

  #confirmation-modaltakoua.active {
    display: flex;
    animation: fadeIn 0.3s ease forwards;
  }

  #confirmation-modaltakoua .modaltakoua-content {
    background-color: rgb(36, 37, 43);
    margin: auto;
    width: 90%;
    max-width: 400px;
    height: 90%;
    border-radius: 16px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
    border: 1px solid rgba(227, 196, 58, 0.2);
    overflow: hidden;
    animation: modaltakouaSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    color: white;
    padding: 0;
  }

  #confirmation-modaltakoua .modaltakoua-header {
    background-color: rgb(45, 46, 54);
    background-image: linear-gradient(to right, rgba(227, 196, 58, 0.2), transparent);
    padding: 18px 24px;
    border-bottom: 1px solid rgba(227, 196, 58, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  #confirmation-modaltakoua .modaltakoua-header h2 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #e3c43a;
    letter-spacing: -0.01em;
  }

  #confirmation-modaltakoua .modaltakoua-body {
    padding: 24px;
    text-align: center;
  }

  #confirmation-modaltakoua .modaltakoua-body p {
    margin-bottom: 20px;
    font-size: 16px;
    line-height: 1.5;
  }

  #confirmation-modaltakoua .modaltakoua-footer {
    padding: 16px 24px;
    border-top: 1px solid rgba(227, 196, 58, 0.2);
    display: flex;
    justify-content: center;
    gap: 12px;
    background-color: rgba(45, 46, 54, 0.5);
  }

  #confirmation-modaltakoua .confirm-btn {
    background: linear-gradient(135deg, #e3c43a, #d4b52f);
    color: rgb(29, 30, 35);
    border: none;
    border-radius: 10px;
    padding: 10px 20px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 10px rgba(227, 196, 58, 0.3);
  }

  #confirmation-modaltakoua .confirm-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(227, 196, 58, 0.4);
  }

  #confirmation-modaltakoua .cancel-btn {
    background-color: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    padding: 10px 20px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  #confirmation-modaltakoua .cancel-btn:hover {
    background-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px);
  }

  /* Essential notification styles */
  .notification-panel {
    position: fixed;
    top: 60px;
    right: -350px;
    width: 350px;
    height: 80vh;
    background-color: #24252b;
    border-left: 1px solid rgba(255, 255, 255, 0.1);
    z-index: 1000;
    transition: right 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: -5px 0 20px rgba(0, 0, 0, 0.3);
    border-radius: 12px 0 0 12px;
    overflow: hidden;
    z-index: 99999;
  }

  .notification-panel.active {
    right: 0;
  }

  .overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    z-index: 999;
    transition: opacity 0.3s ease;
  }

  .overlay.active {
    display: block;
  }

  /* Enhanced notification styles */
  .notification-btn {
    position: relative;
    background: none;
    border: none;
    color: var(--text-primary, #fff);
    font-size: 1.2rem;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: background-color 0.2s, transform 0.2s;
  }

  .notification-btn:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: scale(1.1);
  }

  .notification-badge {
    position: absolute;
    top: 0;
    right: 0;
    background: linear-gradient(135deg, #e3c43a, #d4b52f);
    color: #1d1e23;
    font-size: 0.7rem;
    font-weight: bold;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s infinite;
    box-shadow: 0 2px 5px rgba(227, 196, 58, 0.3);
  }

  @keyframes pulse {
    0% {
      transform: scale(1);
      box-shadow: 0 0 0 0 rgba(227, 196, 58, 0.5);
    }

    70% {
      transform: scale(1.1);
      box-shadow: 0 0 0 6px rgba(227, 196, 58, 0);
    }

    100% {
      transform: scale(1);
      box-shadow: 0 0 0 0 rgba(227, 196, 58, 0);
    }
  }

  .mark-all-read {
    background: none;
    border: none;
    color: #e3c43a;
    font-size: 12px;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 6px;
    transition: all 0.2s;
  }

  .mark-all-read:hover {
    background-color: rgba(227, 196, 58, 0.1);
    transform: translateY(-1px);
  }

  .notification-list {
    overflow-y: auto;
    flex: 1;
  }

  .notification-item {
    padding: 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    gap: 12px;
    transition: all 0.2s;
    cursor: pointer;
  }

  .notification-item:hover {
    background-color: #2d2e36;
    transform: translateX(3px);
  }

  .notification-item.unread {
    border-left: 3px solid #e3c43a;
    background-color: rgba(227, 196, 58, 0.05);
  }

  .notification-icon {
    color: rgba(255, 255, 255, 0.7);
    display: flex;
    align-items: flex-start;
    justify-content: center;
    width: 24px;
    font-size: 16px;
    padding-top: 2px;
  }

  .notification-content {
    flex: 1;
  }

  .notification-content p {
    margin: 0 0 6px 0;
    line-height: 1.4;
    color: #fff;
  }

  .notification-time {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.5);
    display: block;
  }

  .notification-empty,
  .notification-loading,
  .notification-error {
    padding: 20px;
    text-align: center;
    color: rgba(255, 255, 255, 0.5);
    font-style: italic;
  }

  .notification-error {
    color: #ff6b6b;
  }

  .notification-header {
    padding: 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(to right, #2d2e36, #33343e);
    position: sticky;
    top: 0;
    z-index: 2;
  }

  .notification-list {
    overflow-y: auto;
    max-height: calc(100vh - 120px);
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
  }

  .notification-list::-webkit-scrollbar {
    width: 6px;
  }

  .notification-list::-webkit-scrollbar-track {
    background: transparent;
  }

  .notification-list::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 6px;
  }

  .notification-list::-webkit-scrollbar-thumb:hover {
    background-color: rgba(255, 255, 255, 0.3);
  }

  /* Améliorations pour les boutons de détails */
  .view-details-btn {
    background: linear-gradient(135deg, #e3c43a, #d4b52f);
    color: rgb(29, 30, 35);
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 10px rgba(227, 196, 58, 0.2);
  }

  .view-details-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(227, 196, 58, 0.3);
  }

  .view-details-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 5px rgba(227, 196, 58, 0.2);
  }

  .view-details-btn i {
    font-size: 14px;
  }

  /* Style pour les boutons d'action dans le modaltakoua de détails */
  .modaltakoua-actions {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid rgba(227, 196, 58, 0.2);
  }

  /* Amélioration du style pour le bouton Fermer */
  .modaltakoua-actions .fermer-btn {
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.9) !important;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: none;
    padding: 10px 20px;
    margin-top: 0;
  }

  .modaltakoua-actions .fermer-btn:hover {
    background-color: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  }

  .modaltakoua-actions .fermer-btn:active {
    transform: translateY(0);
    box-shadow: none;
  }

  /* Amélioration du style pour le bouton Terminer consultation */
  .modaltakoua-actions .complete-btn {
    margin: 0;
  }

  /* Amélioration des cartes de consultation */
  .consultation-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(227, 196, 58, 0.1);
  }

  .consultation-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    border-color: rgba(227, 196, 58, 0.3);
  }

  /* Animation pour les boutons fancy */
  .fancy-details-button {
    position: relative;
    overflow: hidden;
  }

  .fancy-details-button::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg,
        rgba(255, 255, 255, 0) 0%,
        rgba(255, 255, 255, 0.2) 50%,
        rgba(255, 255, 255, 0) 100%);
    transition: left 0.6s ease;
  }

  .fancy-details-button:hover::after {
    left: 100%;
  }

  /* Status colors */
  .consultation-status.pending {
    background-color: rgb(245, 166, 47);
    color: rgb(29, 26, 17);
  }

  .consultation-status.in-progress {
    background-color: rgb(31, 162, 233);
    color: rgb(10, 13, 15);
  }

  .consultation-status.completed {
    background-color: rgb(45, 187, 94);
    color: rgb(13, 15, 14);
  }

  .consultation-status.cancelled {
    background-color: rgb(204, 31, 31);
    color: rgb(20, 15, 15);
  }

  /* Success notification */
  .success-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #24252b;
    color: white;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    border-left: 4px solid #e3c43a;
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 10px;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s ease;
  }

  .id_utilisateur-avatar {
    width: 35px;
    height: 35px;
    object-fit: cover;
    border-radius: 50%;
  }
  .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--light-gray);
      position: relative;
    }
    
    .page-header h1 {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--text-light);
      position: relative;
      padding-left: 1rem;
    }
    
    .page-header h1::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0.25rem;
      bottom: 0.25rem;
      width: 4px;
      background: var(--primary);
      border-radius: 2px;
    }
    
    .search-container {
      position: relative;
      width: 300px;
      transition: width var(--transition-normal);
    }
    
    .search-container:focus-within {
      width: 320px;
    }
    
    .search-container i {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--primary-color);
      opacity: 0.7;
      transition: all var(--transition-normal);
      font-size: 14px;
      z-index: 2;
    }
    
    .search-container:focus-within i {
      opacity: 1;
      transform: translateY(-50%) scale(1.1);
    }
    
    .search-container input {
      width: 100%;
      padding: 12px 16px 12px 40px;
      border-radius: 12px;
      border: 2px solid transparent;
      background-color: var(--dark-bg-lighter);
      color: var(--text-light);
      font-size: 14px;
      transition: all var(--transition-normal);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    .search-container input::placeholder {
      color: var(--text-light);
      opacity: 0.5;
    }
    
    .search-container input:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px var(--glow-color), 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .header-actions {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-top: 1.5rem;
      margin-bottom: 1.5rem;
    }
    
    .notification-btn {
      position: relative;
      background: none;
      border: none;
      width: 42px;
      height: 42px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: var(--text-light);
      background-color: var(--dark-bg-lighter);
      transition: all var(--transition-normal);
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }
    
    .notification-btn:hover {
      background-color: var(--dark-bg-lightest);
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    
    .notification-btn:active {
      transform: translateY(0);
    }
    
    .notification-btn i {
      font-size: 16px;
      color: var(--text-light);
      transition: color var(--transition-normal);
    }
    
    .notification-btn:hover i {
      color: var(--primary-color);
    }
    
    .notification-badge {
      position: absolute;
      top: -4px;
      right: -4px;
      background-color: var(--primary-color);
      color: var(--text-dark);
      font-size: 10px;
      font-weight: 700;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid var(--dark-bg-lighter);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .new-consultation-btn {
      background-color: var(--primary-hover);
      color: var(--text-dark);
      border: none;
      padding: 0.75rem 1.25rem;
      border-radius: 12px;
      font-weight: 600;
      font-size: 0.9rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      transition: all var(--transition-bounce);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    .new-consultation-btn:hover {
      
      transform: translateY(-2px);
      box-shadow: 0 4px 12px var(--glow-color);
    }
    
    .new-consultation-btn:active {
      transform: translateY(0);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    .new-consultation-btn i {
      font-size: 14px;
    }
    
    .filters {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin-bottom: 2rem;
    }
    
    .filters select {
      flex: 1;
      min-width: 200px;
      padding: 0.75rem 1rem;
      border-radius: 10px;
      border: 1px solid var(--light-gray);
      background-color: var(--dark-bg-lighter);
      color: var(--text-light);
      font-size: 0.9rem;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23f7d84e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 1rem center;
      padding-right: 2.5rem;
      cursor: pointer;
      transition: all var(--transition-normal);
    }
    
    .filters select:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px var(--glow-color);
    }
    
    .filters select:hover {
      border-color: var(--primary-color);
      background-color: var(--dark-bg-lightest);
    }
    
  
</style>

<body>
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
                        <li><a href="index.php" >Accueil</a></li>
                        <li><a href="#services">Evenement</a></li>
                        <li><a href="indexyessine.php">Articles</a></li>
                        <li><a href="consultationList.php" class="active">Consultations</a></li>
                        <?php if (!isset($_SESSION['user_id'])) { ?>
                            <li><a href="login.php"  class="btn btn-primary">Connecter</a></li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>


   <div  class="container2">

    <header class="main-header">
      
    </header>

    <div class="content-wrapper">
      <div class="page-header">
      <h1>Les Consultations</h1>
      <div class="search-container">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Rechercher une consultation...">
      </div>
    </div>
    
    <div class="header-actions">
      <button class="notification-btn">
        <i class="fas fa-bell"></i>
        <span class="notification-badge">3</span>
      </button>
      <button class="new-consultation-btn" onclick="document.getElementById('new-consultation-modaltakoua').style.display='block'">
        <i class="fas fa-plus"></i> Nouvelle consultation
      </button>
    </div>
    
    <div class="filters">
      <select id="status-filter">
        <option value="all">Tous les statuts</option>
        <option value="pending">En attente</option>
        <option value="in-progress">En cours</option>
        <option value="cancelled">Annulée</option>
        <option value="completed">Terminée</option>
      </select>
      <select id="type-filter">
        <option value="all">Tous les types</option>
        <option value="financing">Financement</option>
        <option value="legal">Juridique</option>
        <option value="marketing">Marketing</option>
        <option value="technical">Technique</option>
      </select>
      <select id="date-filter">
        <option value="all">Toutes les dates</option>
        <option value="today">Aujourd'hui</option>
        <option value="week">Cette semaine</option>
        <option value="month">Ce mois</option>
        <option value="custom">Personnalisé</option>
      </select>
    </div>
      <div class="consultations-list">
        <?php foreach ($liste as $consultation): ?>
          <div class="proposition-card" data-id="<?= intval($consultation['id_consultation']); ?>">
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
              <p style="color: yellow;"><?= htmlspecialchars($consultation['description']); ?></p>
            </div>
            <div class="card-footer">
              

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

    <!-- Confirmation modaltakoua -->
    <div id="confirmation-modaltakoua">
      <div class="modaltakoua-content">
        <div class="modaltakoua-header">
          <h2>Confirmation</h2>
        </div>
        <div class="modaltakoua-body">
          <p>Êtes-vous sûr de vouloir terminer cette consultation ?</p>
          <p>Le statut sera changé à "Complété".</p>
          <input type="hidden" id="consultation-id-to-complete" value="">
        </div>
        
          <button type="button" class="cancel-btn">Annuler</button>
          <button type="button" class="confirm-btn">Confirmer</button>
        
      </div>
    </div>
    <!-- Details modaltakoua -->
    <div id="details-modaltakoua" class="modaltakoua" style="display:none;">
      <div class="modaltakoua-content" id="details-content">
        <div class="modaltakoua-header">

          <!-- Ajoutez ceci dans votre modaltakoua de détails -->
          <div class="modaltakoua-header-text">Détails de la consultation</div>
          <span class="close-modaltakoua" style="cursor:pointer;">&times;</span>
        </div>
        <div class="modaltakoua-body">
          <!-- Consultation details will be loaded here -->
          <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Chargement...</span>
          </div>
        </div>
      </div>
    </div>














    <!-- modaltakoua Ajouter Consultation -->
    <div id="new-consultation-modaltakoua" class="modaltakoua" style="display:none; z-index: 99999; height: 100%; ">
      <div class="modaltakoua-content">
        <div class="modaltakoua-header">
          <h2>Nouvelle consultation</h2>
          <button class="close-modaltakoua" onclick="document.getElementById('new-consultation-modaltakoua').style.display='none'">&times;</button>
        </div>
        <div class="modaltakoua-body">
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
              
              <input type="hidden" id="consultation-id_utilisateur" name="id_utilisateur" value="<?= $_SESSION['user_id']?>" required>
            </div>
            <!-- Statut field (hidden and always pending) -->
            <input type="hidden" name="statut" value="pending">

            
              <button class="secondary-btn" type="button" onclick="document.getElementById('new-consultation-modaltakoua').style.display='none'">Annuler</button>
              <button class="primary-btn" type="submit">Soumettre</button>
            
          </form>
        </div>
      </div>
    </div>
 </div>

  <!-- Notification Panel -->
  <div class="notification-panel">
    <div class="notification-header">
      <h3>Notifications</h3>
      <button class="mark-all-read">Tout marquer comme lu</button>
    </div>
    <div class="notification-list">
      <!-- Notifications will be loaded here -->
    </div>
  </div>

  <!-- Overlay -->
  <div class="overlay"></div>



  <script>

    document.addEventListener("DOMContentLoaded", () => {
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

      console.log("DOM fully loaded")

      // Elements
      const detailsmodaltakoua = document.getElementById("details-modaltakoua")
      const detailsContent = document.getElementById("details-content")
      const closeButtons = document.querySelectorAll(".close-modaltakoua")
      const notificationBtn = document.querySelector(".notification-btn")
      const notificationPanel = document.querySelector(".notification-panel")
      const notificationList = document.querySelector(".notification-list")
      const markAllReadBtn = document.querySelector(".mark-all-read")
      const overlay = document.querySelector(".overlay")
      let currentConsultationId = null


      // Function to open details modaltakoua
      function openDetailsmodaltakoua(consultationId) {
        console.log("Opening details modaltakoua for consultation ID:", consultationId)

        // Store the current consultation ID
        currentConsultationId = consultationId

        // Get the modaltakoua body for loading content
        const modaltakouaBody = detailsContent.querySelector(".modaltakoua-body")
        if (!modaltakouaBody) {
          console.error("modaltakoua body not found")
          return
        }

        modaltakouaBody.innerHTML =
          '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i><span>Chargement...</span></div>'

        // Show the modaltakoua
        detailsmodaltakoua.style.display = "flex"
        detailsmodaltakoua.classList.add("modaltakoua-active")

        // Fetch consultation details
        fetch("./reponseList.php?id_consultation=" + consultationId)
          .then((response) => {
            console.log("Response status:", response.status)
            if (!response.ok) throw new Error("Erreur réseau: " + response.status)
            return response.text()
          })
          .then((data) => {
            console.log("Data received, length:", data.length)
            modaltakouaBody.innerHTML = data

            // Check if the consultation is completed
            const statusElement = modaltakouaBody.querySelector(".consultation-status")
            const isCompleted = statusElement && statusElement.textContent.trim().toLowerCase() === "completed"

            // Create action buttons container
            const actionButtons = document.createElement("div")
            actionButtons.className = "modaltakoua-actions"

            // Create fermer button
            const fermerBtn = document.createElement("button")
            fermerBtn.className = "fermer-btn"
            fermerBtn.innerHTML = '<i class="fas fa-times-circle"></i> Fermer'
            fermerBtn.addEventListener("click", (e) => {
              e.preventDefault()
              closeDetailsmodaltakoua()
            })

            // Only add evaluation section and complete button if the consultation is NOT completed
            if (!isCompleted) {
              // Add evaluation section for non-completed consultations
              const evaluationSection = document.createElement("div")
              evaluationSection.className = "evaluation-section"
              evaluationSection.innerHTML = `
                        <h3>Évaluation de la consultation</h3>
                        <div class="evaluation-stars">
                            <button type="button" class="star-btn">☆</button>
                            <button type="button" class="star-btn">☆</button>
                            <button type="button" class="star-btn">☆</button>
                            <button type="button" class="star-btn">☆</button>
                            <button type="button" class="star-btn">☆</button>
                        </div>
                        <div class="evaluation-comment">
                            <textarea placeholder="Commentaires (optionnel)"></textarea>
                        </div>
                    `
              modaltakouaBody.appendChild(evaluationSection)

              // Add complete button only for non-completed consultations
              const completeBtn = document.createElement("button")
              completeBtn.className = "complete-btn"
              completeBtn.innerHTML = '<i class="fas fa-check-circle"></i> Terminer la consultation'
              completeBtn.addEventListener("click", () => {
                // Set the consultation ID in the confirmation modaltakoua
                document.getElementById("consultation-id-to-complete").value = currentConsultationId

                // Show the confirmation modaltakoua
                document.getElementById("confirmation-modaltakoua").classList.add("active")
              })
              actionButtons.appendChild(completeBtn)
            }

            // Add fermer button to action container
            actionButtons.appendChild(fermerBtn)

            // Add action buttons to modaltakoua body
            modaltakouaBody.appendChild(actionButtons)

            // Initialize star rating functionality if not completed
            if (!isCompleted) {
              initStarRating()
            }

            // Apply styles to consultation types
            applyConsultationTypeStyles()
          })
          .catch((error) => {
            console.error("Error fetching consultation details:", error)
            modaltakouaBody.innerHTML =
              '<p class="error-message">Erreur lors du chargement des détails: ' + error.message + "</p>"
          })
      }

      // Function to apply styles to consultation types
      function applyConsultationTypeStyles() {
        document.querySelectorAll(".consultation-type").forEach((element) => {
          const typeText = element.textContent.trim().toLowerCase()

          if (typeText.includes("financing") || typeText.includes("financement")) {
            element.style.backgroundColor = "rgb(207, 97, 125)"
            element.style.color = "black"
          } else if (typeText.includes("legal") || typeText.includes("juridique")) {
            element.style.backgroundColor = "rgb(150, 100, 202)"
            element.style.color = "black"
          } else if (typeText.includes("marketing")) {
            element.style.backgroundColor = "rgb(226, 178, 47)"
            element.style.color = "black"
          } else if (typeText.includes("technical") || typeText.includes("technique")) {
            element.style.backgroundColor = "rgb(115, 185, 223)"
            element.style.color = "black"
          }
        })
      }

      // Function to initialize star rating
      function initStarRating() {
        const starButtons = document.querySelectorAll(".star-btn")
        let currentRating = 0

        starButtons.forEach((button, index) => {
          button.addEventListener("click", () => {
            currentRating = index + 1

            // Update star display
            starButtons.forEach((btn, idx) => {
              if (idx < currentRating) {
                btn.classList.add("active")
                btn.innerHTML = "★" // Filled star
              } else {
                btn.classList.remove("active")
                btn.innerHTML = "☆" // Empty star
              }
            })
          })
        })
      }

      // Function to close details modaltakoua
      function closeDetailsmodaltakoua() {
        console.log("Closing details modaltakoua")
        detailsmodaltakoua.classList.remove("modaltakoua-active")
        detailsmodaltakoua.style.display = "none"
      }

      // Function to toggle notification panel
      function toggleNotificationPanel() {
        console.log("Toggling notification panel")
        notificationPanel.classList.toggle("active")
        overlay.classList.toggle("active")

        if (notificationPanel.classList.contains("active")) {
          loadNotifications()
        }
      }

      // Function to load notifications
      function loadNotifications() {
        console.log("Loading notifications")
        notificationList.innerHTML = '<div class="notification-loading">Chargement des notifications...</div>'

        fetch("../../api/get-notifications.php")
          .then((response) => {
            console.log("Notification response status:", response.status)
            if (!response.ok) throw new Error("Erreur HTTP: " + response.status)
            return response.json()
          })
          .then((data) => {
            console.log("Notification data received:", data)

            // Update badge
            const notificationBadge = document.querySelector(".notification-badge")
            if (notificationBadge) {
              notificationBadge.textContent = data.nonLues || 0
              notificationBadge.style.display = data.nonLues > 0 ? "flex" : "none"
            }

            // Display notifications
            if (!Array.isArray(data.notifications) || data.notifications.length === 0) {
              notificationList.innerHTML = '<div class="notification-empty">Aucune notification</div>'
              return
            }

            notificationList.innerHTML = ""

            data.notifications.forEach((notif) => {
              const item = document.createElement("div")
              item.className = "notification-item"
              if (notif.lu == 0) item.classList.add("unread")

              // Store consultation ID as data attribute
              const consultationId = notif.id_consultation
              item.setAttribute("data-consultation-id", consultationId)

              let iconClass = "fas fa-bell"
              if (notif.type === "reponse") {
                iconClass = "fas fa-comment-dots"
              } else if (notif.type === "assignation") {
                iconClass = "fas fa-user-plus"
              } else if (notif.type === "statut") {
                iconClass = "fas fa-check-circle"
              }

              item.innerHTML = `
                        <div class="notification-icon">
                            <i class="${iconClass}"></i>
                        </div>
                        <div class="notification-content">
                            <p>${notif.message}</p>
                            <span class="notification-time">${notif.date_relative || "Récemment"}</span>
                        </div>
                    `

              // Add click event directly to this notification item
              item.addEventListener("click", () => {
                console.log("Notification clicked, consultation ID:", consultationId)
                // Close notification panel
                notificationPanel.classList.remove("active")
                overlay.classList.remove("active")
                // Open details modaltakoua
                openDetailsmodaltakoua(consultationId)
              })

              notificationList.appendChild(item)
            })
          })
          .catch((error) => {
            console.error("Error loading notifications:", error)
            notificationList.innerHTML = `<div class="notification-error">Erreur lors du chargement: ${error.message}</div>`
          })
      }

      // Function to mark all notifications as read
      function markAllNotificationsAsRead() {
        console.log("Marking all notifications as read")

        fetch("../../api/marquer-notifications-lues.php")
          .then((response) => {
            if (!response.ok) throw new Error("Erreur HTTP: " + response.status)
            return response.json()
          })
          .then((data) => {
            if (data.success) {
              document.querySelectorAll(".notification-item.unread").forEach((n) => n.classList.remove("unread"))
              const notificationBadge = document.querySelector(".notification-badge")
              if (notificationBadge) {
                notificationBadge.textContent = "0"
                notificationBadge.style.display = "none"
              }
            }
          })
          .catch((error) => console.error("Error marking notifications as read:", error))
      }

      // Function to check for new notifications
      function checkNotifications() {
        console.log("Checking for new notifications")

        fetch("../../api/get-notification-count.php")
          .then((response) => {
            if (!response.ok) throw new Error("Erreur HTTP: " + response.status)
            return response.json()
          })
          .then((data) => {
            const notificationBadge = document.querySelector(".notification-badge")
            if (notificationBadge) {
              notificationBadge.textContent = data.count || 0
              notificationBadge.style.display = data.count > 0 ? "flex" : "none"
            }
          })
          .catch((error) => console.error("Error checking notifications:", error))
      }

      // Function to save evaluation and update consultation status
      function saveEvaluationAndComplete(consultationId) {
        console.log("Saving evaluation and completing consultation ID:", consultationId)

        // Get the rating from active stars
        const activeStars = document.querySelectorAll(".star-btn.active")
        const rating = activeStars.length

        // Get the comment from textarea
        const commentTextarea = document.querySelector(".evaluation-comment textarea")
        const comment = commentTextarea ? commentTextarea.value.trim() : ""

        // Create form data
        const formData = new FormData()
        formData.append("consultation_id", consultationId)
        formData.append("rating", rating)
        formData.append("comment", comment)

        // Send AJAX request to save evaluation
        fetch("save-evaluation.php", {
            method: "POST",
            body: formData,
          })
          .then((response) => {
            if (!response.ok) throw new Error("Erreur réseau: " + response.status)
            return response.json()
          })
          .then((data) => {
            console.log("Evaluation save response:", data)

            if (data.success) {
              // Fermer les modaltakouas
              const confirmationmodaltakoua = document.getElementById("confirmation-modaltakoua")
              if (confirmationmodaltakoua && confirmationmodaltakoua.classList.contains("active")) {
                confirmationmodaltakoua.classList.remove("active")
              }

              const detailsmodaltakoua = document.getElementById("details-modaltakoua")
              if (detailsmodaltakoua && detailsmodaltakoua.classList.contains("modaltakoua-active")) {
                detailsmodaltakoua.classList.remove("modaltakoua-active")
                detailsmodaltakoua.style.display = "none"
              }

              // Mettre à jour le statut dans la liste si la carte existe
              const card = document.querySelector(`.consultation-card[data-id="${consultationId}"]`)
              if (card) {
                const statusBadge = card.querySelector(".consultation-status")
                if (statusBadge) {
                  // Supprimer toutes les classes de statut
                  statusBadge.classList.remove("pending", "in-progress", "cancelled")
                  // Ajouter la classe completed
                  statusBadge.classList.add("completed")
                  // Mettre à jour le texte
                  statusBadge.textContent = "completed"
                }
              }

              // Show success notification
              showNotification("Consultation évaluée et complétée avec succès!")

              // Reload the page to reflect changes
              setTimeout(() => {
                window.location.href = "consultationList.php"
              }, 1500)
            } else {
              showNotification("Erreur: " + (data.message || "Impossible d'enregistrer l'évaluation."), "error")
            }
          })
          .catch((error) => {
            console.error("Error saving evaluation:", error)
            showNotification("Erreur: " + error.message, "error")
          })
      }

      // Function to update consultation status without evaluation
      function updateConsultationStatus(consultationId) {
        console.log("Updating consultation status to completed for ID:", consultationId)

        // Check if there's an evaluation section
        const evaluationSection = document.querySelector(".evaluation-section")

        if (evaluationSection) {
          // If there's an evaluation section, save the evaluation and complete
          saveEvaluationAndComplete(consultationId)
        } else {
          // If there's no evaluation section, just update the status
          // Create form data
          const formData = new FormData()
          formData.append("action", "complete_consultation")
          formData.append("consultation_id", consultationId)

          // Send AJAX request to update status
          fetch("consultationList.php", {
              method: "POST",
              body: formData,
            })
            .then((response) => {
              if (!response.ok) throw new Error("Erreur réseau: " + response.status)
              return response.json()
            })
            .then((data) => {
              console.log("Status update response:", data)

              if (data.success) {
                // Fermer les modaltakouas
                const confirmationmodaltakoua = document.getElementById("confirmation-modaltakoua")
                if (confirmationmodaltakoua && confirmationmodaltakoua.classList.contains("active")) {
                  confirmationmodaltakoua.classList.remove("active")
                }

                const detailsmodaltakoua = document.getElementById("details-modaltakoua")
                if (detailsmodaltakoua && detailsmodaltakoua.classList.contains("modaltakoua-active")) {
                  detailsmodaltakoua.classList.remove("modaltakoua-active")
                  detailsmodaltakoua.style.display = "none"
                }

                // Mettre à jour le statut dans la liste si la carte existe
                const card = document.querySelector(`.consultation-card[data-id="${consultationId}"]`)
                if (card) {
                  const statusBadge = card.querySelector(".consultation-status")
                  if (statusBadge) {
                    // Supprimer toutes les classes de statut
                    statusBadge.classList.remove("pending", "in-progress", "cancelled")
                    // Ajouter la classe completed
                    statusBadge.classList.add("completed")
                    // Mettre à jour le texte
                    statusBadge.textContent = "completed"
                  }
                }

                // Show success notification
                showNotification("Consultation complétée avec succès!")
                window.location.href = "consultationList.php"
              } else {
                showNotification("Erreur: " + (data.message || "Impossible de mettre à jour le statut."), "error")
              }
            })
            .catch((error) => {
              window.location.href = "consultationList.php"
              console.error("Error updating status:", error)
              showNotification("Erreur: " + error.message, "error")
            })
        }
      }

      // Function to show notification
      function showNotification(message, type = "success") {
        // Create notification element
        const notification = document.createElement("div")
        notification.className = "success-notification"

        let icon = '<i class="fas fa-check-circle"></i>'
        if (type === "error") {
          notification.style.borderLeft = "4px solid #ff6b6b"
          icon = '<i class="fas fa-exclamation-circle"></i>'
        }

        notification.innerHTML = `
            <div class="notification-content">
                ${icon}
                <p>${message}</p>
            </div>
        `

        // Add styles for notification
        notification.style.position = "fixed"
        notification.style.bottom = "20px"
        notification.style.right = "20px"
        notification.style.backgroundColor = "#24252b"
        notification.style.color = "white"
        notification.style.padding = "15px 20px"
        notification.style.borderRadius = "10px"
        notification.style.boxShadow = "0 5px 15px rgba(0,0,0,0.3)"
        notification.style.zIndex = "9999"
        notification.style.display = "flex"
        notification.style.alignItems = "center"
        notification.style.gap = "10px"
        notification.style.transform = "translateY(100px)"
        notification.style.opacity = "0"
        notification.style.transition = "all 0.3s ease"

        // Add notification to document
        document.body.appendChild(notification)

        // Animate notification entry
        setTimeout(() => {
          notification.style.transform = "translateY(0)"
          notification.style.opacity = "1"
        }, 10)

        // Remove notification after 5 seconds
        setTimeout(() => {
          notification.style.transform = "translateY(100px)"
          notification.style.opacity = "0"
          setTimeout(() => {
            document.body.removeChild(notification)
          }, 300)
        }, 5000)
      }

      // Add event listeners

      // View details buttons
      document.querySelectorAll(".view-details-btn").forEach((btn) => {
        btn.addEventListener("click", function() {
          const consultationId = this.getAttribute("data-id")
          if (consultationId) {
            openDetailsmodaltakoua(consultationId)
          }
        })
      })

      // Close buttons in details modaltakoua
      closeButtons.forEach((button) => {
        button.addEventListener("click", function() {
          const modaltakoua = this.closest(".modaltakoua")
          if (modaltakoua === detailsmodaltakoua) {
            closeDetailsmodaltakoua()
          }
        })
      })

      // Notification button
      if (notificationBtn) {
        notificationBtn.addEventListener("click", toggleNotificationPanel)
      }

      // Mark all read button
      if (markAllReadBtn) {
        markAllReadBtn.addEventListener("click", markAllNotificationsAsRead)
      }

      // Close details modaltakoua when clicking outside
      detailsmodaltakoua.addEventListener("click", function(e) {
        if (e.target === this) {
          closeDetailsmodaltakoua()
        }
      })

      // Close notification panel when clicking overlay
      if (overlay) {
        overlay.addEventListener("click", () => {
          notificationPanel.classList.remove("active")
          overlay.classList.remove("active")
        })
      }

      // Confirmation modaltakoua buttons
      const confirmBtn = document.querySelector("#confirmation-modaltakoua .confirm-btn")
      const cancelBtn = document.querySelector("#confirmation-modaltakoua .cancel-btn")

      if (confirmBtn) {
        confirmBtn.addEventListener("click", () => {
          // Get the consultation ID from the hidden input
          const consultationId = document.getElementById("consultation-id-to-complete").value

          if (consultationId) {
            // Update the consultation status
            updateConsultationStatus(consultationId)
          } else {
            console.error("No consultation ID found for completion")
            showNotification("Erreur: ID de consultation non trouvé", "error")
          }
        })
      }

      if (cancelBtn) {
        cancelBtn.addEventListener("click", () => {
          // Close only the confirmation window
          document.getElementById("confirmation-modaltakoua").classList.remove("active")
        })
      }

      // Close with Escape key
      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
          if (detailsmodaltakoua.classList.contains("modaltakoua-active")) {
            closeDetailsmodaltakoua()
          }
          if (document.getElementById("new-consultation-modaltakoua").style.display === "block") {
            document.getElementById("new-consultation-modaltakoua").style.display = "none"
          }
          if (notificationPanel.classList.contains("active")) {
            notificationPanel.classList.remove("active")
            overlay.classList.remove("active")
          }
          if (document.getElementById("confirmation-modaltakoua").classList.contains("active")) {
            document.getElementById("confirmation-modaltakoua").classList.remove("active")
          }
        }
      })

      // Apply type-specific styles to consultation types in the list
      document.querySelectorAll(".consultation-type").forEach((element) => {
        const typeText = element.textContent.trim().toLowerCase()

        if (typeText.includes("financing") || typeText.includes("financement")) {
          element.style.backgroundColor = "rgb(207, 97, 125)"
          element.style.color = "black"
        } else if (typeText.includes("legal") || typeText.includes("juridique")) {
          element.style.backgroundColor = "rgb(150, 100, 202)"
          element.style.color = "black"
        } else if (typeText.includes("marketing")) {
          element.style.backgroundColor = "rgb(226, 178, 47)"
          element.style.color = "black"
        } else if (typeText.includes("technical") || typeText.includes("technique")) {
          element.style.backgroundColor = "rgb(115, 185, 223)"
          element.style.color = "black"
        }
      })

      // Check for notifications on page load
      checkNotifications()

      // Check for new notifications every 30 seconds
      setInterval(checkNotifications, 30000)
    })
  </script>
  <script src="assets/consultation-filters.js"></script>
  <script src="assets/search-highlight.js"></script>

</body>

</html>