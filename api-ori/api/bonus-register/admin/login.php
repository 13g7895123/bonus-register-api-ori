<?php
include_once(__DIR__ . '/../../__Class/ClassLoad.php');
include_once('./config.php');
include_once('./tools.php');

if (isset($_GET['action'])){
    switch($_GET['action']){
        case 'login':
            // 取得 POST DATA
            $json_data = file_get_contents('php://input');  // string
            $post_data = json_decode($json_data, true);     // string轉array

            MYPDO::$table = 'system_user';
            MYPDO::$where = [
                'account' => $data['account'],
                // 'password' => hash('sha512', $data['password']),
                'password' => $data['password'],
                'switch' => 1
            ];
            $result = MYPDO::first();

            if (empty($result)){
                $return['success'] = false;
                $return['msg'] = '帳號或密碼錯誤!';
            }else{
                $return['success'] = true;
                $return['user']['id'] = $result['id'];
                $return['user']['account'] = $result['account'];
                $return['user']['name'] = $result['name'];
            }

            echo json_encode($return);
            break;
    }
}





?>