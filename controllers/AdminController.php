<?php
class AdminController {
    public function index() {
        session_start();
        if (!isset($_SESSION['username'])) {
            header("Location: ../public/login.php");
            exit();
        }
        include '../views/adminView.php';
    }
}
?>
