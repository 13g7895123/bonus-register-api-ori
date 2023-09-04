<?php

include_once(__DIR__ . '/../../__Class/ClassLoad.php');

MYPDO::$host = '139.162.15.125';
MYPDO::$port = '9050';
MYPDO::$db = 'db_test1';
MYPDO::$user = 'test_remote';
MYPDO::$pwd = '820820';

MYPDO::$table = 'phone';
$results = MYPDO::select();

$return['success'] = true;
$return['data'] = $results;

echo json_encode($return);

?> 