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
            header("Location: login.php?message=signup_success");
            exit();
        } else {
            echo "❌ Erreur lors de l'ajout.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
    
    

}
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
            header('Location: ../backoffice/admin.php');
            exit();
            } else {
            header('Location: welcome_page.php');
            exit();

            }
        }
        } catch (PDOException $e) {
            echo 'Erreur BDD : ' . htmlspecialchars($e->getMessage());
        }
    }
    public function getAllUsers(): array {
        try {
            $db = Config::getConnexion();
            $sql = 'SELECT id, name, lastName, email, birthdate
                    FROM users
                    ORDER BY id';
                        $stmt = $db->query($sql);
           
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $users;
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
                    $row['email']
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
                        birthdate = :birthdate
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name'      => $user->getName(),
                ':lastName'  => $user->getLastName(),
                ':email'     => $user->getEmail(),
                ':birthdate' => $user->getBirthdate(),
                ':id'        => $user->getId()
            ]);
        } else {
            
            

            $sql = "UPDATE users SET 
                        name = :name,
                        lastName = :lastName,
                        email = :email,
                        birthdate = :birthdate,
                        password = :password
                    WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            $birthdate2 = sprintf('%04d-%02d-%02d', $user->getBirthYear(), $user->getBirthMonth(), $user->getBirthDay());
            $stmt->execute([
                ':name'      => $user->getName(),
                ':lastName'  => $user->getLastName(),
                ':email'     => $user->getEmail(),
                ':birthdate' => $birthdate2,
                ':password'  => $user->getPassword(),
                ':id'        => $user->getId()
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

}
