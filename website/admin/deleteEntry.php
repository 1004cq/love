<?php

session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';

$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {


    $id = intval($_POST['id']);
    $DbNmae = trim($_POST['DbNmae']);

    $response = array(
        'Status' => false,
        'message' => null,
    );

    if (is_numeric($id) && !empty($DbNmae)) {

        if ($DbNmae == 'loveImg') {
            $deletePhotosSql = "DELETE FROM Photolist WHERE photo_id = $id";
            $deletePhotosResult = mysqli_query($connect, $deletePhotosSql);

            if ($deletePhotosResult) {
                $sql = "delete from $DbNmae where id = $id ";
                $result = mysqli_query($connect, $sql);
                if ($result) {
                    $response['Status'] = true;
                    $response['message'] = '删除成功 已删除当前数据！';
                } else {
                    $response['message'] = '删除失败 数据库处理错误！';
                }
            } else {
                $response['message'] = '删除失败 子相册清空出错！';
            }
        } else {
            $sql = "delete from $DbNmae where id = $id ";
            $result = mysqli_query($connect, $sql);
            if ($result) {
                $response['Status'] = true;
                $response['message'] = '删除成功 已删除当前数据！';
            } else {
                $response['message'] = '删除失败 数据库处理错误！';
            }
        }

    } else {
        $response['message'] = '请求失败 缺少重要参数！';
    }

    echo json_encode($response);

} else {
    $AccessAddress = $file;
    include_once '../404.php';
}

