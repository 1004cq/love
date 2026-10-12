<?php
/*
 *                        _oo0oo_
 *                       o8888888o
 *                       88" . "88
 *                       (| -_- |)
 *                       0\  =  /0
 *                     ___/`---'\___
 *                   .' \\|     |// '.
 *                  / \\|||  :  |||// \
 *                 / _||||| -:- |||||- \
 *                |   | \\\  - /// |   |
 *                | \_|  ''\---/''  |_/ |
 *                \  .-\__  '-'  ___/-. /
 *              ___'. .'  /--.--\  `. .'___
 *           ."" '<  `.___\_<|>_/___.' >' "".
 *          | | :  `- \`.;`\ _ /`;.`/ - ` : | |
 *          \  \ `_.   \_ __\ /__ _/   .-` /  /
 *      =====`-.____`.___ \_____/___.-`___.-'=====
 *                        `=---='
 * 
 * 
 *      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * 
 *            佛祖保佑     永不宕机     永无BUG
 * 
 *        佛曰:  
 *                写字楼里写字间，写字间里程序员；  
 *                程序人员写程序，又拿程序换酒钱。  
 *                酒醒只在网上坐，酒醉还来网下眠；  
 *                酒醉酒醒日复日，网上网下年复年。  
 *                但愿老死电脑间，不愿鞠躬老板前；  
 *                奔驰宝马贵者趣，公交自行程序员。  
 *                别人笑我忒疯癫，我笑自己命太贱；  
 *                不见满街漂亮妹，哪个归得程序员？

 * @Page: "Function.php"
 * @PageDescription："情侣小站全局功能公共函数"
 * @Author: Ki.
 * @Project: LG_NewUi
 * @LastEditTime: 2024-9-14 11:42:27
 * @Description: 小手一牵 岁岁年年
 * Copyright (c) 2022-2023 by Ki All Rights Reserved.
 */

