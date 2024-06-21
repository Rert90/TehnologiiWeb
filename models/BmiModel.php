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
}
?>
