<?php
require_once('../../controller/propcontroller.php');
session_start();
$prop = new propcontroller();
if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo "Non autorisé";
    exit;
}
$meeting=$prop->selectmeet($_SESSION['id']);
echo json_encode(["success" => true, "data" => $meeting]);


?>