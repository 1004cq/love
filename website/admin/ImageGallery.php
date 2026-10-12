<?php
session_start();
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';

class ImageGallery
{
    private $imageDir;

    public function __construct($imageDir)
    {
        $this->imageDir = '../'.$imageDir;
    }

    // 获取所有图片列表
    public function getAllImages()
    {
        // 获取所有图片文件
        $files = glob($this->imageDir . '*.{JPG,PNG,jpg,jpeg,png,gif,webp}', GLOB_BRACE);
        natcasesort($files); // 自然排序
        $files = array_reverse($files); // 倒序

        $absolutePaths = [];

        foreach ($files as $file) {
            $domainImgHost = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $domainImgHost .= "://" . $_SERVER['HTTP_HOST'];

            $fixedPath = preg_replace('/\.\.\//', '', $file);
            $absolutePath = "$domainImgHost/$fixedPath";
            $absolutePaths[] = $absolutePath;
        }

        // 返回所有图片的绝对路径
        return [
            'images' => $files,
            'absolutePaths' => $absolutePaths,
            'isCloud' => 0
        ];
    }
}



if (userHasPermission($_SESSION['loginadmin'], $conn)) {

    $ImageSet_sql = "select * from ImageSet";
    $ImageSet_res = mysqli_query($connect, $ImageSet_sql);
    $ImageSet_data = mysqli_fetch_array($ImageSet_res);
    $Lovefolder = $ImageSet_data['UploadDirectory'];
    $localOrCloud = $ImageSet_data['localOrCloud'];
    include_once 'CloudStorageManager.php';

    if (!$localOrCloud) {
        $imageGallery = new ImageGallery($Lovefolder);
        $result = $imageGallery->getAllImages();
    } else {
        $result = [
            'absolutePaths' => [],
            'isCloud' => 1
        ];

        foreach ($listResponse as $item) {
            if ((isset($item['Size']) && $item['Size'] == 0) || (isset($item['fsize']) && $item['fsize'] == 0)) {
                continue;
            }

            $result['absolutePaths'][] = $item;
        }
    }


    echo json_encode($result);
    // echo json_encode($listResponse);
}
