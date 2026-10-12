<?php
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {

    $PrType = trim(intval($_POST['PrType']));

    $title = trim($_POST['title']);
    $url = trim($_POST['url']);
    $scale = trim($_POST['scale']);
    $top_offset = trim($_POST['top_offset']);
    $left_offset = trim($_POST['left_offset']);
    $created_at = date("Y-m-d H:i:s", time());

    $F_Title = mysqli_real_escape_string($connect, $title);
    $Select_Title = "select title from CoupleAvatarFrames where title = '$F_Title'";
    $resData = mysqli_query($connect, $Select_Title);
    $Title_row = mysqli_num_rows($resData);

    $response = array(
        'Status' => false,
        'message' => null,
    );

    $insertSuccessful = true;

    if (!empty($title) || !empty($url)) {

        if ($PrType) {
            $id = trim($_POST['id']);
            $sql = "UPDATE CoupleAvatarFrames SET title = '$title', url = '$url', scale = '$scale', top_offset = '$top_offset', left_offset = '$left_offset' WHERE id = $id";
        } else {

            if ($Title_row >= 1) {
                $response['message'] = '当前标题/描述数据库已存在 请更换！';
                $insertSuccessful = false;
            }

            $sql = "INSERT CoupleAvatarFrames (title,url,scale,top_offset,left_offset,created_at) values ('$title','$url','$scale','$top_offset','$left_offset','$created_at')";
        }

        if ($insertSuccessful) {
            $result = mysqli_query($connect, $sql);
            if ($result) {
                $response['Status'] = true;
                if ($PrType) {
                    $response['message'] = '更新头像框信息成功！';
                } else {
                    $response['message'] = '新增头像框成功！';
                }
            } else {
                $response['message'] = '新增头像框失败 请重试！';
            }
        }

    } else {
        $response['message'] = '请先填写头像框基本信息！';
    }

    echo json_encode($response);

} else {

    $AccessAddress = $file;
    include_once '../404.php';
}
