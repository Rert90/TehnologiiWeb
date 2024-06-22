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
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-top: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .checkbox-container label {
            display: block;
            margin-bottom: 10px;
        }
        .chart-box {
            display: none;
            margin-top: 20px;
        }
        .select-all-btn {
            display: block;
            margin-bottom: 10px;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .select-all-btn:hover {
            background-color: #45a049;
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
                <input type="radio" name="bmi-category" value="BMI25-29" onchange="generateChart()"> Overweight
            </label>
            <label>
                <input type="radio" name="bmi-category" value="BMI_GE25" onchange="generateChart()"> Obese
            </label>
            <label>
                <input type="radio" name="bmi-category" value="BMI_GE30" onchange="generateChart()"> Pre-Obese
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
<script>
let chartInstance = null;
let allSelected = false;
const countryMapping = {
    AT: 'Austria',
    BE: 'Belgium',
    BG: 'Bulgaria',
    CH: 'Switzerland',
    CY: 'Cyprus',
    CZ: 'Czech Republic',
    DE: 'Germany',
    DK: 'Denmark',
    EE: 'Estonia',
    EL: 'Greece',
    ES: 'Spain',
    FI: 'Finland',
    FR: 'France',
    HR: 'Croatia',
    HU: 'Hungary',
    IE: 'Ireland',
    IS: 'Iceland',
    IT: 'Italy',
    LT: 'Lithuania',
    LU: 'Luxembourg',
    LV: 'Latvia',
    ME: 'Montenegro',
    MK: 'North Macedonia',
    MT: 'Malta',
    NL: 'Netherlands',
    NO: 'Norway',
    PL: 'Poland',
    PT: 'Portugal',
    RO: 'Romania',
    RS: 'Serbia',
    SE: 'Sweden',
    SI: 'Slovenia',
    SK: 'Slovakia',
    TR: 'Turkey',
    UK: 'United Kingdom'
};

const colors = [
    'rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)',
    'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(199, 199, 199, 0.2)', 'rgba(83, 102, 255, 0.2)',
    'rgba(255, 99, 71, 0.2)', 'rgba(60, 179, 113, 0.2)', 'rgba(135, 206, 250, 0.2)', 'rgba(218, 165, 32, 0.2)',
    'rgba(220, 20, 60, 0.2)', 'rgba(0, 191, 255, 0.2)', 'rgba(128, 0, 128, 0.2)', 'rgba(34, 139, 34, 0.2)',
    'rgba(240, 230, 140, 0.2)', 'rgba(139, 0, 0, 0.2)', 'rgba(72, 61, 139, 0.2)', 'rgba(233, 150, 122, 0.2)',
    'rgba(100, 149, 237, 0.2)', 'rgba(255, 215, 0, 0.2)', 'rgba(75, 0, 130, 0.2)', 'rgba(143, 188, 143, 0.2)',
    'rgba(0, 250, 154, 0.2)', 'rgba(199, 21, 133, 0.2)', 'rgba(65, 105, 225, 0.2)', 'rgba(210, 105, 30, 0.2)',
    'rgba(70, 130, 180, 0.2)', 'rgba(244, 164, 96, 0.2)', 'rgba(255, 20, 147, 0.2)', 'rgba(112, 128, 144, 0.2)',
    'rgba(255, 69, 0, 0.2)', 'rgba(139, 69, 19, 0.2)', 'rgba(0, 128, 128, 0.2)', 'rgba(255, 140, 0, 0.2)'
];

const borderColors = colors.map(color => color.replace('0.2', '1'));

async function populateCheckboxes(containerId, url, isBmiCategory = false) {
    const response = await fetch(url);
    const data = await response.json();
    const container = document.getElementById(containerId);
    container.innerHTML = '';

    if (containerId === 'filter-country') {
        const selectAllButton = document.createElement('button');
        selectAllButton.type = 'button';
        selectAllButton.className = 'select-all-btn';
        selectAllButton.innerText = 'Select All';
        selectAllButton.onclick = toggleSelectAllCountries;
        container.appendChild(selectAllButton);
    }

    data.forEach(item => {
        const label = document.createElement('label');
        const checkbox = document.createElement('input');
        checkbox.type = isBmiCategory ? 'radio' : 'checkbox';
        checkbox.value = item.geo ? item.geo : item.year ? item.year : item.bmi;
        checkbox.name = isBmiCategory ? 'bmi-category' : containerId; 
        label.appendChild(checkbox);
        const countryName = countryMapping[item.geo] || item.geo; 
        label.appendChild(document.createTextNode(isBmiCategory ? getBmiLabel(item.bmi) : (item.geo ? countryName : item.year)));
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
    const checkboxes = document.querySelectorAll('#filter-country input[type="checkbox"]');
    allSelected = !allSelected;
    checkboxes.forEach(checkbox => {
        checkbox.checked = allSelected;
    });
    document.querySelector('.select-all-btn').innerText = allSelected ? 'Deselect All' : 'Select All';
}

function generateChart() {
    const chartType = document.getElementById('chart-type').value;
    const filterCountry = Array.from(document.querySelectorAll('#filter-country input:checked')).map(checkbox => checkbox.value);
    const filterYear = Array.from(document.querySelectorAll('#filter-year input:checked')).map(checkbox => checkbox.value);
    const filterBmiCategory = document.querySelector('input[name="bmi-category"]:checked') ? document.querySelector('input[name="bmi-category"]:checked').value : null;

    console.log('Chart Type:', chartType);
    console.log('Selected Countries:', filterCountry);
    console.log('Selected Years:', filterYear);
    console.log('Selected BMI Category:', filterBmiCategory);

    if (!filterBmiCategory) {
        alert('Please select a BMI category.');
        return;
    }

    const params = new URLSearchParams();
    if (filterCountry.length > 0) {
        params.append('country', filterCountry.join(','));
    }
    if (filterYear.length > 0) {
        params.append('year', filterYear.join(','));
    }
    if (filterBmiCategory) {
        params.append('bmi', filterBmiCategory);
    }

    console.log('API Params:', params.toString());

    fetch('../public/api.php?' + params.toString())
        .then(response => response.json())
        .then(data => {
            console.log('API Response Data:', data);
            if (chartInstance) {
                chartInstance.destroy();
            }

            const labels = filterYear;
            const datasets = filterCountry.map((country, index) => ({
                label: countryMapping[country] || country,
                data: filterYear.map(year => {
                    const yearData = data.find(item => item.geo === country);
                    return yearData ? yearData['year_' + year] : null;
                }),
                backgroundColor: colors[index % colors.length],
                borderColor: borderColors[index % borderColors.length],
                borderWidth: 1
            }));

            console.log('Chart Labels:', labels);
            console.log('Chart Datasets:', datasets);

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

            document.getElementById('chart-box').style.display = 'block';
            document.querySelector('.export-buttons').style.display = 'block';
        })
        .catch(error => console.error('Error:', error));
}

function exportChart(format) {
    const canvas = document.getElementById('bmi-chart');
    const link = document.createElement('a');

    if (format === 'csv') {
        const filterCountry = Array.from(document.querySelectorAll('#filter-country input:checked')).map(checkbox => checkbox.value);
        const filterYear = Array.from(document.querySelectorAll('#filter-year input:checked')).map(checkbox => checkbox.value);
        const filterBmiCategory = document.querySelector('input[name="bmi-category"]:checked') ? document.querySelector('input[name="bmi-category"]:checked').value : null;

        const params = new URLSearchParams();
        if (filterCountry.length > 0) {
            params.append('country', filterCountry.join(','));
        }
        if (filterYear.length > 0) {
            params.append('year', filterYear.join(','));
        }
        if (filterBmiCategory) {
            params.append('bmi', filterBmiCategory);
        }

        fetch('../public/api.php?' + params.toString())
            .then(response => response.json())
            .then(data => {
                const csvContent = "data:text/csv;charset=utf-8,"
                    + "Country,BMI\n"
                    + data.map(item => filterYear.map(year => `${countryMapping[item.geo] || item.geo},${item['year_' + year]}`).join("\n")).join("\n");

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
