<?php
session_start();
// if (!isset($_SESSION['user_id']) || (!($_SESSION['user_id']) == 0)) {
//     header('Location: ../frontoffice/login.php');
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/back.css">
    
    <title>Administration des utilisateurs </title>

    <style type="text/css">
       :root {
    :
    --primary-color: #e3c43a; /* Couleur principale */
    --primary-hover: #c9ab2a; /* Jaune foncé (équivalent au hover) */
    --accent-color: #f0d66a; /* Jaune clair (accentuée) */
    
    --dark-color: rgb(24, 25, 30); /* Noir foncé (color-secondary-2) */
    --dark-secondary: rgb(39, 40, 45); /* Gris foncé (color-secondary-1) */
    --dark-tertiary: rgba(227, 196, 58, 0.15); /* Transparent */

    --text-primary: #ffffff; /* Blanc */
    --text-secondary: #aaaaaa; /* Inchangé, tu peux adapter si besoin */

    --success-color: #4cd137; /* Inchangé */
    --warning-color: #ffb142; /* Inchangé */
    --danger-color: #ff5252; /* Inchangé */

    --border-radius: 8px;
    --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

    --transition: all 0.3s ease; /* Basé sur transition-speed */


}


        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        main{
            background-color:var(--dark-secondary) ;
        }

        

        /* Header Styles */
        header {
            background-color: var(--dark-secondary);
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            border-bottom: 2px solid var(--primary-color);
        }

        header h1 {
            color: var(--primary-color);
            font-size: 24px;
            font-weight: 700;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
            margin: 0;
            flex-grow: 1;
        }

        button,
        .btn-back,
        .btn-stats {
            background-color: var(--dark-tertiary);
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 10px 15px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        button:hover,
        .btn-back:hover,
        .btn-stats:hover {
            background-color: var(--primary-color);
            color: var(--dark-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        }

        #addUserBtn {
            background-color: var(--primary-color);
            color: var(--dark-color);
        }

        #addUserBtn:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

       

        /* Search Container */
        .search-container {
            width: 100%;
            margin-bottom: 25px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: space-between;
            align-items: center;
            background-color: var(--dark-secondary);
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: 1px solid var(--dark-tertiary);
        }

        .search-container form {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-container label {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .search-container select,
        .search-container input[type="text"] {
            background-color: var(--dark-tertiary);
            border: 1px solid #444;
            color: var(--text-primary);
            padding: 10px 15px;
            border-radius: var(--border-radius);
            font-size: 14px;
            min-width: 200px;
            transition: var(--transition);
        }

        .search-container select:focus,
        .search-container input[type="text"]:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
        }

        .search-container button {
            min-width: 100px;
        }

        /* Table Styles */
        #usersTable {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 30px;
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        #usersTable thead {
            background-color: var(--dark-secondary);
        }

        #usersTable th {
            text-align: left;
            padding: 15px;
            font-weight: 600;
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        #usersTable tbody tr {
            background-color: var(--dark-tertiary);
            transition: var(--transition);
        }

        #usersTable tbody tr:nth-child(odd) {
            background-color: var(--dark-secondary);
        }

        #usersTable tbody tr:hover {
            background-color: rgba(255, 215, 0, 0.1);
        }

        #usersTable td {
            padding: 12px 15px;
            border-bottom: 1px solid #333;
            color: var(--text-primary);
        }

        #usersTable td button {
            background-color: transparent;
            border: none;
            color: var(--text-secondary);
            font-size: 16px;
            cursor: pointer;
            transition: var(--transition);
            padding: 5px;
            margin: 0 5px;
        }

        #usersTable td button:hover {
            color: var(--primary-color);
            transform: scale(1.2);
            background-color: transparent;
            box-shadow: none;
        }

        /* Form Inputs in Table */
        #usersTable input[type="text"],
        #usersTable input[type="email"],
        #usersTable input[type="password"],
        #usersTable select {
            width: 100%;
            padding: 8px 12px;
            background-color: var(--dark-color);
            border: 1px solid #444;
            border-radius: 4px;
            color: var(--text-primary);
            transition: var(--transition);
        }

        #usersTable input:focus,
        #usersTable select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
        }

        /* New User Row */
        #newUserRow {
            background-color: rgba(255, 215, 0, 0.05) !important;
            border-left: 3px solid var(--primary-color);
        }

        #newUserRow td {
            padding: 15px;
        }

        #newUserRow button {
            padding: 8px 12px;
            margin: 5px;
            background-color: var(--dark-tertiary);
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 4px;
        }

        #newUserRow button:hover {
            background-color: var(--primary-color);
            color: var(--dark-color);
        }

        #cancelBtn {
            background-color: transparent;
            border: 1px solid #666;
            color: #666;
        }

        #cancelBtn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: #999;
            color: #999;
        }

        /* Error Message */
        .error-message {
            color: var(--danger-color);
            font-size: 14px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        /* Footer */
        .footer {
            background-color: var(--dark-secondary);
            padding: 20px;
            text-align: center;
            margin-top: auto;
            border-top: 2px solid var(--primary-color);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-copyright p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-container {
                flex-direction: column;
                align-items: stretch;
            }

            .search-container form {
                width: 100%;
            }

            #usersTable {
                display: block;
                overflow-x: auto;
            }
        }

        /* Animation for buttons */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        #addUserBtn:hover {
            animation: pulse 1.5s infinite;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--dark-color);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--dark-tertiary);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* Focus styles for accessibility */
        *:focus-visible {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Status indicators */
        .status-active {
            display: inline-block;
            width: 10px;
            height: 10px;
            background-color: var(--success-color);
            border-radius: 50%;
            margin-right: 5px;
        }

        .status-inactive {
            display: inline-block;
            width: 10px;
            height: 10px;
            background-color: var(--danger-color);
            border-radius: 50%;
            margin-right: 5px;
        }

        /* Tooltip styles */
        [data-tooltip] {
            position: relative;
            cursor: help;
        }

        [data-tooltip]:before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: 8px 12px;
            background-color: var(--dark-color);
            color: var(--text-primary);
            border: 1px solid var(--primary-color);
            border-radius: 4px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 10;
        }

        [data-tooltip]:hover:before {
            opacity: 1;
            visibility: visible;
            bottom: calc(100% + 5px);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 6px;
        }

        .status-active {
            background-color: rgba(76, 209, 55, 0.15);
            color: var(--success-color);
        }

        .status-active::before {
            background-color: var(--success-color);
        }

        .status-inactive {
            background-color: rgba(255, 82, 82, 0.15);
            color: var(--danger-color);
        }

        .status-inactive::before {
            background-color: var(--danger-color);
        }

        .custom-button {
            background-color: var(--primary-color);
            color: var(--dark-color);
            border: none;
            padding: 12px 20px;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: var(--box-shadow);
        }

        .custom-button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        }

        admin-sidebar {
            width: 260px;
            background-color: rgb(29, 30, 35);
            border-right: 1px solid var(--color-border-primary);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--color-border-primary);
        }

        .sidebar-header h2 {
            margin: 0;
            font-size: 1.25rem;
            color: var(--color-text-primary);
        }

        .admin-badge {
            background-color: rgba(227, 196, 58, 0.15);
            color: rgb(227, 196, 58);
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
            font-weight: 600;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--color-text-secondary);
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-item a svg {
            margin-right: 0.75rem;
        }

        .nav-item a:hover {
            color: var(--color-text-primary);
            background-color: rgba(255, 255, 255, 0.05);
        }

        .nav-item.active a {
            color: rgb(227, 196, 58);
            background-color: rgba(227, 196, 58, 0.1);
        }

        .nav-item.active a::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background-color: rgb(227, 196, 58);
        }

        .notification-badge {
            background-color: rgb(227, 196, 58);
            color: rgb(29, 30, 35);
            font-size: 0.75rem;
            font-weight: 600;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
        }

        .sidebar-divider {
            height: 1px;
            background-color: var(--color-border-primary);
            margin: 1rem 1.5rem;
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--color-border-primary);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.75rem;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--color-border-primary);
            border-radius: var(--radius-md);
            color: var(--color-text-secondary);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .logout-btn svg {
            margin-right: 0.75rem;
        }

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--color-text-primary);
        }
    </style>
