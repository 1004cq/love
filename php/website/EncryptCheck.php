<?php
session_start();

include_once "admin/Database.php";
include_once 'admin/Function.php';

if (!defined('KI_Eecrypt')) {
    define('KI_Eecrypt', 'Kgs$JC!V');
}

$passwordValidator = new PasswordValidator($conn);

function Ki_Encrypt($password, $pageid = 'AllPage')
{
    global $passwordValidator;
    // 动态 Page ID 和加密操作
    $md5pw = md5(md5($password) . KI_Eecrypt);
    $postpwd = isset($_POST['pagepwd']) ? addslashes(trim($_POST['pagepwd'])) : '';
    $cookiepwd = isset($_COOKIE['ki_Enc_' . $pageid]) ? addslashes(trim($_COOKIE['ki_Enc_' . $pageid])) : '';

    // 如果 cookie 存在并正确，直接返回
    if ($cookiepwd == $md5pw) {
        // 验证成功后，清除 SESSION 数据
        unset($_SESSION['encryption_data']);
        echo json_encode(['success' => true]);
        return;
    }

    $PwChenckResult = $passwordValidator->validate($postpwd, $password, 1);

    // 如果提交的密码正确，设置 cookie 并返回成功响应
    if ($PwChenckResult['success']) {
        setcookie('ki_Enc_' . $pageid, $md5pw, time() + 36000, '/');
        // 验证成功后，清除 SESSION 数据
        unset($_SESSION['encryption_data']);

        echo json_encode(['success' => true]);
        return;
    }
    // 密码错误，返回错误信息
    echo json_encode(['success' => false, 'message' => $PwChenckResult['message']]);
    exit();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = isset($_SESSION['encryption_data']) ? $_SESSION['encryption_data'] : null;

    if ($data) {
        $password = $data['password'];
        $pageid = $data['pageid'];

        // 调用加密函数
        Ki_Encrypt($password, $pageid);
    } else {
        echo json_encode(['success' => false, 'message' => '未找到加密数据 请重试！']);
        exit();
    }
}
