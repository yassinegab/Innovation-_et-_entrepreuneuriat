<?php
require_once('../../controller/collaborationscontroller.php');
require_once('../../controller/propcontroller.php');
$prop= new propcontroller();
$collab=new Collaborationscontroller();
$list=$collab->afficher();
if(isset($_GET['idsupp']))
{
    $collab->suppcollab($_GET['idsupp']);
    header("Location: collaborations.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Collaborations</title>
    <link rel="stylesheet" href="assets/collaborations.css">
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
                    <img src="assets/398582438_2615487838627907_5927319269485945046_n.jpg" alt="Photo de profil">
                </div>
                <div class="mobile-menu-btn">☰</div>
            </nav>
        </div>
    </header>

    <section class="page-header">
        <div class="container">
            <h1 class="page-title fade-in">Mes Collaborations</h1>
            <p class="page-description fade-in delay-1">Gérez vos collaborations actuelles et passées. Vous pouvez voir les détails, envoyer des messages ou annuler des collaborations si nécessaire.</p>
        </div>
    </section>

    <main class="main-content container">
        <section class="section fade-in delay-2">
            <div class="actions-bar">
                <div class="search-box">
                    <input type="text" placeholder="Rechercher un collaborateur...">
                </div>
                <div class="filter-dropdown">
                    <button class="filter-btn">Filtrer</button>
                </div>
                <button class="btn btn-primary"><span class="btn-icon">➕</span>Nouvelle collaboration</button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Collaborateur</th>
                            <th>Rôle</th>
                            <th>proposition titre</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($list as $colllaborationn)
                    {$user=$prop->afficheruser($colllaborationn['collaborateur_id']);
                    $propo=$prop->afficherById($colllaborationn['id_proposition']);
                    ?>
                        <tr>
                            <td>
                                <div class="collaborator">
                                    <div class="collaborator-info">
                                        <div class="collaborator-name"><?= $user['prenomuser'].' '.$user['nomuser'] ?></div>
                                        <div class="collaborator-email"><?= $user['mail'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?=  $colllaborationn['role']  ?></td>
                            <td><?=  $propo['Titre']  ?></td>
                            <td><?=  $colllaborationn['date_debut']  ?></td>
                            <td><?=  $colllaborationn['date_fin']  ?></td>
                            <td><?=  $colllaborationn['type_collaboration']  ?></td>
                            <?php 
                                if($colllaborationn['statut']=='Actif'){
                            ?>
                            <td><span class="status-badge status-active">Actif</span></td><?php }?>
                            <?php 
                                if($colllaborationn['statut']=='En attente'){
                            ?>
                            <td><span class="status-badge status-pending">En attente</span></td><?php }?>
                            <?php 
                                if($colllaborationn['statut']=='Terminé'){
                            ?>
                            <td><span class="status-badge status-completed">Terminé</span></td><?php }?>
                            <?php 
                                if($colllaborationn['statut']=='Annulé'){
                            ?>
                            <td><span class="status-badge status-cancelled">Annulé</span></td><?php }?>

                            <td>
                                <div class="actions">
                                    
                                    <a  class="action-btn message" title="Envoyer un message" >✉️</a>
                                    <a href="collaborations.php?idsupp=<?=$colllaborationn['id_collaboration']?>" class="action-btn cancel" title="Annuler la collaboration" onclick="openCancelModal('<?= $user['prenomuser'].' '.$user['nomuser'] ?>')">❌</a>
                                </div>
                            </td>
                        </tr>
                    <?php }?>    
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <div class="page-item">«</div>
                <div class="page-item active">1</div>
                <div class="page-item">2</div>
                <div class="page-item">3</div>
                <div class="page-item">»</div>
            </div>
        </section>
    </main>

    <!-- Modal pour annuler une collaboration -->
    <div class="modal-overlay" id="cancelModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Annuler la collaboration</h3>
                <button class="modal-close" onclick="closeModal('cancelModal')">✕</button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir annuler votre collaboration avec <span id="cancelCollaboratorName">ce collaborateur</span> ?</p>
                <div class="form-group">
                    <label for="cancelReason" class="form-label">Raison de l'annulation</label>
                    <textarea id="cancelReason" class="form-control" placeholder="Veuillez indiquer la raison de l'annulation..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="closeModal('cancelModal')">Annuler</button>
                <button class="btn btn-primary" style="background-color: var(--color-danger);">Confirmer l'annulation</button>
            </div>
        </div>
    </div>

    <!-- Modal pour envoyer un message -->
    <div class="modal-overlay" id="messageModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Envoyer un message</h3>
                <button class="modal-close" onclick="closeModal('messageModal')">✕</button>
            </div>
            <div class="modal-body">
                <p>Envoyer un message à <span id="messageCollaboratorName">ce collaborateur</span></p>
                <div class="form-group">
                    <label for="messageSubject" class="form-label">Sujet</label>
                    <input type="text" id="messageSubject" class="form-control" placeholder="Sujet du message">
                </div>
                <div class="form-group">
                    <label for="messageContent" class="form-label">Message</label>
                    <textarea id="messageContent" class="form-control" placeholder="��crivez votre message ici..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="closeModal('messageModal')">Annuler</button>
                <button class="btn btn-primary" style="background-color: var(--color-info);">Envoyer</button>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="copyright">
                &copy; 2025 ProfilApp. Tous droits réservés.
            </div>
        </div>
    </footer>
    <script src="assets/collaborations.js"></script>
</body>
</html>    

