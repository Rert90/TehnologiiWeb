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
            $criteria['country'] = explode(',', $_GET['country']);
        }
        if (isset($_GET['year'])) {
            $criteria['year'] = explode(',', $_GET['year']);
        }
        if (isset($_GET['bmi'])) {
            $criteria['bmi'] = $_GET['bmi'];
        }

        $stmt = $this->bmiModel->getBmiData($criteria);
        $bmiData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($bmiData);
    }

    public function insertBmiData() {
        $input = json_decode(file_get_contents('php://input'), true);

        $bmi = $input['bmi'];
        $geo = $input['geo'];
        $year_2008 = $input['year_2008'];
        $year_2014 = $input['year_2014'];
        $year_2017 = $input['year_2017'];
        $year_2019 = $input['year_2019'];
        $year_2022 = $input['year_2022'];

        if($this->bmiModel->insertData($bmi, $geo, $year_2008, $year_2014, $year_2017, $year_2019, $year_2022)) {
            echo json_encode(["message" => "Data inserted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to insert data"]);
        }
    }

    public function updateCountrySelectionCount() {
        $input = json_decode(file_get_contents('php://input'), true);
    
        if (!empty($input['countries'])) {
            if ($this->bmiModel->updateCountrySelectionCount($input['countries'])) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update country selection count']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No countries provided']);
        }
    }

    public function addCountry() {
        $input = json_decode(file_get_contents('php://input'), true);

        if ($this->bmiModel->addCountry(
            $input['geo'],
            $input['bmi'],
            $input['year_2008'],
            $input['year_2014'],
            $input['year_2017'],
            $input['year_2019'],
            $input['year_2022']
        )) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add country']);
        }
    }

    public function editCountry() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            $input = $_POST; 
        }

        if ($this->bmiModel->editCountry(
            $input['geo'],
            $input['bmi'],
            $input['year_2008'],
            $input['year_2014'],
            $input['year_2017'],
            $input['year_2019'],
            $input['year_2022']
        )) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to edit country']);
        }
    }

    public function deleteCountry($geo, $bmi) {
        if ($this->bmiModel->deleteCountry($geo, $bmi)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete country']);
        }
    }

    public function exportData() {
        $this->bmiModel->exportData();
    }

    public function getCountries() {
        $stmt = $this->bmiModel->getCountries();
        $countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($countries);
    }

    public function getYears() {
        $stmt = $this->bmiModel->getYears();
        $years = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($years);
    }

    public function getBmi() {
        $stmt = $this->bmiModel->getBmi();
        $bmi = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($bmi);
    }

    public function getTopCountries() {
        $stmt = $this->bmiModel->getTopCountries();
        $topCountries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($topCountries);
    }

    public function getCountryData($geo, $bmi) {
        $stmt = $this->bmiModel->getCountryData($geo, $bmi);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