function userHasPermission($username, $conn)
{
    // 验证会话是否存在并且不为空
    if (!isset($username) || $username === '') {
        return false;
    }
    $sqlUser = "SELECT user FROM login WHERE user = ?";
    $stmt_User = $conn->prepare($sqlUser);
    $stmt_User->bind_param("s", $username);
    $result = $stmt_User->execute();
    if (!$result) {
        echo "错误信息：" . $stmt_User->error;
        return false;
    }
    $stmt_User->store_result();
    $num_rows = $stmt_User->num_rows;
    $stmt_User->close();
    if ($num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

//截取URL为数组
function ExtractURL($UrlArr)
{
    $URlData = explode("\n", $UrlArr);
    $URLList = array();
    foreach ($URlData as $array_L) {
        $array_L = trim($array_L);
        array_push($URLList, $array_L);
    }
    return $URLList;
}

//截取URL为对象
function FormatCarouselDataWithUrlKey($carouselData)
{
    // 将以换行符分隔的字符串转换为数组
    $urlArray = explode("\n", $carouselData);
    $formattedUrls = [];

    // 遍历数组，创建一个包含 url 键的对象
    foreach ($urlArray as $url) {
        $url = trim($url); // 去除多余的空白字符
        if ($url) { // 确保 URL 非空
            // 将 URL 包装成 { url: 'URL' } 形式的对象
            $formattedUrls[] = ['url' => $url];
        }
    }

    return $formattedUrls;
}


//获取用户真实IP
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $_SERVER['REMOTE_ADDR'] = $list[0];
    $ip_if = $_SERVER['REMOTE_ADDR'];
} else {
    $ip_if = $_SERVER['REMOTE_ADDR'];
}

//IP归属地获取 A （直接输出模式）
function get_ips($ip)
{
    echo Get_City($ip);
}

//IP归属地获取 B （返回值模式）
function Get_City($ip)
{
    $API_URL = 'https://api.nsmao.net/api/ipip/query';
    $get_post_data = array(
        'ip' => $ip,
    );
    @$resdata = api::send($API_URL, $get_post_data, 'GET', false, '');
    @$CityArr = json_decode($resdata, true);
    if (@$CityArr['code'] == 200) {
        if ((@$CityArr['data']['province'] == @$CityArr['data']['city']) ||  @$CityArr['data']['city'] == '') {
            return @$CityArr['data']['country'] . " · " . @$CityArr['data']['province'];
        } else {
            return @$CityArr['data']['province'] . " · " . @$CityArr['data']['city'];
        }
    } else {
        return "未知";
    }
}

function Get_QQData($qq)
{
    $API_URL = 'https://api.nsmao.net/api/qq/query';
    $get_post_data = array(
        'qq' => $qq,
    );
    $qqInfoData = array(
    'Status' => false,
    'data' => null,
    );
    @$resdata = api::send($API_URL, $get_post_data, 'GET', false, '');
    @$ResArr = json_decode($resdata, true);
    if (@$ResArr['code'] == 200) {
        $qqInfoData = array(
        'Status' => true,
        'data' => $ResArr['data'],
        );
    }
    return $qqInfoData;
}


//留言时间
function time_tran($time)
{
    $text = '';
    if (!$time) {
        return $text;
    }
    $current = time();
    $t = $current - $time;
    $retArr = array('刚刚', '秒前', '分钟前', '小时前', '天前', '月前', '年前');
    switch ($t) {
        case $t < 0:
            $text = date('Y-m-d', $time);
            break;
        case $t == 0:
            $text = $retArr[0];
            break;
        case $t < 60:
            $text = $t . $retArr[1];
            break;
        case $t < 3600:
            $text = floor($t / 60) . $retArr[2];
            break;
        case $t < 86400:
            $text = floor($t / 3600) . $retArr[3];
            break;
        case $t < 2592000:
            $text = floor($t / 86400) . $retArr[4];
            break;
        case $t < 31536000:
            $text = floor($t / 2592000) . $retArr[5];
            break;
        default:
            $text = floor($t / 31536000) . $retArr[6];
    }
    return $text;
}

function isPastDate($dateStr)
{
    $inputTime = strtotime($dateStr);

    $currentTime = time();

    if ($inputTime < $currentTime) {
        return true;
    } else {
        return false;
    }
}

// 判断倒计时是否已过去
function add_one_year($dateStr)
{
    // 将传入的日期解析为时间戳
    $inputTime = strtotime($dateStr);

    // 获取当前日期的年份
    $currentYear = date('Y');

    // 获取传入日期的年份
    $inputYear = date('Y', $inputTime);

    // 获取当前日期的字符串表示
    $currentDateStr = date('Y-m-d');

    // 如果传入日期已经过去，则动态计算增加的年数
    if ($inputTime < strtotime($currentDateStr)) {
        // 计算传入日期与当前日期的差值
        $diffYears = date('Y', strtotime($currentDateStr)) - date('Y', $inputTime);
        $diffMonths = date('m', strtotime($currentDateStr)) - date('m', $inputTime);
        $diffDays = date('d', strtotime($currentDateStr)) - date('d', $inputTime);

        // 如果传入日期的月份和日期在当前日期之前，年份增加1
        if ($diffMonths > 0 || ($diffMonths == 0 && $diffDays >= 0)) {
            $diffYears++;
        }

        // 动态增加年数
        $newYear = $currentYear + $diffYears;

        // 根据动态增加的年数调整传入日期
        $newDateStr = date('Y-m-d', strtotime("+{$diffYears} year", $inputTime));
    } else {
        // 如果传入日期在当前日期之后，则保持原日期不变
        $newDateStr = $dateStr;
    }

    return $newDateStr;
}

//获取天数
function day_down($timestamp, $targetDate, $countdown)
{
    // 获取当前时间戳
    $currentTime = time();

    // 将传入的时间戳转换为时间戳
    $timestamp = strtotime($timestamp);

    // 如果需要倒计时
    if ($countdown) {
        // 使用 add_one_year 函数来动态调整传入的日期
        $targetDate = add_one_year($targetDate);
    }

    // 将目标日期转换为时间戳
    $targetTime = strtotime($targetDate);

    // 计算剩余天数
    $diffSeconds = $targetTime - $timestamp;
    $days = floor($diffSeconds / (60 * 60 * 24));
    $remainingDays = $days - floor(($targetTime - $currentTime) / (60 * 60 * 24));

    // 返回倒计时信息
    return array($days, $remainingDays);
}


//点点滴滴详情时间
function page_time($time)
{
    $text = '';
    if (!$time) {
        return $text;
    }
    if (!is_numeric($time)) {
        $time = strtotime($time);
    }
    $current = time();
    $t = $current - $time;
    $retArr = array('刚刚', '秒前', '分钟前', '小时前', '天前', '月前', '年前');
    switch ($t) {
        case $t < 0:
            $text = date('Y-m-d', $time);
            break;
        case $t == 0:
            $text = $retArr[0];
            break;
        case $t < 60:
            $text = $t . $retArr[1];
            break;
        case $t < 3600:
            $text = floor($t / 60) . $retArr[2];
            break;
        case $t < 86400:
            $text = floor($t / 3600) . $retArr[3];
            break;
        case $t < 2592000:
            $text = floor($t / 86400) . $retArr[4];
            break;
        case $t < 31536000:
            $text = date('Y/m/d', $time);
        default:
            $text = date('Y/m/d', $time);
    }
    return $text;
}

//文章强制时间排序
function sortArticlesByTime($articles)
{
    usort($articles, function ($a, $b) {
        $timeA = $a['articletime'];
        $timeB = $b['articletime'];
        return $timeB - $timeA;
    });

    return $articles;
}


//获取操作系统
function GetOs()
{
    $agent = $_SERVER['HTTP_USER_AGENT'];
    //由于大部分安卓用户无法获取到系统标识 
    //所以这里直接定义如果获取不到默认就是安卓系统...
    $os = 'Android';

    if (preg_match('/win/i', $agent)) {
        $os = 'Windows';
    } elseif (preg_match('/android/i', $agent)) {
        $os = 'Android';
    } elseif (preg_match('/iphone|ipad|ipod/i', $agent)) {
        $os = 'iOS';
    } elseif (preg_match('/mac/i', $agent)) {
        $os = 'Mac';
    } elseif (preg_match('/linux/i', $agent)) {
        $os = 'Linux';
    } elseif (preg_match('/unix/i', $agent)) {
        $os = 'Unix';
    } elseif (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent)) {
        $os = 'SunOS';
    } elseif (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent)) {
        $os = 'IBM OS/2';
    }

    return $os;
}

