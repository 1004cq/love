<?php

session_start();
include_once "Database.php";
include_once 'Function.php';

$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {

    $MesgContent = trim($_POST['MesgContent']);

    $response = array(
        'Status' => false,
        'message' => null,
        'Content' => null
    );

    if (!empty($MesgContent)) {
        $response['Status'] = true;
        $response['Content'] = parseEmojis($MesgContent);
    } else {
        $response['message'] = '处理失败 缺少留言文本内容！';
    }

    echo json_encode($response);

} else {
    $AccessAddress = $file;
    include_once '../404.php';
}

