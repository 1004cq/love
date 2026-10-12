<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
header("Content-Type:text/html; charset=utf-8");
include_once __DIR__.'/Config_DB.php';
mysqli_report(MYSQLI_REPORT_OFF);
$connect = @mysqli_connect($db_address, $db_username, $db_password, $db_name);

if (!$connect) {
    define('ConnDie', true);
    include_once 'connectDie.php';
    exit;
}

$LGNew_UI_Code = $LGUI_Code;
$MailSend = "select SendingSwitch,LoginMailSwitch,LoginSendlMinute FROM MailSet";
$MailSend_res = mysqli_query($connect, $MailSend);
$MailSend_arr = $MailSend_res ? (mysqli_fetch_array($MailSend_res) ?: []) : [];
$PublicSendingSwitch = $MailSend_arr['SendingSwitch'] ?? 0;
$PublicLoginMailSwitch = $MailSend_arr['LoginMailSwitch'] ?? 0;
$PublicLoginSendlMinut = intval($MailSend_arr['LoginSendlMinute'] ?? 10);

// Single text query (was duplicated: title-only then select *)
$sql = "select * from text";
$result = mysqli_query($connect, $sql);
$text = $result ? (mysqli_fetch_array($result) ?: []) : [];
$GLOBALS['WebQueryResult'] = $text;
$LoveTitle = $text['title'] ?? 'LGNewUI';

$AvatarFrames_sql = "select * from CoupleAvatarFrames";
$AvatarFrames_result = mysqli_query($connect, $AvatarFrames_sql);
$AvatarFrames = [];
while ($AvatarFrames_result && ($row = mysqli_fetch_assoc($AvatarFrames_result))) {
    $AvatarFrames[] = $row;
}
$GLOBALS['CoupleAvatarFrames'] = $AvatarFrames;



 
mysqli_set_charset($connect, "utf8mb4");
