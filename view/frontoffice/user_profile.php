<?php
session_start();
require_once(__DIR__ . '/../../controller/user_controller.php');
// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $uploadDir = 'uploads/profiles/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $file = $_FILES['profile_image'];
    $fileName = uniqid() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;

    // Validate image
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    $user_id=$_SESSION['user_id'];
    if (in_array($file['type'], $allowedTypes) && $file['size'] <= $maxSize) {
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $userC = new User_controller();
            $userC->updateProfileImage($user_id, $targetPath);
            $user = $userC->load_user($user_id); // Refresh user data
            $message = "Profile image updated successfully!";
        } else {
            $error = "Error uploading file.";
        }
    } else {
        $error = "Invalid file type or size (max 2MB allowed).";
    }
}

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}



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
    /* Custom color scheme */
    --color-primary: #e3c43a;
    --color-secondary-1: rgb(39, 40, 45);
    --color-secondary-2: rgb(24, 25, 30);
    --color-white: #ffffff;
    --color-primary-light: #f0d66a;
    --color-primary-dark: #c9ab2a;
    --color-primary-transparent: rgba(227, 196, 58, 0.15);
    --transition-speed: 0.3s;
    
    /* Additional colors */
    --text-color: #E0E0E0;
    --text-light: #777777;
    --border-color: #333333;
    --success-color: #28A745;
    --danger-color: #DC3545;
    --shadow: 0 0 20px rgba(227, 196, 58, 0.3);
}

/* Reset & base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--color-secondary-2);
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
    color: var(--color-primary);
    font-weight: 500;
    text-decoration: none;
    margin-bottom: 20px;
    transition: all var(--transition-speed);
    padding: 8px 12px;
    border-radius: 6px;
}

.back-btn i {
    margin-right: 8px;
}

.back-btn:hover {
    background-color: var(--color-primary-transparent);
}

/* Profile card */
.profile-card {
    background: var(--color-secondary-1);
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
    border: 3px solid var(--color-primary);
    margin: 0 auto 20px;
    display: block;
    cursor: pointer;
    transition: transform var(--transition-speed);
}

.profile-avatar:hover {
    transform: scale(1.05);
}

.image-upload {
    position: relative;
    display: inline-block;
}

/* Name / Role */
.profile-name {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--color-primary);
}

.profile-role {
    color: var(--color-white);
    font-weight: 500;
    margin-bottom: 30px;
    display: block;
    text-transform: capitalize;
}

/* Status badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    background-color: var(--color-primary-transparent);
    color: var(--color-primary);
    margin-bottom: 25px;
}

.status-badge i {
    font-size: 0.6rem;
    margin-right: 5px;
    color: var(--success-color);
}

/* Info section */
.profile-info {
    text-align: left;
    margin: 25px 0;
    background-color: var(--color-secondary-2);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
}

.info-item {
    margin-bottom: 20px;
}

.info-item:last-child {
    margin-bottom: 0;
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
    color: var(--color-primary);
    width: 20px;
    text-align: center;
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
    transition: all var(--transition-speed);
    border: none;
    cursor: pointer;
    width: 100%;
}

.btn i {
    margin-right: 8px;
}

.btn-primary {
    background-color: var(--color-primary);
    color: var(--color-secondary-2);
}

.btn-primary:hover {
    background-color: var(--color-primary-light);
}

.btn-secondary {
    background-color: var(--color-secondary-2);
    color: var(--color-white);
    border: 1px solid var(--border-color);
}

.btn-secondary:hover {
    background-color: rgba(255, 255, 255, 0.05);
}

.btn-danger {
    background-color: var(--danger-color);
    color: var(--color-white);
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
    
    .profile-card {
        padding: 25px;
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
    <form method="POST" enctype="multipart/form-data">
        <label class="image-upload">
            <?php if ($user->getProfileImage()): ?>
                <img src="<?= htmlspecialchars($user->getProfileImage()) ?>?<?= time() ?>" 
                     alt="Avatar" class="profile-avatar" id="profile-image">
            <?php else: ?>
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user->getName()) ?>&background=2E8B57&color=fff" 
                     alt="Avatar" class="profile-avatar" id="profile-image">
            <?php endif; ?>
            <input type="file" id="file-input" name="profile_image" accept="image/*" style="display: none;">
        </label>
        <button type="submit" class="btn btn-primary" style="margin-top: 15px;">
            <i class="fas fa-upload"></i> Update Profile Image
        </button>
    </form>
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
                <a href="monprofil.php?id=<?= $_SESSION['user_id'] ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Modifier le profil
                </a>
                
            </div>
        </div>
    </div>
    <script>
document.getElementById('file-input').addEventListener('change', function(e) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById('profile-image').src = reader.result;
    }
    if (e.target.files[0]) {
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
</body>
</html>