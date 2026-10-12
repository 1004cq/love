<?php
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {

    $diy_load = trim($_POST['diy_load']);
    $aiv_name_show = trim($_POST['aiv_name_show']);

    $RandomSort = trim($_POST['RandomSort']);
    $pcPhotoCoverHeight = trim($_POST['pcPhotoCoverHeight']);
    $mobilePhotoCoverHeight = trim($_POST['mobilePhotoCoverHeight']);
    $pcImgMaxHeight = trim($_POST['pcImgMaxHeight']);
    $mobileImgMaxHeight = trim($_POST['mobileImgMaxHeight']);

    include_once 'connect.php';
    $sql = "update diySet set diy_load = '$diy_load', aiv_name_show = '$aiv_name_show',pcPhotoCoverHeight = '$pcPhotoCoverHeight',mobilePhotoCoverHeight ='$mobilePhotoCoverHeight',pcImgMaxHeight = '$pcImgMaxHeight',mobileImgMaxHeight = '$mobileImgMaxHeight',RandomSort = '$RandomSort' ";
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
