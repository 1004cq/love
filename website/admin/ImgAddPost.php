<?php
// 主相册新增插入数据库
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $imgText = htmlspecialchars(trim($_POST['imgText']), ENT_QUOTES);
    $imgDatd = trim($_POST['imgDatd']);
    $u_name = trim($_POST['upload_name']);
    $img_code = trim($_POST['img_code']);
    $encryptionSwitch = trim($_POST['encryptionSwitch']);
    $PasswordSee = trim($_POST['PasswordSee']);
    $imgUrl = htmlspecialchars(trim($_POST['imgUrl']), ENT_QUOTES);
    if ($imgText <> '' && $imgUrl <> '') {
        $charu = "insert into loveImg (imgDatd,imgText,imgUrl,upload_name,img_code,encryptionSwitch,PasswordSee) values ('$imgDatd','$imgText','$imgUrl','$u_name','$img_code','$encryptionSwitch','$PasswordSee')";
        $result = mysqli_query($connect, $charu);
        if ($result) {
            echo "1";
        } else {
            echo "0";
        }
    } else {
        echo 2;
    }

} else {
    $AccessAddress = $file;
    include_once '../404.php';
}