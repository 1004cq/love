<?php
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $title = trim($_POST['title']);
    $logo = htmlspecialchars(trim($_POST['logo']), ENT_QUOTES);
    $writing = htmlspecialchars(trim($_POST['writing']), ENT_QUOTES);
    $WebPjax = trim($_POST['WebPjax']);
    $WebBlur = trim($_POST['WebBlur']);
    $Fullscreen = trim($_POST['Fullscreen']);
    $BackgroundMask = trim($_POST['BackgroundMask']);
    $seo_switch = trim($_POST['seo_switch']);
    $site_description = trim($_POST['site_description']);
    $global_keywords = trim($_POST['global_keywords']);

    $sql = "UPDATE text SET title = ?, logo = ?, writing = ?,seo_switch = ?,site_description = ?,global_keywords = ?  WHERE id = 1";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssiss", $title, $logo, $writing,$seo_switch,$site_description,$global_keywords);
        if ($stmt->execute()) {
            echo "1";
        } else {
            echo "0";
        }
        $stmt->close();
    } else {
        echo "0";
    }


    $diy = "update diySet set Pjaxkg = '$WebPjax' , Blurkg = '$WebBlur', Fullscreen = '$Fullscreen',BackgroundMask = '$BackgroundMask' where id = 1";
    $diyresult = mysqli_query($connect, $diy);
    if ($diyresult) {
        echo "3";
    } else {
        echo "4";
    }
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}
