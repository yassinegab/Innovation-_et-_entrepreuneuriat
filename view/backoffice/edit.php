<?php
include_once(__DIR__ .'/../../controller/user_controller.php');

if (!isset($_GET['id'])) {
    echo "No user ID provided.";
    exit();
}

$userC = new User_controller();
$usr = $userC->load_user($_GET['id']); 
if (!$usr) {
    echo "User not found.";
    exit();
}

// Add image upload handling

$targetPath = $usr->getProfileImage();
$oldtargetPath = $targetPath;
$uploadMessage = null;
$NotHumanFace = 1;      // assume “no face” until proven otherwise
$fileName      = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload first
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../frontoffice/uploads/profiles/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $file = $_FILES['profile_image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (in_array($file['type'], $allowedTypes) && $file['size'] <= $maxSize) {
            // Use temporary file path for face detection
            $tmpFilePath = $file['tmp_name'];
            
            // Execute Python face detection on TEMP file
            //--------------
            $python = 'C:/Users/LENOVO/AppData/Local/Microsoft/WindowsApps/python.exe';
            $script = 'facedetect.py';
            $command = "\"$python\" \"$script\" " . escapeshellarg($tmpFilePath) . " 2>&1";
            //----------------
            $command = "python3 facedetect.py " . escapeshellarg($tmpFilePath);
            $hasFace = trim(shell_exec($command));

            if ($hasFace === "True") {
                $NotHumanFace = 0;
                // Generate new filename only after successful face check
                $fileName = uniqid() . '_' . basename($file['name']);
                $targetPath = $uploadDir . $fileName;
                
                // Move the file only if face detected
                if (!move_uploaded_file($tmpFilePath, $targetPath)) {
                    $uploadMessage = "Error uploading image.";
                    $targetPath = $oldtargetPath; // Revert to old image
                }
            } else {
                $uploadMessage = "No human face detected in the image.";
                $targetPath = $oldtargetPath; // Keep existing image
                $NotHumanFace = 1;
            }
        } else {
            $uploadMessage = "Invalid image file (max 2MB, JPG/PNG/GIF only).";
        }
    }

   
        $targetPath = $uploadDir . $fileName;

    $image = $targetPath; // Use new path if valid, otherwise keeps old
//-----------
    $name = $_POST['name'];
    $lastname = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $active_account = $_POST['account_state'];

    if (empty($password)) {
        $password = $usr->getPassword(); 
    }

   
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }

    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $image=$oldtargetPath;
    if ($NotHumanFace==0) {
        $image=$targetPath;
    }

    //-----------
    // Create user object with updated image path
    $usr2 = new User(
        $_GET['id'], 
        $name, 
        $lastname, 
        $day, 
        $month, 
        $year, 
        $password, 
        $email,
        $active_account,
        $image
    );
    
    $userC->update_user($usr2);

    if($uploadMessage==null){
    
    header("Location: user_profile.php?id=" . $_GET['id']);
    exit();
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="assets/edit.css">
    <style>
       :root {
    --primary-color: #FFD700; /* Yellow */
    --primary-hover: #FFC107; /* Darker Yellow */
    --accent-color: #FFEB3B; /* Light Yellow */
    --dark-color: #121212; /* Near Black */
    --dark-secondary: #1e1e1e; /* Dark Gray */
    --dark-tertiary: #2d2d2d; /* Medium Gray */
    --text-primary: #ffffff;
    --text-secondary: #aaaaaa;
    --success-color: #4cd137;
    --warning-color: #ffb142;
    --danger-color: #ff5252;
    --border-radius: 8px;
    --box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background-color: var(--dark-color);
    color: var(--text-primary);
    line-height: 1.6;
    padding: 0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-container {
    background-color: var(--dark-secondary);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 30px;
    width: 100%;
    max-width: 500px;
    border: 1px solid var(--dark-tertiary);
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
    background: var(--primary-color);
    background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-hover) 100%);
}

h2 {
    color: var(--primary-color);
    margin-bottom: 25px;
    font-size: 28px;
    font-weight: 700;
    text-align: center;
    position: relative;
    padding-bottom: 15px;
}

h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background-color: var(--primary-color);
    border-radius: 3px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

label {
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 14px;
    margin-bottom: 5px;
    display: block;
}

input, select {
    background-color: var(--dark-tertiary);
    border: 1px solid #444;
    color: var(--text-primary);
    padding: 12px 15px;
    border-radius: var(--border-radius);
    font-size: 16px;
    width: 100%;
    transition: var(--transition);
}

input:focus, select:focus {
    border-color: var(--primary-color);
    outline: none;
    box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.2);
}

input::placeholder {
    color: #666;
}

select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23FFD700' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    padding-right: 40px;
}

.birth-date-container {
    display: grid;
    grid-template-columns: 1fr 1.5fr 1fr;
    gap: 10px;
}

