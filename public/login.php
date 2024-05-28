<?php
require_once '../config/db.php';
require_once '../controllers/UserController.php';

$userController = new UserController($db);
$userController->login();
?>
