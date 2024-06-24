<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="en-US">
<head>
    <link rel="shortcut icon" type="image/jpg" href="../public/images/logomin.jpg"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Charts - VisB</title>
    <link href="../public/css/styles.css" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/9f74761d90.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <?php if (isset($_SESSION['username'])): ?>
            <li><a href="../public/admin.php"><i class="fa fa-black-tie"></i> Admin</a></li>
        <?php else: ?>
            <li><a href="../public/login.php"><i class="fas fa-sign-in-alt"></i> Sign In</a></li>
        <?php endif; ?>
    </ul>
</nav>
<h2 class="centered-title">Visualize and Compare BMI Data</h2>
<div class="filters-box">
    <form id="chart-form">
        <label for="chart-type">Chart Type:</label>
        <select id="chart-type" name="chart-type">
            <option value="bar">Bar</option>
            <option value="line">Line</option>
            <option value="pie">Pie</option>
        </select>

        <label for="filter-country">Filter by Country:</label>
        <div id="filter-country" class="checkbox-container">
            <button type="button" class="select-all-btn" onclick="toggleSelectAllCountries()">Select All</button>
        </div>

        <label for="filter-year">Filter by Year:</label>
        <div id="filter-year" class="checkbox-container"></div>

        <label for="filter-bmi-category">Filter by BMI Category:</label>
        <div id="filter-bmi-category" class="checkbox-container">
            <label>
                <input type="radio" name="bmi-category" value="BMI25-29" onchange="generateChart()"> Pre-Obese
            </label>
            <label>
                <input type="radio" name="bmi-category" value="BMI_GE25" onchange="generateChart()"> Overweight
            </label>
            <label>
                <input type="radio" name="bmi-category" value="BMI_GE30" onchange="generateChart()"> Obese
            </label>
        </div>

        <button type="button" onclick="generateChart()">Generate Chart</button>
    </form>
</div>

<div class="chart-box" id="chart-box">
    <canvas id="bmi-chart"></canvas>
    <div class="export-buttons">
        <button class="button-download" onclick="exportChart('csv')">Export CSV</button>
        <button class="button-download" onclick="exportChart('webp')">Export WebP</button>
        <button class="button-download" onclick="exportChart('svg')">Export SVG</button>
    </div>
</div>
<script src="../public/js/charts.js"></script>
</body>
</html>
