<?php
//恋爱事件更新处理
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $name_w = htmlspecialchars(trim($_POST['eventname']), ENT_QUOTES);
    $icon = $_POST['icon'];
    $id = $_POST['id'];
    $img = htmlspecialchars($_POST['imgurl'], ENT_QUOTES);
    $name = mysqli_real_escape_string($connect, $name_w);
    if (!empty($img)) {
        $img = htmlspecialchars($_POST['imgurl'], ENT_QUOTES);
    } else {
        $img = 0;
    }
    if (!$icon) {
        $icon = 0;
    } else {
        $icon = $_POST['icon'];
    }
    $sql = "update lovelist set eventname = '$name',icon ='$icon',imgurl ='$img' where id =$id ";
    $reslove = mysqli_query($connect, $sql);
    if ($reslove) {
        echo "1";
    } else {
        echo "0";
    }
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}
