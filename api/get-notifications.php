<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    $db = new PDO('mysql:host=localhost;dbname=mydbname', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Fetch ALL notifications for ALL users
    $query = "SELECT * FROM notifications ORDER BY date_notification DESC LIMIT 100";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ Count ALL unread notifications (for all users)
    $queryCount = "SELECT COUNT(*) as count FROM notifications WHERE lu = 0";
    $stmtCount = $db->prepare($queryCount);
    $stmtCount->execute();
    $countResult = $stmtCount->fetch(PDO::FETCH_ASSOC);
    $nonLues = $countResult['count'];

    // ✅ Format relative dates
    foreach ($notifications as &$notif) {
        $date = new DateTime($notif['date_notification']);
        $now = new DateTime();
        $diff = $now->diff($date);

        if ($diff->d > 0) {
            $notif['date_relative'] = 'Il y a ' . $diff->d . ' jour' . ($diff->d > 1 ? 's' : '');
        } elseif ($diff->h > 0) {
            $notif['date_relative'] = 'Il y a ' . $diff->h . ' heure' . ($diff->h > 1 ? 's' : '');
        } elseif ($diff->i > 0) {
            $notif['date_relative'] = 'Il y a ' . $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
        } else {
            $notif['date_relative'] = 'À l\'instant';
        }
    }

    echo json_encode([
        'success' => true,
        'notifications' => $notifications,
        'nonLues' => $nonLues
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
}
?>
