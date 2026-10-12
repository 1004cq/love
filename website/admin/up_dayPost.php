<?php
session_start();

include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
$day_title = trim($_POST['day_title']);
$day_date = trim($_POST['day_date']);
$day_type = trim($_POST['day_type']);
$day_Weights = trim($_POST['day_Weights']);
$isDateHidden = trim($_POST['isDateHidden']);

$id = trim($_POST['id']);
    if ($day_title == '' || $day_date == '') {
        echo "99";
        exit;
    }
    $up_sql = "update loveday set day_title = '$day_title',day ='$day_date',day_type = '$day_type',day_Weights = '$day_Weights',isDateHidden = '$isDateHidden' where id = $id";
    $result = mysqli_query($connect, $up_sql);
    if ($result) {
        echo "1";
    } else {
        echo "0";
    }

} else {
    $AccessAddress = $file;
    include_once '../404.php';
}