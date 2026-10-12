<?php
//邮件信息配置
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $title = trim($_POST['title']);
    $from_name = htmlspecialchars(trim($_POST['from_name']), ENT_QUOTES);
    $host = htmlspecialchars(trim($_POST['host']), ENT_QUOTES);
    $mail_ssl = trim($_POST['mail_ssl']);
    $port = trim($_POST['port']);
    $mail_user = trim($_POST['mail_user']);
    $mail_pw = trim($_POST['mail_pw']);
    $admin_mail = trim($_POST['admin_mail']);
    $admin_name = trim($_POST['admin_name']);
    $user_mail = trim($_POST['user_mail']);
    $user_name = trim($_POST['user_name']);
    $SendingSwitch = trim($_POST['SendingSwitch']);
    $LoginMailSwitch = trim($_POST['LoginMailSwitch']);
    $LoginSendlMinute = trim($_POST['LoginSendlMinute']);
    $sql = "update MailSet set title = '$title', from_name = '$from_name' , host = '$host',mail_ssl = '$mail_ssl',port = '$port',mail_user = '$mail_user',mail_pw = '$mail_pw',admin_mail = '$admin_mail',admin_name = '$admin_name',user_mail = '$user_mail',user_name = '$user_name',SendingSwitch = '$SendingSwitch',LoginMailSwitch ='$LoginMailSwitch',LoginSendlMinute='$LoginSendlMinute'";
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