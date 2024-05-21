<?php
include 'db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['register-username'];
    $password = $_POST['register-password'];
    $password_confirm = $_POST['register-password-confirm'];
    $admin_key = $_POST['admin-key'];
    $error_message = '';

    if ($password !== $password_confirm) {
        $error_message = "Passwords do not match";
    } else {
        $sql = "SELECT * FROM users WHERE admin_key='$admin_key' AND password=''";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password, admin_key) VALUES ('$username', '$hashed_password', '$admin_key')";
            if ($conn->query($sql) === TRUE) {
                header("Location: signin.html");
                exit();
            } else {
                $error_message = "Error: " . $conn->error;
            }
        } else {
            $error_message = "Invalid admin key";
        }
    }
    $_SESSION['error_message'] = $error_message;
    header("Location: register.html");
    exit();
}
$conn->close();
?>
