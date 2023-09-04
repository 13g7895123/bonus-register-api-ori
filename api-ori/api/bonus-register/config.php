<?php
include_once(__DIR__ . '/../../__Class/ClassLoad.php');

// DB
MYPDO::$host = '139.162.15.125';
MYPDO::$port = '3306';
// MYPDO::$db = 'db_test';
MYPDO::$db = 'db_bonus_register';
// MYPDO::$user = 'test_remote';
MYPDO::$user = 'bonus_register_remote';
MYPDO::$pwd = '820820';

// CORS
// $url_arr = ['http://170.187.229.132:9054/'];
// $http_origin = $_SERVER['HTTP_ORIGIN'];

// if (in_array($http_origin, $url_arr)){
//     header("Access-Control-Allow-Origin: $http_origin");
// }

// header('Access-Control-Allow-Headers: Content-Type');
// header('Access-Control-Allow-Headers: Auth');

?>