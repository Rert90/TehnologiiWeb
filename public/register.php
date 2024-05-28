<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../config/db.php';
require_once '../controllers/UserController.php';

$db = new PDO($dsn, $username, $password);
$userController = new UserController($db);
$userController->register();
?>
