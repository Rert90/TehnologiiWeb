<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../public/login.php");
    exit();
}

$directory = 'C:/xampp/htdocs/TehnologiiWeb'; 
$files = scandir($directory);
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <link rel="shortcut icon" type="image/jpg" href="logomin.jpg"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - VisB</title>
    <link href="styles.css" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/9f74761d90.js" crossorigin="anonymous"></script>
</head>
<body>
<nav>
    <div class="logo">
        <img src="logo.jpg" alt="Logo proiect">
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
    <h3>Project Files</h3>
    <ul>
        <?php if ($files !== false): ?>
            <?php foreach ($files as $file): ?>
                <?php if ($file !== '.' && $file !== '..'): ?>
                    <li><a href="<?php echo $directory . '/' . $file; ?>" target="_blank"><?php echo $file; ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Could not access the project files directory.</p>
        <?php endif; ?>
    </ul>
</div>
<footer class="footer">
    <div class="footer-section">
        <h3 class="footer-heading">About Us</h3>
        <p class="footer-text">Bine ați venit pe platforma noastră dedicată sănătății și bunăstării! Aici puteți vizualiza și compara informații despre indicele de masă corporală (IMC), folosind datele precise furnizate de Eurostat. Scopul nostru este să vă oferim instrumentele necesare pentru a lua decizii informate în privința sănătății dumneavoastră și pentru a vă ajuta să atingeți obiectivele de wellness. Vă invităm să explorați resursele noastre și să ne urmăriți pe rețelele sociale pentru a rămâne la curent cu cele mai recente actualizări și sfaturi despre sănătate.</p>
    </div>
    <div class="footer-follow">
        <h3 class="footer-heading">Follow Us</h3>
        <a href="https://www.facebook.com/" class="footer-link" target="_blank"><i class="fab fa-facebook footer-icon"></i></a>
        <a href="https://www.instagram.com/" class="footer-link" target="_blank"><i class="fab fa-instagram footer-icon"></i></a>
        <a href="https://www.youtube.com/" class="footer-link" target="_blank"><i class="fab fa-youtube footer-icon"></i></a>
    </div>
</footer>
</body>
</html>
