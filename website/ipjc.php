<?php

include_once 'admin/connect.php';

$ipchaxun = "select * from IPerror";
$ipres = mysqli_query($connect, $ipchaxun);

while ($IPinfo = mysqli_fetch_array($ipres)) {

    $iplist = $IPinfo['State'];

    $banned_ip = array($iplist);

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $_SERVER['REMOTE_ADDR'] = $list[0];
        $IPAdd = $_SERVER['REMOTE_ADDR'];
    } else {
        $IPAdd = $_SERVER['REMOTE_ADDR'];
    }

    if (in_array($IPAdd, $banned_ip)) {
        $testingIP = $IPinfo['State'];
        $testingTime = $IPinfo['Time'];
        $testingCity = $IPinfo['ipAdd'];
        $testingText = $IPinfo['text'];
        $PageType = 1;
        include_once '404.php';
    }
}

