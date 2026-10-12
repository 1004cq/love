<?php
declare(strict_types=1);
/*
 * Project: LG_NewUi
 * Author: Ki
 * Last Updated: 2024-12-21
 * Description: 小手一牵 岁岁年年
 * 
 * Copyright (c) 2022-2024 by Ki. All Rights Reserved.
 * 
 * License: This software is licensed for both personal and commercial use under specific terms.
 * Redistribution, modification, or resale without explicit written permission is strictly prohibited.
 * Unauthorized use, copying, or redistribution will result in immediate termination of support and updates.
 * 
 * Documentation and License: https://lguiy.kikiw.cn/wiki
 * 
 * * Warning: This is an original work, and all copyright belongs to LG_NewUi.
 * Any attempt to scrape, copy, sell, or redistribute this software without permission is strictly forbidden.
 * Violators will face immediate legal action, blacklisting, and permanent cessation of future updates.
 * 
 * Development requires substantial time and effort. We appreciate your respect for the work involved and your support.
 */
require_once __DIR__ . '/bootstrap.php';

function checkInstallLock(): void {
    $installLockPath = dirname(__DIR__) . '/install/install.lock';
    if (!file_exists($installLockPath)) {
        header('Location: /install/index.php');
        exit;
    }
}

checkInstallLock();

//数据库地址 
$db_address = "";

//数据库用户名
$db_username = "";

//数据库密码
$db_password = "";

//数据库名
$db_name = "";

/*
-----------------------------------------
小站管理员后台修改敏感内容时，需要进行安全码认证。
请为认证设置一个自定义安全码。
-----------------------------------------
*/
$LGUI_Code = "";

// Production can inject secrets without storing them in the web directory.
$db_address = getenv('LG_DB_HOST') ?: $db_address;
$db_username = getenv('LG_DB_USER') ?: $db_username;
$db_password = getenv('LG_DB_PASSWORD') ?: $db_password;
$db_name = getenv('LG_DB_NAME') ?: $db_name;
$LGUI_Code = getenv('LG_SECURITY_CODE') ?: $LGUI_Code;

$version = "2026 升级版";

$versionCode = "20260913";
