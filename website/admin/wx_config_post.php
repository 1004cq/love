<?php
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {

    $audit_mode = intval(trim($_POST['audit_mode']));

    $template_id = intval(trim($_POST['template_id']));

    $swiperHeight = intval(trim($_POST['swiperHeight']));

    $test_cover = htmlspecialchars(trim($_POST['test_cover']), ENT_QUOTES);

    $sample_title = trim($_POST['sample_title']);

    $sample_description = trim($_POST['sample_description']);

    $share_title = trim($_POST['share_title']);

    $share_cover = htmlspecialchars(trim($_POST['share_cover']), ENT_QUOTES);

    $custom_font_url = htmlspecialchars(trim($_POST['custom_font_url']), ENT_QUOTES);


    $UP_wx_app_sql = "UPDATE wx_app_config SET audit_mode = ?, template_id = ?, custom_font_url = ?, swiperHeight = ?, sample_title = ?, test_cover = ?, sample_description = ?, share_title = ?, share_cover = ? WHERE id = 1";
    $stmt = $conn->prepare($UP_wx_app_sql);

    if (!$stmt) {
        echo "Error preparing the statement: " . $conn->error;
        exit;
    }

    $stmt->bind_param("iisisssss", $audit_mode, $template_id, $custom_font_url, $swiperHeight, $sample_title, $test_cover, $sample_description, $share_title, $share_cover);

    $result = $stmt->execute();


    $response = array(
        'Status' => false,
        'message' => '更新小程序配置失败 请联系开发者！',
    );


    if ($result) {
        $response['Status'] = true;
        $response['message'] = '更新小程配置成功！ <br/>请前往监控API页面手动执行更新！';
    }

    $stmt->close();

    echo json_encode($response);
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}
