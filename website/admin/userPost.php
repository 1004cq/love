<?php
session_start();

$file = htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
include_once 'connect.php';
include_once "Database.php";
include_once 'Function.php';

if (@$_SESSION['securityCodeDetection'] <> $LGNew_UI_Code) {
    $SCode = trim($_POST['SCode']);
}

if (userHasPermission($_SESSION['loginadmin'], $conn)) {

    $index_leav_num = trim($_POST['index_leav_num']);
    $open_pw = trim($_POST['open_pw']);
    $diy_page_pw = trim($_POST['diy_page_pw']);
    $url_pw = trim($_POST['url_pw']);
    $siteMaintenance = trim($_POST['siteMaintenance']);
    $SmTitie = trim($_POST['SmTitie']);
    $SmText = trim($_POST['SmText']);
    $SmBgUrl = trim($_POST['SmBgUrl']);
    $index_essay_num = trim($_POST['index_essay_num']);
    $adminName_one = trim($_POST['adminName_one']);
    $pw_one = trim($_POST['pw_one']);
    $adminName_two = trim($_POST['adminName_two']);
    $pw_two = trim($_POST['pw_two']);
    $Webanimation = trim($_POST['Webanimation']);
    $pcCarouselHeight = trim($_POST['pcCarouselHeight']);
    $mobileCarouselHeight = trim($_POST['mobileCarouselHeight']);

    $cssCon = htmlspecialchars(trim($_POST['cssCon']), ENT_QUOTES);
    $headCon = htmlspecialchars(trim($_POST['headCon']), ENT_QUOTES);
    $footerCon = htmlspecialchars(trim($_POST['footerCon']), ENT_QUOTES);
    $carousel = htmlspecialchars(trim($_POST['carousel']), ENT_QUOTES);
    $is_aboutActive = trim($_POST['is_aboutActive']);
    $TabTemplate = trim($_POST['currentBottomTab']);


    $sql = "SELECT * FROM login";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) > 0) {
        $logins = array();
        while ($row = mysqli_fetch_array($result)) {
            $logins[] = $row;
        }
    }

    if (@$LGNew_UI_Code == $SCode || @$_SESSION['securityCodeDetection'] == $LGNew_UI_Code) {

        if (@!$_SESSION['securityCodeDetection']) {
            $_SESSION['securityCodeDetection'] = $SCode;
        }

        // 更新前端动画开关
        $sql = "update text set animation = '$Webanimation' ";
        $result = mysqli_query($connect, $sql);

        // 修改管理员账号密码
        if ($pw_one) {

            $loginsql_one = "update login set user = '$adminName_one'  ,pw ='" . PasswordEncryption($pw_one) . "' where id = 1";
            session_destroy();
        } else {

            // 如果密码为空则只修改用户名
            $loginsql_one = "update login set user = '$adminName_one' where id = 1";
            if ($adminName_one <> $logins[0]['user']) {
                session_destroy();
            }
        }

        // 修改子用户账号密码
        if ($pw_two) {
            $loginsql_two = "update login set user = '$adminName_two',pw ='" . PasswordEncryption($pw_two) . "' where id = 2";
            session_destroy();
        } else {

            // 如果密码为空则只修改用户名
            $loginsql_two = "update login set user = '$adminName_two'  where id = 2";
            if ($adminName_two <> $logins[1]['user']) {
                session_destroy();
            }
        }

        $loginresult = mysqli_query($connect, $loginsql_one);
        if ($loginresult) {
            // 管理员信息成修改功
            echo "1";
        } else {
            //修改失败
            echo "0" . $logins[0]['user'];
        }
        $login_two = mysqli_query($connect, $loginsql_two);
        if ($login_two) {
            //子管理用户信息修改成功
            echo "8";
        } else {
            //修改失败
            echo "9" . $logins[1]['user'];
        }
        if ($result) {
            //修改动画开关成功
            echo "3";
        } else {
            echo "4";
        }
        // 修改自定义属性
        $diysql = "update diySet set headCon = '$headCon',footerCon = '$footerCon',cssCon = '$cssCon',index_essay_num = '$index_essay_num',index_leav_num = '$index_leav_num',diy_page_pw = '$diy_page_pw',open_pw = '$open_pw',url_pw = '$url_pw',carousel = '$carousel',is_aboutActive = '$is_aboutActive',StationMaintenanceSwitch = '$siteMaintenance',SmTitie = '$SmTitie',SmText = '$SmText',SmBgUrl = '$SmBgUrl',pcCarouselHeight = '$pcCarouselHeight', mobileCarouselHeight = '$mobileCarouselHeight', TabTemplate = '$TabTemplate' ";
        $diyresult = mysqli_query($connect, $diysql);
        if ($diyresult) {
            echo "5";
        } else {
            echo "6";
        }
    } else {
        echo "7";
    }

} else {
    $AccessAddress = $file;
    include_once '../404.php';
}

