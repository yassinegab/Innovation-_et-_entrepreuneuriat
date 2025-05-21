<?php
// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the user controller
include_once(__DIR__ .'/../../controller/user_controller.php');
$userC = new User_controller();

// Default values
$searchName = isset($_GET['userSearch']) ? trim($_GET['userSearch']) : "";
$status_filter = isset($_GET['status']) ? $_GET['status'] : "all";
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : "name_asc";
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$results_per_page = 12;

// Calculate offset for pagination
$offset = ($page - 1) * $results_per_page;

// Get users based on search criteria
$users = $userC->getAllUsers($searchName);

// Apply status filter if not 'all'
if ($status_filter !== "all") {
    $active_value = ($status_filter === "active") ? 1 : 0;
    $users = array_filter($users, function($user) use ($active_value) {
        return $user['active_account'] == $active_value;
    });
}

// Apply sorting
usort($users, function($a, $b) use ($sort_by) {
    switch ($sort_by) {
        case 'name_asc':
            return strcasecmp($a['name'], $b['name']);
        case 'name_desc':
            return strcasecmp($b['name'], $a['name']);
        case 'recent':
            $date_a = !empty($a['last_active']) ? strtotime($a['last_active']) : 0;
            $date_b = !empty($b['last_active']) ? strtotime($b['last_active']) : 0;
            return $date_b - $date_a;
        default:
            return strcasecmp($a['name'], $b['name']);
    }
});

// Get total results for pagination
$total_results = count($users);
$total_pages = ceil($total_results / $results_per_page);

