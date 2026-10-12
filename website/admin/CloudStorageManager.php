<?php
session_start();

include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';

if (!userHasPermission($_SESSION['loginadmin'], $conn)) {
    die("<script>alert('参数错误 请先登陆');location.href = 'login.php'</script>");
}

require '../vendor/autoload.php';



use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use OSS\OssClient;
use OSS\Core\OssException;
use Qiniu\Auth as QiniuAuth;
use Qiniu\Storage\BucketManager;

class CloudStorageManager
{
    private $client;
    private $OssClient;
    private $qiniuAuth;
    private $qiniuBucketManager;

    public function __construct($config)
    {
        switch ($config['id']) {
            case 1: // AWS S3
                $this->client = new S3Client([
                    'version' => 'latest',
                    'region' => $config['Region'],
                    'credentials' => [
                        'key' => $config['AccessKey'],
                        'secret' => $config['SecretKey'],
                    ],
                    'endpoint' => $config['Endpoint'],
                    'use_path_style_endpoint' => true,
                ]);
                break;
            case 2: // Tencent Cloud COS
                $this->client = new S3Client([
                    'version' => 'latest',
                    'region' => trim($config['Region']),
                    'credentials' => [
                        'key' => trim($config['AccessKey']),
                        'secret' => trim($config['SecretKey']),
                    ],
                    'endpoint' => $config['Endpoint'] ? trim($config['Endpoint']) : 'https://cos.' . trim($config['Region']) . '.myqcloud.com',
                    'signature_version' => 'v4',
                ]);
                break;
            case 3: // Aliyun OSS
                $this->OssClient = new OssClient(
                    $config['AccessKey'],
                    $config['SecretKey'],
                    $config['Endpoint']
                );
                break;
            case 4: // Qiniu
                $this->qiniuAuth = new QiniuAuth($config['AccessKey'], $config['SecretKey']);
                $this->qiniuBucketManager = new BucketManager($this->qiniuAuth);
                break;
            case 5: // Upyun
                $this->client = new S3Client([
                    'version' => 'latest',
                    'region' => $config['Region'],
                    'credentials' => [
                        'key' => $config['AccessKey'],
                        'secret' => $config['SecretKey'],
                    ],
                    'endpoint' => 'https://s3.api.upyun.com/',
                    'use_path_style_endpoint' => true,
                ]);
                break;
            default:
                throw new Exception('Unknown storage provider');
        }
    }

