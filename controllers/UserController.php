<?php
require_once '../models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new UserModel($db);
    }

    public function login() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['login-username'];
            $password = $_POST['login-password'];
            $admin = $this->userModel->getAdmin($username);

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'admin';
                header('Location: ../public/admin.php');
                exit();
            } else {
                $error = 'Invalid username or password';
                include '../views/loginView.php';
            }
        } else {
            include '../views/loginView.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['register-username'];
            $password = $_POST['register-password'];
            $confirmPassword = $_POST['register-password-confirm'];
            $adminKey = trim($_POST['admin-key']); 

            if ($password !== $confirmPassword) {
                $error = 'Passwords do not match';
                include '../views/registerView.php';
            } else if (empty($adminKey)) {
                $error = 'Admin key is required';
                include '../views/registerView.php';
            } else {
                $existingAdminKey = $this->userModel->checkAdminKey($adminKey);
                if ($existingAdminKey) {
                    $this->userModel->addAdmin($username, $password, $adminKey);
                    header('Location: ../public/login.php');
                    exit();
                } else {
                    $error = 'Invalid admin key';
                    include '../views/registerView.php';
                }
            }
        } else {
            include '../views/registerView.php';
        }
    }
}
?>
