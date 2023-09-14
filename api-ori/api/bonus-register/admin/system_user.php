<?php
/* ===== test url =====
 *http://170.187.229.132:9091/api/bonus-register/admin/player_user.php?action=player_user
 * ===============*/

include_once(__DIR__ . '/../../../__Class/ClassLoad.php');
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../tools.php');

if (isset($_GET['action'])){
    switch($_GET['action']){
        case 'system_user':
            // 取得 POST DATA
            $json_data = file_get_contents('php://input');  // string
            $post_data = json_decode($json_data, true);     // string轉array

            MYPDO::$table = 'system_user';
            $results = MYPDO::select();

            if (empty($results)){
                $return['success'] = false;
                $return['msg'] = '查無資料';
            }else{
                $return['success'] = true;
                $return['data'] = $results;
            }

            echo json_encode($return);
            break;
        case 'server_list':
            $post_data = tools::post_data();    // 取得 POST DATA

            MYPDO::$table = 'server';
            $results = MYPDO::select();

            $return['success'] = true;
            $return['data'] = $results;
            $return['post_data'] = $post_data;
            echo json_encode($return);
            break;
        case 'add_system_user':
            $post_data = tools::post_data();    // 取得 POST DATA
            $server_list = $post_data['server'];
            $server_count = count($server_list);

            MYPDO::$table = 'system_user';
            MYPDO::$data = [
                'name' => $post_data['name'],
                'account' => $post_data['account'],
                'password' => $post_data['password'],
                'manage_server_count' => $server_count,
                'switch' => $post_data['switch'],
            ];
            $insert_id = MYPDO::insert();

            foreach ($server_list as $key => $val){
                $server_data = tools::server_data($val);

                MYPDO::$table = 'server_management';
                MYPDO::$data = [
                    'system_user_id' => $insert_id,
                    'server_id' => $server_data['server_data'],
                    'server_name' => $server_data['server_name'],
                    'server_code_name' => $server_data['server_code_name'],
                ];
                $insert_id = MYPDO::insert();
            }
            $return['success'] = 'true';
            $return['post_data'] = $post_data;

            echo json_encode($return);
            break;
        case 'edit_system_user':
            $post_data = tools::post_data();    // 取得 POST DATA

            MYPDO::$table = 'system_user';
            MYPDO::$data = [
                'name' => $post_data['name'],
                'account' => $post_data['account'],
                'password' => $post_data['password'],
                'switch' => $post_data['switch'],
                'last_login_time' => $post_data['last_login_time'],
            ];
            MYPDO::$where = ['id' => $post_data['id']];
            $save_id = MYPDO::save();

            $return['success'] = 'true';
            $return['msg'] = '修改資料成功';
            $return['save_id'] = $save_id;
            echo json_encode($return);
            break;
        case 'delete_system_user':
            $post_data = tools::post_data();    // 取得 POST DATA

            MYPDO::$table = 'system_user';
            MYPDO::$where = ['id' => $post_data['id']];
            $del_count = MYPDO::del();

            $return['success'] = true;
            $return['msg'] = '刪除資料成功';
            $return['test'] = $del_count;

            if ($del_count == 1){
                $return['success'] = true;
            }else{
                $return['success'] = false;
                $return['msg'] = '刪除資料異常';
            }

            echo json_encode($return);
            break;
    }
}
?>