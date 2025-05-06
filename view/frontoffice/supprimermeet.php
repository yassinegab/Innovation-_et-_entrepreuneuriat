<?php
require_once('../../controller/propcontroller.php');
session_start();
$prop = new propcontroller();
if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo "Non autorisé";
    exit;
}
$prop->deletemeet($_SESSION['id']);

?>