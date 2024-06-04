<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['user_name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    try {
    
        $pdo = new PDO('mysql:host=localhost;dbname=visb_db', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);

    
        if ($stmt->execute()) {
            $_SESSION['message'] = "Mesajul a fost trimis cu succes!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "A apărut o eroare la trimiterea mesajului.";
            $_SESSION['msg_type'] = "error";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Eroare: " . $e->getMessage();
        $_SESSION['msg_type'] = "error";
    }

    header("Location: ../public/contact.php");
    exit();
}
?>
