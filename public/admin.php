<?php
session_start();
require_once '../controllers/AdminController.php';

$adminController = new AdminController();

$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

switch ($action) {
    case 'add':
        $adminController->showAddCountryPage();
        break;
    case 'edit':
        $geo = isset($_GET['geo']) ? $_GET['geo'] : null;
        if ($geo) {
            $adminController->showEditCountryPage($geo);
        } else {
            echo "Geo parameter is missing.";
        }
        break;
    default:
        $adminController->showAdminPage();
        break;
}
?>
