<?php
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $card1 = htmlspecialchars(trim($_POST['card1']), ENT_QUOTES);
    $card2 = htmlspecialchars(trim($_POST['card2']), ENT_QUOTES);
    $card3 = htmlspecialchars(trim($_POST['card3']), ENT_QUOTES);
    $card4 = htmlspecialchars(trim($_POST['card4']), ENT_QUOTES);
    $deci1 = htmlspecialchars(trim($_POST['deci1']), ENT_QUOTES);
    $deci2 = htmlspecialchars(trim($_POST['deci2']), ENT_QUOTES);
    $deci3 = htmlspecialchars(trim($_POST['deci3']), ENT_QUOTES);
    $deci4 = htmlspecialchars(trim($_POST['deci4']), ENT_QUOTES);
    $icp = htmlspecialchars(trim($_POST['icp']), ENT_QUOTES);
    $Copyright = htmlspecialchars(trim($_POST['Copyright']), ENT_QUOTES);
    $Moelicp = trim($_POST['Moelicp']);
    $policeICP = trim($_POST['policeICP']);

    $bgimg = htmlspecialchars(trim($_POST['bgimg']), ENT_QUOTES);

    $sql = "update text set Moelicp = '$Moelicp', policeICP = '$policeICP', icp = '$icp', Copyright = '$Copyright', card1 = '$card1',card2 = '$card2',card3 = '$card3',card4 = '$card4',deci1 = '$deci1',deci2 = '$deci2',deci3 = '$deci3',deci4 = '$deci4',bgimg = '$bgimg' ";
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

