<?php
session_start();
require_once(__DIR__ . '/../../controller/user_controller.php');

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the profile user ID from URL parameter
$profile_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If no ID provided or invalid ID, redirect to users page
if ($profile_id <= 0) {
    header('Location: allUsers.php');
    exit();
}

$userC = new User_controller();
$current_user_id = $_SESSION['user_id'];



// Fetch the profile user object
$profile_user = $userC->load_user($profile_id);

// If user not found, redirect to users page
if (!$profile_user) {
    header('Location: allUsers.php');
    exit();
}

// Check if the viewed profile is the current user's profile
$is_own_profile = ($current_user_id == $profile_id);

// Get connection status (placeholder - implement your own logic)
// Possible values: none, pending_sent, pending_received, connected, blocked
$connection_status = "none";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?php echo htmlspecialchars($profile_user->getName()); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Base styles and variables */
        :root {
            --dark-bg: #121212;
            --dark-surface: #1e1e1e;
            --dark-surface-2: #2d2d2d;
            --dark-surface-3: #333333;
            --yellow-primary: #FFD700;
            --yellow-hover: #FFC107;
            --yellow-muted: rgba(255, 215, 0, 0.15);
            --text-primary: #ffffff;
            --text-secondary: #aaaaaa;
            --success-color: #4cd137;
            --danger-color: #ff5252;
            --border-radius: 8px;
            --box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            --transition: all 0.3s ease;
        }

        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Navigation */
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            color: var(--yellow-primary);
            font-weight: 500;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .back-btn i {
            margin-right: 8px;
        }

        .back-btn:hover {
            background-color: rgba(255, 215, 0, 0.1);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: var(--border-radius);
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            background-color: var(--dark-surface-2);
            color: var(--text-primary);
        }

        .action-btn i {
            margin-right: 8px;
        }

        .action-btn:hover {
            background-color: var(--dark-surface-3);
        }

        .action-btn.primary {
            background-color: var(--yellow-primary);
            color: #000;
        }

        .action-btn.primary:hover {
            background-color: var(--yellow-hover);
        }

        .action-btn.danger {
            background-color: rgba(255, 82, 82, 0.2);
            color: var(--danger-color);
        }

        .action-btn.danger:hover {
            background-color: var(--danger-color);
            color: var(--text-primary);
        }

        /* Profile header */
        .profile-header {
            background-color: var(--dark-surface);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--dark-surface-2);
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--yellow-primary), var(--yellow-hover));
        }

        .profile-header-content {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--yellow-primary);
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--text-primary);
        }

        .profile-username {
            color: var(--yellow-primary);
            font-size: 16px;
            margin-bottom: 15px;
            display: block;
        }

        .profile-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 15px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .meta-item i {
            margin-right: 8px;
            color: var(--yellow-primary);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .status-active {
            background-color: rgba(76, 209, 55, 0.2);
            color: var(--success-color);
        }

        .status-inactive {
            background-color: rgba(255, 82, 82, 0.2);
            color: var(--danger-color);
        }

        .connection-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            margin-left: auto;
        }

        .connection-none {
            background-color: var(--dark-surface-2);
            color: var(--text-secondary);
        }

        .connection-pending {
            background-color: rgba(255, 177, 66, 0.2);
            color: #ffb142;
        }

        .connection-connected {
            background-color: rgba(76, 209, 55, 0.2);
            color: var(--success-color);
        }

        .connection-blocked {
            background-color: rgba(255, 82, 82, 0.2);
            color: var(--danger-color);
        }

        /* Profile content */
        .profile-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        /* Profile section */
        .profile-section {
            background-color: var(--dark-surface);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid var(--dark-surface-2);
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--yellow-primary);
            display: flex;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--dark-surface-2);
        }

        .section-title i {
            margin-right: 10px;
        }

        /* Personal info */
        .info-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .info-item {
            background-color: var(--dark-surface-2);
            padding: 15px;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .info-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .info-label {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 5px;
        }

        .info-value {
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .info-value i {
            margin-right: 10px;
            color: var(--yellow-primary);
            width: 20px;
            text-align: center;
        }

        /* Activity section */
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background-color: var(--dark-surface-2);
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .activity-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--yellow-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--yellow-primary);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .activity-time {
            font-size: 14px;
            color: var(--text-secondary);
        }

        /* Quick actions */
        .quick-actions {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .action-card {
            background-color: var(--dark-surface-2);
            border-radius: var(--border-radius);
            padding: 20px;
            transition: var(--transition);
            border: 1px solid var(--dark-surface-3);
            display: flex;
            align-items: center;
            gap: 15px;
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-color: var(--yellow-primary);
        }

        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--yellow-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--yellow-primary);
            font-size: 20px;
        }

        .action-details {
            flex: 1;
        }

        .action-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .action-desc {
            font-size: 14px;
            color: var(--text-secondary);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: var(--dark-surface);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            width: 90%;
            max-width: 500px;
            border: 1px solid var(--dark-surface-2);
            position: relative;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--yellow-primary);
        }

        .modal-body {
            margin-bottom: 30px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 20px;
            cursor: pointer;
        }

        /* Responsive styles */
        @media (max-width: 992px) {
            .profile-content {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .profile-header-content {
                flex-direction: column;
                text-align: center;
            }

            .profile-meta {
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }

            .connection-badge {
                margin-left: 0;
                margin-top: 10px;
            }

            .nav-container {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .action-buttons {
                width: 100%;
                justify-content: space-between;
            }

            .info-list {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .action-buttons {
                flex-direction: column;
                width: 100%;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-header, .profile-section {
            animation: fadeIn 0.5s ease forwards;
        }

        .profile-section:nth-child(2) {
            animation-delay: 0.1s;
        }

        .profile-section:nth-child(3) {
            animation-delay: 0.2s;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navigation -->
        <div class="nav-container">
            <a href="showUsers.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
            
            <div class="action-buttons">
                <a href="messages.php?user=<?php echo $profile_id; ?>" class="action-btn primary">
                    <i class="fas fa-comment"></i> Message
                </a>
                <a href="collaborations.php" class="btn btn-outline"><span class="btn-icon">👥</span>Mes collaborations</a>
                
                <?php if (!$is_own_profile): ?>
                    <?php if ($connection_status == "none"): ?>
                        <button class="action-btn" id="connect-btn">
                            <i class="fas fa-user-plus"></i> Connect
                        </button>
                    <?php elseif ($connection_status == "pending_sent"): ?>
                        <button class="action-btn" id="cancel-request-btn">
                            <i class="fas fa-user-clock"></i> Cancel Request
                        </button>
                    <?php elseif ($connection_status == "pending_received"): ?>
                        <button class="action-btn" id="accept-request-btn">
                            <i class="fas fa-user-check"></i> Accept Request
                        </button>
                    <?php elseif ($connection_status == "connected"): ?>
                        <button class="action-btn" id="disconnect-btn">
                            <i class="fas fa-user-minus"></i> Disconnect
                        </button>
                    <?php endif; ?>
                    
                    <button class="action-btn danger" id="block-btn">
                        <i class="fas fa-ban"></i> Block
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-header-content">
                <?php if ($profile_user->getProfileImage()): ?>
                    <img src="<?= htmlspecialchars($profile_user->getProfileImage()) ?>" alt="Profile Picture" class="profile-avatar">
                <?php else: ?>
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($profile_user->getName()) ?>&background=2d2d2d&color=FFD700" alt="Profile Picture" class="profile-avatar">
                <?php endif; ?>
                
                <div class="profile-info">
                    <h1 class="profile-name"><?php echo htmlspecialchars($profile_user->getName() . ' ' . $profile_user->getLastName()); ?></h1>
                    <span class="profile-username">@<?php echo htmlspecialchars($profile_user->getName()); ?></span>
                    
                    <div class="profile-meta">
                        <div class="meta-item">
                            <i class="fas fa-envelope"></i>
                            <?php echo htmlspecialchars($profile_user->getEmail()); ?>
                        </div>
                        
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <?php 
                                $birthdate = sprintf('%04d-%02d-%02d', 
                                    $profile_user->getBirthYear(), 
                                    $profile_user->getBirthMonth(), 
                                    $profile_user->getBirthDay()
                                );
                                echo htmlspecialchars($birthdate);
                            ?>
                        </div>
                        
                        <?php if ($profile_user->getActiveAccount() == 1): ?>
                            <span class="status-badge status-active">
                                <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px;"></i> Active
                            </span>
                        <?php else: ?>
                            <span class="status-badge status-inactive">
                                <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px;"></i> Inactive
                            </span>
                        <?php endif; ?>
                        
                        <?php if (!$is_own_profile): ?>
                            <?php if ($connection_status == "pending_sent"): ?>
                                <span class="connection-badge connection-pending">
                                    <i class="fas fa-clock"></i> Request Sent
                                </span>
                            <?php elseif ($connection_status == "pending_received"): ?>
                                <span class="connection-badge connection-pending">
                                    <i class="fas fa-user-clock"></i> Request Received
                                </span>
                            <?php elseif ($connection_status == "connected"): ?>
                                <span class="connection-badge connection-connected">
                                    <i class="fas fa-user-check"></i> Connected
                                </span>
                            <?php elseif ($connection_status == "blocked"): ?>
                                <span class="connection-badge connection-blocked">
                                    <i class="fas fa-ban"></i> Blocked
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="profile-content">
            <div class="main-content">
                <!-- Personal Information -->
                <div class="profile-section">
                    <h3 class="section-title">
                        <i class="fas fa-user"></i> Personal Information
                    </h3>
                    
                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">
                                <i class="fas fa-user"></i>
                                <?php echo htmlspecialchars($profile_user->getName() . ' ' . $profile_user->getLastName()); ?>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">
                                <i class="fas fa-envelope"></i>
                                <?php echo htmlspecialchars($profile_user->getEmail()); ?>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Birth Date</div>
                            <div class="info-value">
                                <i class="fas fa-calendar-alt"></i>
                                <?php echo htmlspecialchars($birthdate); ?>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Account Status</div>
                            <div class="info-value">
                                <i class="fas fa-check-circle"></i>
                                <?php echo $profile_user->getActiveAccount() == 1 ? 'Active' : 'Inactive'; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="profile-section">
                    <h3 class="section-title">
                        <i class="fas fa-history"></i> Recent Activity
                    </h3>
                    
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Last Login</div>
                                <div class="activity-time">
                                    <?php 
                                        // Format last login time
                                        $last_login=null;
                                        $last_loginday=random_int(0, 31);
                                        $last_loginhour=random_int(0, 23);
                                        $last_loginminute=random_int(0, 59);
                                        if ($last_login==null) {
                                            $login_time = new DateTime($last_login);
                                            $now = new DateTime();
                                            $interval = $now->diff($login_time);
                                            
                                            if ($interval->days > 0) {
                                                echo $interval->days . " days ago";
                                            } elseif ($interval->h > 0) {
                                                echo $interval->h . " hours ago";
                                            } elseif ($interval->i > 0) {
                                                echo $interval->i . " minutes ago";
                                            } else {
                                                echo "Just now";
                                            }
                                        } 
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Joined</div>
                                <div class="activity-time">
                                    <?php 
                                        // Format join date (placeholder - replace with actual data)
                                        echo "January 15, 2023";
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Quick Actions -->
                <div class="profile-section">
                    <h3 class="section-title">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h3>
                    
                    <div class="quick-actions">
                        <a href="messages.php?user=<?php echo $profile_id; ?>" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-comment"></i>
                            </div>
                            <div class="action-details">
                                <div class="action-title">Send Message</div>
                                <div class="action-desc">Start a conversation with <?php echo htmlspecialchars($profile_user->getName()); ?></div>
                            </div>
                        </a>
                        
                        <?php if (!$is_own_profile && $connection_status != "blocked"): ?>
                            <div class="action-card" id="share-profile">
                                <div class="action-icon">
                                    <i class="fas fa-share-alt"></i>
                                </div>
                                <div class="action-details">
                                    <div class="action-title">Share Profile</div>
                                    <div class="action-desc">Share this profile with your connections</div>
                                </div>
                            </div>
                            
                            <div class="action-card" id="report-user">
                                <div class="action-icon">
                                    <i class="fas fa-flag"></i>
                                </div>
                                <div class="action-details">
                                    <div class="action-title">Report User</div>
                                    <div class="action-desc">Report inappropriate behavior</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Block User Modal -->
    <div class="modal" id="block-modal">
        <div class="modal-content">
            <button class="close-modal">&times;</button>
            <h3 class="modal-title">Block User</h3>
            <div class="modal-body">
                <p>Are you sure you want to block <?php echo htmlspecialchars($profile_user->getName()); ?>? They will no longer be able to:</p>
                <ul style="margin: 15px 0 15px 20px;">
                    <li>Send you messages</li>
                    <li>See your profile</li>
                    <li>Send you connection requests</li>
                </ul>
                <p>You can unblock them later from your settings.</p>
            </div>
            <div class="modal-actions">
                <button class="action-btn" id="cancel-block">Cancel</button>
                <button class="action-btn danger" id="confirm-block">Block User</button>
            </div>
        </div>
    </div>
    
    <!-- Report User Modal -->
    <div class="modal" id="report-modal">
        <div class="modal-content">
            <button class="close-modal">&times;</button>
            <h3 class="modal-title">Report User</h3>
            <div class="modal-body">
                <p>Please select the reason for reporting this user:</p>
                <div style="margin: 20px 0;">
                    <div style="margin-bottom: 10px;">
                        <input type="radio" id="reason1" name="report-reason" value="inappropriate">
                        <label for="reason1">Inappropriate content</label>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <input type="radio" id="reason2" name="report-reason" value="harassment">
                        <label for="reason2">Harassment or bullying</label>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <input type="radio" id="reason3" name="report-reason" value="spam">
                        <label for="reason3">Spam or scam</label>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <input type="radio" id="reason4" name="report-reason" value="fake">
                        <label for="reason4">Fake account</label>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <input type="radio" id="reason5" name="report-reason" value="other">
                        <label for="reason5">Other</label>
                    </div>
                </div>
                <textarea placeholder="Additional details (optional)" style="width: 100%; padding: 10px; background-color: var(--dark-surface-2); border: 1px solid var(--dark-surface-3); color: var(--text-primary); border-radius: var(--border-radius); min-height: 100px;"></textarea>
            </div>
            <div class="modal-actions">
                <button class="action-btn" id="cancel-report">Cancel</button>
                <button class="action-btn danger" id="confirm-report">Submit Report</button>
            </div>
        </div>
    </div>

    <script>
        // Connect button functionality
        document.getElementById('connect-btn')?.addEventListener('click', function() {
            // Replace with your actual connection request logic
            this.innerHTML = '<i class="fas fa-user-clock"></i> Request Sent';
            
            // Add connection badge
            const profileMeta = document.querySelector('.profile-meta');
            const connectionBadge = document.createElement('span');
            connectionBadge.className = 'connection-badge connection-pending';
            connectionBadge.innerHTML = '<i class="fas fa-clock"></i> Request Sent';
            profileMeta.appendChild(connectionBadge);
        });
        
        // Block user modal
        const blockModal = document.getElementById('block-modal');
        const blockBtn = document.getElementById('block-btn');
        const cancelBlock = document.getElementById('cancel-block');
        const confirmBlock = document.getElementById('confirm-block');
        const closeBlockModal = blockModal.querySelector('.close-modal');
        
        blockBtn?.addEventListener('click', function() {
            blockModal.style.display = 'flex';
        });
        
        cancelBlock?.addEventListener('click', function() {
            blockModal.style.display = 'none';
        });
        
        closeBlockModal?.addEventListener('click', function() {
            blockModal.style.display = 'none';
        });
        
        confirmBlock?.addEventListener('click', function() {
            // Replace with your actual block user logic
            blockModal.style.display = 'none';
            alert('User has been blocked');
        });
        
        // Report user modal
        const reportModal = document.getElementById('report-modal');
        const reportBtn = document.getElementById('report-user');
        const cancelReport = document.getElementById('cancel-report');
        const confirmReport = document.getElementById('confirm-report');
        const closeReportModal = reportModal.querySelector('.close-modal');
        
        reportBtn?.addEventListener('click', function() {
            reportModal.style.display = 'flex';
        });
        
        cancelReport?.addEventListener('click', function() {
            reportModal.style.display = 'none';
        });
        
        closeReportModal?.addEventListener('click', function() {
            reportModal.style.display = 'none';
        });
        
        confirmReport?.addEventListener('click', function() {
            // Replace with your actual report user logic
            reportModal.style.display = 'none';
            alert('Report submitted successfully');
        });
        
        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === blockModal) {
                blockModal.style.display = 'none';
            }
            if (event.target === reportModal) {
                reportModal.style.display = 'none';
            }
        });
        
        // Share profile functionality
        document.getElementById('share-profile')?.addEventListener('click', function() {
            // Replace with your actual share functionality
            alert('Share functionality would open here');
        });
    </script>
</body>
</html>