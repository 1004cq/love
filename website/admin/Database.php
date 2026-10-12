<?php
/*
 * @Page: "数据库预处理信息配置"
 * @Author: Ki.
 * @Project: LG_NewUi
 * @LastEditTime: 2024-04-30 14:44:48
 * @Description: 小手一牵 岁岁年年
 * Copyright (c) 2022-2024 by Ki All Rights Reserved.
 */
error_reporting(E_ALL);
ini_set('display_errors', '0');
header("Content-Type:text/html; charset=utf-8");
include_once __DIR__.'/Config_DB.php';
mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli($db_address, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    define('ConnDie', true);
    include_once 'connectDie.php';
    exit;
}

$conn->set_charset("utf8mb4");
