<?php
require_once '../config/db.php';
require_once '../controllers/ApiController.php';

$apiController = new ApiController();
$apiController->getBmiData();
?>
