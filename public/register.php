<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/db.php'; 
require_once '../controllers/UserController.php';
$database = new Database();
$dbConnection = $database->getConnection();
$userController = new UserController($dbConnection);
$userController->register();
?>
