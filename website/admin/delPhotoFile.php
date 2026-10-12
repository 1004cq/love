<?php
require_once __DIR__ . '/bootstrap.php';
lg_start_session();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';

$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

// 检查用户是否有权限
if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $uploadDir_sql = "SELECT UploadDirectory FROM ImageSet";
    $uploadDir_res = mysqli_query($connect, $uploadDir_sql);
    $uploadDir_data = mysqli_fetch_array($uploadDir_res);

    $allowedDirectory = realpath($_SERVER['DOCUMENT_ROOT'] . '/' . $uploadDir_data['UploadDirectory']);
    if ($allowedDirectory === false) {
        http_response_code(404);
        exit('上传目录不存在。');
    }
    $allowedPrefix = rtrim($allowedDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    function resolveDeletableMedia(string $allowedPrefix, string $filename): ?string
    {
        if ($filename === '' || str_contains($filename, "\0")) return null;
        $candidate = realpath($allowedPrefix . ltrim($filename, '/\\'));
        if ($candidate === false || !is_file($candidate)) return null;
        return str_starts_with($candidate, $allowedPrefix) ? $candidate : null;
    }

    // 判断是否为批量删除
    if (isset($_POST['isBatchDelete']) && $_POST['isBatchDelete'] == 1 && isset($_POST['keys'])) {
        // 批量删除的逻辑
        $files = json_decode($_POST['keys'], true);
        if (!is_array($files) || count($files) > 200) {
            http_response_code(422);
            exit('无效的批量删除请求。');
        }
        $failedDeletions = [];
        // error_log('keys为: ' . print_r($files, true) . "\n", 3, 'upload_errors.log');
        
        foreach ($files as $filename) {
            $filepath = resolveDeletableMedia($allowedPrefix, (string)$filename);

            // 确保路径不被篡改且文件存在
            if ($filepath !== null) {
                if (!unlink($filepath)) {
                    $failedDeletions[] = $filename;
                }
            } else {
                $failedDeletions[] = $filename;
            }
        }


        // 如果有删除失败的文件，返回失败信息
        if (count($failedDeletions) > 0) {
            // echo "删除失败的文件： " . implode(", ", $failedDeletions);
            echo 0; 
        } else {
            echo 1; // 所有文件删除成功
        }
    }
    // 单个文件删除的逻辑
    else if (isset($_POST['name'])) {
        $filename = $_POST['name'];
        $filepath = resolveDeletableMedia($allowedPrefix, (string)$filename);

        if ($filepath !== null) {
            if (unlink($filepath)) {
                echo 1;
            } else {
                echo 2;
            }
        } else {
            echo 3;
        }
    } else {
        echo "没有提供文件名或批量删除标识。";
    }
} else {
    $AccessAddress = $file;
    include_once '../404.php';
}
