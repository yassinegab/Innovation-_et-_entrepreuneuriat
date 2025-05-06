<?php
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once(__DIR__ . '/../../controller/user_controller.php');

$userC = new User_controller();
$user_id = $_SESSION['user_id'];


       
        $userC->updateLastLoginDate($user_id);


// Fetch the user object using the controller method
$user = $userC->load_user($user_id);

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - GreenMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
        :root {
    /* Neon-yellow on dark theme */
    --primary-color: #FFD600;      /* buttons, highlights, titles */
    --primary-light: #1a1a1a;      /* cards, panels */
    --primary-dark: #bfa500;       /* hover states on yellow */
    --text-color: #E0E0E0;         /* main text */
    --text-light: #777777;         /* secondary text, labels */
    --border-color: #333333;       /* input borders, separators */
    --success-color: #28A745;      /* unchanged, for “active” badges */
    --danger-color: #DC3545;       /* unchanged, for errors */
    --white: #FFFFFF;
    --shadow: 0 0 20px #FFD600;    /* neon glow */
    --transition: all 0.3s ease;
}

/* Reset & base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: #0d0d0d;       /* page background */
    color: var(--text-color);
    line-height: 1.6;
    min-height: 100vh;
    padding: 20px;
}

.container {
    max-width: 1000px;
    margin: 0 auto;
}

/* Back button */
.back-btn {
    display: inline-flex;
    align-items: center;
    color: var(--primary-color);
    font-weight: 500;
    text-decoration: none;
    margin-bottom: 20px;
    transition: var(--transition);
}
.back-btn i {
    margin-right: 8px;
}
.back-btn:hover {
    background-color: rgba(255, 214, 0, 0.1);
}

/* Profile card */
.profile-card {
    background: var(--primary-light);
    border-radius: 12px;
    box-shadow: var(--shadow);
    padding: 40px;
    text-align: center;
    animation: fadeIn 0.6s ease-in-out;
    border: 1px solid #2f2f2f;
    width: 100%;
}

/* Avatar */
.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--primary-color);
    margin: 0 auto 20px;
    display: block;
    background-color: var(--primary-color);
}

/* Name / Role */
.profile-name {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--primary-color);
}
.profile-role {
    color: var(--primary-color);
    font-weight: 500;
    margin-bottom: 30px;
    display: block;
    text-transform: capitalize;
}

/* Info section */
.profile-info {
    text-align: left;
    margin: 25px 0;
    background-color: #121212;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
}
.info-item {
    margin-bottom: 20px;
}
.info-label {
    font-size: 0.9rem;
    color: var(--text-light);
    margin-bottom: 5px;
}
.info-value {
    font-weight: 500;
    padding: 8px 0;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    color: var(--text-color);
}
.info-value i {
    margin-right: 10px;
    color: var(--primary-color);
    width: 20px;
    text-align: center;
}

/* Status badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    background-color: rgba(255, 214, 0, 0.2);
    color: var(--primary-color);
}

/* Actions */
.profile-actions {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 30px;
}
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 20px;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    width: 100%;
}
.btn i {
    margin-right: 8px;
}
.btn-primary {
    background-color: var(--primary-color);
    color: var(--white);
}
.btn-primary:hover {
    background-color: var(--primary-dark);
}
.btn-danger {
    background-color: var(--danger-color);
    color: var(--white);
}
.btn-danger:hover {
    background-color: #C82333;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Responsiveness */
@media (max-width: 768px) {
    .container {
        padding: 0 15px;
        max-width: 100%;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <a href="acceuil.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Retour à l'accueil
        </a>

        <div class="profile-card">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user->getName()); ?>&background=2E8B57&color=fff" 
                 alt="Avatar" class="profile-avatar">
            <h2 class="profile-name"><?php echo htmlspecialchars($user->getName()); ?></h2>
            <span class="profile-role"><?php echo ucfirst(htmlspecialchars("000")); ?></span>
            


            <div class="profile-info">
                <div class="info-item">
                    <div class="info-label">Adresse email</div>
                    <div class="info-value">
                        <i class="fas fa-envelope"></i>
                        <?php echo htmlspecialchars($user->getEmail()); ?>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Statut du compte</div>
                    <div class="info-value">
                        <i class="fas fa-check-circle"></i>
                        <span class="status-badge">Actif</span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">name</div>
                    <div class="info-value">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($user->getName()); ?>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">last name</div>
                    <div class="info-value">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($user->getLastName()); ?>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">birthdate</div>
                    <div class="info-value">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($birthdate2 = sprintf('%04d-%02d-%02d', $user->getBirthYear(), $user->getBirthMonth(), $user->getBirthDay())); ?>
                    </div>
                </div>

            </div>
            
            <div class="profile-actions">
                <a href="modifierprofil.php" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Modifier le profil
                </a>
                
            </div>
        </div>
    </div>
</body>
</html>