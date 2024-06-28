<?php
include_once '../config/db.php';
include_once '../models/BmiModel.php';

class AdminController {
    private $db;
    private $bmiModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->bmiModel = new BmiModel($this->db);
    }

    public function showAdminPage() {
        require_once '../views/adminView.php';
    }

    public function showAddCountryPage() {
        require_once '../views/addCountryView.php';
    }

    public function showEditCountryPage($geo) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM bmi_data WHERE geo = ?");
            $stmt->execute([$geo]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$data) {
                die('Country not found');
            }
            require_once '../views/editCountryView.php';
        } catch (PDOException $e) {
            die('Failed to fetch data: ' . $e->getMessage());
        }
    }
}
?>
