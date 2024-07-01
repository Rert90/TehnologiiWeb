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
            try {
                foreach ($input['countries'] as $country) {
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

    public function addCountry() {
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            $stmt = $this->db->prepare("
                INSERT INTO bmi_data (geo, bmi, year_2008, year_2014, year_2017, year_2019, year_2022)
                VALUES (:geo, :bmi, :year_2008, :year_2014, :year_2017, :year_2019, :year_2022)
            ");
            $stmt->execute([
                'geo' => $input['geo'],
                'bmi' => $input['bmi'],
                'year_2008' => $input['year_2008'],
                'year_2014' => $input['year_2014'],
                'year_2017' => $input['year_2017'],
                'year_2019' => $input['year_2019'],
                'year_2022' => $input['year_2022'],
            ]);
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function editCountry() {
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            $stmt = $this->db->prepare("
                UPDATE bmi_data
                SET bmi = :bmi, year_2008 = :year_2008, year_2014 = :year_2014, year_2017 = :year_2017, year_2019 = :year_2019, year_2022 = :year_2022
                WHERE geo = :geo AND bmi = :bmi
            ");
            $stmt->execute([
                'geo' => $input['geo'],
                'bmi' => $input['bmi'],
                'year_2008' => $input['year_2008'],
                'year_2014' => $input['year_2014'],
                'year_2017' => $input['year_2017'],
                'year_2019' => $input['year_2019'],
                'year_2022' => $input['year_2022'],
            ]);
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function exportData() {
        try {
            $stmt = $this->db->query("SELECT * FROM bmi_data");
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
}
?>
