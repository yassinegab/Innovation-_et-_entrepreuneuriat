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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = $_POST['name'];
    $lastname = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];

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

    
    $usr2 = new user($_GET['id'], $name, $lastname, $day, $month, $year, $password, $email);

    $userC->update_user($usr2);

    
    header("Location: user_profile.php?id=" . $_GET['id']);
    exit();  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="assets/edit.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        input, select {
            display: block;
            margin: 10px 0;
            padding: 8px;
        }
        label {
            font-weight: bold;
        }
        .form-container {
            max-width: 400px;
            margin: auto;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Edit User</h2>
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
</body>
</html>
