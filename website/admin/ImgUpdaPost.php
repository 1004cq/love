<?php
// 更新主相册内容
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $id = trim($_POST['id']);
    $imgText = htmlspecialchars(trim($_POST['imgText']), ENT_QUOTES);
    $imgDatd = trim($_POST['imgDatd']);
    $imgUrl = htmlspecialchars(trim($_POST['imgUrl']), ENT_QUOTES);
    @$encryptionSwitch = trim($_POST['encryptionSwitch']);
    @$PasswordSee = trim($_POST['PasswordSee']);
    $sql = "update loveImg set imgText = '$imgText', imgDatd = '$imgDatd',imgUrl ='$imgUrl',encryptionSwitch= '$encryptionSwitch',PasswordSee = '$PasswordSee' where id = $id";
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

