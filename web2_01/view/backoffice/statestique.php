<?php
session_start();
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
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #f72585;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
            color: var(--dark-color);
        }
        
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }
        
        .card-icon {
            font-size: 24px;
            color: var(--accent-color);
        }
        
        .stat-card {
            text-align: center;
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 10px 0;
        }
        
        .stat-label {
            font-size: 14px;
            color: #6c757d;
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
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .negative {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
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
        }
        
        .users-icon { background-color: rgba(67, 97, 238, 0.1); color: var(--primary-color); }
        .active-icon { background-color: rgba(76, 201, 240, 0.1); color: var(--success-color); }
        .pending-icon { background-color: rgba(248, 150, 30, 0.1); color: var(--warning-color); }
        
        h1 {
            color: var(--dark-color);
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
        }
        
        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <h1><i class="fas fa-chart-line"></i> Statistics Dashboard</h1>
    
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
                <h2 class="card-title">Pending Verifications</h2>
                <i class="card-icon fas fa-user-clock"></i>
            </div>
            <div class="stat-value"><?= number_format($pendingVerifications) ?></div>
            <div class="stat-label">Awaiting Approval</div>
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
</body>
</html>