//获取设备浏览器
function GetBrowser()
{
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $br = $_SERVER['HTTP_USER_AGENT'];

        if (preg_match('/MSIE/i', $br)) {
            $br = 'MSIE';
        } elseif (preg_match('/Firefox/i', $br)) {
            $br = 'Firefox';
        } elseif (preg_match('/Chrome/i', $br) || preg_match('/CriOS/i', $br)) {
            $br = 'Chrome';
        } elseif (preg_match('/Safari/i', $br)) {
            $br = 'Safari';
        } elseif (preg_match('/Opera/i', $br)) {
            $br = 'Opera';
        } elseif (preg_match('/360SE/i', $br) || preg_match('/360EE/i', $br)) {
            $br = '360_Browser';
        } elseif (preg_match('/QQBrowser/i', $br)) {
            $br = 'QQ_Browser';
        } elseif (preg_match('/Quark/i', $br)) {
            $br = 'Quark';
        } else {
            $br = 'Chrome';
        }
        return $br;
    }
}

// 获取操作系统图标
function GetOsIcon($or)
{
    $iconCode = '';
    if (
        $or == 'Windows 10' || $or == 'Windows 8.1' || $or == 'Windows 8' ||
        $or == 'Windows 7' || $or == 'Windows' || $or == 'Windows XP' || $or == 'Windows 2000'
    ) {
        $iconCode = "<svg class='icon' aria-hidden='true'>
            <use xlink:href='#icon-windows'></use>
        </svg>";
    } elseif ($or == 'Android') {
        $iconCode = "<svg class='icon' aria-hidden='true'>
            <use xlink:href='#icon-anzhuo1'></use>
        </svg>";
    } elseif ($or == 'iOS') {
        $iconCode = "<svg class='icon' aria-hidden='true'>
            <use xlink:href='#icon-IOS'></use>
        </svg>";
    } elseif ($or == 'Mac') {
        $iconCode = "<svg class='icon' aria-hidden='true'>
            <use xlink:href='#icon-macos'></use>
        </svg>";
    } elseif ($or == 'Linux') {
        $iconCode = "<svg class='icon' aria-hidden='true'>
            <use xlink:href='#icon-linux'></use>
        </svg>";
    } else {
        $iconCode = "<svg class='icon' aria-hidden='true'>
            <use xlink:href='#icon-weizhi'></use>
        </svg>";
    }
    return $iconCode;
}



