<?php
//修改点点滴滴内容
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $id = $_POST['id'];
    $title = htmlspecialchars(trim($_POST['articletitle']), ENT_QUOTES);
    $text = trim($_POST['articletext']);
    $time = strtotime($_POST['little_day']);
    $encryptionSwitch = isset($_POST['encryptionSwitch']) ? trim($_POST['encryptionSwitch']) : null;
    $PasswordSee = isset($_POST['PasswordSee']) ? trim($_POST['PasswordSee']) : null;

    $sql = "UPDATE article SET articletitle = ?, articletext = ?, articletime = ?, encryptionSwitch = ?, PasswordSee = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "Error preparing the statement: " . $conn->error;
        exit;
    }

    $stmt->bind_param("sssisi", $title, $text, $time, $encryptionSwitch, $PasswordSee, $id);

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
