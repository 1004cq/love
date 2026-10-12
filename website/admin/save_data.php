<?php
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $rawData = file_get_contents('php://input');

    if ($rawData === false) {
        echo json_encode([
            'status' => false,
            'message' => '错误：无法获取输入数据'
        ]);
        exit;
    }

    $data = json_decode($rawData, true);

    if ($data === null) {
        echo json_encode([
            'status' => false,
            'message' => '错误：无法解析 JSON 数据'
        ]);
        exit;
    }

    $filePath = '../api/ctplTow.json';
    $saveResult = file_put_contents($filePath, json_encode($data, JSON_UNESCAPED_UNICODE));

    if ($saveResult === false) {
        echo json_encode([
            'status' => false,
            'message' => '错误：保存数据失败，请检查文件权限或路径是否正确'
        ]);
        exit;
    }

    echo json_encode([
        'status' => true,
        'message' => '保存数据成功 请前往小程序查看是否正常！'
    ]);

} else {
    $AccessAddress = $file;
    include_once '../404.php';
}