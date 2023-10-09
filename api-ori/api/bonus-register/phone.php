<?php
include_once(__DIR__ . '/../__Class/ClassLoad.php');
include_once('./config.php');
include_once('./tools.php');
include('./common_class.php');

// 引入DB資訊
common::db_config();

if (isset($_GET['action'])){
    switch($_GET['action']){
        case 'sendCode':
            // 取得 POST DATA
            $post_data = common::post_data();

            if (isset($post_data['phone'])){                
                $phone = $post_data['phone'];
                $verify_data['token'] = $post_data['token'];
                $verify_result = tools::velify_token($verify_data);

                if ($verify_result['success'] == true){
                    $return['test'] = $verify_result['success'];
                    // 確認簡訊剩餘數量
                    $server_code = $post_data['server'];
                    MYPDO::$table = 'server_management';
                    MYPDO::$where = ['server_code_name' => $server_code];
                    $result = MYPDO::first();
                    $system_user_id = $result['system_user_id'];
                    MYPDO::$table = 'server_management';
                    MYPDO::$where = ['server_code_name' => $server_code];
                    $result = MYPDO::first();
                    $system_user_id = $result['system_user_id'];
                    // $system_user_id = SYSAction::SQL_Data('server_management', '$server_code_name', $server_code, 'system_user_id');
                    $msg_num = SYSAction::SQL_Data('system_user', 'id', $system_user_id, 'msg_num');

                    if ($msg_num > 0){
                        // 發送驗證碼
                        $validation_code = tools::validation_code();
                        $msg = "【遊戲帳號註冊】您的驗證碼為「".$validation_code."」，10分鐘內有效；驗證碼提供給他人可能導致帳號被盜，請勿泄露，謹防被騙。";
                        $sms_result = json_decode(tools::omgms($phone, $msg), true);
                        
                        // =====驗證簡訊是否傳送成功=====
                        if ($sms_result['StatusCode']){     // 回傳狀態碼成功
                            // 驗證資料存入DB
                            MYPDO::$table = 'phone_validation';
                            MYPDO::$data = [
                                'phone' => $phone,
                                'validation_code' => $validation_code,
                                'validation_code_create_at_timestamp' => time(),
                                'destination' => $sms_result['Result']['Destination'],
                                'status' => $sms_result['Result']['Status'],
                                'desc' => $sms_result['Result']['Desc'],
                                'messageId' => $sms_result['Result']['MessageId'],
                            ];
                            $insertId = MYPDO::insert();
                            
                            // 確認寫入成功
                            if ($insertId > 0){
                                $return['success'] = true;
                                $return['msg_num'] = $msg_num;
                                $return['msg'] = '認證碼已發送至手機';
                            }else{
                                $return['success'] = false;
                                $return['msg'] = 'API傳送有誤';
                            }
                        }else{
                            $return['success'] = false;
                            $return['msg'] = '寫入資料庫錯誤';
                        }
                    }else{
                        $return['success'] = false;
                        $return['msg'] = '簡訊發送異常，請通知管理員';
                        $return['num'] = $msg_num;
                    }   // End msg number check  
                }else{
                    $return['success'] = false;
                    $return['msg'] = $verify_result['msg'];
                }   /* End token verify */              
            }else{
                $return['success'] = false;
                $return['msg'] = '手機號碼有誤';
            }
            $return['verify_result'] = $verify_result;

            echo json_encode($return);
            break;
        case 'varify_validation_code':
            // 取得 POST DATA
            $post_data = common::post_data();

            // 驗證token
            if (isset($post_data['token'])){
                $verify_data['token'] = $post_data['token'];
                $verify_result = tools::velify_token($verify_data);

                if ($verify_result['success']){
                    if (isset($post_data['phone']) && isset($post_data['code'])){
                        $phone = $post_data['phone'];
                        $code = $post_data['code'];
        
                        MYPDO::$table = 'phone_validation';
                        MYPDO::$where = [
                            'phone' => $phone,
                            'validation_code' => $code
                        ];
                        $result = MYPDO::first();
        
                        if (!empty($result)){   // 該資料存在，驗證時間
        
                            $timestamp = $result['validation_code_create_at_timestamp'];
                            $time_diff = time() - $timestamp;
                            $valid_minute = 10;
        
                            if ($time_diff > ($valid_minute * 60)){   // 驗證碼超過有效時限
                                $return['success'] = false;
                                $return['msg'] = '驗證碼已失效';
                            }else{
                                MYPDO::$table = 'phone_validation';
                                MYPDO::$data = ['result' => 1];
                                MYPDO::$where = [
                                    'phone' => $phone,
                                    'validation_code' => $code
                                ];
                                $update_id = MYPDO::save();
        
                                if ($update_id > 0){
                                    $return['success'] = true;
                                    $return['msg'] = '驗證成功';
                                }else{
                                    $return['success'] = false;
                                    $return['msg'] = '驗證失敗';
                                    $return['code'] = $code;
                                }
                            }
                        }else{
                            $return['success'] = false;
                            $return['msg'] = '資料不存在';
                        }
                    }
                }else{
                    $return['success'] = false;
                    $return['msg'] = $verify_result['msg'];
                }   /* End token verify */
            }else{
                $return['success'] = false;
                $return['msg'] = '未存取token資料';
            }   /* End isset token */
            
            echo json_encode($return);
            break;
        case 'token':
            $token_data = tools::token();
            $token = $token_data['token'];
            $ip = tools::ip();

            MYPDO::$table = 'token';
            MYPDO::$where = ['ip' => $ip];
            $result = MYPDO::first();

            if (empty($result)){
                MYPDO::$table = 'token';
                MYPDO::$data = [
                    'ip' => $ip,
                    'token' => $token,
                    'create_at_timestamp' => time(),
                    'create_at' => date("Y-m-d H:i:s")
                ];
                $insert_id = MYPDO::insert();
            }else{
                MYPDO::$table = 'token';
                MYPDO::$data = [
                    'ip' => $ip,
                    'token' => $token,
                    'create_at_timestamp' => time(),
                    'create_at' => date("Y-m-d H:i:s")
                ];
                MYPDO::$where = ['ip' => $ip];
                $update_id = MYPDO::save();
            }            

            if ($insert_id > 0 || $update_id > 0){
                $return['success'] = true;
                $return['data'] = $token;
            }else{
                $return['success'] = false;
            }
            $return['ins'] = $insert_id;
            $return['upd'] = $update_id;

            echo json_encode($return);
            break;
    }
}





?>