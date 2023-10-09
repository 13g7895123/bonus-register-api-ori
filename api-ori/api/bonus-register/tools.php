<?php

include_once(__DIR__ . '/../__Class/ClassLoad.php');
include_once(__DIR__ . './config.php');
class tools
{
    // 簡訊-歐買尬
    public static function omgms($phone = null, $msg = null)
    {
        $api_token = base64_encode('90339cff-6d61-4b85-a123-b03a090635ef:'.time());
        $url = 'https://api.omgms.com.tw/api/sms/Single ';
        $data = array(
            'Destination' => $phone,
            'SmsBody' => $msg,
            // 'SmsType'  => 'OTP',
            'SmsType'  => 'SYSTEM',
        );
        
        $curl = curl_init();
        $header = array();
        $header[] = 'Content-type: application/x-www-form-urlencoded';
        $header[] = 'Auth: '.$api_token;

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  // 取得回傳資料

        // 執⾏
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;

        // echo $output;
    }

    // 簡訊-三竹(需要公司行號才可使用)
    public static function mitake()
    {
        $curl = curl_init();
        // url
        $url = 'https://sms.mitake.com.tw/b2c/mtk/SmSend?';
        $url .= 'CharsetURL=UTF-8';
        // parameters
        $data = 'username=0903706726';
        $data .= '&password=germit0035';
        $data .= '&dstaddr=0903706726';
        $data .= '&smbody=簡訊SmSend測試';
        // 設定curl網址
        curl_setopt($curl, CURLOPT_URL, $url);
        // 設定Header
        curl_setopt($curl, CURLOPT_HTTPHEADER,
        array("Content-type: application/x-www-form-urlencoded")
        );
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HEADER,0);

        // 執⾏
        $output = curl_exec($curl);
        curl_close($curl);
        echo $output;
    }

    // 隨機字串
    public static function validation_code($length = 4, $type = 0){
        
        if ($type == 0){
            $chars = '0123456789';
        }elseif ($type == 1){   // 數字 + 英文大小寫
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        }
        $char_length = strlen($chars);
        $return_str = '';
        for ($i = 0; $i < $length; $i++){
            $random_str = $chars[ rand( 0, $char_length - 1 ) ];
            $return_str .= $random_str;
        }     
        return $return_str;
    }

    // 取得 Post Data
    public static function post_data(){
        $json_data = file_get_contents('php://input');  // string
        $post_data = json_decode($json_data, true);     // string轉array
        return $post_data;
    }

    // 登入紀錄
    public static function login_log($data){

        date_default_timezone_set("Asia/Taipei");   // 設定時區

        $table_name = ($data['is_admin'] == 0) ? 'system_user' : 'system_admin';
        MYPDO::$table = $table_name;
        MYPDO::$data = ['last_login_time' => date('Y/m/d H:i:s')];
        MYPDO::$where = ['id' => $data['id']];
        MYPDO::save();

        return;
    }

    // 取得伺服器資料
    public static function server_data($data){
        $server_text = $data;
        $server_code_name = explode(']', explode('[', $server_text)[1])[0];
        $server_id = SYSAction::SQL_Data('server', 'code_name', $server_code_name, 'id');
        $server_name = SYSAction::SQL_Data('server', 'id', $server_id, 'name');
        $return_data = [];
        $return_data['server_id'] = $server_id;
        $return_data['server_name'] = $server_name;
        $return_data['server_code_name'] = $server_code_name;
        return $return_data;
    }

    // 生成token
    public static function token(){
        $token['token'] = md5(time().'bonus-register');
        return $token;
    }

    public static function ip(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else{
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function velify_token($data){
        
        $ip = self::ip();

        MYPDO::$table = 'token';
        MYPDO::$where = ['ip' => $ip];
        $result = MYPDO::first();
        $token = $result['token'];

        if ($data['token'] == $token){
            $time = $result['create_at_timestamp'];
            $now_time = time();
            if ((($now_time - $time) / 60) > 30){
                $return['success'] = false;
                $return['msg'] = 'token已過期';
            }else{
                $return['success'] = true;
            }
        }else{
            $return['success'] = false;
            $return['msg'] = 'token錯誤';
        }

        return $return;
    }

    public static function test()
    {
        echo 'test123';
    }
}

// echo tools::omgms();
// echo tools::validation_code();
// tool::test();

?>