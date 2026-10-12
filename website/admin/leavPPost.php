<?php
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $jiequ = trim($_POST['jiequ']);
    $leav_day_num = trim($_POST['leav_day_num']);
    $MsgSwitch = trim($_POST['MsgSwitch']);
    $MsgVerify = trim($_POST['MsgVerify']);
    $CutOffSwitch = trim($_POST['CutOffSwitch']);
    $EmojiSwitch = trim($_POST['EmojiSwitch']);
    $lanjiezf = htmlspecialchars(trim($_POST['lanjiezf']), ENT_QUOTES);
    $randomWord = htmlspecialchars(trim($_POST['randomWord']), ENT_QUOTES);
    $anonymousArr = htmlspecialchars(trim($_POST['anonymousArr']), ENT_QUOTES);
    
    $sql = "update leavSet set jiequ = '$jiequ',lanjiezf ='$lanjiezf',leav_day_num ='$leav_day_num',anonymousArr='$anonymousArr',MsgSwitch = '$MsgSwitch',MsgVerify= '$MsgVerify',CutOffSwitch = '$CutOffSwitch',EmojiSwitch = '$EmojiSwitch',randomWord = '$randomWord' ";
    $result = mysqli_query($connect, $sql);
    if ($result) {
        echo "1";
    } else {
        echo "0";
    }
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}