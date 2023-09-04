<?php

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

    public static function test()
    {
        echo 'test123';
    }
}

// echo tools::omgms();
// echo tools::validation_code();
// tool::test();

?>