    /**
     * 列出云存储对象
     *
     * 根据不同的云存储平台（AWS S3、腾讯云 COS、又拍云 USS）进行文件上传、下载或其他操作。
     *
     * @param int $CloudStorageId 云存储平台的标识符：
     *        - 1: AWS S3
     *        - 2: 腾讯云 COS
     *        - 5: 又拍云 USS
     */
    public function listObjects($bucketName, $Endpoint = '', $prefix = '', $Region = '', $CloudStorageId, $customCDN = '')
    {
        try {
            $options = [
                'Bucket' => $bucketName,
                'Prefix' => $prefix,
                'MaxKeys' => 100,
            ];

            $result = $this->client->listObjects($options);

            if (!empty($customCDN)) {
                // 使用提供的自定义CDN域名
                $baseUrl = $customCDN . '/';
            } else {
                // 如果没有自定义域名 根据不同的云存储提供商选择正确的 URL 拼接方式
                if ($CloudStorageId == 1) {
                    // AWS S3
                    $baseUrl = $Endpoint . '/' . $bucketName . '/';
                } else if ($CloudStorageId == 2 || $CloudStorageId == 5) {
                    // 腾讯云 COS & 又拍云 USS
                    if ($Endpoint) {
                        $Endpoint = trim($Endpoint) . '/';
                        $baseUrl = $Endpoint;
                    } else {
                        $baseUrl = 'https://' . $bucketName . '.cos.' . $Region . '.myqcloud.com/';
                    }
                }
            }

            if (isset($result['Contents']) && is_array($result['Contents'])) {
                $folders = [];
                $files = [];

                foreach ($result['Contents'] as &$object) {
                    if (isset($object['Key'])) {
                        if (substr($object['Key'], -1) === '/') {
                            $folders[] = $object;
                        } else {
                            $object['Purl'] = $baseUrl . $object['Key'];
                            $files[] = $object;
                        }
                    }
                }

                $files = array_reverse($files);

                $result['Contents'] = array_merge($folders, $files);
            } else {
                return ['error' => '存储桶中未找到任何对象'];
            }

            return $result['Contents'];
        } catch (AwsException | OssException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // 七牛云列表获取
    public function listBucketFiles($Endpoint, $bucketName, $prefix = '', $limit = 10, $marker = null, $customCDN = '')
    {
        if (!$this->qiniuBucketManager) {
            throw new Exception('Qiniu BucketManager is not initialized.');
        }

        try {
            list($fileList, $marker, $error) = $this->qiniuBucketManager->listFiles($bucketName, $prefix, $marker, $limit);

            if ($error !== null) {
                return ['error' => $error->message()];
            }

            if (!empty($customCDN)) {
                $baseUrl = $customCDN . '/';
            } else {
                $baseUrl = rtrim($Endpoint, '/') . '/';
            }

            if (is_array($fileList) && isset($fileList['items'])) {
                $items = $fileList['items'];

                $items = array_reverse($items);

                foreach ($items as &$file) {
                    if (isset($file['key'])) {
                        $file['Purl'] = $baseUrl . $file['key'];
                    } else {
                        error_log('文件没有 key: ' . json_encode($file), 3, 'admin/upload_errors.log');
                    }
                }
            } else {
                error_log('fileList 不是数组或没有 items: ' . json_encode($fileList), 3, 'admin/upload_errors.log');
            }

            return $items;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // 列出阿里云的对象
    public function listOssObjects($bucketName, $folderPath, $Endpoint, $customCDN = '')
    {
        try {
            if (substr($folderPath, -1) !== '/') {
                $folderPath .= '/';
            }

            $options = [
                'prefix' => $folderPath,
                'MaxKeys' => 100,
                'delimiter' => '/',
            ];

            $result = $this->OssClient->listObjects($bucketName, $options);
            $files = [];

            if ($result->getObjectList()) {
                foreach ($result->getObjectList() as $object) {

                    if (!empty($customCDN)) {
                        $baseUrl = $customCDN . '/';
                    } else {
                        $baseUrl = 'https://' . $bucketName . '.' . $Endpoint . '/';
                    }

                    // 如果是文件夹，保存到 $folders 数组
                    if (substr($object->getKey(), -1) === '/') {
                        $folders[] = [
                            'Key' => $object->getKey(),
                            'Size' => 0,  // 文件夹没有实际的大小，设置为0
                            'StorageClass' => 'Folder',
                            'ETag' => '',
                            'LastModified' => null, // 文件夹没有最后修改时间
                            'Purl' => $baseUrl . $object->getKey(),
                        ];
                    } else {
                        // 否则是文件，保存到 $files 数组
                        $files[] = [
                            'Key' => $object->getKey(),
                            'Size' => $object->getSize(),
                            'StorageClass' => $object->getStorageClass(),
                            'ETag' => $object->getETag(),
                            'LastModified' => $object->getLastModified(),
                            'Purl' => $baseUrl . $object->getKey(),
                        ];
                    }
                }
            }
            $files = array_reverse($files);
            $result = array_merge($folders, $files);
            return $result;
        } catch (OssException $e) {
            error_log('OSS Exception: ' . $e->getMessage(), 3, 'admin/upload_errors.log');
            return ['error' => $e->getMessage()];
        } catch (Exception $e) {
            error_log('General Exception: ' . $e->getMessage(), 3, 'admin/upload_errors.log');
            return ['error' => $e->getMessage()];
        }
    }


    // 云存储删除逻辑
    public function deleteObject($bucketName, $key, $CloudStorageId)
    {
        try {
            switch ($CloudStorageId) {
                case 1:
                case 2:
                case 5:
                    $this->client->deleteObject([
                        'Bucket' => $bucketName,
                        'Key' => $key,
                    ]);
                    break;

                case 3:
                    $this->OssClient->deleteObject($bucketName, $key);
                    break;

                case 4:
                    $this->qiniuBucketManager->delete($bucketName, $key);
                    break;

                default:
                    throw new Exception('Unknown storage provider');
            }
            return ['success' => true];
        } catch (AwsException | OssException | Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // 云存储批量删除逻辑
    public function BatchDeleteObjects($bucketName, $keys, $CloudStorageId)
    {

        try {
            switch ($CloudStorageId) {
                case 1: // AWS S3
                case 2: // 腾讯云 COS
                case 5: // 又拍云 USS
                    $objects = [];
                    foreach ($keys as $key) {
                        $objects[] = ['Key' => $key];
                    }
                    // AWS S3 和 腾讯云 COS 支持批量删除对象
                    $this->client->deleteObjects([
                        'Bucket' => $bucketName,
                        'Delete' => [
                            'Objects' => $objects,
                            'Quiet' => false,
                        ]
                    ]);
                    break;

                case 3: // 阿里云 OSS
                    $this->OssClient->deleteObjects($bucketName, $keys);
                    break;

                case 4: // 七牛云
                    foreach ($keys as $key) {
                        $this->qiniuBucketManager->delete($bucketName, $key);
                    }
                    break;

                default:
                    throw new Exception('Unknown storage provider');
            }
            return ['success' => true];
        } catch (AwsException | OssException | Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}


$CloudStorageConfig_sql = "select * from CloudStorageConfig where Current = 1";
$CloudStorageConfig_res = mysqli_query($connect, $CloudStorageConfig_sql);
$CloudStorageConfig_data = mysqli_fetch_array($CloudStorageConfig_res);

$Lovefolder = $ImageSet_data['UploadDirectory'];
$bucketName = $CloudStorageConfig_data['BucketName'];
$Provider = $CloudStorageConfig_data['Provider'];


$Region = $CloudStorageConfig_data['Region'];
$Endpoint = $CloudStorageConfig_data['Endpoint'];
$CurrentId = $CloudStorageConfig_data['id'];
$customCDN = $CloudStorageConfig_data['customCDN'];

$isBatchDelete = trim($_POST['isBatchDelete']) ?? false;
$isDeteleFile = trim($_POST['isDeteleFile']) ?? false;
$CloudFileKey = trim($_POST['key']) ?? false;
$CloudFileKeys = isset($_POST['keys']) ? json_decode($_POST['keys'], true) : false;

try {

    $cloudStorageManager = new CloudStorageManager($CloudStorageConfig_data);
    $listResponse = [];

    if ($isDeteleFile) {
        if ($isBatchDelete) {
            $deleteResponse = $cloudStorageManager->BatchDeleteObjects($bucketName, $CloudFileKeys, $CurrentId);
        } else {
            $deleteResponse = $cloudStorageManager->deleteObject($bucketName, $CloudFileKey, $CurrentId);
        }
        echo json_encode($deleteResponse);
    } else {
        if ($CurrentId == 3) {
            // 阿里云
            $listResponse = $cloudStorageManager->listOssObjects($bucketName, $Lovefolder, $Endpoint, $customCDN);
        } else if ($CurrentId == 4) {
            //七牛云
            $listResponse = $cloudStorageManager->listBucketFiles($Endpoint, $bucketName, $Lovefolder, 100, '', $customCDN);
        } else {
            // AWS S3 & 腾讯云 & 又拍云
            $listResponse = $cloudStorageManager->listObjects($bucketName, $Endpoint, $Lovefolder, $Region, $CurrentId, $customCDN);
        }
    }


    // echo json_encode($listResponse);
    header('Content-Type: application/json');
} catch (Exception $e) {
    // echo 'Error: ' . $e->getMessage();
}
