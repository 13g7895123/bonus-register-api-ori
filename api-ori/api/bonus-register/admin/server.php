<?php
/* ===== test url =====
 *http://170.187.229.132:9091/api/bonus-register/admin/system_admin.php?action=system_admin
 * ===============*/

include_once(__DIR__ . '/../../../__Class/ClassLoad.php');
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../tools.php');

if (isset($_GET['action'])){
    switch($_GET['action']){
        case 'server':
            // 取得 POST DATA
            $json_data = file_get_contents('php://input');  // string
            $post_data = json_decode($json_data, true);     // string轉array

            MYPDO::$table = 'server';
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
        case 'bg_img_upload':
            if ($_FILES['my_file']['error'] === UPLOAD_ERR_OK){
                echo '檔案名稱: ' . $_FILES['my_file']['name'] . '<br/>';
                echo '檔案類型: ' . $_FILES['my_file']['type'] . '<br/>';
                echo '檔案大小: ' . ($_FILES['my_file']['size'] / 1024) . ' KB<br/>';
                echo '暫存名稱: ' . $_FILES['my_file']['tmp_name'] . '<br/>';
              
                # 檢查檔案是否已經存在
                if (file_exists('upload/' . $_FILES['my_file']['name'])){
                  echo '檔案已存在。<br/>';
                } else {
                    $file = $_FILES['my_file']['tmp_name'];
                    $dest = 'upload/' . $_FILES['my_file']['name'];
              
                    if ( !file_exists($dest) ){
                        mkdir($dest,0775,true);
                    }
                    # 將檔案移至指定位置
                    move_uploaded_file($file, $dest);
                }
              } else {
                echo '錯誤代碼：' . $_FILES['my_file']['error'] . '<br/>';
              }
            break;
    }
}
?>