button {
    background-color: var(--primary-color);
    color: var(--dark-color);
    border: none;
    padding: 14px;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: var(--transition);
    margin-top: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

button:hover {
    background-color: var(--primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
}

.back-link {
    display: inline-block;
    color: var(--text-secondary);
    text-decoration: none;
    margin-top: 20px;
    font-size: 14px;
    transition: var(--transition);
}

.back-link:hover {
    color: var(--primary-color);
}

.back-link::before {
    content: '←';
    margin-right: 5px;
}

.error-message {
    background-color: rgba(255, 82, 82, 0.1);
    color: var(--danger-color);
    padding: 10px;
    border-radius: var(--border-radius);
    margin-bottom: 20px;
    font-size: 14px;
    border-left: 3px solid var(--danger-color);
}

.success-message {
    background-color: rgba(76, 209, 55, 0.1);
    color: var(--success-color);
    padding: 10px;
    border-radius: var(--border-radius);
    margin-bottom: 20px;
    font-size: 14px;
    border-left: 3px solid var(--success-color);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.form-container {
    animation: fadeIn 0.5s ease-out forwards;
}

@media (max-width: 576px) {
    .form-container {
        padding: 20px;
        margin: 15px;
    }
    
    h2 {
        font-size: 24px;
    }
    
    .birth-date-container {
        grid-template-columns: 1fr;
    }
}

*:focus-visible {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}
 .image-upload {
            position: relative;
            margin: 20px 0;
        }
        .profile-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .profile-preview:hover {
            transform: scale(1.05);
        }
        #file-input {
            display: none;
        }
        .upload-help {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-top: 5px;
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
    </style>
</head>
<body>
    <div style="position: absolute; top: 20px; left: 20px;">
    <a href="javascript:history.back()" class="custom-button"> Retour</a>
</div>


    <div class="form-container">
    <h2>Edit User</h2>
    <?php if ($uploadMessage): ?>
        <div class="error-message"><?= $uploadMessage ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="image-upload">
            <label>
                <img src="<?= htmlspecialchars($usr->getProfileImage() ?? 'https://ui-avatars.com/api/?name=' . urlencode($usr->getName())) ?>?<?= time() ?>" 
                     class="profile-preview" 
                     id="image-preview">
                <input type="file" id="file-input" name="profile_image" accept="image/*">
            </label>
            <div class="upload-help">Click image to upload new photo (max 2MB)</div>
        </div>
        


<div class="form-container">
    <h2></h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($usr->getId()) ?>">

        <label>First Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($usr->getName()) ?>" required>

        <label>Last Name</label>
        <input type="text" name="lastName" value="<?= htmlspecialchars($usr->getLastName()) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($usr->getEmail()) ?>" required>

        <label for="role">role</label>
        <select id="role" name="role" required>
            <?php
            for ($i = 1; $i <= 5; $i++) {
                $selected = ($i == $usr->getRole()) ? 'selected' : '';
                echo "<option value=\"$i\" $selected>$i</option>";
            }
            ?>
        </select>
        <label for="role">account state</label>
        <select id="account_state" name="account_state" required>
            <?php
            $account_state = [
                0 => "inactive", 1 => "active"
            ];

            foreach ($account_state as $num => $name) {
                $selected = ($num == $usr->getActiveAccount()) ? 'selected' : '';
                echo "<option value=\"$num\" $selected>$name</option>";
            }
            ?>
        </select>


        <label>Password (leave empty if unchanged)</label>
        <input type="password" name="password" placeholder="••••••••">

        <label for="day">Day</label>
        <select id="day" name="day" required>
            <?php
            for ($i = 1; $i <= 31; $i++) {
                $selected = ($i == $usr->getBirthDay()) ? 'selected' : '';
                echo "<option value=\"$i\" $selected>$i</option>";
            }
            ?>
        </select>

        <label for="month">Month</label>
        <select id="month" name="month" required>
            <?php
            $months = [
                1 => "January", 2 => "February", 3 => "March", 4 => "April",
                5 => "May", 6 => "June", 7 => "July", 8 => "August",
                9 => "September", 10 => "October", 11 => "November", 12 => "December"
            ];

            foreach ($months as $num => $name) {
                $selected = ($num == $usr->getBirthMonth()) ? 'selected' : '';
                echo "<option value=\"$num\" $selected>$name</option>";
            }
            ?>
        </select>

        <label for="year">Year</label>
        <select id="year" name="year" required>
            <?php
            for ($y = 1900; $y <= date('Y'); $y++) {
                $selected = ($y == $usr->getBirthYear()) ? 'selected' : '';
                echo "<option value=\"$y\" $selected>$y</option>";
            }
            ?>
        </select>

        <button type="submit">Update</button>
    </form>
</div>
<script>
document.getElementById('file-input').addEventListener('change', function(e) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById('image-preview').src = reader.result;
    }
    if (e.target.files[0]) {
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
</body>
</html>
