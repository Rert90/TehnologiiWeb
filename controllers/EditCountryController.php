<?php
require_once '../config/db.php';

class EditCountryController {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=visb_db', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Failed to connect to the database: ' . $e->getMessage());
        }
    }

    public function getCountryData($geo, $bmi) {
        $stmt = $this->pdo->prepare("SELECT * FROM bmi_data WHERE geo = ? AND bmi = ?");
        $stmt->execute([$geo, $bmi]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCountry($data) {
        $stmt = $this->pdo->prepare("
            UPDATE bmi_data
            SET year_2008 = ?, year_2014 = ?, year_2017 = ?, year_2019 = ?, year_2022 = ?
            WHERE geo = ? AND bmi = ?
        ");
        $stmt->execute([
            $data['year_2008'],
            $data['year_2014'],
            $data['year_2017'],
            $data['year_2019'],
            $data['year_2022'],
            $data['geo'],
            $data['bmi']
        ]);
    }

    public function deleteCountry($geo, $bmi) {
        $stmt = $this->pdo->prepare("DELETE FROM bmi_data WHERE geo = ? AND bmi = ?");
        $stmt->execute([$geo, $bmi]);
    }
}
?>
