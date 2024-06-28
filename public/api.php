<?php
include_once '../config/db.php';
include_once '../controllers/ApiController.php';

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
        } elseif ($action == 'exportData') {
            $apiController->exportData();
        } else {
            $apiController->getBmiData();
        }
        break;
    case 'POST':
        if ($action == 'updateCountrySelectionCount') {
            $apiController->updateCountrySelectionCount();
        } elseif ($action == 'addCountry') {
            $apiController->addCountry();
        } elseif ($action == 'editCountry') {
            $apiController->editCountry();
        } else {
            echo json_encode(["message" => "Action not supported for POST request"]);
        }
        break;
    default:
        echo json_encode(["message" => "Request method not supported"]);
        break;
}
?>
