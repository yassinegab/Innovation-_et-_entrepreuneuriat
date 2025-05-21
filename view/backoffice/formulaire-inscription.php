
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Inscriptions - STELLIFEROUS</title>
    <link rel="stylesheet" href="styleeya.css">
    <link rel="stylesheet" href="theme-customeya.css">
    <link rel="stylesheet" href="admineya.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/back.css">
   

  
        
    
</head>
<body >
    <style>
        .container2234 {
            max-width: 1200px;
            
            margin-left: 300px;
        }
         :root {
            --primary: rgb(29, 30, 35);
            --primary-light: rgb(39, 40, 45);
            --secondary: rgb(227, 196, 58);
            --secondary-light: rgba(227, 196, 58, 0.1);
            --secondary-hover: rgb(237, 206, 68);
            --white: #ffffff;
            --white-50: rgba(255, 255, 255, 0.5);
            --white-20: rgba(255, 255, 255, 0.2);
            --white-10: rgba(255, 255, 255, 0.1);
            --white-05: rgba(255, 255, 255, 0.05);
            --dark-gray: rgb(32, 33, 38);
            --dark-gray-light: rgb(42, 43, 48);
            --danger: #e74c3c;
            --danger-light: rgba(231, 76, 60, 0.1);
            --danger-hover: #c0392b;
            --success: #2ecc71;
            --success-light: rgba(46, 204, 113, 0.1);
            --success-hover: #27ae60;
            --info: #3498db;
            --info-light: rgba(52, 152, 219, 0.1);
            --border-radius-sm: 6px;
            --border-radius-md: 10px;
            --border-radius-lg: 16px;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.15);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.25);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--primary);
            color: var(--white);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-container {
            background-color: var(--primary-light);
            border-radius: var(--border-radius-md);
            padding: 30px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--white-10);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary) 0%, var(--secondary-hover) 100%);
        }

        .form-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--white);
            position: relative;
            padding-bottom: 12px;
        }

        .form-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--secondary);
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--white-50);
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            background-color: var(--dark-gray);
            border: 1px solid var(--white-20);
            color: var(--white);
            padding: 12px 15px;
            border-radius: var(--border-radius-sm);
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group input::placeholder {
            color: var(--white-50);
            opacity: 0.7;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 2px var(--secondary-light);
        }

        .form-group input:focus + label,
        .form-group select:focus + label {
            color: var(--secondary);
        }

        .form-group input:focus::placeholder {
            opacity: 0.5;
        }

        /* Custom select styling */
        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
        }

        .form-group select option {
            background-color: var(--dark-gray);
            color: var(--white);
            padding: 10px;
        }

        /* Required field indicator */
        .form-group label::after {
            content: '';
            margin-left: 4px;
        }

        .form-group label[for="nom"]::after,
        .form-group label[for="email"]::after,
        .form-group label[for="telephone"]::after,
        .form-group label[for="id_evenement"]::after {
            content: ' *';
            color: var(--secondary);
        }

        /* Form actions */
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            outline: none;
            min-width: 140px;
        }

        .btn-primary {
            background-color: var(--secondary);
            color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--secondary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(227, 196, 58, 0.3);
        }

        .btn-secondary {
            background-color: transparent;
            border: 1px solid var(--white-20);
            color: var(--white);
        }

        .btn-secondary:hover {
            border-color: var(--white);
            background-color: var(--white-05);
            transform: translateY(-2px);
        }

        /* Error message styling */
        .error-message {
            display: none;
            color: var(--danger);
            background-color: var(--danger-light);
            padding: 12px 15px;
            border-radius: var(--border-radius-sm);
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 3px solid var(--danger);
        }

        /* Success message styling */
        .success-message {
            display: none;
            color: var(--success);
            background-color: var(--success-light);
            padding: 12px 15px;
            border-radius: var(--border-radius-sm);
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 3px solid var(--success);
        }

        /* Input focus effect */
        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 2px var(--secondary-light);
        }

        /* Input validation styling */
        .form-group input:invalid:not(:placeholder-shown),
        .form-group select:invalid:not(:placeholder-shown) {
            border-color: var(--danger);
        }

        .form-group input:valid:not(:placeholder-shown),
        .form-group select:valid:not(:placeholder-shown) {
            border-color: var(--success);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }

        /* Animation for form elements */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            animation: fadeIn 0.3s ease forwards;
            animation-delay: calc(var(--animation-order) * 0.1s);
            opacity: 0;
        }

        .form-group:nth-child(1) { --animation-order: 1; }
        .form-group:nth-child(2) { --animation-order: 2; }
        .form-group:nth-child(3) { --animation-order: 3; }
        .form-group:nth-child(4) { --animation-order: 4; }
        .form-group:nth-child(5) { --animation-order: 5; }
        .form-group:nth-child(6) { --animation-order: 6; }

        /* Form field icon indicators */
        .field-icon {
            position: absolute;
            right: 15px;
            top: 40px;
            color: var(--white-50);
            transition: all 0.3s ease;
        }

        /* Tooltip for form fields */
        .tooltip {
            position: relative;
            display: inline-block;
            margin-left: 5px;
        }

        .tooltip .tooltip-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            background-color: var(--white-20);
            color: var(--white);
            border-radius: 50%;
            font-size: 12px;
            font-weight: bold;
            cursor: help;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: var(--dark-gray-light);
            color: var(--white);
            text-align: center;
            border-radius: var(--border-radius-sm);
            padding: 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--white-10);
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>
   
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
                <a href="#" class="sidebar-menu-link has-submenu"  onclick="toggleSubmenu(event)">
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
        <div class="container2234">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="menu-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <div class="breadcrumb">
                        <span>Administration</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                        <span>Gestion des Inscriptions</span>
                    </div>
                </div>
                
            </header>
            
            <!-- Main Content -->
            
                <h1>Gestion des Inscriptions</h1>
               
              
                
                <!-- Formulaire d'ajout d'inscription -->
                <div class="form-container">
                    <h2 class="form-title">Ajouter une inscription</h2>
                    <form id="inscription-form" action="formulaire-inscription.php" method="POST" onsubmit="return validateForm()">
                        <div class="form-group">
                            <label for="nom">Nom complet *</label>
                            <input type="text" id="nom" name="nom" placeholder="Entrez le nom complet" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" placeholder="Entrez l'adresse email" required>
                        </div>
                        <div class="form-group">
                            <label for="telephone">Téléphone *</label>
                            <input type="tel" id="telephone" name="telephone" placeholder="Entrez le numéro de téléphone" required>
                        </div>
                        <div class="form-group">
                            <label for="entreprise">Entreprise / Organisation</label>
                            <input type="text" id="entreprise" name="entreprise" placeholder="Entrez le nom de l'entreprise">
                        </div>
                        <div class="form-group">
                            <label for="fonction">Fonction</label>
                            <input type="text" id="fonction" name="fonction" placeholder="Entrez la fonction">
                        </div>
                        <div class="form-group">
                            <label for="id_evenement">Événement *</label>
                            <select id="id_evenement" name="id_evenement" required>
                                <option value="">Sélectionnez un événement</option>
                                <?php foreach ($listeEvenements as $evenement): ?>
                                <option value="<?= $evenement['id_ev']; ?>"><?= htmlspecialchars($evenement['nom']); ?> - <?= htmlspecialchars($evenement['date']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-actions">
                            <button type="reset" class="btn btn-secondary">Réinitialiser</button>
                            <button type="submit" name="ajout" class="btn btn-primary">Ajouter l'inscription</button>
                        </div>
                    </form>
                </div>
                
                <!-- Tableau des inscriptions -->
                <div class="section">
                    <h2 class="section-title">Liste des inscriptions</h2>
                    <div class="table-container">
                        <table id="inscriptions-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Entreprise</th>
                                    <th>Fonction</th>
                                    <th>Événement</th>
                                    <th>Date d'inscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="inscriptions-list">
                                <?php if (empty($listeInscriptions)): ?>
                                <tr class="no-inscriptions">
                                    <td colspan="9">Aucune inscription n'a été ajoutée</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($listeInscriptions as $inscription): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($inscription['id_inscription']); ?></td>
                                        <td><?= htmlspecialchars($inscription['nom']); ?></td>
                                        <td><?= htmlspecialchars($inscription['email']); ?></td>
                                        <td><?= htmlspecialchars($inscription['telephone']); ?></td>
                                        <td><?= htmlspecialchars($inscription['entreprise'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($inscription['fonction'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($inscription['nom_evenement']); ?></td>
                                        <td><?= htmlspecialchars($inscription['date_inscription']); ?></td>
                                        <td class="action-buttons">
                                            <button class="action-btn edit-btn" onclick="openEditModal(<?= $inscription['id_inscription']; ?>)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                                Modifier
                                            </button>
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?');">
                                                <input type="hidden" name="id_inscription" value="<?= $inscription['id_inscription']; ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" class="action-btn delete-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            
        
    </div>
    
    <!-- Modal d'édition -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
            <h2 class="modal-title">Modifier l'inscription</h2>
            <form id="edit-form" action="formulaire-inscription.php" method="POST">
                <input type="hidden" id="edit-id" name="id_inscription">
                <input type="hidden" name="action" value="update">
                
                <div class="form-group">
                    <label for="edit-nom">Nom complet *</label>
                    <input type="text" id="edit-nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="edit-email">Email *</label>
                    <input type="email" id="edit-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="edit-telephone">Téléphone *</label>
                    <input type="tel" id="edit-telephone" name="telephone" required>
                </div>
                <div class="form-group">
                    <label for="edit-entreprise">Entreprise / Organisation</label>
                    <input type="text" id="edit-entreprise" name="entreprise">
                </div>
                <div class="form-group">
                    <label for="edit-fonction">Fonction</label>
                    <input type="text" id="edit-fonction" name="fonction">
                </div>
                <div class="form-group">
                    <label for="edit-evenement">Événement *</label>
                    <select id="edit-evenement" name="id_evenement" required>
                        <?php foreach ($listeEvenements as $evenement): ?>
                        <option value="<?= $evenement['id_ev']; ?>"><?= htmlspecialchars($evenement['nom']); ?> - <?= htmlspecialchars($evenement['date']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
    <script src="assets/back.js"></script>
    <script>
        // Fonction pour valider le formulaire
        function validateForm() {
            const nom = document.getElementById('nom').value;
            const email = document.getElementById('email').value;
            const telephone = document.getElementById('telephone').value;
            const evenement = document.getElementById('id_evenement').value;
            
            if (!nom || !email || !telephone || !evenement) {
                alert('Veuillez remplir tous les champs obligatoires.');
                return false;
            }
            
            // Validation de l'email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Veuillez entrer une adresse email valide.');
                return false;
            }
            
            return true;
        }
        
        // Fonctions pour le modal d'édition
        function openEditModal(id) {
            // Récupérer les données de l'inscription
            const row = document.querySelector(`tr td:first-child:contains('${id}')`).parentNode;
            const nom = row.cells[1].textContent;
            const email = row.cells[2].textContent;
            const telephone = row.cells[3].textContent;
            const entreprise = row.cells[4].textContent !== '-' ? row.cells[4].textContent : '';
            const fonction = row.cells[5].textContent !== '-' ? row.cells[5].textContent : '';
            const evenement = row.cells[6].textContent;
            
            // Remplir le formulaire d'édition
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nom').value = nom;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-telephone').value = telephone;
            document.getElementById('edit-entreprise').value = entreprise;
            document.getElementById('edit-fonction').value = fonction;
            
            // Trouver l'ID de l'événement correspondant au nom
            const evenementSelect = document.getElementById('edit-evenement');
            for (let i = 0; i < evenementSelect.options.length; i++) {
                if (evenementSelect.options[i].textContent.includes(evenement)) {
                    evenementSelect.selectedIndex = i;
                    break;
                }
            }
            
            // Afficher le modal
            document.getElementById('edit-modal').style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('edit-modal').style.display = 'none';
        }
        
        // Fermer le modal si l'utilisateur clique en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('edit-modal');
            if (event.target === modal) {
                closeEditModal();
            }
        }
        
        // Afficher le message de succès si nécessaire
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        document.getElementById('success-message').style.display = 'block';
        setTimeout(function() {
            document.getElementById('success-message').style.display = 'none';
        }, 3000);
        <?php endif; ?>
        
        // Polyfill pour la méthode :contains
        if (!Element.prototype.matches) {
            Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
        }
        
        if (!Element.prototype.closest) {
            Element.prototype.closest = function(s) {
                var el = this;
                do {
                    if (el.matches(s)) return el;
                    el = el.parentElement || el.parentNode;
                } while (el !== null && el.nodeType === 1);
                return null;
            };
        }
        
        // Extension pour sélectionner les éléments contenant un texte spécifique
        document.querySelectorAll = function(selector) {
            if (selector.includes(':contains')) {
                const parts = selector.split(':contains');
                const baseSelector = parts[0];
                const textToMatch = parts[1].replace(/['"()]/g, '');
                
                const elements = document.querySelectorAll(baseSelector);
                const result = [];
                
                for (let i = 0; i < elements.length; i++) {
                    if (elements[i].textContent.includes(textToMatch)) {
                        result.push(elements[i]);
                    }
                }
                
                return result;
            } else {
                return document.querySelectorAll(selector);
            }
        };
    </script>
</body>
</html>