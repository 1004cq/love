<?php
//更新恋爱日期
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';

$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
if (userHasPermission($_SESSION['loginadmin'], $conn)) {

    $id = trim($_POST['id']);
    $MsgText = trim($_POST['MsgText']);
    $MsgName = trim($_POST['MsgName']);
    $MsgQQ = trim($_POST['MsgQQ']);
    $MsgTime = strtotime($_POST['MsgTime']);
    $MsgIP = trim($_POST['MsgIP']);
    $MsgCity = trim($_POST['MsgCity']);
    $Os_info = trim($_POST['Os_info']);
    $Br_info = trim($_POST['Br_info']);


    $up_sql = "update leaving set name = '$MsgName',QQ ='$MsgQQ',text = '$MsgText',time = '$MsgTime',ip = '$MsgIP',city = '$MsgCity' ,Os_info = '$Os_info',Br_info = '$Br_info'  where id = $id";
    
    $result = mysqli_query($connect, $up_sql);
    if ($result) {
        echo 1;
    } else {
        echo 0;
    }

} else {
    $AccessAddress = $file;
    include_once '../404.php';
}