// 获取浏览器图标
function GetBrIcon($br)
{
    $iconCode = '';
    switch ($br) {
        case 'Chrome':
            $iconCode = "<svg class='icon' aria-hidden='true'>
            <use xlink:href='#icon-chrome'></use>
        </svg>";
            break;
        case 'Firefox':
            $iconCode = "<svg class='icon' aria-hidden='true'>
            <use xlink:href='#icon-huohu'></use>
        </svg>";
            break;
        case 'Safari':
            $iconCode = "<svg class='icon' aria-hidden='true'>
            <use xlink:href='#icon-safari1'></use>
        </svg>";
            break;
        case '360_Browser':
            $iconCode = "<svg class='icon' aria-hidden='true'>
            <use xlink:href='#icon-a-360liulanqi'></use>
        </svg>";
            break;
        case 'QQ_Browser':
            $iconCode = "<svg class='icon' aria-hidden='true'>
            <use xlink:href='#icon-qqBrowser'></use>
        </svg>";
            break;
        case 'Opera':
            $iconCode = "<svg class='icon' aria-hidden='true'>
            <use xlink:href='#icon-opera'></use>
        </svg>";
            break;
        case 'Quark':
            $iconCode = "<svg class='icon' aria-hidden='true'>
            <use xlink:href='#icon-kuakeliulanqi'></use>
        </svg>";
            break;
            // 如以上浏览器都无法识别则输出默认chrome
        default:
            $iconCode = "<svg class='icon' aria-hidden='true'>
            <use xlink:href='#icon-chrome'></use>
        </svg>";
    }
    return $iconCode;
}


// 记录管理员登录信息
function AddBehavior($conn, $U_name, $USER, $user_ip, $user_gsd, $date, $Os, $Br)
{
    $AddSql = "insert into Behavior (L_name,L_user,L_ip,L_city,L_date,L_os,L_br) values (?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($AddSql);
    $stmt->bind_param("sssssss", $U_name, $USER, $user_ip, $user_gsd, $date, $Os, $Br);
    $result = $stmt->execute();
    if (!$result)
        echo "错误信息：" . $stmt->error;
    $stmt->fetch();
}

// rgb转16进制
function rgbToHex($rgb)
{
    $parts = explode(",", $rgb);
    $hexParts = [];
    foreach ($parts as $part) {
        $intPart = intval(trim($part));
        $hexPart = str_pad(dechex($intPart), 2, "0", STR_PAD_LEFT);
        $hexParts[] = $hexPart;
    }
    $hexColor = "#" . implode("", $hexParts);
    return $hexColor;
}


function PasswordEncryption($hashed_password)
{
    $Apassword = password_hash($hashed_password, PASSWORD_DEFAULT);
    return $Apassword;
}

function DetermineAvatarAddress($GenderType)
{
    global $WebQueryResult;

    if (!empty($WebQueryResult)) {
        // 自定义头像地址
        if ($WebQueryResult['if_aiv']) {
            // 男主头像地址
            if ($GenderType) {
                $AvatarUrl = $WebQueryResult['diyb_url'];
            } else {
                // 女主头像地址
                $AvatarUrl = $WebQueryResult['diyg_url'];
            }
        } else {
            // 使用QQ号码拼接头像地址
            if ($GenderType) {
                $AvatarUrl = 'https://q1.qlogo.cn/g?b=qq&nk=' . $WebQueryResult['boyimg'] . '&s=640';
            } else {
                // 女主头像地址
                $AvatarUrl = 'https://q1.qlogo.cn/g?b=qq&nk=' . $WebQueryResult['girlimg'] . '&s=640';
            }
        }
    }

    return $AvatarUrl;
}

function getAvatarFrames($AvatarId)
{
    global $CoupleAvatarFrames;

    if (!empty($CoupleAvatarFrames)) {
        foreach ($CoupleAvatarFrames as $frame) {
            if ($frame['id'] == $AvatarId) {
                $current_frame = $frame;
                break; // 找到后退出循环
            }
        }
        $AtrStr = "data-src= '{$current_frame['url']}' style='transform: scale({$current_frame['scale']});top: {$current_frame['top_offset']}px;left: {$current_frame['left_offset']}px;' ";
    } else {
        $AtrStr = '';
    }

    echo $AtrStr;
}

