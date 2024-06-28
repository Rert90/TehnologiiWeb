let chartInstance = null;
let allSelected = false;

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
        const countryName = item.geo; 
        label.appendChild(document.createTextNode(isBmiCategory ? getBmiLabel(item.bmi) : (item.geo ? countryName : item.year)));
        container.appendChild(label);
    });
}

function getBmiLabel(bmiValue) {
    switch (bmiValue) {
        case 'BMI25-29':
            return 'Pre-Obese';
        case 'BMI_GE25':
            return 'Overweight';
        case 'BMI_GE30':
            return 'Obese';
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

async function updateCountrySelectionCount(selectedCountries) {
    try {
        const response = await fetch('../public/api.php?action=updateCountrySelectionCount', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ countries: selectedCountries })
        });
        const result = await response.json();
        console.log('Update Country Selection Count Result:', result);
    } catch (error) {
        console.error('Error updating country selection count:', error);
    }
}

function generateChart() {
    const chartType = document.getElementById('chart-type').value;
    const filterCountry = Array.from(document.querySelectorAll('#filter-country input:checked')).map(checkbox => checkbox.value);
    const filterYear = Array.from(document.querySelectorAll('#filter-year input:checked')).map(checkbox => checkbox.value);
    const filterBmiCategory = document.querySelector('input[name="bmi-category"]:checked') ? document.querySelector('input[name="bmi-category"]:checked').value : null;

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

    fetch('../public/api.php?' + params.toString())
        .then(response => response.json())
        .then(data => {
            if (chartInstance) {
                chartInstance.destroy();
            }

            const labels = filterYear;
            const datasets = filterCountry.map((country, index) => ({
                label: country,
                data: filterYear.map(year => {
                    const yearData = data.find(item => item.geo === country);
                    return yearData ? yearData['year_' + year] : null;
                }),
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

            document.getElementById('chart-box').style.display = 'block';
            document.querySelector('.export-buttons').style.display = 'block';

            updateCountrySelectionCount(filterCountry);
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
        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svg.setAttribute("width", canvas.width);
        svg.setAttribute("height", canvas.height);
        const svgNS = svg.namespaceURI;
        const rect = document.createElementNS(svgNS, 'rect');
        rect.setAttribute('width', '100%');
        rect.setAttribute('height', '100%');
        rect.setAttribute('fill', 'white');
        svg.appendChild(rect);

        const img = document.createElementNS(svgNS, 'image');
        img.setAttribute('href', canvas.toDataURL('image/png'));
        img.setAttribute('x', '0');
        img.setAttribute('y', '0');
        img.setAttribute('width', canvas.width);
        img.setAttribute('height', canvas.height);
        svg.appendChild(img);

        const serializer = new XMLSerializer();
        const svgBlob = new Blob([serializer.serializeToString(svg)], { type: 'image/svg+xml' });
        const svgUrl = URL.createObjectURL(svgBlob);
        link.href = svgUrl;
        link.download = 'chart.svg';
        link.click();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    populateCheckboxes('filter-country', '../public/api.php?action=getCountries');
    populateCheckboxes('filter-year', '../public/api.php?action=getYears');
    populateCheckboxes('filter-bmi-category', '../public/api.php?action=getBmi', true);
});
