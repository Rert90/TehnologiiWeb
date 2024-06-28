<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=visb_db', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("
            INSERT INTO bmi_data (geo, bmi, year_2008, year_2014, year_2017, year_2019, year_2022)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_POST['geo'],
            $_POST['bmi'],
            $_POST['year_2008'],
            $_POST['year_2014'],
            $_POST['year_2017'],
            $_POST['year_2019'],
            $_POST['year_2022']
        ]);

        header("Location: ../public/admin.php");
        exit();
    } catch (PDOException $e) {
        die('Failed to insert data: ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <link rel="shortcut icon" type="image/jpg" href="../public/images/logomin.jpg"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Country Data - VisB</title>
    <link href="../public/css/styles.css" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/9f74761d90.js" crossorigin="anonymous"></script>
</head>
<body>
<nav>
    <div class="logo">
        <img src="../public/images/logo.jpg" alt="Logo proiect">
    </div>
    <ul>
        <li><a href="../public/index.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="../public/charts.php"><i class="fas fa-chart-bar"></i> Charts</a></li>
        <li><a href="../public/contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
        <li><a href="../public/logout.php"><i class="fas fa-sign-out-alt"></i> Disconnect</a></li>
    </ul>
</nav>
<div class="add-container">
    <h2>Add Country Data</h2>
    <form method="post" class="form-container">
        <label for="geo" class="form-label">Geo:</label>
        <input type="text" id="geo" name="geo" class="form-input" required>

        <label for="bmi" class="form-label">BMI:</label>
        <input type="text" id="bmi" name="bmi" class="form-input" required>
        
        <label for="year_2008" class="form-label">2008:</label>
        <input type="text" id="year_2008" name="year_2008" class="form-input">
        
        <label for="year_2014" class="form-label">2014:</label>
        <input type="text" id="year_2014" name="year_2014" class="form-input">
        
        <label for="year_2017" class="form-label">2017:</label>
        <input type="text" id="year_2017" name="year_2017" class="form-input">
        
        <label for="year_2019" class="form-label">2019:</label>
        <input type="text" id="year_2019" name="year_2019" class="form-input">
        
        <label for="year_2022" class="form-label">2022:</label>
        <input type="text" id="year_2022" name="year_2022" class="form-input">
        
        <button type="submit" class="form-button">Add Country</button>
    </form>
</div>
</body>
</html>
