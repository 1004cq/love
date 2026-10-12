<?php
// 新增恋爱日期插入数据库
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $day_title = trim($_POST['day_title']);
    $day_date = trim($_POST['day_date']);
    $day_type = trim($_POST['day_type']);
    $day_Weights = trim($_POST['day_Weights']);
    $isDateHidden = trim($_POST['isDateHidden']);

    if ($day_title == '' || $day_date == '') {
        echo "99";
        exit;
    }
    $in_sql = "insert into loveday (day_title,day,day_type,day_Weights,isDateHidden) values ('$day_title','$day_date','$day_type','$day_Weights','$isDateHidden')";
    $result = mysqli_query($connect, $in_sql);
    if ($result) {
        echo "1";
    } else {
        echo "0";
    }

} else {
    $AccessAddress = $file;
    include_once '../404.php';
}