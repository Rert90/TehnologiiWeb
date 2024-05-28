<?php
require_once '../config/db.php';
require_once '../controllers/AdminController.php';

$adminController = new AdminController();
$adminController->index();
?>
