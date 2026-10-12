<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/admin/bootstrap.php';
lg_start_session();
header('Access-Control-Allow-Methods: POST');

function checkLock()
{
    if (file_exists(__DIR__ . '/install.lock')) {
        include_once "AlreadyInstalled.php";
        exit;
    }
}

checkLock();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    @$SQL_File = dirname(__DIR__) . "/install/LGNewUiDBsql.sql";
    @$Config_DBPath = dirname(__DIR__) . "/admin/Config_DB.php";
    @$install_lock_file = dirname(__DIR__) . '/install/install.lock';
    @$DBlocalhost = trim($_POST['localhost']);
    @$DBuserName = trim($_POST['userName']);
    @$DBpassord = trim($_POST['password']);
    @$Database = trim($_POST['DBName']);
    @$SecureCode = trim($_POST['SecureCode']);
    @$POStType = trim($_POST['Type']);
    @$SqlUpdate = trim($_POST['SqlUpdate']);


    if ($POStType == 'install') {
        $response = [];
        if (!preg_match('/^[A-Za-z0-9_]{1,64}$/', $Database)) {
            lg_json_response([['status' => 0, 'info' => '数据库名只能包含字母、数字和下划线。']]);
        }
        if (strlen($DBlocalhost) > 255 || strlen($DBuserName) > 128 || strlen($DBpassord) > 512 || strlen($SecureCode) < 8) {
            lg_json_response([['status' => 0, 'info' => '配置格式不正确；安全码至少需要 8 位，例如 love2026。']]);
        }
        if (!empty($DBlocalhost) && !empty($DBuserName) && !empty($DBpassord) && !empty($Database) && !empty($SecureCode)) {
            $checkMySQLVersion = checkMySQLVersion($DBlocalhost, $DBuserName, $DBpassord, $Database, $SecureCode);
            $response[] = $checkMySQLVersion;
            if ($SqlUpdate) {
                return;
            }
            if ($checkMySQLVersion['status'] === 1) {
                $importDatabase = importDatabase($DBlocalhost, $DBuserName, $DBpassord, $Database, $SQL_File);
                $response[] = $importDatabase;

                if ($importDatabase['status'] === 1) {
                    $updateConfigFile = updateConfigFile($Config_DBPath, $DBlocalhost, $DBuserName, $DBpassord, $Database, $SecureCode);
                    $response[] = $updateConfigFile;
                }
            }
        } else {
            $response[] = [
                'status' => 0,
                'info' => '数据库配置信息填写错误，请重试！'
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    } elseif ($POStType == 'SetUpdates') {
        if (@$_SESSION['dbConnectionInfo'] <> '') {
            $conn = new mysqli($_SESSION['dbConnectionInfo']['localhost'], $_SESSION['dbConnectionInfo']['userName'], $_SESSION['dbConnectionInfo']['password'], $_SESSION['dbConnectionInfo']['database']);
            $conn->set_charset("utf8mb4");

            if ($SqlUpdate == 1) {
                $upLoveInfoResult = versionUpdate($conn);
            } else {
                $UpDataArr = [
                    'title' => trim($_POST['title']),
                    'writing' => trim($_POST['writing']),
                    'logo' => trim($_POST['logo']),
                    'boy' => trim($_POST['boy']),
                    'boyimg' => trim($_POST['boyimg']),
                    'girl' => trim($_POST['girl']),
                    'girlimg' => trim($_POST['girlimg']),
                    'startTime' => trim($_POST['startTime']),
                ];
                $upLoveInfoResult = UpLoveInfo($conn, $UpDataArr);
            }

            if ($upLoveInfoResult['status'] === 1) {
                $createInstallLock = createInstallLock($install_lock_file);
                $responseTest[] = $createInstallLock;

                if ($SqlUpdate == 1) {
                    $updateConfigFile = updateConfigFile($Config_DBPath, $_SESSION['dbConnectionInfo']['localhost'], $_SESSION['dbConnectionInfo']['userName'], $_SESSION['dbConnectionInfo']['password'], $_SESSION['dbConnectionInfo']['database'], $_SESSION['dbConnectionInfo']['SCode']);
                    $responseTest[] = $updateConfigFile;
                }

                session_destroy();
            }

            $responseTest[] = $upLoveInfoResult;
        } else {
            $responseTest[] = [
                'status' => 0,
                'info' => '数据库临时账号密码过期 请重新安装！'
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($responseTest);
    } elseif ($POStType == 'skipUpdates') {
        $createInstallLock = createInstallLock($install_lock_file);
        $responseTest[] = $createInstallLock;
        header('Content-Type: application/json');
        echo json_encode($responseTest);
    } else {
        if (!empty($DBlocalhost) && !empty($DBuserName) && !empty($DBpassord)) {
            $checkMySQLVersion = checkMySQLVersion($DBlocalhost, $DBuserName, $DBpassord, $Database, $SecureCode);
            header('Content-Type: application/json');
            echo json_encode($checkMySQLVersion);
        } else {
            header('Content-Type: application/json');
            $response = array('status' => 0, 'info' => '错误：请先填写数据库配置信息！');
            echo json_encode($response);
            exit;
        }
    }
} else {
    header('Content-Type: application/json');
    $response = array('status' => 0, 'info' => '错误：请求不合法 请通过POST提交！');
    echo json_encode($response);
}


function UpLoveInfo($conn, $UpData)
{
    $UpSql = "update text set title = ?,writing = ?,logo = ?,boy = ?,girl = ?,boyimg = ?,girlimg = ?,startTime = ?,if_aiv = 0";
    $stmt = $conn->prepare($UpSql);
    $stmt->bind_param("ssssssss", $UpData['title'], $UpData['writing'], $UpData['logo'], $UpData['boy'], $UpData['girl'], $UpData['boyimg'], $UpData['girlimg'], $UpData['startTime']);
    $result = $stmt->execute();
    if ($result) {
        return array('status' => 1, 'info' => '小站信息已修改成功 请前往后台更换默认账号密码！');
    } else {
        return array('status' => 0, 'info' => "小站信息修改失败：" . $stmt->error);
    }
}

function versionUpdate($conn)
{
    // 秋季限定 => 冬至限定
    $sqlUpdates = [
        "CREATE TABLE `wx_app_config` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `audit_mode` TINYINT DEFAULT 0,
            `template_id` INT(11) DEFAULT NULL,
            `custom_font_url` VARCHAR(255) DEFAULT NULL,
            `swiperHeight` INT(11) NOT NULL,
            `sample_title` VARCHAR(255) NOT NULL,
            `test_cover` VARCHAR(255) NOT NULL,
            `sample_description` VARCHAR(255) NOT NULL,
            `share_title` VARCHAR(255) NOT NULL,
            `share_cover` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
    
        "INSERT INTO `wx_app_config` (`audit_mode`, `template_id`, `custom_font_url`, `swiperHeight`, `sample_title`, `test_cover`, `sample_description`, `share_title`, `share_cover`) VALUES
        (1, 3, NULL, 700, '正在读这篇文章的你是正在经历着青春岁月？', '/Style/img/AdminLogo.webp', '在时光的长河中，青春如同一颗璀璨而又转瞬即逝的流星，划过我们生命的夜空。它是一个短暂的美梦，当我们从梦中醒来，却发现它早已消失得无影无踪。那么，正在读这篇文章的你，是正在经历着如梦似幻的青春岁月，还是那一段段青春往事早已沉淀为心中难以言说的遗憾呢？', 'LGNewUi', '/Style/img/AdminLogo.webp');",
    
        "ALTER TABLE `CloudStorageConfig`
        ADD COLUMN `customCDN` VARCHAR(255) NOT NULL AFTER `Endpoint`;"
    ];
    
    mysqli_select_db($conn, $_SESSION['dbConnectionInfo']['database']);

    $SqlSuccess = 1;
    foreach ($sqlUpdates as $sql) {

        if ($conn->query($sql) === TRUE) {
            $result[] = "<i>SQL 执行成功: </i>" . $sql;
        } else {
            $SqlSuccess = 0;
            $result[] = "<i class='err'>SQL 执行失败: </i>" . $conn->error;
        }
    }

    return array('status' => $SqlSuccess, 'info' => $SqlSuccess ? '所有SQL语句执行成功！' : 'SQL语句存在执行错误 请重试 <br/>本次更新未完成！', 'data' => $result);
}


function checkMySQLVersion($localhost, $DB_User, $DB_Pw, $Database, $SCode)
{
    try {
        $dsn = "mysql:host=$localhost;charset=utf8mb4";
        $pdo = new PDO($dsn, $DB_User, $DB_Pw);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->query("SELECT VERSION() AS version");
        $mysql_version = $stmt->fetch(PDO::FETCH_ASSOC)['version'];

        if (version_compare($mysql_version, '5.7', '>=')) {
            $dbConnectionInfo = [
                'localhost' => $localhost,
                'userName' => $DB_User,
                'password' => $DB_Pw,
                'database' => $Database,
                'SCode' => $SCode,
            ];
            $_SESSION['dbConnectionInfo'] = $dbConnectionInfo;
            return array('status' => 1, 'info' => '数据库连接成功！<br/>MySQL版本符合要求，MySQL：' . $mysql_version);
        } else {
            return array('status' => 0, 'info' => 'MySQL 版本不符合要求，请使用 MySQL 5.7 或更高版本。当前版本：' . $mysql_version);
        }
    } catch (PDOException $e) {
        return array('status' => 0, 'info' => '数据库连接失败 - ' . $e->getMessage());
    }
}


function importDatabase($hostname, $username, $password, $database, $sql_file)
{
    try {
        if (!preg_match('/^[A-Za-z0-9_]{1,64}$/', $database)) {
            throw new InvalidArgumentException('数据库名格式不正确。');
        }
        $pdo = new PDO("mysql:host=$hostname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :database");
        $stmt->bindParam(':database', $database);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $stmt = $pdo->prepare("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $stmt->execute();
        }

        $pdo->exec("USE `{$database}`");

        if (file_exists($sql_file)) {
            $sql_content = file_get_contents($sql_file);
            $pdo->exec($sql_content);
            $response = array('status' => 1, 'info' => 'SQL 文件导入成功。');
        } else {
            $response = array('status' => 0, 'info' => 'SQL 文件不存在。');
            // throw new Exception("SQL 文件不存在。");
        }
    } catch (PDOException $e) {
        $response = array('status' => 0, 'info' => '连接失败: ' . $e->getMessage());
    } catch (Exception $e) {
        $response = array('status' => 0, 'info' => '错误: ' . $e->getMessage());
    }
    return $response;
}



function updateConfigFile($configFilePath, $dbAddress, $dbUsername, $dbPassword, $dbName, $SCode)
{
    if (file_exists($configFilePath) && is_writable($configFilePath)) {
        $configContent = file_get_contents($configFilePath);
        if ($configContent === false) {
            return ['status' => 0, 'info' => '无法读取配置文件内容。'];
        }


        $patterns = [
            '/\$db_address\s*=\s*".*?";/',
            '/\$db_username\s*=\s*".*?";/',
            '/\$db_password\s*=\s*".*?";/',
            '/\$db_name\s*=\s*".*?";/',
            '/\$LGUI_Code\s*=\s*".*?";/'
        ];

        $replacements = [
            '$db_address = ' . var_export($dbAddress, true) . ';',
            '$db_username = ' . var_export($dbUsername, true) . ';',
            '$db_password = ' . var_export($dbPassword, true) . ';',
            '$db_name = ' . var_export($dbName, true) . ';',
            '$LGUI_Code = ' . var_export($SCode, true) . ';'
        ];

        $newContent = preg_replace($patterns, $replacements, $configContent);

        if (file_put_contents($configFilePath, $newContent) !== false) {
            return ['status' => 1, 'info' => '数据库信息配置已更新。'];
        } else {
            return ['status' => 0, 'info' => '数据库信息配置更新失败。'];
        }
    } else {
        return ['status' => 0, 'info' => '无法读取或写入配置文件。'];
    }
}

function createInstallLock($install_lock_file)
{
    if (file_put_contents($install_lock_file, 'LGNewUI_lock # Install Time #' . gmdate("Y-m-d H:i:s", time() + 8 * 3600)) !== false) {
        return [
            'status' => 1,
            'info' => '安装锁文件已创建。'
        ];
    } else {
        return [
            'status' => 0,
            'info' => '安装锁文件创建失败。'
        ];
    }
}
