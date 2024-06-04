<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <link rel="shortcut icon" type="image/jpg" href="../public/images/logomin.jpg"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VisB</title>
    <link href="../public/css/styles.css" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/9f74761d90.js" crossorigin="anonymous"></script>
</head>
<body>
<nav>
    <div class="logo">
        <img src="../public/images/logo.jpg" alt="Logo proiect">
    </div>
    <ul>
    <ul>
        <li><a href="../public/index.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="../public/charts.php"><i class="fas fa-chart-bar"></i> Charts</a></li>
        <li><a href="../public/contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
        <?php if (isset($_SESSION['username'])): ?>
            <li><a href="../public/admin.php"><i class="fa fa-black-tie" ></i>  Admin</a></li>
        <?php else: ?>
            <li><a href="../public/login.php"><i class="fas fa-sign-in-alt"></i> Sign In</a></li>
        <?php endif; ?>
    </ul>
</nav>
<div class="floating-logo">
    <img src="../public/images/logomin.jpg" alt="Logo proiect">
</div>
<section class="bmi-explorer">
    <h2>Bine ați venit la BMI Explorer!</h2>
    <p>Explorați și comparați indicele de masă corporală (BMI) într-un mod interactiv și informativ. BMI Explorer vă permite să accesați și să analizați datele referitoare la BMI folosind datele publice furnizate de Eurostat, prin intermediul propriului nostru API REST/GraphQL.</p>
    <h3>Caracteristici principale:</h3>
    <ul>
        <li>Vizualizare interactivă: Experimentați grafice interactive și instrumente de vizualizare care vă permit să înțelegeți și să explorați diferite aspecte ale BMI.</li>
        <li>Comparare facilă: Comparați nivelurile BMI între țări și în funcție de sex, pentru a identifica tendințe și diferențe semnificative.</li>
        <li>Acces rapid la date: Utilizați API-ul nostru REST/GraphQL pentru a accesa datele brute și a le integra în propriile dvs. aplicații sau analize.</li>
        <li>Actualizări constante: Datele noastre sunt actualizate periodic, asigurându-vă că aveți întotdeauna cele mai recente informații disponibile.</li>
    </ul>
    <p>Începeți să explorați acum și să descoperiți insigiențele interesante despre BMI în întreaga Europă și dincolo!</p>
    <div class="bmi-box">
        <p class="bmi-text">BMI</p>
        <p class="bmi-subtext">(apropie cursorul)</p>
        <div class="bmi-image">
            <img src="../public/images/imaginebmi.png" alt="Imagine BMI">
        </div>
    </div>
</section>
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
