<?php

$time = gmdate("Y-m-d H:i:s", time() + 8 * 3600);
$gsd = Get_City($ip_if);
$log_file = "access_log.log";

// 记录更多访问信息
$page_visited = $_SERVER['REQUEST_URI'];  // 访问的页面
$request_method = $_SERVER['REQUEST_METHOD'];  // 请求方法
$user_agent = $_SERVER['HTTP_USER_AGENT'];  // 用户代理信息
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direct Access';  // 来源页面

// 生成日志条目
$log_entry = "\n". $ip_if . "|" . $gsd . "|" . $time . "|" . $request_method . "|" . $page_visited . "|" . $user_agent . "|" . $referer . "\n";

if (!isset($_COOKIE["getIP"])) {
    $fp = fopen($log_file, "a");
    fputs($fp, $log_entry);
    fclose($fp);
    setcookie("getIP", $ip_if, time() + 120);
}

