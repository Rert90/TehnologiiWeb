<?php
require_once '../config/db.php';
require_once '../controllers/PageController.php';

$pageController = new PageController();
$pageController->contact();
?>
