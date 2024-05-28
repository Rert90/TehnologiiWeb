<?php
class ApiController {
    public function getBmiData() {
        header('Content-Type: application/json');
        
        // Date de test pentru BMI
        $data = [
            ["country" => "Romania", "male_bmi" => 25.3, "female_bmi" => 24.6],
            ["country" => "Germany", "male_bmi" => 26.5, "female_bmi" => 24.7],
            ["country" => "France", "male_bmi" => 25.9, "female_bmi" => 24.3],
            ["country" => "Italy", "male_bmi" => 25.1, "female_bmi" => 23.9]
        ];

        echo json_encode($data);
    }
}
?>
