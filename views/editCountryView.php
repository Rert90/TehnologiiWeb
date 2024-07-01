<?php
require_once '../config/db.php';
require_once '../controllers/EditCountryController.php';

$editCountryController = new EditCountryController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $editCountryController->deleteCountry($_GET['geo'], $_GET['bmi']);
        header("Location: ../public/admin.php");
        exit();
    } else {
        $editCountryController->updateCountry($_POST);
        header("Location: ../public/admin.php");
        exit();
    }
}

$countryData = $editCountryController->getCountryData($_GET['geo'], $_GET['bmi']);
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <link rel="shortcut icon" type="image/jpg" href="../public/images/logomin.jpg"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Country Data - VisB</title>
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
<div class="form-container">
    <h2>Edit Country Data</h2>
    <form method="post">
        <label for="geo">Geo:</label>
        <input type="text" id="geo" name="geo" value="<?php echo htmlspecialchars($countryData['geo']); ?>" required>

        <label for="bmi">BMI:</label>
        <input type="text" id="bmi" name="bmi" value="<?php echo htmlspecialchars($countryData['bmi']); ?>" required>
        
        <label for="year_2008">2008:</label>
        <input type="text" id="year_2008" name="year_2008" value="<?php echo htmlspecialchars($countryData['year_2008']); ?>">
        
        <label for="year_2014">2014:</label>
        <input type="text" id="year_2014" name="year_2014" value="<?php echo htmlspecialchars($countryData['year_2014']); ?>">
        
        <label for="year_2017">2017:</label>
        <input type="text" id="year_2017" name="year_2017" value="<?php echo htmlspecialchars($countryData['year_2017']); ?>">
        
        <label for="year_2019">2019:</label>
        <input type="text" id="year_2019" name="year_2019" value="<?php echo htmlspecialchars($countryData['year_2019']); ?>">
        
        <label for="year_2022">2022:</label>
        <input type="text" id="year_2022" name="year_2022" value="<?php echo htmlspecialchars($countryData['year_2022']); ?>">

        <button type="submit">Update Country</button>
        <button type="submit" name="delete" class="delete-btn">Delete Country</button>
    </form>
</div>
</body>
</html>