// Url参数为2 则输出src QQ邮箱使用
// Url参数为3 则输出image 微信小程序使用
function parseEmojis($content, $Url = 1)
{

    // 定义表情存放的 JSON 文件路径
    $jsonFile = dirname(__DIR__) . '/OwO/emoji.json';

    if ($Url == 2 || $Url == 3) {
        $domain = $_SERVER['HTTP_HOST'];
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $url = $protocol . $domain . "/OwO/images";
    } else {
        $url = "../OwO/images";
    }

    $jsonData = file_get_contents($jsonFile);

    if ($jsonData === false) {
        throw new Exception('Unable to load JSON file.');
    }

    $emojiData = json_decode($jsonData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data: ' . json_last_error_msg());
    }

    $emojiMap = [];
    foreach ($emojiData as $category) {
        if (isset($category['container']) && is_array($category['container'])) {
            foreach ($category['container'] as $emoji) {
                $iconPath = $emoji['icon'];

                if (preg_match('/^[a-zA-Z0-9-_\/]+\.(gif|png)$/', $iconPath)) {
                    $emojiMap[$emoji['data']] = $iconPath;
                } else {
                    // 如查询不到当前表情数据 则直接返回源文本
                    continue;
                }
            }
        }
    }

    $parsedContent = preg_replace_callback('/([:@]{1,2})\((.*?)\)/u', function ($matches) use ($emojiMap, $url, $Url) {
        $prefix = $matches[1];
        $key = $prefix . '(' . $matches[2] . ')';

        if (isset($emojiMap[$key])) {
            
            if ($Url == 3) {
                $emojiHtml = '<img style="height:20px;margin: 0 2px;vertical-align: middle;" src="' . htmlspecialchars($url . '/' . $emojiMap[$key], ENT_QUOTES, 'UTF-8') . '">';
            }else if ($Url == 2) {
                $emojiHtml = '<img class="lazy" style="width:25px;margin: 0 2px;" src="' . htmlspecialchars($url . '/' . $emojiMap[$key], ENT_QUOTES, 'UTF-8') . '">';
            } else {
                $emojiHtml = '<img class="lazy" data-src="' . htmlspecialchars($url . '/' . $emojiMap[$key], ENT_QUOTES, 'UTF-8') . '"/>';
            }
            return $emojiHtml;
        }

        return htmlspecialchars($matches[0], ENT_QUOTES, 'UTF-8'); // 返回原始文本 过滤 XSS 
    }, $content);

    return $parsedContent;
}

function getNextSequentialNumber()
{
    static $currentNumber = 0;

    $currentNumber++;
    return $currentNumber;
}

function extract_numbers($ICPText)
{

    preg_match_all('/\d+/', $ICPText, $matches);
    $numbers = implode('', $matches[0]);

    return trim($numbers);
}

class api
{
    public static function send($API_URL, $get_post_data, $type, $ifsign, $sk)
    {

        $get_post_data['key'] = 'NUEADcLLpeHxTLSXJmNK7A3E4T';
        $get_post_data = http_build_query($get_post_data);
        if ($ifsign) {
            $sign = md5($get_post_data . $sk);
            $res = self::send_curl($API_URL, $type, $get_post_data, $sign);
        } else {
            $res = self::send_curl($API_URL, $type, $get_post_data, null);
        }
        return $res;
    }
    public static function send_curl($API_URL, $type, $get_post_data, $sign)
    {
        $ch = curl_init();
        if ($type == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $API_URL);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $get_post_data);
        } elseif ($type == 'GET') {
            curl_setopt($ch, CURLOPT_URL, $API_URL . '?' . $get_post_data);
        }
        if ($sign) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['sign:' . $sign]);
        }
        curl_setopt($ch, CURLOPT_REFERER, $API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $resdata = curl_exec($ch);
        curl_close($ch);
        return $resdata;
    }
}

class PasswordValidator
{
    private $maxAttempts;   // 最大尝试次数
    private $lockoutTime;   // 临时封禁时长（秒）
    private $maxPermanentAttempts;  // 永久封禁尝试次数
    private $dbConnection;  // 数据库连接

