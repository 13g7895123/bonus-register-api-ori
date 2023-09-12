<?php
/* ===== test url =====
 *http://170.187.229.132:9091/api/bonus-register/admin/system_admin.php?action=system_admin
 * ===============*/

include_once(__DIR__ . '/../../../__Class/ClassLoad.php');
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../tools.php');

if (isset($_GET['action'])){
    switch($_GET['action']){
        case 'system_admin':
            // 取得 POST DATA
            $json_data = file_get_contents('php://input');  // string
            $post_data = json_decode($json_data, true);     // string轉array

            MYPDO::$table = 'system_admin';
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
        case 'get_system_admin':     // 透過ID查詢資料

            $post_data = tools::post_data();    // 取得 POST DATA

            MYPDO::$table = 'system_admin';
            MYPDO::$where = ['id' => $post_data['id']];
            $result = MYPDO::first();

            $return['test'] = $post_data;
            if (empty($result)){
                $return['success'] = false;
                $return['msg'] = '查無資料';
            }else{
                $return['success'] = true;
                $return['data'] = $result;
            }

            echo json_encode($return);
            break;
        case 'add_system_admin':
            $post_data = tools::post_data();    // 取得 POST DATA

            MYPDO::$table = 'system_admin';
            MYPDO::$data = [
                'name' => $post_data['name'],
                'account' => $post_data['account'],
                'password' => $post_data['password'],
                'switch' => $post_data['switch'],
                'last_login_time' => $post_data['last_login_time'],
            ];
            $insert_id = MYPDO::insert();

            if ($insert_id > 0){
                $return['success'] = 'true';
                $return['msg'] = '新增資料成功';
                $return['insert_id'] = $insert_id;
            }else{
                $return['success'] = 'true';
                $return['msg'] = '寫入資料庫錯誤';
            }

            echo json_encode($return);
            break;
        case 'edit_system_admin':
            $post_data = tools::post_data();    // 取得 POST DATA

            MYPDO::$table = 'system_admin';
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
        case 'delete_system_admin':

            $post_data = tools::post_data();    // 取得 POST DATA

            MYPDO::$table = 'system_admin';
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