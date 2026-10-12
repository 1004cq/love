<?php
require_once __DIR__ . '/bootstrap.php';
lg_start_session();
error_reporting(0);
include_once 'Function.php';
include($_SERVER['DOCUMENT_ROOT'] . '/ipjc.php');
$name = trim((string)($_POST['name'] ?? ''));
$qq = trim((string)($_POST['qq'] ?? ''));
$Msgtext = trim((string)($_POST['text'] ?? ''));
$time = time();

$IPAddress =  $ip_if;

$Os_info = GetOs();
$Br_info = GetBrowser();
$Filter_Name = replaceSpecialChar($name);
$Filter_QQ = replaceSpecialChar($qq);
$Filter_Text = replaceSpecialChar($Msgtext);
$Filter_Time = replaceSpecialChar($time);
$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

if ($qq <> "anon") {
    function checkQQ($qq)
    {
        if (preg_match("/^[1-9][0-9]{4,}$/", $qq)) {
            return true;
        } else {
            return false;
        }
    }
} else {
    function checkQQ($qq)
    {
        return true;
    }
}

function replaceSpecialChar($Symbol)
{
    $Filter = "/\/|\~|\!|\-|\=|\#|\\$|\%|\^|\&|\*|\"|\+|\{|\}|\<|\>|\?|\[|\]|\,|\/|\;|\'|\`|\=|\\\|\||/";
    return preg_replace($Filter, "", $Symbol);
}


include_once 'Database.php';
$diySet = "select * from leavSet";
$res_diy = mysqli_query($conn, $diySet);
$diySetinfo = mysqli_fetch_array($res_diy);
$leav_day_num = $diySetinfo['leav_day_num'];
$MsgSwitch = $diySetinfo['MsgSwitch'];
$Msg_Vf = $diySetinfo['MsgVerify'] == 2 ? 1 : 0;

$response = array(
    'Status' => false,
    'sign' => null,
    'MsgHtml' => null,
    'message' => null,
);
$result = false;
$_SESSION['message_count_limit'] = (int)($_SESSION['message_count_limit'] ?? 0);

if (mb_strlen($name) > 40 || mb_strlen($Msgtext) > 1000 || strlen($qq) > 20) {
    lg_json_response(['Status' => false, 'message' => '留言内容过长，请精简后重试。'], 422);
}

if ($MsgSwitch == 2) {
    $ErrMsg = '管理员已关闭留言接口 你无权访问！';
    include_once '../404.php';
}



if ($_SESSION['message_count_limit'] < $leav_day_num) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($qq) && (!empty($Filter_Name)) && (!empty($Filter_Text))) {
            if (filter_var($IPAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                if (checkQQ($Filter_QQ)) {
                    $charu = "insert into leaving (name,QQ,text,time,ip,city,switch,Os_info,Br_info) values (?,?,?,?,?,?,?,?,?)";
                    $stmt = $conn->prepare($charu);
                    $stmt->bind_param("ssssssiss", $Filter_Name, $Filter_QQ, $Filter_Text, $Filter_Time, $Filter_IP, $Filter_City, $MsgVerify, $Filter_Os, $Filter_Br);
                    $Filter_Name = replaceSpecialChar($name);
                    $Filter_QQ = replaceSpecialChar($qq);
                    $Filter_Text = replaceSpecialChar($Msgtext);
                    $Filter_Time = replaceSpecialChar($time);
                    $Filter_IP = $IPAddress;
                    $Filter_Os = $Os_info;
                    $Filter_Br = $Br_info;
                    $Filter_City = Get_City($IPAddress);
                    $MsgVerify = $Msg_Vf;
                    $result = $stmt->execute();
                    if (!$result)
                        echo "错误信息：" . $stmt->error;
                } else {
                    $response['message'] = '留言失败 QQ号码格式错误！';
                }
            } else {
                $response['message'] = '留言失败 当前IP格式错误<br/>请更换网络后重试！';
            }
        } else {
            $response['message'] = '留言失败 表单不可为空 请重试！';
        }

        if ($result) {
            $response['Status'] = true;
            $response['MsgHtml'] = parseEmojis($Filter_Text);

            if ($PublicSendingSwitch == "true" && $diySetinfo['MsgVerify'] == 1) {
                $response['sign'] = 1;
                $response['message'] = '留言提交成功  已发送邮件提醒小站管理员 <br/> 请等待管理员审核通过后进行展示！';
            } else if ($PublicSendingSwitch == "false" && $diySetinfo['MsgVerify'] == 1) {
                $response['sign'] = 3;
                $response['message'] = '留言成功 请等待管理员审核通过后进行展示！';
            } else if ($PublicSendingSwitch == "true") {
                $response['sign'] = 2;
                $response['message'] = '留言成功 已发送邮件提醒小站管理员！';
            } else if ($diySetinfo['MsgVerify'] == 2) {
                $response['sign'] = 3;
                $response['message'] = '留言成功 感谢您写下的留言祝福！';
            }

            $_SESSION['message_count_limit']++;
        } else {
            $response['message'] = '留言失败 请联系小站管理员排查错误！';
        }
    } else {
        $AccessAddress = $file;
        include_once '../404.php';
    }
} else {
    $response['message'] = '留言失败 你今天留言次数已超过<br/>管理员设定的限制次数！';
}

header('Content-Type: application/json');
echo json_encode($response);
