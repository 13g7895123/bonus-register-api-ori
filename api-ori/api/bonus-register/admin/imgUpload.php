<?php
//引入
include_once(__DIR__ . '/../../../__Class/ClassLoad.php');
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../tools.php');

if (isset($_GET['action'])){
    switch ($_GET['action']){
        case 'imgUpload':
            $web_path = $_SERVER['DOCUMENT_ROOT'];  // 網站根目錄

            $type_name = 'server';
            $img_file_name = uuid();
            $extension = explode('.', $_FILES['file']['name'])[1];
            $img_path = "/"."img_upload/".$type_name.'/'.$img_file_name.'.'.$extension;

            $full_path = $web_path.$img_path;
            move_uploaded_file($_FILES['file']['tmp_name'], $full_path);

            $db_data = [];
            $db_data['server_id'] = $_GET['sid'];
            $db_data['bg_img_name'] = $_FILES['file']['name'];
            $db_data['bg_img_path'] = $img_path;

            $api_url = 'http://170.187.229.132:9091/api/bonus-register/admin/server.php?action=img_info';
            $response = curl_api($api_url, $db_data);

            echo $response;
            break;
    }
}

function uuid(){
    $chars = md5(uniqid(mt_rand(), true));
    $uuid = substr ( $chars, 0, 8 ) . '-'
        . substr ( $chars, 8, 4 ) . '-'
        . substr ( $chars, 12, 4 ) . '-'
        . substr ( $chars, 16, 4 ) . '-'
        . substr ( $chars, 20, 12 );
    return $uuid ;
}

function curl_api($url, $set){
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

?>