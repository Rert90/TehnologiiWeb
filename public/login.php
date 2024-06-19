<?php
require_once '../config/db.php'; 
require_once '../controllers/UserController.php';

$database = new Database();
$dbConnection = $database->getConnection();
$userController = new UserController($dbConnection);
$userController->login();
?>
