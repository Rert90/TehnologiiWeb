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

    public function updateCountrySelectionCount() {
        $input = json_decode(file_get_contents('php://input'), true);
    
        if (!empty($input['countries'])) {
            try {
                foreach ($input['countries'] as $country) {
                    
                    file_put_contents('log.txt', "Processing country: $country\n", FILE_APPEND);
    
                    $stmt = $this->db->prepare("
                        INSERT INTO country_selections (country_code, selection_count) 
                        VALUES (:country, 1) 
                        ON DUPLICATE KEY UPDATE selection_count = selection_count + 1
                    ");
                    $stmt->execute(['country' => $country]);
                }
                echo json_encode(['status' => 'success']);
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No countries provided']);
        }
    }
    
    

header("Content-Type: application/json; charset=UTF-8");
$apiController = new ApiController();

$request_method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : 'getBmiData';
switch($request_method) {
    case 'GET':
        if ($action == 'getCountries') {
            $apiController->getCountries();
        } elseif ($action == 'getYears') {
            $apiController->getYears();
        } elseif ($action == 'getBmi') {
            $apiController->getBmi();
        } elseif ($action == 'getTopCountries') {
            $apiController->getTopCountries();
        } else {
            $apiController->getBmiData();
        }
        break;
    case 'POST':
        if ($action == 'updateCountrySelectionCount') {
            $apiController->updateCountrySelectionCount();
        } else {
            echo json_encode(["message" => "Action not supported for POST request"]);
        }
        break;
    default:
        echo json_encode(["message" => "Request method not supported"]);
        break;
}
?>
