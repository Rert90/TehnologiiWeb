<?php
include_once '../config/db.php';
include_once '../models/BmiModel.php';

class ApiController {
    private $db;
    private $bmiModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->bmiModel = new BmiModel($this->db);
    }

    public function getBmiData() {
        $criteria = [];
        if (isset($_GET['country'])) {
            $criteria['country'] = $_GET['country'];
        }
        if (isset($_GET['year'])) {
            $criteria['year'] = $_GET['year'];
        }

        $stmt = $this->bmiModel->getBmiData($criteria);
        $bmiData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($bmiData);
    }

    public function insertBmiData() {
        $input = json_decode(file_get_contents('php://input'), true);

        $freq = $input['freq'];
        $unit = $input['unit'];
        $bmi = $input['bmi'];
        $geo = $input['geo'];
        $year_2008 = $input['year_2008'];
        $year_2014 = $input['year_2014'];
        $year_2017 = $input['year_2017'];
        $year_2019 = $input['year_2019'];
        $year_2022 = $input['year_2022'];

        if($this->bmiModel->insertData($freq, $unit, $bmi, $geo, $year_2008, $year_2014, $year_2017, $year_2019, $year_2022)) {
            echo json_encode(["message" => "Data inserted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to insert data"]);
        }
    }
}
?>
