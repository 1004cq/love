<?php
// 新增点点滴滴内容
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
if (userHasPermission($_SESSION['loginadmin'], $conn)) {

    $title = htmlspecialchars(trim($_POST['articletitle']), ENT_QUOTES);
    $city_ipa = trim($_POST['city_ipa']);
    $text = trim($_POST['articletext']);
    $name = trim($_POST['articlename']);
    $encryptionSwitch = isset($_POST['encryptionSwitch']) ? trim($_POST['encryptionSwitch']) : null;
    $PasswordSee = isset($_POST['PasswordSee']) ? trim($_POST['PasswordSee']) : null;
    $time = strtotime($_POST['little_day']);

    $sql = "INSERT INTO article (articletitle, articletext, articletime, articlename, ip_city, encryptionSwitch, PasswordSee) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "Error preparing the statement: " . $conn->error;
        exit;
    }

    $stmt->bind_param("sssssss", $title, $text, $time, $name, $city_ipa, $encryptionSwitch, $PasswordSee);

    $result = $stmt->execute();

    if ($result) {
        echo "1";
    } else {
        echo "0";
    }

    $stmt->close();
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}
