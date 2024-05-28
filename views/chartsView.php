<?php session_start(); ?>
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
            <li><a href="../public/admin.php"><i class="fa fa-black-tie" ></i>  Admin</a></li>
        <?php else: ?>
            <li><a href="../public/login.php"><i class="fas fa-sign-in-alt"></i> Sign In</a></li>
        <?php endif; ?>
    </ul>
</nav>
<div class="charts-container">
    <h2>Visualize and Compare BMI Data</h2>
    <form id="chart-form">
        <label for="chart-type">Choose Chart Type:</label>
        <select id="chart-type" name="chart-type">
            <option value="bar">Bar</option>
            <option value="line">Line</option>
            <option value="pie">Pie</option>
        </select>
        
        <label for="filter-country">Filter by Country:</label>
        <select id="filter-country" name="filter-country">
            <option value="all">All</option>
            <option value="Romania">Romania</option>
            <option value="Germany">Germany</option>
            <option value="France">France</option>
            <option value="Italy">Italy</option>
        </select>
        
        <button type="button" onclick="generateChart()">Generate Chart</button>
    </form>
    
    <canvas id="bmi-chart"></canvas>
    <div class="export-buttons">
        <button onclick="exportChart('csv')">Export CSV</button>
        <button onclick="exportChart('webp')">Export WebP</button>
        <button onclick="exportChart('svg')">Export SVG</button>
    </div>
</div>
<script>
async function generateChart() {
    const chartType = document.getElementById('chart-type').value;
    const filterCountry = document.getElementById('filter-country').value;
    
    const response = await fetch('../public/api.php');
    const data = await response.json();
    
    let filteredData = data;
    if (filterCountry !== 'all') {
        filteredData = data.filter(item => item.country === filterCountry);
    }
    
    const labels = filteredData.map(item => item.country);
    const maleBmi = filteredData.map(item => item.male_bmi);
    const femaleBmi = filteredData.map(item => item.female_bmi);
    
    const ctx = document.getElementById('bmi-chart').getContext('2d');
    const chart = new Chart(ctx, {
        type: chartType,
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Male BMI',
                    data: maleBmi,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Female BMI',
                    data: femaleBmi,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function exportChart(format) {
    const canvas = document.getElementById('bmi-chart');
    let link = document.createElement('a');
    
    if (format === 'csv') {
        const chartType = document.getElementById('chart-type').value;
        const filterCountry = document.getElementById('filter-country').value;
        const response = await fetch('../public/api.php');
        const data = await response.json();
        
        let filteredData = data;
        if (filterCountry !== 'all') {
            filteredData = data.filter(item => item.country === filterCountry);
        }
        
        const csvContent = "data:text/csv;charset=utf-8,"
            + "Country,Male BMI,Female BMI\n"
            + filteredData.map(item => `${item.country},${item.male_bmi},${item.female_bmi}`).join("\n");
        
        link.setAttribute('href', encodeURI(csvContent));
        link.setAttribute('download', 'bmi_data.csv');
        link.click();
        
    } else if (format === 'webp') {
        link.href = canvas.toDataURL('image/webp');
        link.download = 'chart.webp';
        link.click();
        
    } else if (format === 'svg') {
        link.href = canvas.toDataURL('image/svg+xml');
        link.download = 'chart.svg';
        link.click();
    }
}
</script>
</body>
</html>
