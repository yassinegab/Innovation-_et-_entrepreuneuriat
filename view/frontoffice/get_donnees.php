<?php
require_once('../../controller/propcontroller.php');
session_start();
$prop = new propcontroller();
if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo "Non autorisé";
    exit;
}
$listnotif = $prop->affichernotifById($_SESSION['id']);

$html = ""; // variable pour accumuler le HTML

foreach ($listnotif as $notif) {
    $user = $prop->afficheruser($_SESSION['id']);
    $propos = $prop->afficherById($notif['id_proposition']);

    $html .= '
    <div class="notif-card" data-id="1">
        <div class="notif-author">' . $user['nomuser'] . ' ' . $user['prenomuser'] . '</div>
        <div class="notif-date">' . $propos['Titre'] . '</div>
        <div class="notif-text">' . $propos['Type'] . '</div>
        <div class="notif-buttons">
            <a href="index.php?idprop=' . $propos['ID_Proposition'] . '&iddemande=' . $notif['id_demande'] . '" class="btn-approve" data-id="1">Accepter</a>
            <a href="index.php?suppdemande=' . $notif['id_demande'] . '" class="btn-decline" data-id="1">Refuser</a>
            <button id="showCollabBtn" class="action-btn btn_affmodal"
                title="Voir les détails"
                data-id="' . $notif['id_demande'] . '"
                data-id2="' . $notif['id_proposition'] . '"
                data-nameuser="' . $user['nomuser'] . ' ' . $user['prenomuser'] . '"
                data-titreprop="' . $propos['Titre'] . '"
                data-role="' . $notif['role'] . '"
                data-date1="' . $notif['date_debut'] . '"
                data-date2="' . $notif['date_fin'] . '"
                data-type="' . $notif['type'] . '">👁️</button>
        </div>
    </div>';
}

echo $html;
?>
