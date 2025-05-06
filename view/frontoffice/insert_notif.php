<?php
require_once('../../controller/propcontroller.php');

$prop = new propcontroller();

$data = json_decode(file_get_contents("php://input"), true);
$sender = $data['sender'];
$receiver = $data['receiver'];
$content = $data['content'];
$prop->inserernotifmeet($sender,$receiver,$content);
?>