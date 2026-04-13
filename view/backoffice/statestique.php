<?php

require_once(__DIR__ . "/../../controller/user_controller.php");

// Retrieve basic stats
$userController = new User_controller();
$totalUsers = $userController->getTotalUsers();
$activeUsers = $userController->getActiveUsers();
$pendingVerifications = $userController->getPendingVerifications();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Statistics Dashboard</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
    --primary-color: #FFD700; /* Yellow */
    --secondary-color: #FFC107; /* Amber */
    --accent-color: #FFEB3B; /* Light Yellow */
    --light-color: #f8f9fa;
    --dark-color: #121212; /* Near Black */
    --success-color: #4cd137;
    --warning-color: #ffb142;
    --danger-color: #ff5252;
    --card-bg: #1e1e1e; /* Dark Gray */
    --card-border: #333333;
    --text-primary: #ffffff;
    --text-secondary: #aaaaaa;
}



.dashboard {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.card {
    background-color: var(--card-bg);
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    border: 1px solid var(--card-border);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(255, 215, 0, 0.2);
    border-color: var(--primary-color);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #333;
}

.card-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.card-icon {
    font-size: 24px;
    color: var(--primary-color);
}

.stat-card {
    text-align: center;
}

.stat-value {
    font-size: 36px;
    font-weight: 700;
    color: var(--primary-color);
    margin: 10px 0;
    text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
}

.stat-label {
    font-size: 14px;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.stat-change {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 5px;
}

.positive {
    background-color: rgba(76, 209, 55, 0.2);
    color: #4cd137;
}

.negative {
    background-color: rgba(255, 82, 82, 0.2);
    color: #ff5252;
}

.summary-card {
    grid-column: 1 / -1;
}

.summary-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.summary-stat {
    display: flex;
    align-items: center;
}

.summary-icon {
    font-size: 20px;
    margin-right: 10px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.summary-stat:hover .summary-icon {
    transform: scale(1.1);
}

.users-icon { 
    background-color: rgba(255, 215, 0, 0.15); 
    color: var(--primary-color); 
}

.active-icon { 
    background-color: rgba(255, 215, 0, 0.15); 
    color: var(--primary-color); 
}

.pending-icon { 
    background-color: rgba(255, 215, 0, 0.15); 
    color: var(--primary-color); 
}

h1 {
    color: var(--primary-color);
    text-align: center;
    margin-bottom: 30px;
    font-weight: 700;
    text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
}

/* Animation for stat values */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.card:hover .stat-value {
    animation: pulse 1.5s infinite;
}

/* Glowing effect for icons on hover */
.card:hover .card-icon {
    text-shadow: 0 0 10px var(--primary-color);
}

@media (max-width: 768px) {
    .dashboard {
        grid-template-columns: 1fr;
    }
}
    </style>
</head>
<body>
    <h1><i class="fas fa-chart-line"></i> Statistics </h1>
    
    <div class="dashboard">
        <!-- Summary Cards -->
        <div class="card stat-card">
            <div class="card-header">
                <h2 class="card-title">Total Users</h2>
                <i class="card-icon fas fa-users"></i>
            </div>
            <div class="stat-value"><?= number_format($totalUsers) ?></div>
            <div class="stat-label">All Registered Users</div>
            <div class="stat-change positive">+12% from last month</div>
        </div>
        
        <div class="card stat-card">
            <div class="card-header">
                <h2 class="card-title">Active Users</h2>
                <i class="card-icon fas fa-user-check"></i>
            </div>
            <div class="stat-value"><?= number_format($activeUsers) ?></div>
            <div class="stat-label">Active in last 30 days</div>
            <div class="stat-change positive">+8% from last month</div>
        </div>
        
        <div class="card stat-card">
            <div class="card-header">
                <h2 class="card-title">Inactive Users</h2>
                <i class="card-icon fas fa-user-clock"></i>
            </div>
            <div class="stat-value"><?= number_format($pendingVerifications) ?></div>
            <div class="stat-label">inactive accounts</div>
            <div class="stat-change negative">+3% from last month</div>
        </div>
        
        <!-- Quick Summary -->
        <div class="card summary-card">
            <div class="card-header">
                <h2 class="card-title">Quick Summary</h2>
                <i class="card-icon fas fa-clipboard-list"></i>
            </div>
            <div class="summary-stats">
                <div class="summary-stat">
                    <div class="summary-icon users-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?= number_format($totalUsers) ?></div>
                        <div class="stat-label">Total Users</div>
                    </div>
                </div>
                <div class="summary-stat">
                    <div class="summary-icon active-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?= number_format($activeUsers) ?></div>
                        <div class="stat-label">Active Users</div>
                    </div>
                </div>
                <div class="summary-stat">
                    <div class="summary-icon pending-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?= number_format($pendingVerifications) ?></div>
                        <div class="stat-label">Pending Verifications</div>
                    </div>
                </div>
                <div class="summary-stat">
                    <div class="summary-icon" style="background-color: rgba(108, 117, 125, 0.1); color: #6c757d;">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?= $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0 ?>%</div>
                        <div class="stat-label">Activation Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h1> </h1>
     <h1><i class="fas fa-chart-line"></i> USERS </h1>
</body>
</html>