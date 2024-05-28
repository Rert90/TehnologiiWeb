<!DOCTYPE html>
<html lang="en-US">
<head>
    <link rel="shortcut icon" type="image/jpg" href="../public/images/logomin.jpg"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact - VisB</title>
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
        <li><a href="../public/login.php"><i class="fas fa-sign-in-alt"></i> Sign In</a></li></ul>
</nav>
<div class="floating-logo">
    <img src="../public/images/logomin.jpg" alt="Logo proiect">
</div>
<div class="contact-container">
    <div class="contact-box">
        <h2>Contact Us</h2>
        <form action="../public/proceseaza.php" method="post">
            <ul>
                <li>
                    <label for="name"><i class="fas fa-user"></i> Name:</label>
                    <input type="text" id="name" name="user_name" placeholder="Name" required>
                </li>
                <li>
                    <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </li>
                <li>
                    <label for="message"><i class="fas fa-pencil-alt"></i> Message:</label>
                    <textarea id="message" name="message" placeholder="Your message..." required></textarea>
                </li>
                <li>
                    <button class="button-contact" type="submit"><i class="fas fa-paper-plane"></i> Submit</button>
                </li>
            </ul>
        </form>
    </div>
    <div class="contact-box">
        <h2>Contact Information</h2>
        <ul>
            <li><a href="tel:+40751606896"><i class="fas fa-phone"></i> Call us</a></li>
            <li><a href="mailto:robertolariu0@gmail.com?subject=MailBMI"><i class="fas fa-envelope"></i> Email Us</a></li>
            <li><i class="fas fa-map-marker-alt"></i> Address: Strada General Henri Mathias Berthelot 16, Iași, Romania</li>
        </ul>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2712.186597337963!2d27.572146276120442!3d47.17378307115338!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40cafb6227e846bd%3A0x193e4b6864504e2c!2sFacultatea%20de%20Informatic%C4%83!5e0!3m2!1sro!2sro!4v1712658004540!5m2!1sro!2sro" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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
</body>
</html>
