<?php
include_once(__DIR__ . '/../../__Class/ClassLoad.php');

error_reporting(E_ERROR | E_PARSE);

// 時區
date_default_timezone_set('Asia/Taipei');

// DB
MYPDO::$host = '139.162.15.125';
MYPDO::$port = '3306';
MYPDO::$db = 'db_bonus_register';
MYPDO::$user = 'bonus_register_remote';
MYPDO::$pwd = '820820';

// CORS
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Headers: Auth');

?>