<?php
// 恋爱事件插入处理
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $name_w = htmlspecialchars(trim($_POST['eventname']), ENT_QUOTES);
    if ($_POST['img'] === 0) {
        $img = 0;
    } else {
        $img = htmlspecialchars($_POST['img'], ENT_QUOTES);
    }
    if ($_POST['icon'] == 1) {
        $icon = 1;
    } else {
        $icon = 0;
    }
    $name = mysqli_real_escape_string($connect, $name_w);
    $sele_name = "select eventname from lovelist where eventname = '$name'";
    $res = mysqli_query($connect, $sele_name);
    $name_row = mysqli_num_rows($res);
    if ($name_row >= 1) {
        echo "事件名称已存在 请重新输入！";
        exit;
    }
    $charu = "insert into lovelist (eventname,icon,imgurl) values ('$name','$icon','$img')";
    $result = mysqli_query($connect, $charu);
    if ($result) {
        echo "1";
    } else {
        echo "0";
    }
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}
