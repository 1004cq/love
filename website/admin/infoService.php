<?php

include_once 'connect.php';
include_once "Database.php";
include_once "Function.php";

$response = array(
    'Status' => false,
    'message' => null,
    'randomContent' => null,
    'data' => null,
    'city' => Get_City($ip_if),
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $action = isset($_POST['action']) ? $_POST['action'] : null;

// var_dump($_POST);

    if (!$action) {
        $RandomWordSql = "SELECT randomWord FROM leavSet";
        $RandomWordResult = mysqli_query($connect, $RandomWordSql);
        $RandomWordArr = mysqli_fetch_array($RandomWordResult);
        
        if ($RandomWordResult) {
            $blessings = explode("\n", $RandomWordArr['randomWord']);
            $randomIndex = array_rand($blessings);
            $response['randomContent'] = trim($blessings[$randomIndex]);

            $response['Status'] = true;
            $response['message'] = '获取随机祝福文案成功！';
        } else {
            $response['message'] = '获取随机祝福文案失败 请重试！';
        }

    } elseif ($action == 'qq') {
        
        if (isset($_POST['qq']) && !empty($_POST['qq'])) {
            $qq = trim($_POST['qq']);
            $qqData = Get_QQData($qq);

            $response['Status'] = $qqData['Status'];
            if ($response['Status']) {
                $response['message'] = '获取头像&昵称信息成功！';
                $response['data'] = $qqData['data'];
            }else{
                $response['message'] = '获取头像&昵称失败！';
            }

        } else {
            $response['message'] = '缺少QQ号码参数！';
        }
    } else {
        $response['message'] = '无效的请求类型！';
    }
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    $response['message'] = '当前接口只允许 POST 请求！';
}

echo json_encode($response);
