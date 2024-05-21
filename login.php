<?php
include 'db.php';

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['login-username'];
    $password = $_POST['login-password'];
    $error_message = '';

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location: admin.php");
            exit();
        } else {
            $error_message = "Invalid password";
        }
    } else {
        $error_message = "No user found";
    }
    $_SESSION['error_message'] = $error_message;
    header("Location: signin.html");
    exit();
}
$conn->close();
?>
