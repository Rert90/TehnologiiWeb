<?php
class PageController {
    public function home() {
        include '../views/homeView.php';
    }

    public function contact() {
        include '../views/contactView.php';
    }

    public function charts() {
        include '../views/chartsView.php';
    }

    public function login() {
        include '../views/loginView.php';
    }

    public function register() {
        include '../views/registerView.php';
    }
}
?>
