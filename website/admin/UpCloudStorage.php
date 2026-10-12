<?php
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {

    @$currentCloudId = intval(trim($_POST['currentCloudId']));
    @$AccessKey = trim($_POST['AccessKey']);
    @$SecretKey = trim($_POST['SecretKey']);
    @$Bucket = trim($_POST['Bucket']);
    @$Endpoint = htmlspecialchars(trim($_POST['Endpoint']), ENT_QUOTES, 'UTF-8') ?? '';
    @$customCDN = htmlspecialchars(trim($_POST['customCDN']), ENT_QUOTES, 'UTF-8') ?? '';
    @$Region = trim($_POST['Region']) ?? '';

    @$updateType = trim($_POST['updateType']) ?? '';

    $response = [];

    if ($updateType) {
        mysqli_begin_transaction($connect);
        try {
            $resetCurrentSql = "UPDATE CloudStorageConfig SET Current = NULL";
            mysqli_query($connect, $resetCurrentSql);

            $setCurrentSql = "UPDATE CloudStorageConfig SET Current = 1 WHERE id = $currentCloudId";
            mysqli_query($connect, $setCurrentSql);

            mysqli_commit($connect);
            $response = array('States' => true, 'info' => '选择对象存储成功！');
        } catch (Exception $e) {
            mysqli_rollback($connect);
            $response = array('States' => false, 'info' => '选择对象存储失败，请重试！');
        }
    } else {
        $Up_CloudStorage = "UPDATE CloudStorageConfig set AccessKey	 = '$AccessKey',SecretKey ='$SecretKey',BucketName = '$Bucket',Endpoint = '$Endpoint',customCDN = '$customCDN',Region = '$Region' where id = $currentCloudId";
        $result = mysqli_query($connect, $Up_CloudStorage);

        if ($result) {
            $response = array(
                'States' => true,
                'info' => '修改配置成功 正在刷新页面！',
            );
        } else {
            $response = array(
                'States' => false,
                'info' => '修改配置失败 请重试！',
            );
        }
    }


    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}
