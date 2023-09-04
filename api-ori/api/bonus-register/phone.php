<?php
include_once(__DIR__ . '/../../__Class/ClassLoad.php');
include_once('./config.php');
include_once('./tools.php');

if (isset($_GET['action'])){
    switch($_GET['action']){
        case 'sendCode':
            // 取得 POST DATA
            $json_data = file_get_contents('php://input');  // string
            $post_data = json_decode($json_data, true);     // string轉array

            if (isset($post_data['phone'])){
                // 發送驗證碼
                $phone = $post_data['phone'];

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
                $return['msg'] = '手機號碼有誤';
            }

            echo json_encode($return);
            break;
        case 'varify_validation_code':
            // 取得 POST DATA
            $json_data = file_get_contents('php://input');  // string
            $post_data = json_decode($json_data, true);     // string轉array
                      
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
                    $valid_minute = 1;

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
                        }
                    }
                }else{
                    $return['success'] = false;
                    $return['msg'] = '資料不存在';
                }
            }

            echo json_encode($return);
            break;
    }
}





?>