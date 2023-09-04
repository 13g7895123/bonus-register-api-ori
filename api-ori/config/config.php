<?php

// $project = 'bonus_summary';

// if ($project == 'bonus_health_insurance'){
//     $account = 'bonus_health_insurance_remote';
//     $pwd = '820820';
//     $db = 'db_bonus_health_insurance';
// }elseif ($project == 'bonus_summary'){
//     $account = 'bonus_assistant_remote';
//     $pwd = '820820';
//     $db = 'db_assistant';
// }

//連線資料庫設定
const SQLPORT_SQL = '3306'; //資料庫port
const HOSTNAME_SQL = '139.162.15.125'; //資料庫位址
// const USERNAME_SQL = 'bonus_health_insurance_remote'; //帳號
const USERNAME_SQL = ''; //帳號
// const PASSWORD_SQL = '820820'; //密碼
const PASSWORD_SQL = ''; //密碼
// const DATABASE_SQL = 'db_bonus_health_insurance'; //資料庫名稱
const DATABASE_SQL = ''; //資料庫名稱
const SESSION_TIMEOUT = 86400; //session保留時間(單位秒)

//API驗證帳密
const AUTH_USER = "test";
const AUTH_PW = "00000000";

?>