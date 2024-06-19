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
        if (isset($_GET['country']) && $_GET['country'] !== 'all') {
            $criteria['country'] = $_GET['country'];
        }
        if (isset($_GET['year'])) {
            $criteria['year'] = $_GET['year'];
        }

        $stmt = $this->bmiModel->getBmiData($criteria);
        $bmiData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($bmiData);
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
        } else {
            $apiController->getBmiData();
        }
        break;
    default:
        echo json_encode(["message" => "Request method not supported"]);
        break;
}
?>
