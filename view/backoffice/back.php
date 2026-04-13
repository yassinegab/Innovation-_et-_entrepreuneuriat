<?php
require_once '../../controller//propcontroller.php';
require_once '../../controller/collaborationscontroller.php';
$collab=new Collaborationscontroller();
$list=$collab->afficher();
$prop= new propcontroller();
$liste=$prop->afficher();
$dataid=0;
if(isset($_GET['modifid']))
{
    
    $prop->modify2('Approuvé',$_GET['modifid']);
    header("Location: back.php");

}
if(isset($_GET['modifid2'])){ $prop->modify2('Refusé',$_GET['modifid2']);header("Location: back.php");}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice - Tableau de Gestion</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/back.css">
    
    
</head>
<body>
     <script>
    const items = <?= json_encode($liste, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
                        console.log("cbnnnn")
        console.log(items); 
    </script>
    <script>
        function toggleSubmenu(event) {
    
    event.preventDefault(); // Empêche le lien de naviguer
    const submenu = event.currentTarget.nextElementSibling; // Récupère le sous-menu
    submenu.classList.toggle('active'); // Bascule la classe active
}
    </script>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">B</div>
            <div class="sidebar-logo-text">Backoffice</div>
        </div>
        
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item active">
                <a href="#" class="sidebar-menu-link">
                    <span class="sidebar-menu-icon">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"></path>
                        </svg>
                    </span>
                    Tableau de bord
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="#" class="sidebar-menu-link has-submenu" onclick="toggleSubmenu(event)">
                    <span class="sidebar-menu-icon">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"></path>
                        </svg>
                    </span>
                    Listes
                </a>
                <ul class="submenu">
                    <li class="submenu-item">
                        <a href="back.php" class="submenu-link">
                            <i class="fas fa-handshake"></i> Collaborations
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="reponseListb.php" class="submenu-link">
                            <i class="fas fa-comments"></i> Consultations
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="formulaire-evenement.php" class="submenu-link">
                            <i class="fas fa-calendar-alt"></i> Événements
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="formulaire-inscription.php" class="submenu-link">
                            <i class="fas fa-user-plus"></i> Inscriptions
                        </a>
                    </li>
                    <li class="submenu-item">
                        <a href="adminyessine.php" class="submenu-link">
                            <i class="fas fa-newspaper"></i> Articles
                        </a>
                    </li>
                     <li class="submenu-item">
                        <a href="commentaire.php" class="submenu-link">
                            <i class="fas fa-newspaper"></i> Commentaires
                        </a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-menu-item">
                <a href="admin.php" class="sidebar-menu-link">
                    <span class="sidebar-menu-icon">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                        </svg>
                    </span>
                    Utilisateurs
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="#" class="sidebar-menu-link">
                    <span class="sidebar-menu-icon">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"></path>
                        </svg>
                    </span>
                    Rapports
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="#" class="sidebar-menu-link">
                    <span class="sidebar-menu-icon">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z"></path>
                        </svg>
                    </span>
                    Paramètres
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="../frontoffice/index.php" class="sidebar-menu-link">
                    <span class="sidebar-menu-icon">
                        
                    </span>
                    page d acceuil
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="top-bar">
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
                </svg>
            </button>
            <h1 class="page-title">Tableau de Gestion</h1>
            <div class="search-container">
                <span class="search-icon">
                    <svg class="icon icon-sm" viewBox="0 0 24 24">
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
                    </svg>
                </span>
                <input type="text" id="searchInput" placeholder="Rechercher...">
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Liste des demandes</h2>
                <div class="card-actions">
                    <button class="btn-icon">
                        <svg class="icon icon-sm" viewBox="0 0 24 24">
                            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"></path>
                        </svg>
                    </button>
                    <button class="btn-icon">
                        <svg class="icon icon-sm" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"></path>
                            <path d="M7 10h10v2H7z"></path>
                            <path d="M7 7h7v2H7z"></path>
                            <path d="M7 13h7v2H7z"></path>
                            <path d="M15 13h2v2h-2z"></path>
                            <path d="M15 7h2v2h-2z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Date Soumission</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Table data directly in HTML -->
                       
                        <?php
            foreach($liste as $proposition)
            {
            ?>
                        <tr data-id="<?= $dataid ;?>">
                            <td class="table-title"><?=$proposition['Titre'];?></td>
                            <td class="table-description"><?=$proposition['Description'];?></td>
                            <td><span class="table-type type-materiel"><?=$proposition['Type'];?></span></td>
                            <td class="table-date"><?=$proposition['Date_Soumission'];?></td>
                            <?php 
                            if($proposition['Statut']=='Refusé')echo '<td><span class="status status-rejected">Rejeté</span></td>';
                            if($proposition['Statut']=='En attente')echo '<td><span class="status status-pending">en attente</span></td>';
                            if($proposition['Statut']=='Approuvé')echo '<td><span class="status status-approved">Approuvé</span></td>';?>

                            
                            



                            <td class="actions">
                                <button class="btn btn-sm btn-view btn-icon-text" data-id="<?= $dataid ;?>">
                                    <svg class="icon icon-sm" viewBox="0 0 24 24">
                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"></path>
                                    </svg>
                                    Voir
                                </button>
                                <?php 
                           
                            if($proposition['Statut'] == 'En attente') {
                                echo '<a href="back.php?modifid='.$proposition['ID_Proposition'].'" class="btn btn-sm btn-approve btn-icon-text" data-id="' . $dataid . '">
                                        <svg class="icon icon-sm" viewBox="0 0 24 24">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path>
                                        </svg>
                                        Confirmer
                                      </a>
                                      <a href="back.php?modifid2='.$proposition['ID_Proposition'].'" class="btn btn-sm btn-reject btn-icon-text" data-id="' . $dataid . '">
                                        <svg class="icon icon-sm" viewBox="0 0 24 24">
                                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
                                        </svg>
                                        Rejeter
                                      </a>';
                            }
                            ?>
                            </td>
                            
                            

                            
                        </tr>
                        
                      <?php $dataid++; }?>  
                    </tbody>
                </table>
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
                           
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($list as $colllaborationn)
                    {
                    $propo=$prop->afficherById($colllaborationn['id_proposition']);
                    ?>
                        <tr>
                            
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

                            
                        </tr>
                    <?php }?>    
                    </tbody>
                </table>
            </div>               


            <div class="pagination">
                <div class="pagination-info">
                    Affichage de 1-5 sur 5 éléments
                </div>
                <div class="pagination-controls">
                    <button class="pagination-button disabled">
                        <svg class="icon icon-sm" viewBox="0 0 24 24">
                            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"></path>
                        </svg>
                    </button>
                    <button class="pagination-button active">1</button>
                    <button class="pagination-button disabled">
                        <svg class="icon icon-sm" viewBox="0 0 24 24">
                            <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal for viewing details -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Détails</h2>
                <button class="close-modal" id="closeModal">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Modal content will be populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-approve btn-icon-text" id="modalApprove">
                    <svg class="icon icon-sm" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path>
                    </svg>
                    Confirmer
                </button>
                <button class="btn btn-reject btn-icon-text" id="modalReject">
                    <svg class="icon icon-sm" viewBox="0 0 24 24">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
                    </svg>
                    Rejeter
                </button>
                <button class="btn btn-view" id="modalClose">Fermer</button>
            </div>
        </div>
    </div>

    
        <script src="assets/back.js"></script>
       
    
   
    <script>
document.addEventListener('DOMContentLoaded', function () {
    window.toggleSubmenu = function(event) {
        event.preventDefault(); // Empêche le lien de naviguer
        const submenu = event.currentTarget.nextElementSibling;
        submenu.classList.toggle('active');
    };
});
</script>

</body>
</html>