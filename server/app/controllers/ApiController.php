<?php
namespace App\controllers;

class ApiController {
    public function __construct() {

    }

    protected function request() {
        $data_json = json_decode(file_get_contents('php://input'), true);
        $data_request = $_REQUEST;
        return is_array($data_json) ? array_merge($data_json, $data_request) : $data_request;
    }

    protected function response($data) {
        header('Content-type: application/json');
        echo json_encode($data);
        die();
    }
}
