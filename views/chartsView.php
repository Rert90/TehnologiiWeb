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
    <style>
        .checkbox-container {
            column-count: 3;
            column-gap: 20px;
        }
        .checkbox-container label {
            display: block;
        }
    </style>
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
        <div id="filter-country" class="checkbox-container">
            <label>
                <input type="checkbox" id="select-all-countries" onclick="toggleSelectAllCountries()">
                Select All
            </label>
        </div>

        <label for="filter-year">Filter by Year:</label>
        <div id="filter-year" class="checkbox-container"></div>

        <label for="filter-bmi-category">Filter by BMI Category:</label>
        <div id="filter-bmi-category" class="checkbox-container"></div>

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
let chartInstance = null;

async function populateCheckboxes(containerId, url, isBmiCategory = false) {
    const response = await fetch(url);
    const data = await response.json();
    const container = document.getElementById(containerId);
    if (containerId === 'filter-country') {
        container.innerHTML = '<label><input type="checkbox" id="select-all-countries" onclick="toggleSelectAllCountries()"> Select All</label>';
    } else {
        container.innerHTML = '';
    }

    data.forEach(item => {
        const label = document.createElement('label');
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.value = item.geo ? item.geo : item.year ? item.year : item.bmi;
        checkbox.name = containerId;
        label.appendChild(checkbox);
        label.appendChild(document.createTextNode(isBmiCategory ? getBmiLabel(item.bmi) : (item.geo ? item.geo : item.year)));
        container.appendChild(label);
    });
}

function getBmiLabel(bmiValue) {
    switch (bmiValue) {
        case 'BMI25-29':
            return 'Overweight';
        case 'BMI_GE25':
            return 'Obese';
        case 'BMI_GE30':
            return 'Pre-Obese';
        default:
            return bmiValue;
    }
}

function toggleSelectAllCountries() {
    const selectAllCheckbox = document.getElementById('select-all-countries');
    const checkboxes = document.querySelectorAll('#filter-country input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        if (checkbox !== selectAllCheckbox) {
            checkbox.checked = selectAllCheckbox.checked;
        }
    });
}

function generateChart() {
    const chartType = document.getElementById('chart-type').value;
    const filterCountry = Array.from(document.querySelectorAll('#filter-country input:checked')).map(checkbox => checkbox.value);
    const filterYear = Array.from(document.querySelectorAll('#filter-year input:checked')).map(checkbox => checkbox.value);
    const filterBmiCategory = Array.from(document.querySelectorAll('#filter-bmi-category input:checked')).map(checkbox => checkbox.value);

    const params = new URLSearchParams();
    if (filterCountry.length > 0) {
        params.append('country', filterCountry.join(','));
    }
    if (filterYear.length > 0) {
        params.append('year', filterYear.join(','));
    }
    if (filterBmiCategory.length > 0) {
        params.append('bmi', filterBmiCategory.join(','));
    }

    fetch('../public/api.php?' + params.toString())
        .then(response => response.json())
        .then(data => {
            if (chartInstance) {
                chartInstance.destroy();
            }

            const labels = data.map(item => item.geo);
            const colors = [
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)'
            ];
            const borderColors = [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
            ];
            const datasets = filterYear.map((year, index) => ({
                label: `BMI Data for ${year}`,
                data: data.map(item => item['year_' + year]),
                backgroundColor: colors[index % colors.length],
                borderColor: borderColors[index % borderColors.length],
                borderWidth: 1
            }));

            const ctx = document.getElementById('bmi-chart').getContext('2d');
            chartInstance = new Chart(ctx, {
                type: chartType,
                data: {
                    labels: labels,
                    datasets: datasets
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
        const filterCountry = Array.from(document.querySelectorAll('#filter-country input:checked')).map(checkbox => checkbox.value);
        const filterYear = Array.from(document.querySelectorAll('#filter-year input:checked')).map(checkbox => checkbox.value);
        const filterBmiCategory = Array.from(document.querySelectorAll('#filter-bmi-category input:checked')).map(checkbox => checkbox.value);

        const params = new URLSearchParams();
        if (filterCountry.length > 0) {
            params.append('country', filterCountry.join(','));
        }
        if (filterYear.length > 0) {
            params.append('year', filterYear.join(','));
        }
        if (filterBmiCategory.length > 0) {
            params.append('bmi', filterBmiCategory.join(','));
        }

        fetch('../public/api.php?' + params.toString())
            .then(response => response.json())
            .then(data => {
                const csvContent = "data:text/csv;charset=utf-8,"
                    + "Country,BMI\n"
                    + data.map(item => filterYear.map(year => `${item.geo},${item['year_' + year]}`).join("\n")).join("\n");

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
    populateCheckboxes('filter-country', '../public/api.php?action=getCountries');
    populateCheckboxes('filter-year', '../public/api.php?action=getYears');
    populateCheckboxes('filter-bmi-category', '../public/api.php?action=getBmi', true);
});
</script>
</body>
</html>
