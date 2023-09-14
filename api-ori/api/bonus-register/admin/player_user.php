<?php
/* ===== test url =====
 *http://170.187.229.132:9091/api/bonus-register/admin/player_user.php?action=player_user
 * ===============*/

include_once(__DIR__ . '/../../../__Class/ClassLoad.php');
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../tools.php');

if (isset($_GET['action'])){
    switch($_GET['action']){
        case 'player_user':     // 取得所有資料
            // 取得 POST DATA
            $post_data = tools::post_data();

            MYPDO::$table = 'player_user';
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
        case 'get_player_user':     // 透過ID查詢資料

            $post_data = tools::post_data();    // 取得 POST DATA

            MYPDO::$table = 'player_user';
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
        case 'add_player_user':
            $post_data = tools::post_data();    // 取得 POST DATA

            $server_text = $post_data['serverText'];
            $server_code_name = explode(']', explode('[', $server_text)[1])[0];
            $server_id = SYSAction::SQL_Data('server', 'code_name', $server_code_name, 'id');
            $server_name = SYSAction::SQL_Data('server', 'id', $server_id, 'name');
            
            MYPDO::$table = 'player_user';
            MYPDO::$data = [
                'account' => $post_data['account'],
                'password' => $post_data['password'],
                'phone' => $post_data['phone'],
                'birthday' => $post_data['birthday'],
                'server_id' => $server_id,
                'server_name' => $server_name,
                'server_code_name' => $server_code_name,
                'switch' => 1,
            ];
            $insert_id = MYPDO::insert();

            $return['success'] = 'true';
            $return['insert_id'] = $insert_id;
            echo json_encode($return);
            break;
        case 'edit_player_user':
            
            $post_data = tools::post_data();    // 取得 POST DATA
            
            MYPDO::$table = 'player_user';
            MYPDO::$data = [
                'account' => $post_data['account'],
                'password' => $post_data['password'],
                'phone' => $post_data['phone'],
                'birthday' => $post_data['birthday'],
                'switch' => $post_data['switch'],
                // 'server_text' => $post_data['server_text'],
            ];
            MYPDO::$where = ['id' => $post_data['id']];
            $save_id = MYPDO::save();

            $return['success'] = 'true';
            $return['save_id'] = $save_id;
            echo json_encode($return);
            break;
        case 'delete_player_user':
        
            $post_data = tools::post_data();    // 取得 POST DATA

            MYPDO::$table = 'player_user';
            MYPDO::$where = ['id' => $post_data['id']];
            $del_count = MYPDO::del();

            $return['success'] = true;
            $return['test'] = $del_count;

            if ($del_count == 1){
                $return['success'] = true;
            }else{
                $return['success'] = false;
                $return['msg'] = '刪除資料異常';
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
    }
}
?>