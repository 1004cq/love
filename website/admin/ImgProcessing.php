<?php
// 设置本地上传图片水印
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $TextWateSwitch = trim($_POST['TextWateSwitch']);
    $ImgText = trim($_POST['ImgText']);
    $OpacityValue = trim($_POST['OpacityValue']);
    $colorPicker = trim($_POST['colorPicker']);
    $PhotoQuality = trim($_POST['PhotoQuality']);
    $cloudStorageSwitch = trim($_POST['cloudStorageSwitch']);
    $UploadDirectory = trim($_POST['UploadDirectory']);
    
    $Up_ImageSet = "update ImageSet set TextWateSwitch = '$TextWateSwitch',ImgText ='$ImgText',ColorOpacity = '$OpacityValue',colorPicker = '$colorPicker',PhotoQuality = '$PhotoQuality',UploadDirectory = '$UploadDirectory',localOrCloud = '$cloudStorageSwitch' where id = 1";
    $result = mysqli_query($connect, $Up_ImageSet);
    
    if ($result) {
        $response = array(
            'success' => true,
            'info' => '修改成功 正在刷新页面',
        );
    } else {
        $response = array(
            'err' => true,
            'info' => '修改失败 请重试',
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}