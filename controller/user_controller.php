<?php
include_once(__DIR__ . "/../model/user.php");
require_once(__DIR__ .'/../config.php');
class User_controller{
    public function add_user($user){
        $sql = 'INSERT INTO users (id, name, lastName, password, email, birthdate)
        VALUES (:id, :name, :lastName, :password, :email, :birthdate)';
    $db = Config::getConnexion();
    try{
        $idQuery = $db->query("
                SELECT COALESCE(MIN(t1.id + 1), 1) AS next_id
                FROM users t1
                LEFT JOIN users t2 ON t1.id + 1 = t2.id
                WHERE t2.id IS NULL
            ");
            $row = $idQuery->fetch();
            $nextId = $row['next_id'];

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id',$nextId);
        $stmt->bindValue(':name', $user->getName());
        $stmt->bindValue(':lastName', $user->getLastName());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':email', $user->getEmail());
        $birthdate = sprintf('%04d-%02d-%02d', $user->getBirthYear(), $user->getBirthMonth(), $user->getBirthDay());
        $stmt->bindValue(':birthdate',  $birthdate);
        if ($stmt->execute()) {
            header("Location: login2.php?message=signup_success");
            exit();
        } else {
            echo "❌ Erreur lors de l'ajout.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
    
    

}

   /* function load_user2($id) {
    

    $stmt = $pdo->prepare("SELECT id, name, email, lastName FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}*/


public function add_user2($user){
    $sql = 'INSERT INTO users (id, name, lastName, password, email, birthdate)
    VALUES (:id, :name, :lastName, :password, :email, :birthdate)';
$db = Config::getConnexion();
try{
    $idQuery = $db->query("
            SELECT COALESCE(MIN(t1.id + 1), 1) AS next_id
            FROM users t1
            LEFT JOIN users t2 ON t1.id + 1 = t2.id
            WHERE t2.id IS NULL
        ");
        $row = $idQuery->fetch();
        $nextId = $row['next_id'];

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id',$nextId);
    $stmt->bindValue(':name', $user->getName());
    $stmt->bindValue(':lastName', $user->getLastName());
    $stmt->bindValue(':password', $user->getPassword());
    $stmt->bindValue(':email', $user->getEmail());
    $birthdate = sprintf('%04d-%02d-%02d', $user->getBirthYear(), $user->getBirthMonth(), $user->getBirthDay());
    $stmt->bindValue(':birthdate',  $birthdate);
    if ($stmt->execute()) {
    } else {
        echo "❌ Erreur lors de l'ajout.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}



}
public function login_user($user){
        try {
            $db = Config::getConnexion();

            
            $sql  = 'SELECT id, name, lastName, password
                     FROM users
                     WHERE email = :email';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':email', $user->getEmail());
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            
           if (!$row) {
            echo '<p style="color:red;">Email introuvable.</p>';
        } elseif (!($row['password'] === $user->getPassword())) {
            
            echo '<p style="color:red;">Mot de passe invalide.</p>';
        } else {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['user_name'] = $row['name'] . ' ' . $row['lastName'];
            if($row['id']==0) {
            header('Location: ../backoffice/back.php');
            exit();
            } else {
            header('Location: login2.php?twofactor=1');
            exit();

            }
        }
        } catch (PDOException $e) {
            echo 'Erreur BDD : ' . htmlspecialchars($e->getMessage());
        }
    }
public function getAllUsers(string $searchTerm ): array {
    try {
        $db = Config::getConnexion();
        $sql = 'SELECT id, name, lastName, email, birthdate,role,active_account,profile_image FROM users';
        
        if (!empty($searchTerm)) {
            $sql .= ' WHERE name LIKE :search OR lastName LIKE :search OR email LIKE :search';
        }
        $sql .= ' ORDER BY id';
        
        $stmt = $db->prepare($sql);
        
        if (!empty($searchTerm)) {
            $searchParam = '%' . $searchTerm . '%';
            $stmt->bindValue(':search', $searchParam);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Erreur getAllUsers: ' . $e->getMessage());
        return [];
    }
}


    function load_user($id) {
    
        $db = Config::getConnexion();
    
        try {
            $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($row) {
                
                $birthParts = explode('-', $row['birthdate']);
                $year = (int)$birthParts[0];
                $month = (int)$birthParts[1];
                $day = (int)$birthParts[2];
    
                return new User(
                    $row['id'],
                    $row['name'],
                    $row['lastName'],
                    $day,
                    $month,
                    $year,
                    $row['password'],
                    $row['email'],
                    $row['active_account'],
                    $row['profile_image']
                );
            } else {
                return null;
            }
    
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return null;
        }
    }
    public function update_user($user)
{
    try {
        $pdo = Config::getConnexion();

        if (empty($user->getPassword())) {
            $sql = "UPDATE users SET 
                        name = :name,
                        lastName = :lastName,
                        email = :email,
                        birthdate = :birthdate,
                        active_account = :active_account,
                        profile_image = :profile_image
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name'      => $user->getName(),
                ':lastName'  => $user->getLastName(),
                ':email'     => $user->getEmail(),
                ':birthdate' => $user->getBirthdate(),
                ':id'        => $user->getId(),
                ':active_account' => $user->getActiveAccount(),
                ':profile_image' => $user->getProfileImage()
            ]);
        } else {
            
            

            $sql = "UPDATE users SET 
                        name = :name,
                        lastName = :lastName,
                        email = :email,
                        birthdate = :birthdate,
                        password = :password,
                        active_account = :active_account,
                        profile_image = :profile_image

                    WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            $birthdate2 = sprintf('%04d-%02d-%02d', $user->getBirthYear(), $user->getBirthMonth(), $user->getBirthDay());
            $stmt->execute([
                ':name'      => $user->getName(),
                ':lastName'  => $user->getLastName(),
                ':email'     => $user->getEmail(),
                ':birthdate' => $birthdate2,
                ':password'  => $user->getPassword(),
                ':id'        => $user->getId(),
                ':active_account'        => $user->getActiveAccount(),
                ':profile_image' => $user->getProfileImage()
            ]);
            header("Location: admin.php?message=signup_success");
            exit();
        }

    } catch (PDOException $e) {
        die('Error updating user: ' . $e->getMessage());
    }
}
public function delete_user($id) {
    $sql = "DELETE FROM users WHERE id = :id";
    $db = config::getConnexion(); 
    try {
        $query = $db->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();
        return true;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}
public function checkEmail($email) {
    $db = config::getConnexion(); 
    $query = "SELECT COUNT(*) FROM users WHERE email = :email";
    $stmt = $db->prepare($query); // ← use $db instead of $this->db
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    return $stmt->fetchColumn() > 0; // true if exists, false otherwise
}

public function updatePassword($email, $password) {
    $db = config::getConnexion(); 
    $query = "UPDATE users SET password = :password WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $password);
    $stmt->execute();
}

public function getTotalUsers() {
    $db = config::getConnexion(); 
    $query = "SELECT COUNT(*) FROM users";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function getActiveUsers() {
    $db = config::getConnexion(); 
    $query = "SELECT COUNT(*) FROM users WHERE active_account = 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function getPendingVerifications() {
     $db = config::getConnexion(); 
    $query = "SELECT COUNT(*) FROM users WHERE active_account = 0";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}
public function getUserGrowthLast30Days() {
    $db = config::getConnexion(); 
    $growthData = [];
    $date = new DateTime();
    
    // Initialize all 30 days with 0 counts
    for ($i = 29; $i >= 0; $i--) {
        $dateKey = (new DateTime())->modify("-$i days")->format('Y-m-d');
        $growthData[$dateKey] = 0;
    }
    
    // Query database for actual counts
    $query = "SELECT DATE(created_at) as date, COUNT(*) as count 
              FROM users 
              WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
              GROUP BY DATE(created_at)";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    // Merge actual data with initialized array
    while ($row = $result->fetch_assoc()) {
        $growthData[$row['date']] = (int)$row['count'];
    }
    
    return $growthData;
}

public function updateLastLoginDate($id) {


    $db = config::getConnexion(); 
    $query = "UPDATE users
       SET last_login_date = :now,
           active_account      = 1
     WHERE id = :id
";  
    $now= date('Y-m-d H:i:s');
    $stmt= $db->prepare($query);
    $stmt->bindParam(":now", $now);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    
    
    
    
}
public function updateProfileImage($user_id, $imagePath) {
    $db = config::getConnexion(); 
    $query = "UPDATE users SET profile_image = :imagePath WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':imagePath', $imagePath);
    $stmt->bindValue(':id', $user_id);
    $stmt->execute();
   
}
function getUserIdByEmail($email) {
    $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
    
    $db = config::getConnexion(); 
    
    $stmt = $db->prepare($sql); // use $sql, not $query
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC); // fetch the result

    return $user ? $user['id'] : null;
}




}
?>
