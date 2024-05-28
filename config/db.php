<?php
$dsn = 'mysql:host=localhost;dbname=visb_db';
$username = 'root';
$password = ''; // Completează parola pentru utilizatorul root dacă este necesară

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
