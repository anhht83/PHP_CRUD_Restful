<?php

require "vendor/autoload.php";
$REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];
$REQUEST_URI = $_SERVER['REQUEST_URI'];
$REQUEST_URI = explode('?', $REQUEST_URI);
$REQUEST_URI = $REQUEST_URI[0];
$SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
$SCRIPT_NAME = str_replace('index.php', '', $SCRIPT_NAME);
$route = $REQUEST_METHOD . ' ' . str_replace($SCRIPT_NAME, '', $REQUEST_URI);

switch ($route) {
    case 'GET api/stock':
        $newsController = new \App\controllers\StockController();
        $newsController->get();
        break;
    case 'POST api/stock':
        $newsController = new \App\controllers\StockController();
        $newsController->save();
        break;
}
die();
