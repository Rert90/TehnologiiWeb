<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "visb_db";

// Crearea conexiunii
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificarea conexiunii
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Citirea fișierului TSV
$file = fopen("sdg_02_10_tabular.tsv", "r");

// Sărim peste antet
fgetcsv($file, 1000, "\t");

// Inserarea datelor în tabel
while (($line = fgetcsv($file, 1000, "\t")) !== FALSE) {
    $fields = explode(',', $line[0]);
    $freq = $fields[0];
    $unit = $fields[1];
    $bmi = $fields[2];
    $geo = $fields[3];

    $year_2008 = $line[1] !== ':' ? $line[1] : NULL;
    $year_2014 = $line[2] !== ':' ? $line[2] : NULL;
    $year_2017 = $line[3] !== ':' ? $line[3] : NULL;
    $year_2019 = $line[4] !== ':' ? $line[4] : NULL;
    $year_2022 = $line[5] !== ':' ? $line[5] : NULL;

    $sql = "INSERT INTO bmi_data (freq, unit, bmi, geo, year_2008, year_2014, year_2017, year_2019, year_2022)
            VALUES ('$freq', '$unit', '$bmi', '$geo', '$year_2008', '$year_2014', '$year_2017', '$year_2019', '$year_2022')";

    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

fclose($file);
$conn->close();
?>
