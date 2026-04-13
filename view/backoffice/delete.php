<?php
include_once(__DIR__ .'/../../controller/user_controller.php');
$userC=new User_controller();
$id = $_GET['id'];
$userC->delete_user($id);
header("Location: admin.php?message=signup_success");
exit();
