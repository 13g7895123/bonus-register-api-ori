<?php
/* ===== test url =====
 *http://170.187.229.132:9091/api/bonus-register/admin/system_admin.php?action=system_admin
 * ===============*/

include_once(__DIR__ . '/../../../__Class/ClassLoad.php');
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../tools.php');

$domain = 'http://missa.mercylife.cc';

if (isset($_GET['action'])){
    switch($_GET['action']){
        case 'server':
            // 取得 POST DATA
            $json_data = file_get_contents('php://input');  // string
            $post_data = json_decode($json_data, true);     // string轉array

            MYPDO::$table = 'server';
            $results = MYPDO::select();

            foreach ($results as $rkey => $rval){
                if (is_null($rval['bg_img_path'])){
                    $results[$rkey]['bg_img_path'] = $domain.'/img_upload/server/default/bg.jpg';
                }else{
                    $results[$rkey]['bg_img_path'] = $domain.$rval['bg_img_path'];
                }
            }

            if (empty($results)){
                $return['success'] = false;
                $return['msg'] = '查無資料';
            }else{
                $return['success'] = true;
                $return['data'] = $results;
            }

            echo json_encode($return);
            break;
        case 'get_server':     // 透過ID查詢資料
            $post_data = tools::post_data();    // 取得 POST DATA

            MYPDO::$table = 'server';
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
        case 'add_server':
            $post_data = tools::post_data();    // 取得 POST DATA

            MYPDO::$table = 'server';
            MYPDO::$data = [
                'name' => $post_data['name'],
                'code_name' => $post_data['code_name'],
                'max_num' => $post_data['max_num'],
                'bg_img_path' => '/img_upload/server/default/bg.jpg',
                'db_name' => $post_data['db_name'],
                'db_ip' => $post_data['db_ip'],
                'db_port' => $post_data['db_port'],
                'db_username' => $post_data['db_username'],
                'db_password' => $post_data['db_password'],
                'switch' => $post_data['switch'],
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
        case 'edit_server':
            $post_data = tools::post_data();    // 取得 POST DATA

            MYPDO::$table = 'server';
            MYPDO::$data = [
                'name' => $post_data['name'],
                'code_name' => $post_data['code_name'],
                'max_num' => $post_data['max_num'],
                'db_name' => $post_data['db_name'],
                'db_ip' => $post_data['db_ip'],
                'db_port' => $post_data['db_port'],
                'db_username' => $post_data['db_username'],
                'db_password' => $post_data['db_password'],
                'switch' => $post_data['switch'],
            ];
            MYPDO::$where = ['id' => $post_data['id']];
            $save_id = MYPDO::save();

            $return['success'] = 'true';
            $return['msg'] = '修改資料成功';
            $return['save_id'] = $save_id;
            echo json_encode($return);
            break;
        case 'delete_server':
            $post_data = tools::post_data();    // 取得 POST DATA

            MYPDO::$table = 'server';
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
        case 'img_info':
            $server_id = $_POST['server_id'];
            $bg_img_name = $_POST['bg_img_name'];
            $bg_img_path = $_POST['bg_img_path'];

            MYPDO::$table = 'server';
            MYPDO::$data = [
                'bg_img_name' => $bg_img_name,
                'bg_img_path' => $bg_img_path,
            ];
            MYPDO::$where = ['id' => $server_id];
            $save_id = MYPDO::save();

            if ($save_id > 0){
                $response['success'] = true;
                $response['msg'] = '上傳檔案成功';
            }else{
                $response['success'] = false;
                $response['msg'] = '上傳檔案失敗';
            }

            echo json_encode($response);
            break;
        case 'get_bg_img':
            $post_data = tools::post_data();    // 取得 POST DATA

            MYPDO::$table = 'server';
            MYPDO::$where = ['id' => $post_data['id']];
            $result = MYPDO::first();

            if (is_null($result['bg_img_path'])){
                $return['success'] = false;
                $return['msg'] = '查無資料';
                $return['data'] = $domain.'	/img_upload/server/default/bg.jpg';
            }else{
                $return['success'] = true;
                $return['data'] = $domain.$result['bg_img_path'];
            }

            echo json_encode($return);
            break;
    }
}
?>