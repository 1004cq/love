<?php

require_once __DIR__ . '/bootstrap.php';
lg_start_session();
include_once "Database.php";
include_once "connect.php";
include_once 'Function.php';
$user = trim((string)($_POST['adminName'] ?? ''));
$pw = (string)($_POST['pw'] ?? '');
@$user_ip = $ip_if;
@$user_gsd = Get_City(@$user_ip);
@$user_br = GetBrowser();
@$user_os = GetOs();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    lg_json_response(['state' => 0, 'message' => '仅支持 POST 请求'], 405);
}
if ($user === '' || $pw === '' || mb_strlen($user) > 64 || strlen($pw) > 256) {
    lg_json_response(['state' => 0, 'message' => '用户名或密码格式不正确'], 422);
}

$Love_Name_Sql = "select boy,girl from text";
$result_Name = mysqli_query($conn, $Love_Name_Sql);
if ($result_Name && mysqli_num_rows($result_Name)) {
    $User_Name = mysqli_fetch_array($result_Name);
}

$id = 0;
$Login_user = '';
$Login_pw = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "select id, user, pw from login where user = ? limit 1";
    $stmt_select = $conn->prepare($sql);
    if (!$stmt_select) {
        lg_json_response(['state' => 0, 'message' => '登录服务暂不可用'], 503);
    }
    $USER = $user;
    $stmt_select->bind_param("s", $USER);
    $stmt_select->bind_result($id, $Login_user, $Login_pw);
    $result = $stmt_select->execute();
    if (!$result) {
        lg_json_response(['state' => 0, 'message' => '登录服务暂不可用'], 503);
    }
    $stmt_select->fetch();
    $stmt_select->close();
}

if ($id == 1) {
    $U_name = $User_Name['boy'] ?? '';
} else if ($id == 2) {
    $U_name = $User_Name['girl'] ?? '';
}



@$user_Arr = [
    'state' => 0,
    'user' => @$USER,
    'user_gsd' => @$user_gsd,
    'user_name' => @$U_name,
    'user_ip' => @$user_ip,
    'date' => date("Y-m-d H:i:s"),
    'sendMail' => false,
    'LoginMailSwitch' => $PublicLoginMailSwitch,
    'message' => '',
];

function checkSendMail($conn, $SfTime)
{
    $timeInterval = $SfTime * 60;
    // 获取最新登录时间
    $latestLoginTimeQuery = "SELECT L_date FROM Behavior ORDER BY L_date DESC LIMIT 1";
    $SendMailresult = $conn->query($latestLoginTimeQuery);
    if ($SendMailresult && $SendMailresult->num_rows > 0) {
        $latestLoginTimeRow = $SendMailresult->fetch_assoc();
        $latestLoginTime = $latestLoginTimeRow['L_date'];
        $currentTime = time();
        $timeDifference = $currentTime - $latestLoginTime;
        $sendMail = ($timeDifference > $timeInterval) ? true : false;
    } else {
        $sendMail = true;
    }
    return $sendMail;
}


function Detection_Admin($u, $lu)
{
    if ($u == $lu) {
        return true;
    } else {
        return false;
    }
}


$passwordValidator = new PasswordValidator($conn);

$PwChenckResult = $passwordValidator->validate($pw, $Login_pw);


if (Detection_Admin($USER, $Login_user)) {
    if ($PwChenckResult['success']) {
        $user_Arr['sendMail'] = checkSendMail($conn, $PublicLoginSendlMinut);
        AddBehavior($conn, $user_Arr['user_name'], $user_Arr['user'], $user_Arr['user_ip'], $user_Arr['user_gsd'], time(), $user_os, $user_br);
        session_regenerate_id(true);
        $_SESSION['loginadmin'] = $USER;
        $_SESSION['login_at'] = time();
        $user_Arr['state'] = 1;
        $user_Arr['message'] = $PwChenckResult['message'].$U_name;
    } else {
        //密码错误
        $user_Arr['message'] = $PwChenckResult['message'];
    }
} else {
    //用户名错误
    $user_Arr['message'] = $PwChenckResult['message'];
}
echo json_encode($user_Arr);
