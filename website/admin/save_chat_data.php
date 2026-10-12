<?php
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {

    $rawData = file_get_contents('php://input');

    if ($rawData === false) {
        echo json_encode(['status' => false, 'message' => '无法获取输入数据']);
        exit();
    }

    $data = json_decode($rawData, true);

    if ($data === null) {
        echo json_encode(['status' => false, 'message' => 'JSON 数据解析失败']);
        exit();
    }

    $filePath = '../api/chatData.json';
    $saveResult = file_put_contents($filePath, json_encode($data, JSON_UNESCAPED_UNICODE));

    if ($saveResult === false) {
        echo json_encode(['status' => false, 'message' => '保存数据失败，请联系开发者排查！']);
        exit();
    }

    echo json_encode(['status' => true, 'message' => '保存数据成功 请前往小程序查看是否正常！']);
    
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}