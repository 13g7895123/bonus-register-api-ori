<?php
include_once(__DIR__ . '/../../__Class/ClassLoad.php');
include_once('./config.php');
include_once('./tools.php');

error_reporting(E_ERROR | E_PARSE);

if (isset($_GET['action'])){
    switch($_GET['action']){
        case 'server_name':
            // 取得 POST DATA
            $json_data = file_get_contents('php://input');  // string
            $post_data = json_decode($json_data, true);     // string轉array

            if (isset($post_data['server'])){
                $server = $post_data['server'];
            }

            if ($server != ''){
                
                MYPDO::$table = 'server';
                MYPDO::$where = [
                    'eng_name' => $server,
                ];
                $result = MYPDO::first();

                if (!empty($result)){
                    $return['success'] = true;
                    $return['data'] = $result['name'];
                }else{
                    $return['success'] = false;
                    $return['msg'] = '伺服器不存在';
                }

            }else{
                $return['success'] = false;
                $return['msg'] = '輸入資料有誤，請重新確認';
            }

            echo json_encode($return);
            break;
    }
}

?>