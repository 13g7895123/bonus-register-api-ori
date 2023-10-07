<?php
include_once(__DIR__ . '/../__Class/ClassLoad.php');

class common
{
    // 資料庫設定
    public static function db_config()
    {
        MYPDO::$host = '139.162.15.125';
        MYPDO::$port = '9901';
        MYPDO::$db = 'register-db';
        MYPDO::$user = 'register_user';
        MYPDO::$pwd = '5mu8nd5m';
    }

    // 取得POST的資料
    public static function post_data()
    {
        // vue
        // $json_data = file_get_contents('php://input');  // string
        // $post_data = json_decode($json_data, true);     // string轉array
        
        // jquery
        $post_data = $_POST;

        return $post_data;
    }

    public static function curl_api($url, $set){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($set));
        if (substr($url, 0, 8) == 'https://') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        $str = curl_exec($ch);
        if ($errno = curl_errno($ch)){
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return $str;
    }
}
?>