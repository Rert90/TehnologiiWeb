<?php
class BmiModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getBmiData($criteria) {
        $query = "SELECT * FROM bmi_data WHERE 1=1";
        if (!empty($criteria['country'])) {
            $query .= " AND geo IN (" . implode(',', array_fill(0, count($criteria['country']), '?')) . ")";
        }
        if (!empty($criteria['year'])) {
            $yearConditions = array_map(function ($year) {
                return "year_$year IS NOT NULL";
            }, $criteria['year']);
            $query .= " AND (" . implode(' OR ', $yearConditions) . ")";
        }
        if (!empty($criteria['bmi'])) {
            $query .= " AND bmi = ?";
        }

        $stmt = $this->conn->prepare($query);

        $paramIndex = 1;
        if (!empty($criteria['country'])) {
            foreach ($criteria['country'] as $country) {
                $stmt->bindValue($paramIndex++, $country);
            }
        }
        if (!empty($criteria['bmi'])) {
            $stmt->bindValue($paramIndex++, $criteria['bmi']);
        }

        $stmt->execute();
        return $stmt;
    }

    public function getCountries() {
        $query = "SELECT DISTINCT geo FROM bmi_data";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getYears() {
        $query = "SELECT '2008' as year UNION SELECT '2014' UNION SELECT '2017' UNION SELECT '2019' UNION SELECT '2022'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getBmi() {
        $query = "SELECT DISTINCT bmi FROM bmi_data";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getTopCountries() {
        $query = "SELECT country_code, selection_count FROM country_selections ORDER BY selection_count DESC LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function insertData($bmi, $geo, $year_2008, $year_2014, $year_2017, $year_2019, $year_2022) {
        $query = "INSERT INTO bmi_data (bmi, geo, year_2008, year_2014, year_2017, year_2019, year_2022) 
                  VALUES (:bmi, :geo, :year_2008, :year_2014, :year_2017, :year_2019, :year_2022)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'bmi' => $bmi,
            'geo' => $geo,
            'year_2008' => $year_2008,
            'year_2014' => $year_2014,
            'year_2017' => $year_2017,
            'year_2019' => $year_2019,
            'year_2022' => $year_2022
        ]);
        return $stmt;
    }

    public function updateCountrySelectionCount($countries) {
        try {
            foreach ($countries as $country) {
                $stmt = $this->conn->prepare("
                    INSERT INTO country_selections (country_code, selection_count) 
                    VALUES (:country, 1) 
                    ON DUPLICATE KEY UPDATE selection_count = selection_count + 1
                ");
                $stmt->execute(['country' => $country]);
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function addCountry($geo, $bmi, $year_2008, $year_2014, $year_2017, $year_2019, $year_2022) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO bmi_data (geo, bmi, year_2008, year_2014, year_2017, year_2019, year_2022)
                VALUES (:geo, :bmi, :year_2008, :year_2014, :year_2017, :year_2019, :year_2022)
            ");
            $stmt->execute([
                'geo' => $geo,
                'bmi' => $bmi,
                'year_2008' => $year_2008,
                'year_2014' => $year_2014,
                'year_2017' => $year_2017,
                'year_2019' => $year_2019,
                'year_2022' => $year_2022,
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function editCountry($geo, $bmi, $year_2008, $year_2014, $year_2017, $year_2019, $year_2022) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE bmi_data
                SET bmi = :bmi, year_2008 = :year_2008, year_2014 = :year_2014, year_2017 = :year_2017, year_2019 = :year_2019, year_2022 = :year_2022
                WHERE geo = :geo AND bmi = :bmi
            ");
            $stmt->execute([
                'geo' => $geo,
                'bmi' => $bmi,
                'year_2008' => $year_2008,
                'year_2014' => $year_2014,
                'year_2017' => $year_2017,
                'year_2019' => $year_2019,
                'year_2022' => $year_2022,
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function exportData() {
        try {
            $stmt = $this->conn->query("SELECT * FROM bmi_data");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $csvContent = "geo,bmi,year_2008,year_2014,year_2017,year_2019,year_2022\n";
            foreach ($data as $row) {
                $csvContent .= implode(",", $row) . "\n";
            }

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename=bmi_data.csv');
            echo $csvContent;
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
?>
