<?php
//站点底部版权信息修改更新
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $icp = trim($_POST['icp']);
    $policeICP = trim($_POST['policeICP']);
    $Moelicp = trim($_POST['Moelicp']);
    $Copyright = trim($_POST['Copyright']);
    $sql = "update text set icp = '$icp', Copyright = '$Copyright',Moelicp = '$Moelicp', policeICP = '$policeICP' ";
    $result = mysqli_query($connect, $sql);
    if ($result) {
        echo "<script>alert('更新成功');location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('更新失败');history.back();</script>";
    }
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}