// Get current page results
$current_page_users = array_slice($users, $offset, $results_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Base styles and variables */
        :root {
            /* Couleurs personnalisées */
            --color-primary: #e3c43a;
            --color-primary-light: #f0d66a;
            --color-primary-dark: #c9ab2a;
            --color-primary-transparent: rgba(227, 196, 58, 0.15);
            --color-primary-glow: rgba(227, 196, 58, 0.4);
            
            --color-secondary-1: rgb(39, 40, 45);
            --color-secondary-2: rgb(24, 25, 30);
            --color-secondary-3: rgb(18, 19, 22);
            
            --color-white: #ffffff;
            --color-white-muted: rgba(255, 255, 255, 0.7);
            
            /* Couleurs additionnelles */
            --text-color: #E0E0E0;
            --text-light: #999999;
            --text-muted: #777777;
            
            --border-color: #333333;
            --border-light: #444444;
            
            --success-color: #28A745;
            --success-dark: #1e7e34;
            --success-light: rgba(40, 167, 69, 0.15);
            
            --danger-color: #DC3545;
            --danger-dark: #bd2130;
            --danger-light: rgba(220, 53, 69, 0.15);
            
            --shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            --hover-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --border-radius-lg: 16px;
            
            --transition-speed: 0.3s;
            --transition-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: var(--color-secondary-2);
            color: var(--text-color);
            min-height: 100vh;
            line-height: 1.6;
            padding: 0;
            position: relative;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(50, 50, 60, 0.2) 0%, transparent 80%),
                radial-gradient(circle at 90% 80%, rgba(50, 50, 60, 0.2) 0%, transparent 80%);
        }

        /* Subtle background pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23333333' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: -1;
            opacity: 0.05;
        }

        /* Container styling */
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Glowing accent */
        .glow-accent {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: var(--color-primary);
            filter: blur(150px);
            opacity: 0.1;
            z-index: -1;
        }

        .glow-accent-1 {
            top: -100px;
            left: -100px;
        }

        .glow-accent-2 {
            bottom: 20%;
            right: -100px;
        }

        /* Header styling */
        .page-header {
            margin-bottom: 50px;
            position: relative;
            animation: fadeInDown 0.8s var(--transition-function);
            padding-bottom: 20px;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent, 
                var(--color-primary-transparent), 
                var(--color-primary), 
                var(--color-primary-transparent), 
                transparent);
        }

        .page-header h1 {
            color: var(--color-primary);
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .page-header h1::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--color-primary);
            border-radius: 2px;
            box-shadow: 0 2px 10px var(--color-primary-glow);
        }

        .page-header p {
            color: var(--text-light);
            font-size: 1.1rem;
            max-width: 600px;
            font-weight: 300;
        }

        /* Search and filter section */
        .search-container {
            background-color: var(--color-secondary-1);
            border-radius: var(--border-radius-lg);
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            position: relative;
            animation: fadeIn 0.8s var(--transition-function);
            overflow: hidden;
        }

        .search-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--color-primary), transparent);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
        }

        .search-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at top left, var(--color-primary-transparent) 0%, transparent 60%);
            opacity: 0.1;
            z-index: 0;
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        .search-input-container {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 16px 20px 16px 50px;
            background-color: var(--color-secondary-2);
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            color: var(--text-color);
            font-size: 1rem;
            transition: all var(--transition-speed) var(--transition-function);
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
            font-family: 'Poppins', sans-serif;
        }

        .search-input:focus {
            border-color: var(--color-primary);
            outline: none;
            box-shadow: 0 0 0 3px var(--color-primary-transparent), inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-primary);
            font-size: 1.1rem;
            transition: all var(--transition-speed) var(--transition-function);
        }

        .search-input:focus + .search-icon {
            color: var(--color-primary-light);
        }

        .filter-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-select {
            padding: 16px 20px;
            background-color: var(--color-secondary-2);
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            color: var(--text-color);
            font-size: 1rem;
            min-width: 180px;
            cursor: pointer;
            transition: all var(--transition-speed) var(--transition-function);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23e3c43a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 20px;
            padding-right: 45px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
            font-family: 'Poppins', sans-serif;
        }

        .filter-select:focus {
            border-color: var(--color-primary);
            outline: none;
            box-shadow: 0 0 0 3px var(--color-primary-transparent), inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .search-button {
            background-color: var(--color-primary);
            color: var(--color-secondary-3);
            border: none;
            padding: 16px 30px;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all var(--transition-speed) var(--transition-function);
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }

        .search-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.6s var(--transition-function);
        }

        .search-button:hover {
            background-color: var(--color-primary-light);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .search-button:hover::before {
            left: 100%;
        }

        .search-button:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Results info */
        .results-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            color: var(--text-light);
            font-size: 0.95rem;
            padding: 0 5px;
            animation: fadeIn 0.8s var(--transition-function);
            animation-delay: 0.2s;
            animation-fill-mode: both;
        }

        .view-toggle {
            display: flex;
            gap: 10px;
            background-color: var(--color-secondary-1);
            padding: 5px;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .view-toggle button {
            background-color: transparent;
            border: none;
            color: var(--text-light);
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            transition: all var(--transition-speed) var(--transition-function);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .view-toggle button.active {
            background-color: var(--color-primary);
            color: var(--color-secondary-3);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .view-toggle button:hover:not(.active) {
            background-color: var(--border-color);
            color: var(--text-color);
        }

        /* User grid */
        .user-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .user-card {
            background-color: var(--color-secondary-1);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            transition: all var(--transition-speed) var(--transition-function);
            position: relative;
            height: 100%;
            transform-origin: center bottom;
        }

        .user-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--color-primary-transparent) 0%, transparent 50%);
            opacity: 0;
            transition: opacity var(--transition-speed) var(--transition-function);
            z-index: 1;
            pointer-events: none;
        }

        .user-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--hover-shadow);
            border-color: var(--color-primary);
        }

        .user-card:hover::before {
            opacity: 0.1;
        }

        .user-card-header {
            padding: 30px 20px;
            text-align: center;
            position: relative;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2), transparent);
        }

        .user-avatar-container {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 0 auto 20px;
        }

        .user-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--color-primary);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2), 0 0 0 5px var(--color-primary-transparent);
            transition: all var(--transition-speed) var(--transition-function);
            position: relative;
            z-index: 2;
        }

        .avatar-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--color-primary);
            filter: blur(15px);
            opacity: 0.2;
            z-index: 1;
            transition: all var(--transition-speed) var(--transition-function);
        }

        .user-card:hover .user-avatar {
            transform: scale(1.05);
            border-color: var(--color-primary-light);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3), 0 0 0 5px var(--color-primary-transparent);
        }

        .user-card:hover .avatar-glow {
            opacity: 0.3;
            width: 110%;
            height: 110%;
        }

        .user-status {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 2;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-active {
            background-color: var(--success-light);
            color: var(--success-color);
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .status-active::before {
            content: '';
            display: block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--success-color);
            box-shadow: 0 0 0 2px var(--success-light), 0 0 10px var(--success-color);
            animation: pulse 2s infinite;
        }

        .status-inactive {
            background-color: var(--danger-light);
            color: var(--danger-color);
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .status-inactive::before {
            content: '';
            display: block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--danger-color);
        }

        .user-name {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--color-primary);
            position: relative;
            display: inline-block;
        }

        .user-username {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-bottom: 5px;
            font-weight: 300;
        }

        .user-card-body {
            padding: 25px 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            height: calc(100% - 230px); /* Adjust based on header height */
            position: relative;
            z-index: 2;
        }

        .user-email {
            color: var(--text-color);
            font-size: 0.95rem;
            margin-bottom: 20px;
            word-break: break-all;
            background-color: rgba(0, 0, 0, 0.1);
            padding: 10px 15px;
            border-radius: var(--border-radius);
            display: inline-block;
            border: 1px solid var(--border-color);
            transition: all var(--transition-speed) var(--transition-function);
        }

        .user-card:hover .user-email {
            border-color: var(--color-primary-transparent);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .user-last-active {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 25px;
            font-style: italic;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .user-last-active i {
            color: var(--color-primary);
            font-size: 0.9rem;
        }

        .user-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: auto;
            margin-bottom: 30px;
        }

        .action-button {
            flex: 1;
            padding: 12px 15px;
            border-radius: var(--border-radius);
            border: none;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all var(--transition-speed) var(--transition-function);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.6s var(--transition-function);
        }

        .action-button:hover::before {
            left: 100%;
        }

        .message-btn {
            background-color: var(--color-primary);
            color: var(--color-secondary-3);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .message-btn:hover {
            background-color: var(--color-primary-light);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .message-btn:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .profile-btn {
            background-color: var(--color-secondary-2);
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .profile-btn:hover {
            background-color: var(--border-color);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-btn:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* List view */
        .user-list {
            display: none; /* Hidden by default, shown via JS */
            margin-bottom: 50px;
        }

        .user-list-item {
            display: flex;
            align-items: center;
            background-color: var(--color-secondary-1);
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
            transition: all var(--transition-speed) var(--transition-function);
            position: relative;
            overflow: hidden;
        }

        .user-list-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background-color: var(--color-primary);
            opacity: 0;
            transition: all var(--transition-speed) var(--transition-function);
        }

        .user-list-item:hover {
            border-color: var(--color-primary);
            transform: translateY(-5px) translateX(5px);
            box-shadow: var(--card-shadow);
        }

        .user-list-item:hover::before {
            opacity: 1;
        }

        .list-user-avatar-container {
            position: relative;
            margin-right: 25px;
        }

        .list-user-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--color-primary);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all var(--transition-speed) var(--transition-function);
            position: relative;
            z-index: 2;
        }

        .list-avatar-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--color-primary);
            filter: blur(15px);
            opacity: 0;
            z-index: 1;
            transition: all var(--transition-speed) var(--transition-function);
        }

        .user-list-item:hover .list-user-avatar {
            transform: scale(1.05);
            border-color: var(--color-primary-light);
        }

        .user-list-item:hover .list-avatar-glow {
            opacity: 0.2;
            width: 110%;
            height: 110%;
        }

        .list-user-info {
            flex: 1;
        }

        .list-user-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--color-primary);
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .list-user-email {
            color: var(--text-light);
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .list-user-email i {
            color: var(--color-primary);
            font-size: 0.9rem;
        }

        .list-user-status {
            margin: 0 25px;
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .list-user-actions {
            display: flex;
            gap: 10px;
            margin-left: 15px;
        }

        /* Empty state */
        .empty-state {
            background-color: var(--color-secondary-1);
            border-radius: var(--border-radius-lg);
            padding: 80px 40px;
            text-align: center;
            margin-bottom: 50px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            animation: fadeIn 0.8s var(--transition-function);
            position: relative;
            overflow: hidden;
        }

        .empty-state::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, var(--color-primary-transparent) 0%, transparent 70%);
            opacity: 0.1;
            z-index: 0;
        }

        .empty-state-icon {
            font-size: 5rem;
            color: var(--color-primary);
            margin-bottom: 30px;
            opacity: 0.8;
            position: relative;
            z-index: 1;
            text-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .empty-state h3 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: var(--color-primary);
            position: relative;
            z-index: 1;
        }

        .empty-state p {
            color: var(--text-light);
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }

        .empty-state .search-button {
            margin: 0 auto;
            display: inline-flex;
            position: relative;
            z-index: 1;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 50px;
            margin-bottom: 50px;
            animation: fadeIn 0.8s var(--transition-function);
            animation-delay: 0.4s;
            animation-fill-mode: both;
        }

        .pagination-button {
            background-color: var(--color-secondary-1);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            min-width: 45px;
            height: 45px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition-speed) var(--transition-function);
            text-decoration: none;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .pagination-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: all 0.6s var(--transition-function);
        }

        .pagination-button:hover:not(.disabled)::before {
            left: 100%;
        }

        .pagination-button:hover:not(.disabled) {
            border-color: var(--color-primary);
            background-color: var(--color-primary-transparent);
            color: var(--color-primary);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .pagination-button.active {
            background-color: var(--color-primary);
            color: var(--color-secondary-3);
            border-color: var(--color-primary);
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .pagination-button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .user-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
            
            .page-header h1 {
                font-size: 2.4rem;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px 15px;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            .filter-container {
                flex-direction: column;
            }
            
            .user-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }
            
            .list-user-actions {
                flex-direction: column;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .search-container {
                padding: 25px 20px;
            }
        }

        @media (max-width: 576px) {
            .user-grid {
                grid-template-columns: 1fr;
            }
            
            .user-list-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 25px 20px;
            }
            
            .list-user-avatar-container {
                margin-bottom: 20px;
                margin-right: 0;
            }
            
            .list-user-status {
                margin: 15px 0;
            }
            
            .list-user-actions {
                margin-top: 20px;
                width: 100%;
                margin-left: 0;
            }
            
            .action-button {
                width: 100%;
            }
            
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }

        .user-card {
            animation: fadeIn 0.6s var(--transition-function) forwards;
            animation-fill-mode: both;
        }

        .user-card:nth-child(2) { animation-delay: 0.1s; }
        .user-card:nth-child(3) { animation-delay: 0.15s; }
        .user-card:nth-child(4) { animation-delay: 0.2s; }
        .user-card:nth-child(5) { animation-delay: 0.25s; }
        .user-card:nth-child(6) { animation-delay: 0.3s; }
        .user-card:nth-child(7) { animation-delay: 0.35s; }
        .user-card:nth-child(8) { animation-delay: 0.4s; }
        .user-card:nth-child(9) { animation-delay: 0.45s; }
        .user-card:nth-child(10) { animation-delay: 0.5s; }
        .user-card:nth-child(11) { animation-delay: 0.55s; }
        .user-card:nth-child(12) { animation-delay: 0.6s; }

        .user-list-item {
            animation: fadeIn 0.6s var(--transition-function) forwards;
            animation-fill-mode: both;
        }

        .user-list-item:nth-child(2) { animation-delay: 0.1s; }
        .user-list-item:nth-child(3) { animation-delay: 0.15s; }
        .user-list-item:nth-child(4) { animation-delay: 0.2s; }
        .user-list-item:nth-child(5) { animation-delay: 0.25s; }
        .user-list-item:nth-child(6) { animation-delay: 0.3s; }
        .user-list-item:nth-child(7) { animation-delay: 0.35s; }
        .user-list-item:nth-child(8) { animation-delay: 0.4s; }
        .user-list-item:nth-child(9) { animation-delay: 0.45s; }
        .user-list-item:nth-child(10) { animation-delay: 0.5s; }
        .user-list-item:nth-child(11) { animation-delay: 0.55s; }
        .user-list-item:nth-child(12) { animation-delay: 0.6s; }
    </style>
</head>
<body>
    <!-- Glowing accents -->
    <div class="glow-accent glow-accent-1"></div>
    <div class="glow-accent glow-accent-2"></div>

    <div class="container">
        <div class="page-header">
            <h1>Find Users</h1>
            <p>Search for users to chat with or view their profiles</p>
        </div>

        <div class="search-container">
            <form method="GET" action="showUsers.php" class="search-form">
                <div class="search-input-container">
                    <input type="text" name="userSearch" class="search-input" placeholder="Search by name, username or email" value="<?php echo htmlspecialchars($searchName); ?>">
                    <i class="fas fa-search search-icon"></i>
                </div>
                
                <div class="filter-container">
                    <select name="status" class="filter-select">
                        <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Users</option>
                        <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active Users</option>
                        <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive Users</option>
                    </select>
                    
                    <select name="sort" class="filter-select">
                        <option value="name_asc" <?php echo $sort_by === 'name_asc' ? 'selected' : ''; ?>>Name (A-Z)</option>
                        <option value="name_desc" <?php echo $sort_by === 'name_desc' ? 'selected' : ''; ?>>Name (Z-A)</option>
                        <option value="recent" <?php echo $sort_by === 'recent' ? 'selected' : ''; ?>>Recently Active</option>
                    </select>
                </div>
                
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>

        <div class="results-info">
            <div>
                <?php if ($total_results > 0): ?>
                    Showing <?php echo $offset + 1; ?>-<?php echo min($offset + $results_per_page, $total_results); ?> of <?php echo $total_results; ?> users
                <?php else: ?>
                    No users found
                <?php endif; ?>
            </div>
            
            <div class="view-toggle">
                <button type="button" class="grid-view-btn active" title="Grid View">
                    <i class="fas fa-th"></i>
                </button>
                <button type="button" class="list-view-btn" title="List View">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <?php if (count($current_page_users) > 0): ?>
            <!-- Grid View (default) -->
            <div class="user-grid">
                <?php foreach ($current_page_users as $user): ?>
                    <div class="user-card">
                        <div class="user-card-header">
                            <span class="user-status <?php echo $user['active_account'] == 1 ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo $user['active_account'] == 1 ? 'Active' : 'Inactive'; ?>
                            </span>
                            <div class="user-avatar-container">
                                <div class="avatar-glow"></div>
                                <img src="<?php echo !empty($user['profile_image']) ? htmlspecialchars($user['profile_image']) : '../frontoffice/uploads/profiles/default-avatar.png'; ?>" alt="<?php echo htmlspecialchars($user['name']); ?>" class="user-avatar">
                            </div>
                            <h3 class="user-name"><?php echo htmlspecialchars($user['name']); ?></h3>
                            <div class="user-username">@<?php echo htmlspecialchars($user['lastName']); ?></div>
                        </div>
                        <div class="user-card-body">
                            <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
                            <div class="user-last-active">
                                <i class="fas fa-clock"></i>
                                <?php 
                                    if (!empty($user['last_active'])) {
                                        $last_active = new DateTime($user['last_active']);
                                        $now = new DateTime();
                                        $interval = $now->diff($last_active);
                                        
                                        if ($interval->days > 0) {
                                            echo "Last active " . $interval->days . " days ago";
                                        } elseif ($interval->h > 0) {
                                            echo "Last active " . $interval->h . " hours ago";
                                        } elseif ($interval->i > 0) {
                                            echo "Last active " . $interval->i . " minutes ago";
                                        } else {
                                            echo "Active now";
                                        }
                                    } else {
                                        echo "Never active";
                                    }
                                ?>
                            </div>
                            <div class="user-actions">
                                <a href="monprofil.php?id=<?php echo $user['id']; ?>" class="action-button profile-btn">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- List View (hidden by default, shown via JS) -->
            <div class="user-list" style="display: none;">
                <?php foreach ($current_page_users as $user): ?>
                    <div class="user-list-item">
                        <div class="list-user-avatar-container">
                            <div class="list-avatar-glow"></div>
                            <img src="<?php echo !empty($user['profile_image']) ? htmlspecialchars($user['profile_image']) : '../frontoffice/uploads/profiles/default-avatar.png'; ?>" alt="<?php echo htmlspecialchars($user['name']); ?>" class="list-user-avatar">
                        </div>
                        
                        <div class="list-user-info">
                            <div class="list-user-name">
                                <?php echo htmlspecialchars($user['name']); ?> <?php echo htmlspecialchars($user['lastName']); ?>
                            </div>
                            <div class="list-user-email">
                                <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?>
                            </div>
                        </div>
                        
                        <span class="list-user-status <?php echo $user['active_account'] == 1 ? 'status-active' : 'status-inactive'; ?>">
                            <?php echo $user['active_account'] == 1 ? 'Active' : 'Inactive'; ?>
                        </span>
                        
                        <div class="list-user-actions">
                            <a href="messages.php?user=<?php echo $user['id']; ?>" class="action-button message-btn">
                                <i class="fas fa-comment"></i> Message
                            </a>
                            <a href="profile.php?id=<?php echo $user['id']; ?>" class="action-button profile-btn">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <a href="?userSearch=<?php echo urlencode($searchName); ?>&status=<?php echo $status_filter; ?>&sort=<?php echo $sort_by; ?>&page=1" class="pagination-button <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                    
                    <a href="?userSearch=<?php echo urlencode($searchName); ?>&status=<?php echo $status_filter; ?>&sort=<?php echo $sort_by; ?>&page=<?php echo $page - 1; ?>" class="pagination-button <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <i class="fas fa-angle-left"></i>
                    </a>
                    
                    <?php
                    $start_page = max(1, $page - 2);
                    $end_page = min($start_page + 4, $total_pages);
                    
                    if ($end_page - $start_page < 4 && $start_page > 1) {
                        $start_page = max(1, $end_page - 4);
                    }
                    
                    for ($i = $start_page; $i <= $end_page; $i++): 
                    ?>
                        <a href="?userSearch=<?php echo urlencode($searchName); ?>&status=<?php echo $status_filter; ?>&sort=<?php echo $sort_by; ?>&page=<?php echo $i; ?>" class="pagination-button <?php echo $i == $page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <a href="?userSearch=<?php echo urlencode($searchName); ?>&status=<?php echo $status_filter; ?>&sort=<?php echo $sort_by; ?>&page=<?php echo $page + 1; ?>" class="pagination-button <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                        <i class="fas fa-angle-right"></i>
                    </a>
                    
                    <a href="?userSearch=<?php echo urlencode($searchName); ?>&status=<?php echo $status_filter; ?>&sort=<?php echo $sort_by; ?>&page=<?php echo $total_pages; ?>" class="pagination-button <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <!-- Empty state -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-users-slash"></i>
                </div>
                <h3>No users found</h3>
                <p>Try adjusting your search or filter criteria</p>
                <a href="showUsers.php" class="search-button">
                    <i class="fas fa-sync"></i> Reset Filters
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // View toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const gridViewBtn = document.querySelector('.grid-view-btn');
            const listViewBtn = document.querySelector('.list-view-btn');
            const userGrid = document.querySelector('.user-grid');
            const userList = document.querySelector('.user-list');
            
            gridViewBtn.addEventListener('click', function() {
                gridViewBtn.classList.add('active');
                listViewBtn.classList.remove('active');
                userGrid.style.display = 'grid';
                userList.style.display = 'none';
            });
            
            listViewBtn.addEventListener('click', function() {
                listViewBtn.classList.add('active');
                gridViewBtn.classList.remove('active');
                userList.style.display = 'block';
                userGrid.style.display = 'none';
            });
            
            // Subtle hover effect for cards
            const userCards = document.querySelectorAll('.user-card');
            userCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    setTimeout(() => {
                        this.style.zIndex = '10';
                    }, 200);
                });
                
                card.addEventListener('mouseleave', function() {
                    setTimeout(() => {
                        this.style.zIndex = '1';
                    }, 300);
                });
            });
        });
    </script>
</body>
</html>