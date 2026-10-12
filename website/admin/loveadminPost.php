<?php
//修改情侣相关内容
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $boy = htmlspecialchars(trim($_POST['boy']));
    $girl = htmlspecialchars(trim($_POST['girl']));
    $boyimg = htmlspecialchars(trim($_POST['boyimg']), ENT_QUOTES);
    $girlimg = htmlspecialchars(trim($_POST['girlimg']), ENT_QUOTES);
    $if_aiv = trim($_POST['if_aiv']);
    $diy_b = trim($_POST['b_diy']);
    $diy_g = trim($_POST['g_diy']);
    $loveDayTitle = trim($_POST['loveDayTitle']);
    $loveIconLink = trim($_POST['loveIconLink']);
    $startTime = trim($_POST['startTime']);
    $ml_frame = trim($_POST['ml_frame'] == 'null' ? 0 : intval($_POST['ml_frame']));
    $fl_frame = trim($_POST['fl_frame'] == 'null' ? 0 : intval($_POST['fl_frame']));

    function checkQQ($qq)
    {
        if (preg_match("/^[1-9][0-9]{4,}$/", $qq)) {
            return true;
        } else {
            return false;
        }
    }
    if (checkQQ($boyimg) && checkQQ($girlimg)) {
    $loveIconLink = trim($_POST['loveIconLink']);
        $sql = "update text set startTime = '$startTime', girlimg = '$girlimg' , boyimg = '$boyimg', girl = '$girl' , boy = '$boy', diyb_url = '$diy_b' , diyg_url = '$diy_g',if_aiv = '$if_aiv',ml_frame = '$ml_frame',fl_frame = '$fl_frame', loveDayTitle = '$loveDayTitle', loveIconLink = '$loveIconLink' ";
        $result = mysqli_query($connect, $sql);
        if ($result) {
            echo "1";
        } else {
            echo "0";
        }
    } else {
        echo "3";
    }
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}