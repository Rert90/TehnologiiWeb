<!DOCTYPE html>
<html lang="en-US">
<head>
    <link rel="shortcut icon" type="image/jpg" href="../public/images/logomin.jpg"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - VisB</title>
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
        <li><a href="../public/login.php"><i class="fas fa-sign-in-alt"></i> Sign In</a></li>
    </ul>
</nav>
<div class="login-register-section">
    <div class="login-register-container">
        <form id="login-form" class="login-register-form" action="../public/login.php" method="post">
            <div class="input-container">
                <label for="login-username"><i class="fas fa-user"></i> Username:</label>
                <input type="text" id="login-username" name="login-username" required>
            </div>
            <div class="input-container">
                <label for="login-password"><i class="fas fa-lock"></i> Password:</label>
                <input type="password" id="login-password" name="login-password" required>
            </div>
            <button type="submit" class="button-login"><i class="fas fa-sign-in-alt"></i> Login</button>
            <?php if (isset($error)): ?>
                <p style="color:red;"><?= $error ?></p>
            <?php endif; ?>
        </form>
        <p>Don't have an account? <a href="../public/register.php">Register here</a></p> <!-- Corectat aici -->
    </div>
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
</
