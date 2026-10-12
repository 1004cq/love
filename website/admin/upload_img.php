<?php
session_start();
include_once "Database.php";
include_once 'Function.php';
require '../vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use OSS\OssClient;
use OSS\Core\OssException;
use Qiniu\Auth as QiniuAuth;
use Qiniu\Storage\UploadManager;

if (userHasPermission($_SESSION['loginadmin'], $conn)) {

    include_once 'connect.php';
    $ImageSet_sql = "select * from ImageSet";
    $ImageSet_res = mysqli_query($connect, $ImageSet_sql);
    $ImageSet_data = mysqli_fetch_array($ImageSet_res);

    $Lovefolder = $ImageSet_data['UploadDirectory'];
    $MediaUploadMethod = $ImageSet_data['localOrCloud'];


    // 获取站点域名
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
    $domain = $_SERVER['HTTP_HOST'];
    $GetcurrentUrl = $protocol . $domain;

    //图片水印+压缩质量 
    function compressImage($sourcePath, $targetPath, $skipCompression, $skipWatermark)
    {
        global $ImageSet_data;


        $PhotoQuality = $ImageSet_data['PhotoQuality'];
        $TextWateSwitch = $ImageSet_data['TextWateSwitch'];

        if (($skipCompression == 1 || $PhotoQuality == 100) && ($TextWateSwitch == "false" || $skipWatermark == 1)) {
            return copy($sourcePath, $targetPath);
        }
        

        $info = getimagesize($sourcePath);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($sourcePath);
                break;

            // case 'image/gif':
            //     $image = imagecreatefromgif($sourcePath);
            //     break;
            default:
                return false;
        }

        // 检查图片方向并进行调整
        @$exif = exif_read_data($sourcePath);
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $image = imagerotate($image, 180, 0); // 使用透明色
                    break;
                case 6:
                    $image = imagerotate($image, -90, 0); // 使用透明色
                    break;
                case 8:
                    $image = imagerotate($image, 90, 0); // 使用透明色
                    break;
            }
        }

        // 设置透明背景
        if ($mime == 'image/png') {
            imagealphablending($image, false); // 关闭混合
            imagesavealpha($image, true); // 保存 alpha 通道
            $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127); // 完全透明
            imagefill($image, 0, 0, $transparent); // 填充透明背景
        }

        // 设置字体大小
        $watermarkSize = imagesx($image) * 0.1;
        $fontSize = $watermarkSize / 4;
        $font = __DIR__ . "/../Style/Font/SFont.ttf";
        $Color_str = $ImageSet_data['colorPicker'] . ',' . $ImageSet_data['ColorOpacity'];
        $arrayColor = explode(",", $Color_str);
        $color = imagecolorclosestalpha($image, $arrayColor[0], $arrayColor[1], $arrayColor[2], $arrayColor[3]);
        $text = $ImageSet_data['ImgText'];
        $textInfo = imagettfbbox($fontSize, 0, $font, $text);
        $textWidth = $textInfo[2] - $textInfo[0];
        $textHeight = $textInfo[1] - $textInfo[7];
        $x = imagesx($image) - $textWidth - 80;
        $y = imagesy($image) - 10 - $textHeight;

        // 添加文本水印
        if ($TextWateSwitch != "false" && $skipWatermark != 1) {
            imagettftext($image, $fontSize, 0, $x, $y, $color, $font, $text);
        }

        // 压缩图片


        if ($PhotoQuality == 100 || $skipCompression == 1) {
            // imagepng($image, $targetPath, 0);
            imagewebp($image, $targetPath, 100);
        } else {
            imagejpeg($image, $targetPath, $PhotoQuality); // JPEG 压缩
        }

        // 销毁图片资源
        imagedestroy($image);

        return true;
    }


    class FileHandler
    {
        // 生成唯一文件名
        public static function generateUniqueFileName($extension)
        {
            return date('YmdHis') . '_' . uniqid() . $extension;
        }

        // 压缩图片并添加水印
        public static function compressAndWatermarkImage($sourcePath, $destinationPath, $skipCompression = 0, $skipWatermark = 0)
        {
            compressImage($sourcePath, $destinationPath, $skipCompression, $skipWatermark);
        }

        public static function getImageExtension($fileTmpName, $fileType = false)
        {
            if ($fileType) {
                $imageInfo = getimagesize($fileTmpName);
                return image_type_to_extension($imageInfo[2]);
            } else {
                $extension = pathinfo($fileTmpName, PATHINFO_EXTENSION);
                return $extension;
            }
        }
    }

    // 本地上传
    class LocalUploader
    {
        private $file;
        private $folderName;
        private $currentUrl;

        public function __construct($file, $folderName, $currentUrl)
        {
            $this->file = $file;
            $this->folderName = $folderName;
            $this->currentUrl = $currentUrl;
        }

        public function upload()
        {
            if ($this->file <> '') {
                return $this->MediaFiles();
            }

            return '当前上传列表为空 请选择图片或视频文件！';
        }

        private function MediaFiles()
        {
            $destination = $this->folderName . $this->file['name'];
            if (rename($this->file['tmp_name'], $destination)) {
                return $this->currentUrl . "/" . str_replace('../', '', $this->folderName) . $this->file['name'];
            } else {
                return '媒体文件移动失败！';
            }
        }
    }

    // 云存储
    class CloudStorageUploader
    {
        private $storageProvider;
        private $file;
        private $Lovefolder;
        private $CloudStorageConfig;

        public function __construct($file, $storageProvider, $Lovefolder, $CloudStorageConfig)
        {
            $this->file = $file;
            $this->storageProvider = $storageProvider;
            $this->Lovefolder = $Lovefolder;
            $this->CloudStorageConfig = $CloudStorageConfig;
        }

        public function upload()
        {

            switch ($this->CloudStorageConfig['id']) {
                case 1:
                    return $this->uploadToMinio();
                case 2:
                    return $this->uploadToTencent();
                case 3:
                    return $this->uploadToAliyun();
                case 4:
                    return $this->uploadToQiniu();
                case 5:
                    return $this->uploadToUpyun();
                default:
                    return [
                        'file' => basename($this->file['name']),
                        'info' => '未知的存储服务商',
                        'status' => 'error'
                    ];
            }
        }

        private function uploadToMinio()
        {
            $accessKey = trim($this->CloudStorageConfig['AccessKey']);
            $secretKey = trim($this->CloudStorageConfig['SecretKey']);
            $bucket = trim($this->CloudStorageConfig['BucketName']);
            $endpoint = trim($this->CloudStorageConfig['Endpoint']);
            $region = trim($this->CloudStorageConfig['Region']);
            $cdnDomain = trim($this->CloudStorageConfig['customCDN']);

            $s3Client = new S3Client([
                'version' => 'latest',
                'region' => $region,
                'credentials' => [
                    'key' => $accessKey,
                    'secret' => $secretKey,
                ],
                'endpoint' => $endpoint,
                'use_path_style_endpoint' => true,
            ]);

            try {
                $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key' => $this->Lovefolder,
                    'ContentType' => $this->file['type'],
                ]);
            } catch (AwsException $e) {
                error_log('AWS S3-创建文件夹失败: ' . $e->getAwsErrorMessage(), 3, 'upload_errors.log');
            }

            try {
                $result = $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key' => $this->Lovefolder . basename($this->file['name']),
                    'SourceFile' => $this->file['tmp_name'],
                    'ContentType' => $this->file['type'],
                ]);

                $fileUrl = $cdnDomain . '/' . $this->Lovefolder . basename($this->file['name']);


                return [
                    'file' => basename($this->file['name']),
                    'url' => $cdnDomain ? $fileUrl : $result['ObjectURL'],
                    'status' => 'success'
                ];
            } catch (AwsException $e) {
                return [
                    'file' => basename($this->file['name']),
                    'error' => '上传失败: ' . $e->getAwsErrorMessage(),
                    'status' => 'error'
                ];
            }
        }

        private function uploadToTencent()
        {
            $accessKey = trim($this->CloudStorageConfig['AccessKey']);
            $secretKey = trim($this->CloudStorageConfig['SecretKey']);
            $bucket = trim($this->CloudStorageConfig['BucketName']);
            $region = trim($this->CloudStorageConfig['Region']);
            $cdnDomain = trim($this->CloudStorageConfig['customCDN']);
            
            if (!$cdnDomain) {
                $cdnDomain = 'https://' . $bucket . '.cos.' . $region . '.myqcloud.com';
            }

            if ($this->CloudStorageConfig['Endpoint']) {
                $endpoint = trim($this->CloudStorageConfig['Endpoint']);
            } else {
                $endpoint = 'https://cos.' . $region . '.myqcloud.com';
            }


            $s3Client = new S3Client([
                'version' => 'latest',
                'region' => $region,
                'credentials' => [
                    'key' => $accessKey,
                    'secret' => $secretKey,
                ],
                'endpoint' => $endpoint,
                'signature_version' => 'v4',
            ]);

            try {
                $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key' => $this->Lovefolder,
                    'ContentType' => $this->file['type'],
                ]);
            } catch (AwsException $e) {
                error_log('COS-创建文件夹失败: ' . $e->getAwsErrorMessage(), 3, 'upload_errors.log');
            }

            try {
                $result = $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key' => $this->Lovefolder . basename($this->file['name']),
                    'SourceFile' => $this->file['tmp_name'],
                    'ContentType' => $this->file['type'],
                    'ACL' => 'public-read',
                ]);


                $fileUrl = $cdnDomain . '/' . $this->Lovefolder . basename($this->file['name']);


                return [
                    'file' => basename($this->file['name']),
                    'url' => $fileUrl,
                    'status' => 'success'
                ];
            } catch (\Exception $e) {
                return [
                    'file' => basename($this->file['name']),
                    'error' => '上传失败: ' . $e->getMessage(),
                    'status' => 'error'
                ];
            }
        }

        private function uploadToAliyun()
        {
            $accessKey = trim($this->CloudStorageConfig['AccessKey']);
            $secretKey = trim($this->CloudStorageConfig['SecretKey']);
            $bucket = trim($this->CloudStorageConfig['BucketName']);
            $endpoint = trim($this->CloudStorageConfig['Endpoint']);
            $cdnDomain = trim($this->CloudStorageConfig['customCDN']);
            if (!$cdnDomain) {
                $cdnDomain = 'https://' . $bucket . '.' . $endpoint;
            }

            try {
                $ossClient = new OssClient($accessKey, $secretKey, $endpoint);

                try {
                    $ossClient->putObject($bucket, $this->Lovefolder, '');
                } catch (OssException $e) {
                    error_log('OSS-创建文件夹失败: ' . $e->getMessage(), 3, 'upload_errors.log');
                }

                $result = $ossClient->uploadFile($bucket, $this->Lovefolder . basename($this->file['name']), $this->file['tmp_name']);

                $fileUrl = $cdnDomain . '/' . $this->Lovefolder . basename($this->file['name']);


                return [
                    'file' => basename($this->file['name']),
                    'url' => $fileUrl,
                    'status' => 'success'
                ];
            } catch (OssException $e) {
                return [
                    'file' => basename($this->file['name']),
                    'error' => '上传失败: ' . $e->getMessage(),
                    'status' => 'error'
                ];
            }
        }

        private function uploadToQiniu()
        {
            $accessKey = trim($this->CloudStorageConfig['AccessKey']);
            $secretKey = trim($this->CloudStorageConfig['SecretKey']);
            $bucket = trim($this->CloudStorageConfig['BucketName']);
            $domain = trim($this->CloudStorageConfig['Endpoint']);
            $cdnDomain = trim($this->CloudStorageConfig['customCDN']);
            if (!$cdnDomain) {
                $cdnDomain = $domain;
            }

            try {
                $auth = new QiniuAuth($accessKey, $secretKey);
                $token = $auth->uploadToken($bucket);
                $uploadMgr = new UploadManager();

                list($ret, $err) = $uploadMgr->putFile($token, $this->Lovefolder . basename($this->file['name']), $this->file['tmp_name']);

                if ($err !== null) {
                    throw new Exception($err->message());
                }

                $fileUrl = $cdnDomain . '/' . $ret['key'];

                return [
                    'file' => basename($this->file['name']),
                    'url' => $fileUrl,
                    'status' => 'success'
                ];
            } catch (\Exception $e) {
                return [
                    'file' => basename($this->file['name']),
                    'error' => '上传失败: ' . $e->getMessage(),
                    'status' => 'error'
                ];
            }
        }

        private function uploadToUpyun()
        {
            $accessKey = trim($this->CloudStorageConfig['AccessKey']);
            $secretKey = trim($this->CloudStorageConfig['SecretKey']);
            $bucket = trim($this->CloudStorageConfig['BucketName']);
            $endpoint = trim($this->CloudStorageConfig['Endpoint']);
            $region = trim($this->CloudStorageConfig['Region']);
            $customSite = trim($this->CloudStorageConfig['customCDN']);

            if (!$customSite) {
                $customSite = $endpoint;
            }
        
            $s3Client = new S3Client([
                'version' => 'latest',
                'region' => $region,
                'credentials' => [
                    'key' => $accessKey,
                    'secret' => $secretKey,
                ],
                'endpoint' => 'https://s3.api.upyun.com',
                'use_path_style_endpoint' => true,
                'http' => [
                    'timeout' => 120,
                    'connect_timeout' => 60,
                ],
            ]);

            try {
                $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key' => $this->Lovefolder,
                    'ContentType' => $this->file['type'],
                ]);
            } catch (AwsException $e) {
                error_log('Upyun-创建文件夹失败: ' . $e->getAwsErrorMessage(), 3, 'upload_errors.log');
            }

            try {
                $result = $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key' => $this->Lovefolder . basename($this->file['name']),
                    'SourceFile' => $this->file['tmp_name'],
                    'ContentType' => $this->file['type'],
                ]);

                $fileUrl = $customSite . '/' . $this->Lovefolder . basename($this->file['name']);


                return [
                    'file' => basename($this->file['name']),
                    'url' => $fileUrl,
                    'status' => 'success'
                ];
            } catch (AwsException $e) {
                return [
                    'file' => basename($this->file['name']),
                    'error' => '上传失败: ' . $e->getAwsErrorMessage(),
                    'status' => 'error'
                ];
            }
        }
    }


    $isMediaType = isset($_POST['isMediaType']);
    $skipCompression = (int)($_POST['skipCompression'] ?? 0) === 1 ? 1 : 0;
    $skipWatermark = (int)($_POST['skipWatermark'] ?? 0) === 1 ? 1 : 0;

    $files = $_FILES['image'] ?? ['name' => []];

    $urls = array();


    foreach ($files['name'] as $key => $name) {

        try {
            if ($files['error'][$key] !== UPLOAD_ERR_OK || !is_uploaded_file($files['tmp_name'][$key])) {
                $response['data']['info'] = '媒体文件上传错误 请重试！';
                break;
            }

            $file = [
                'name' => $files['name'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'type' => $files['type'][$key],
                'size' => $files['size'][$key],
                'error' => $files['error'][$key]
            ];

            if ($file['size'] <= 0 || $file['size'] > 50 * 1024 * 1024) {
                throw new RuntimeException('单个媒体文件必须小于 50MB。');
            }
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $detectedMime = (string)$finfo->file($file['tmp_name']);
            $allowedMedia = [
                'image/jpeg' => '.jpg', 'image/png' => '.png',
                'image/webp' => '.webp', 'image/gif' => '.gif',
                'video/mp4' => '.mp4', 'video/webm' => '.webm',
                'video/quicktime' => '.mov',
            ];
            if (!isset($allowedMedia[$detectedMime])) {
                throw new RuntimeException('不支持的媒体格式。');
            }
            $file['type'] = $detectedMime;

            $fileType = $file['type'];
            if (strpos($fileType, 'image/') === 0) {
                $extension = FileHandler::getImageExtension($file['tmp_name'], 1);
                $newFileName = FileHandler::generateUniqueFileName($extension);
                $file['name'] = $newFileName;
                // 使用 FileHandler 进行图片压缩和水印处理
                FileHandler::compressAndWatermarkImage($file['tmp_name'], $file['tmp_name'], $skipCompression, $skipWatermark);
            } elseif (strpos($fileType, 'video/') === 0) {
                $newFileName = FileHandler::generateUniqueFileName($allowedMedia[$detectedMime]);
                $file['name'] = $newFileName;
            }


            // 如果是本地上传
            if (!$MediaUploadMethod) {
                $Lovefolder = '../' . $ImageSet_data['UploadDirectory'];
                if (!is_dir($Lovefolder)) {
                    if (!mkdir($Lovefolder, 0777, true)) {
                        $response['data']['info'] = '上传错误：无法创建本地上传目录！';
                        break;
                    }
                }
                $uploader = new LocalUploader($file, $Lovefolder, $GetcurrentUrl);
                $urls[] = $uploader->upload();
            } else {

                $CloudStorageConfig_sql = "select * from CloudStorageConfig where Current = 1";
                $CloudStorageConfig_res = mysqli_query($connect, $CloudStorageConfig_sql);
                $CloudStorageConfig_data = mysqli_fetch_array($CloudStorageConfig_res);

                $uploader = new CloudStorageUploader($file, $CloudStorageProvider, $Lovefolder, $CloudStorageConfig_data);

                $uploadResult = $uploader->upload();
                $urls[] = $uploadResult['url'];
                $response['data']['info'] = $uploadResult['error'];
            }
        } catch (Exception $e) {
            $response['data']['info'] = '发生错误: ' . $e->getMessage();
            error_log($e->getMessage() . "\n" . $e->getTraceAsString(), 3, 'upload_errors.log');
            break;
        }
    }


    if ($urls) {
        $response = array(
            'success' => true,
            'message' => '请求成功',
            'data' => array(
                'info' => '上传成功 文件已上传至相应策略',
                'src' => implode("\n", $urls),
                'Coversrc' => null,
            )
        );
        if ($isMediaType) {
            $response['data']['Coversrc'] = $urls;
        }
    } else {
        $response['success'] = false;
        $response['data']['info'] = '错误 未选择媒体文件上传！';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $AccessAddress = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
    include_once '../404.php';
}
