<?php
class BmiModel {
    private $conn;
    private $table_name = "bmi_data";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getBmiData($criteria = []) {
        $query = "SELECT geo, year_2008, year_2014, year_2017, year_2019, year_2022 FROM " . $this->table_name;
        $conditions = [];
        $params = [];

        if (!empty($criteria['country'])) {
            $conditions[] = "geo = :geo";
            $params[':geo'] = $criteria['country'];
        }
        if (!empty($criteria['year'])) {
            $conditions[] = "year_" . $criteria['year'] . " IS NOT NULL";
        }

        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }

        $stmt->execute();
        return $stmt;
    }

    public function getCountries() {
        $query = "SELECT DISTINCT geo FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getYears() {
        $query = "SELECT '2008' AS year UNION SELECT '2014' UNION SELECT '2017' UNION SELECT '2019' UNION SELECT '2022'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
