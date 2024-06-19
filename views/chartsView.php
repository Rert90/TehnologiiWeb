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
        </select>

        <label for="filter-year">Filter by Year:</label>
        <select id="filter-year" name="filter-year"></select>
        
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
async function populateSelectOptions(selectId, url, defaultOption = null) {
    const response = await fetch(url);
    const data = await response.json();
    const select = document.getElementById(selectId);
    select.innerHTML = ''; 

    if (defaultOption) {
        const defaultOpt = document.createElement('option');
        defaultOpt.value = defaultOption.toLowerCase();
        defaultOpt.innerHTML = defaultOption;
        select.appendChild(defaultOpt);
    }

    data.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.geo ? item.geo.toLowerCase() : item.year;
        opt.innerHTML = item.geo ? item.geo : item.year;
        select.appendChild(opt);
    });
}

function generateChart() {
    const chartType = document.getElementById('chart-type').value;
    const filterCountry = document.getElementById('filter-country').value;
    const filterYear = document.getElementById('filter-year').value;

    const params = new URLSearchParams();
    if (filterCountry !== 'all') {
        params.append('country', filterCountry);
    }
    if (filterYear) {
        params.append('year', filterYear);
    }

    fetch('../public/api.php?' + params.toString())
        .then(response => response.json())
        .then(data => {
            console.log(data); 
            const labels = data.map(item => item.geo);
            const bmiValues = data.map(item => item['year_' + filterYear]);

            const ctx = document.getElementById('bmi-chart').getContext('2d');
            new Chart(ctx, {
                type: chartType,
                data: {
                    labels: labels,
                    datasets: [{
                        label: `BMI Data for ${filterYear}`,
                        data: bmiValues,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error:', error));
}

function exportChart(format) {
    const canvas = document.getElementById('bmi-chart');
    const link = document.createElement('a');
    
    if (format === 'csv') {
        const chartType = document.getElementById('chart-type').value;
        const filterCountry = document.getElementById('filter-country').value;
        const filterYear = document.getElementById('filter-year').value;

        const params = new URLSearchParams();
        if (filterCountry !== 'all') {
            params.append('country', filterCountry);
        }
        if (filterYear) {
            params.append('year', filterYear);
        }

        fetch('../public/api.php?' + params.toString())
            .then(response => response.json())
            .then(data => {
                const csvContent = "data:text/csv;charset=utf-8,"
                    + "Country,BMI\n"
                    + data.map(item => `${item.geo},${item['year_' + filterYear]}`).join("\n");

                link.setAttribute('href', encodeURI(csvContent));
                link.setAttribute('download', 'bmi_data.csv');
                link.click();
            });
        
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

document.addEventListener('DOMContentLoaded', () => {
    populateSelectOptions('filter-country', '../public/api.php?action=getCountries', 'All');
    populateSelectOptions('filter-year', '../public/api.php?action=getYears');
});
</script>
</body>
</html>
