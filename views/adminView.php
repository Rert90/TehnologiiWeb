<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

require_once '../config/db.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=visb_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT name, email, message, created_at FROM messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $topCountriesStmt = $pdo->query("SELECT country_code, selection_count FROM country_selections ORDER BY selection_count DESC LIMIT 10");
    $topCountries = $topCountriesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Failed to fetch messages: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <link rel="shortcut icon" type="image/jpg" href="../public/images/logomin.jpg"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - VisB</title>
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
<div class="admin-container">
    <h2>Admin Dashboard</h2>
    
    <h3>Received Messages</h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $message): ?>
                <tr>
                    <td><?php echo htmlspecialchars($message['name']); ?></td>
                    <td><?php echo htmlspecialchars($message['email']); ?></td>
                    <td><?php echo htmlspecialchars($message['message']); ?></td>
                    <td><?php echo htmlspecialchars($message['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Top Selected Countries</h3>
    <table>
        <thead>
            <tr>
                <th>Country Code</th>
                <th>Selection Count</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($topCountries as $country): ?>
                <tr>
                    <td><?php echo htmlspecialchars($country['country_code']); ?></td>
                    <td><?php echo htmlspecialchars($country['selection_count']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
