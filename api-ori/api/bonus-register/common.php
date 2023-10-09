<?php
include_once(__DIR__ . '/../../__Class/ClassLoad.php');
include_once('./config.php');
include_once('./tools.php');

if (isset($_GET['action'])){
    switch($_GET['action']){
        case 'server_name':
            $post_data = common::post_data();     // string轉array

            if (isset($post_data['server'])){
                $server = $post_data['server'];
            }

            if ($server != ''){
                
                MYPDO::$table = 'server';
                MYPDO::$where = [
                    'code_name' => $server,
                ];
                $result = MYPDO::first();

                if (!empty($result)){
                    $return['success'] = true;
                    $return['data']['name'] = $result['name'];
                    $return['data']['bg'] = $result['bg_img_path'];
                }else{
                    $return['success'] = false;
                    $return['msg'] = '伺服器不存在';
                    $return['server'] = $server;
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