    public function __construct($dbConnection, $maxAttempts = 5, $lockoutTime = 60, $maxPermanentAttempts = 10)
    {
        $this->dbConnection = $dbConnection; // 用于记录IP封禁
        $this->maxAttempts = $maxAttempts;   // 每个IP最大尝试次数
        $this->lockoutTime = $lockoutTime;   // 封禁时长（秒）
        $this->maxPermanentAttempts = $maxPermanentAttempts; // 永久封禁次数
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    // 获取客户端IP地址
    private function get_client_ip()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $_SERVER['REMOTE_ADDR'] = $list[0];
            $currentIPAddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $currentIPAddress = $_SERVER['REMOTE_ADDR'];
        }
        return $currentIPAddress;
    }

    // 检查该IP是否已经被永久封禁
    private function is_ip_banned($ip)
    {
        $stmt = $this->dbConnection->prepare("SELECT * FROM IPerror WHERE State = ?");
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param('s', $ip);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result->fetch_assoc();
    }

    // 将IP永久封禁并写入数据库
    private function ban_ip($ip)
    {
        $city = Get_City($ip);
        $stmt = $this->dbConnection->prepare("INSERT INTO IPerror (ipAdd, Time, State, text) VALUES (?, NOW(), ?, ?)");
        if ($stmt === false) {
        } else {
            $text = '登录密码错误超过限制次数 系统自动封禁';
            $stmt->bind_param('sss', $city, $ip, $text);
            if (!$stmt->execute()) {
                // error_log('插入IP封禁记录失败: ' . $stmt->error, 3, 'upload_errors.log');
            }
            $stmt->close();
        }
    }

    // 检查该IP是否达到最大临时封禁条件
    private function is_locked_out($ip)
    {
        if (!isset($_SESSION['attempts'])) {
            $_SESSION['attempts'] = [];
        }

        $attempts = isset($_SESSION['attempts'][$ip]) ? $_SESSION['attempts'][$ip] : ['count' => 0, 'time' => 0];
        if ($attempts['count'] >= $this->maxAttempts && (time() - $attempts['time']) < $this->lockoutTime) {
            return true;
        }
        return false;
    }

    // 增加IP的错误尝试次数
    private function increment_attempts($ip)
    {
        if (!isset($_SESSION['attempts'][$ip])) {
            $_SESSION['attempts'][$ip] = ['count' => 1, 'time' => time()];
        } else {
            $_SESSION['attempts'][$ip]['count']++;
            $_SESSION['attempts'][$ip]['time'] = time();
        }

        $remainingAttempts = $this->maxPermanentAttempts - $_SESSION['attempts'][$ip]['count'];

        // 如果尝试次数超过10次，永久封禁该IP
        if ($_SESSION['attempts'][$ip]['count'] >= $this->maxPermanentAttempts) {
            $this->ban_ip($ip);
        }

        return $remainingAttempts;
    }

    // 清除该IP的错误尝试次数
    private function clear_attempts($ip)
    {
        if (isset($_SESSION['attempts'][$ip])) {
            unset($_SESSION['attempts'][$ip]);
        }
    }

    public function validate($inputPassword, $correctPassword,$PwType = 0)
    {
        $clientIp = $this->get_client_ip();

        // 检查是否已经永久封禁
        if ($this->is_ip_banned($clientIp)) {
            return ['success' => false, 'message' => '你的IP已被永久封禁！'];
        }

        // 检查是否达到最大临时封禁条件
        if ($this->is_locked_out($clientIp)) {
            $this->increment_attempts($clientIp);
            return ['success' => false, 'message' => '尝试次数过多 请稍后再试！'];
        }

        if (!$PwType) {
           $passwordCheckStatus =  password_verify($inputPassword, $correctPassword);
        }else{
            if($inputPassword == $correctPassword){
                $passwordCheckStatus =  true;
            }else{
                $passwordCheckStatus =  false;
            }
        }

        // 验证密码是否正确
        if ($passwordCheckStatus) {
            // 密码正确，清除尝试记录
            $this->clear_attempts($clientIp);
            return ['success' => true, 'message' => '登录成功 正在为你跳转后台！ <br/> 当前管理员：'];
        } else {
            // 密码错误，增加尝试次数
            $remainingAttempts = $this->increment_attempts($clientIp);
            if (!$PwType) {
                return ['success' => false, 'message' => '用户名或密码错误 请重试！ <br/> 剩余验证次数：' . max(0, $remainingAttempts).'次'];
            }else{
                return ['success' => false, 'message' => '密码验证失败 请重试！ <br/> 剩余验证次数：' . max(0, $remainingAttempts).'次'];
            }
        }
    }
}
