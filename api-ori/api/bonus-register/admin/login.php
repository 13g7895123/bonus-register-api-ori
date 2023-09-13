<?php
include_once(__DIR__ . '/../../../__Class/ClassLoad.php');
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../tools.php');

if (isset($_GET['action'])){
    switch($_GET['action']){
        case 'login':
            // 取得 POST DATA
            $json_data = file_get_contents('php://input');  // string
            $post_data = json_decode($json_data, true);     // string轉array

            MYPDO::$table = 'system_user';
            MYPDO::$where = [
                'account' => $post_data['account'],
                'password' => $post_data['password'],
                'switch' => 1
            ];
            $result = MYPDO::first();
            $return['test'] = 1;

            if (empty($result)){
                $return['test'] = 2;
                MYPDO::$table = 'system_admin';
                MYPDO::$where = [
                    'account' => $post_data['account'],
                    'password' => $post_data['password'],
                    'switch' => 1
                ];
                $result = MYPDO::first();

                if (empty($result)){
                    $return['success'] = false;
                    $return['msg'] = '帳號或密碼錯誤!';
                    $return['user']['account'] = $post_data['account'];
                    $return['user']['name'] = $post_data['account'];
                }else{
                    $login_log['is_admin'] = 1;
                    $login_log['id'] = $result['id'];
                    tools::login_log($login_log);

                    $return['test'] = 3;
                    $return['success'] = true;
                    $return['user']['id'] = $result['id'];
                    $return['user']['account'] = $result['account'];
                    $return['user']['name'] = $result['name'];
                    $return['user']['is_admin'] = 1;
                }
            }else{
                $login_log['is_admin'] = 0;
                $login_log['id'] = $result['id'];
                tools::login_log($login_log);

                $return['success'] = true;
                $return['user']['id'] = $result['id'];
                $return['user']['account'] = $result['account'];
                $return['user']['name'] = $result['name'];
                $return['user']['is_admin'] = 0;
            }

            echo json_encode($return);
            break;
    }
}





?>