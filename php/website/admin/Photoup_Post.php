<?php
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $id = trim($_POST['id']);
    $photoText = htmlspecialchars(trim($_POST['photoText']), ENT_QUOTES);
    $photoDatd = trim($_POST['photoDatd']);
    $by_name = trim($_POST['by_name']);
    $photoType = trim($_POST['photoType']);
    $photoUrl = htmlspecialchars(trim($_POST['photoUrl']), ENT_QUOTES);
    $VideoCover = htmlspecialchars(trim($_POST['VideoCover'] ? $_POST['VideoCover'] : ''), ENT_QUOTES);

    $sql = "update Photolist set photo_text = '$photoText',photo_url ='$photoUrl',photo_date = '$photoDatd',photo_byname = '$by_name',photo_type = '$photoType',VideoCover = '$VideoCover' where id = '$id'";
    $result = mysqli_query($connect, $sql);
    if ($result) {
        echo "1";
    } else {
        echo "0";
    }
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}