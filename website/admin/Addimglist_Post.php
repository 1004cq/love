<?php
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $id = $_POST['id'];
    $imglist_text = htmlspecialchars(trim($_POST['imglist_text']), ENT_QUOTES);
    $imglistDatd = trim($_POST['imglistDatd']);
    $by_name = trim($_POST['by_name']);
    $img_code = trim($_POST['img_code']);
    $photo_type = trim($_POST['photo_type']);
    $VideoCover = htmlspecialchars(trim($_POST['VideoCover'] ? $_POST['VideoCover'] : ''), ENT_QUOTES);
    $imglistUrl = htmlspecialchars(trim($_POST['imglistUrl']), ENT_QUOTES);

    $num = 1;
    $imglistUrls = explode("\n", $imglistUrl);
    if ($imglist_text == '' || $imglistUrl == '') {
        echo "99";
        exit;
    }
    foreach ($imglistUrls as $url) {
        $in_sql = "INSERT Photolist (photo_id,photo_code,photo_text,photo_url,photo_date,photo_byname,photo_type,VideoCover) values ('$id','$img_code','$imglist_text','$url','$imglistDatd','$by_name','$photo_type','$VideoCover')";
        $result = mysqli_query($connect, $in_sql);
        if ($result) {
            echo "上传当前相册成功 媒体序号：$num </br>";
        } else {
            echo "上传当前相册失败</br>数据库写入错误 请重试！";
        }
        $num++;
    }
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}