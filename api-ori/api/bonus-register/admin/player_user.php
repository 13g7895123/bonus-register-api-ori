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
        case 'edit_player_user':
            
            $post_data = tools::post_data();    // 取得 POST DATA

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
            
            break;
    }
}
?>