</head>

<body>
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
                        <a href="back.php" class="submenu-link" class="fas fa-handshake"> <i class="fas fa-handshake"></i>Collaborations</a>
                    </li>
                     <li class="submenu-item">
                        <a href="reponseListb.php" class="submenu-link" class="fas fa-handshake"> <i class="fas fa-handshake"></i>consultations</a>
                    </li>
                     <li class="submenu-item">
                        <a href="formulaire-evenement.php" class="submenu-link" class="fas fa-handshake"> <i class="fas fa-handshake"></i>evenement</a>
                    </li>
                     <li class="submenu-item">
                        <a href="formulaire-inscription.php" class="submenu-link" class="fas fa-handshake"> <i class="fas fa-handshake"></i>inscription</a>
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
   

    

     <main class="main-content">
        <div>
            <?php include_once(__DIR__ . '/statestique.php');
            ?>
        </div>

        <div class="search-container">
            <form method="GET" style="display: flex; gap: 10px;">
                <label for="sortField">Trier par:</label>
                <select name="sortField" id="sortField">
                    <option value="name">Nom</option>
                    <option value="lastName">Prénom</option>
                    <option value="birthdate">Date de naissance</option>
                    <option value="email">Email</option>
                    <option value="role">role</option>
                </select>
                <button type="submit" name="SORTbtn">Trier</button>
            </form>
            <------
                <form method="GET" style="display: flex; gap: 10px;">
                <input type="text" name="userSearch" id="userSearch" placeholder="Search users..." value="<?= isset($_POST['userSearch']) ? htmlspecialchars($_POST['userSearch']) : '' ?>">
                <button type="submit" name="searchBtn">SEARCH</button>
                </form>
        </div>


        <table id="usersTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Naissance</th>
                    <th>Role</th>
                    <th>active account</th>
                    <th>Actions</th>


                </tr>
            </thead>
            <tbody>
                <?php
                include_once(__DIR__ . '/../../controller/user_controller.php');
                $userC = new User_controller();
                $searchName = isset($_GET['userSearch']) ? trim($_GET['userSearch']) : "";
                $users = $userC->getAllUsers($searchName);
                if (isset($_GET['SORTbtn']) && isset($_GET['sortField'])) {
                    $field = $_GET['sortField'];

                    usort($users, function ($a, $b) use ($field) {
                        // If it's a birthdate, use date comparison
                        if ($field === 'birthdate') {
                            return strtotime($a[$field]) - strtotime($b[$field]);
                        } else if ($field === 'role') {
                            return strcmp($a['role'], $b['role']); // Assuming role_name exists
                        }

                        // Otherwise, sort alphabetically
                        else {
                            return strcmp($a[$field], $b[$field]);
                        }
                    });
                }

                foreach ($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['id']) ?></td>
                        <td><?= htmlspecialchars($u['name']) ?></td>
                        <td><?= htmlspecialchars($u['lastName']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['birthdate']) ?></td>
                        <td><?= htmlspecialchars($u['role']) ?></td>
                        <td>
                            <?php if ($u['active_account'] == 1): ?>
                                <span class="status-badge status-active">Active</span>
                            <?php else: ?>
                                <span class="status-badge status-inactive">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- Ici tu pourrais ajouter des liens Modifier / Supprimer -->
                            <button onclick="window.location.href='edit.php?id=<?= $u['id'] ?>'">✎</button>
                            <button onclick="confirmDelete(<?= $u['id'] ?>); window.location.href='delete.php?id=<?= $u['id'] ?>'">🗑</button>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <!-- Ligne d'ajout, cachée par défaut -->
                <input type="text" type="hidden" name="myInput" id="myInput" style="display: none;" />
                <div class="form-group">

                    <tr id="newUserRow">
                        <form method="POST" id="registerForm">
                            <p id="formError" class="error-message" style="color: red;"></p>
                            <td>—</td>
                            <td><input type="text" id="firstName" name="name" placeholder="name" required></td>
                            <td><input type="text" id="lastName" name="lastName" placeholder="last name" required></td>
                            <td><input type="email" id="email" name="email" placeholder="email" required></td>
                            <td><input type="password" id="password" name="password" placeholder="*******" required></td>

                            <td><select id="day" name="day" required>
                                    <option value="" disabled selected>Day</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="31">31</option>
                                </select></td>
                            <td><select id="month" name="month" required>
                                    <option value="" disabled selected>Month</option>
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select></td>
                            <td><select id="year" name="year" required>
                                    <option value="" disabled selected>Year</option>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
                                    <option value="2020">2020</option>
                                    <option value="2019">2019</option>
                                    <option value="2018">2018</option>
                                    <option value="2017">2017</option>
                                    <option value="2016">2016</option>
                                    <option value="2015">2015</option>
                                    <option value="2014">2014</option>
                                    <option value="2013">2013</option>
                                    <option value="2012">2012</option>
                                    <option value="2011">2011</option>
                                    <option value="2010">2010</option>
                                    <option value="2009">2009</option>
                                    <option value="2008">2008</option>
                                    <option value="2007">2007</option>
                                    <option value="2006">2006</option>
                                    <option value="2005">2005</option>
                                    <option value="2004">2004</option>
                                    <option value="2003">2003</option>
                                    <option value="2002">2002</option>
                                    <option value="2001">2001</option>
                                    <option value="2000">2000</option>
                                    <option value="1999">1999</option>
                                    <option value="1998">1998</option>
                                    <option value="1997">1997</option>
                                    <option value="1996">1996</option>
                                    <option value="1995">1995</option>
                                    <option value="1994">1994</option>
                                    <option value="1993">1993</option>
                                    <option value="1992">1992</option>
                                    <option value="1991">1991</option>
                                    <option value="1990">1990</option>
                                    <option value="1989">1989</option>
                                    <option value="1988">1988</option>
                                    <option value="1987">1987</option>
                                    <option value="1986">1986</option>
                                    <option value="1985">1985</option>
                                    <option value="1984">1984</option>
                                    <option value="1983">1983</option>
                                    <option value="1982">1982</option>
                                    <option value="1981">1981</option>
                                    <option value="1980">1980</option>
                                </select></td>
                            <td>
                                <button type="submit" id="addButton" name="addButton">Enregistrer</button>
                                <button type="button" id="cancelBtn">Annuler</button>
                            </td>
                </div>
                </form>
                </tr>
            </tbody>
        </table>
    </main>
     <script src="assets/back.js"></script>
    <script src="assets/admin.js"></script>
    <?php
    if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['addButton'])) {
        $name = $_POST['name'];
        $lastname = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $day = $_POST['day'];
        $month = $_POST['month'];
        $year = $_POST['year'];
        include_once(__DIR__ . '/../../controller/user_controller.php');
        $usr = new user(5, $name, $lastname, $day, $month, $year, $password, $email, 0, null);
        $userC = new user_controller();
        $userC->add_user2($usr);
        echo '<script>window.location.href = "' . $_SERVER['PHP_SELF'] . '";</script>';
    } else if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['deleteButton'])) {
        $idToDelete = $_POST['myInput'];
        echo '<h1>' . htmlspecialchars($idToDelete) . '</h1>';
        $userC->delete_user($idToDelete);
        echo '<script>window.location.href = "' . $_SERVER['PHP_SELF'] . '";</script>';
    }
    ?>
    

    <script src="../js/admin.js"></script>
</body>

</html>