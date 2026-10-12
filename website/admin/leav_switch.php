<?php
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $id = $_POST['id'];
    $type = $_POST['type'];
    $leav_info_sql = "select * from leaving where id = $id";
    $res = mysqli_query($connect, $leav_info_sql);
    $data = mysqli_fetch_array($res);
    $arr = [
        'data' => $data,
        'msg_s' => "状态修改成功",
        'msg_f' => "状态修改失败",
    ];
    if (is_numeric($id)) {
        if ($type == "adopt") {
            $sql = "update leaving set switch = 1  where id = $id";
        } else if ($type == "fail") {
            $sql = "update leaving set switch = 0  where id = $id";
        }
        $result = mysqli_query($connect, $sql);
        if ($result) {
            echo json_encode($arr);
        } else {
            echo json_encode($arr);
        }
    } else {
        echo "<script>alert('参数错误');history.back();</script>";
    }

} else {
    $AccessAddress = $file;
    include_once '../404.php';
}