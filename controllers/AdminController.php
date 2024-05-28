<?php
class AdminController {
    public function index() {
        session_start();
        if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
            header('Location: login.php');
            exit();
        }

        $directory = 'C:/xampp/htdocs/TehnologiiWeb'; 
        $files = scandir($directory);

        include '../views/adminView.php';
    }
}
?>
