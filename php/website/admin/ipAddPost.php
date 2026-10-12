<?php
// 新增封禁IP插入
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $ipdz = trim($_POST['ipdz']);
    $text = trim($_POST['bz']);
    $time = gmdate("Y-m-d H:i:s", time() + 8 * 3600);
    $city = Get_City($ipdz);

    if ($ipdz == '') {
        echo "0";
        exit;
    }
    $select_Ip = "select State from IPerror where State = '$ipdz'";
    $res = mysqli_query($connect, $select_Ip);
    $Iprow = mysqli_num_rows($res);
    if ($Iprow >= 1) {
        echo "9";
        exit;
    }
    $ipcharu = "insert into IPerror (ipAdd,Time,State,text) values ('$city','$time','$ipdz','$text')";
    $result = mysqli_query($connect, $ipcharu);
    if ($result) {
        echo "1";
    } else {
        echo "0";
    }
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}
mysqli_close($connect);
