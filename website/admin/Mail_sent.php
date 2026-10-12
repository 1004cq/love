<?php
/*
 * @Author: Ki.
 * @Project: LG_NewUi
 * @LastEditTime: 2024-05-01
 * @Description: 小手一牵 岁岁年年
 * Copyright (c) 2022-2023 by Ki All Rights Reserved.
 */

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("<script>alert('请求不合法 请勿直接访问本页');location.href='../index.php';</script>");
}

require_once("../Mail_core.php");
include_once "../admin/connect.php";
include_once "../admin/Database.php";
include_once '../admin/Function.php';

@$mail_type = trim($_POST['type']);
//获取传过来的发信内容
@$mail_ip = trim($_POST['mail_ip']);
@$mail_qq = trim($_POST['mail_qq']);
@$attr = trim($_POST['mail_attr']);
@$sw = trim($_POST['mail_sw']);
@$mail_name = trim($_POST['mail_name']);
@$mail_text = parseEmojis(trim($_POST['mail_text']), 2);
@$mail_sending = trim($_POST['sending']);
@$MsgType = trim($_POST['MsgType']);

// 获取站点域名
@$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
@$domain = $_SERVER['HTTP_HOST'];
@$url = $protocol . $domain;

//数据库中取出邮件配置
$Mail_sql = "select * from MailSet";
$Mail_res = mysqli_query($connect, $Mail_sql);
$Mail_data = mysqli_fetch_array($Mail_res);

// 获取当前时间
$date = date("Y-m-d H:i");

// $title = "「" . $Mail_data['title'] . "」小站中有一条新的留言 待确认";
$info_title = "「" . $Mail_data['title'] . "」";
$Mail_user = $Mail_data['user_mail'];
$Mail_name = $Mail_data['user_name'];


// 管理员审核留言通知游客
if (@$mail_type == "SendAdopt" || @$mail_type == "SendFail") {

    if (!userHasPermission($_SESSION['loginadmin'], $conn)) {
        die("邮件发送失败！暂无权限 请先登录");
    }

    $Mail_user = trim($_POST['user_mail']);
    $Mail_name = trim($_POST['mail_name']);
    $release_time = date("Y-m-d H:i", trim($_POST['release_time']));
    // 审核通过
    if (@$mail_type == "SendAdopt") {
        $title = "「" . $Mail_data['title'] . "」小站中的留言内容 管理员已审核通过";
        $ProcessingStatus = "<p>管理员已在 <b>$date</b> 审核通过</p>";
        // 审核驳回
    } else if (@$mail_type == "SendFail") {
        $title = "很遗憾～「" . $Mail_data['title'] . "」小站中的留言内容 已被管理员驳回";
        $ProcessingStatus = "<p>管理员已在 <b>$date</b> 审核驳回</p>";
    }

    // 管理员登录
} else if (@$mail_type == "admin_login") {
    if (!userHasPermission($_SESSION['loginadmin'], $conn)) {
        die("邮件发送失败！暂无权限 请先登录");
    }
    $login_name = trim($_POST['login_name']);
    $user_gsd = trim($_POST['user_gsd']);
    $user_ip = trim($_POST['user_ip']);
    $login_user = trim($_POST['login_user']);
    $login_date = trim($_POST['login_date']);
    $title = "「" . $Mail_data['title'] . "」小站 管理员 --" . $login_name . "--成功登陆后台";

    // 游客留言通知管理审核
} else {
    if ($Mail_data['SendingSwitch'] == 'false') {
        die('当前已关闭站内邮件提醒！');
    } else {

        if (!($mail_text && $mail_ip && $attr && $mail_qq && $mail_name)) {
            die("邮件发送失败！留言表单不完整");
        }

        if ($MsgType == 2) {
            $title = "「" . $Mail_data['title'] . "」小站中有一条新的留言 待查看";
        } else {
            $title = "「" . $Mail_data['title'] . "」小站中有一条新的留言 待确认";
        }
    }
}

// 留言审核通过/驳回通知游客模板
if (@$mail_type == "SendAdopt" || @$mail_type == "SendFail") {
    $Mail_Templat = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
</head>
<body>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        @media screen and (max-width: 480px) {
            .mail {
                width: 100%;
                margin: 0 auto;
            }

            .mail_warp {
                width: 95% !important;
            }

            .mail_cont {
                width: 92% !important;
            }

            .bg_img {
                width: 100% !important;
                background-size: 100%;
            }
        }

        .mail {
            background-color: #f5f5f6;
            overflow: auto;
            /* height: 100vh; */
        }

        .mail_warp {
            width: 626px;
            margin: 30px auto;
        }

        .mail_cont {
            margin: 0 auto;
            box-sizing: border-box;
            padding: 40px 20px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 12px #e8eaed;
        }

        .mail_cont .card_title {
            margin: 0 0 20px;
            text-align: center;
            font-size: 28px;
            font-weight: normal;
            color: #333333;
            font-weight: 700;
            text-shadow: 0 2px 2px rgb(0 0 0 / 25%);
        }

        .leav_Message {
            margin: 10px 0;
            padding: 0;
            color: #696565;
            font-size: 1rem;
        }

        .leav_Message p {
            font-weight: 500;
            line-height: 2.3rem;
        }

        .leav_Message b {
            color: #55caff;
        }

        .leav_Message .message {
            padding: 10px;
            margin: 10px 0;
            color: #ff9270;
            font-weight: 400;
            border: 1px dashed #ff9270;
            border-radius: 5px;
            position: relative;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .mail .line {
            border-top: 1px dashed #c2c2c2;
            margin-top: 20px;
            line-height: 3rem;
        }

        .mail .line p {
            color: #333;
            font-weight: 700;
        }

        .mail .line p span {
            color: #2196F3;
            font-weight: 500;
        }

        .mail .line p .SwStyle {
            padding: 4px 15px;
            color: #009688;
            border: 1px solid #009688;
            border-radius: 30px;
            margin-left: 6px;
        }

        .footer {
            position: fixed;
            left: 0;
            bottom: 2rem;
            font-size: 14px;
            text-align: center;
            color: #bcbcbc;
            width: 100%;
            box-sizing: border-box;
        }

        .mail_logo {
            text-align: center;
            margin: 2rem 0;
        }

        .mail_logo span {
            background: #ff368b;
            border-radius: 5rem;
            color: #fff;
            font-weight: 700;
            padding: 0.5rem 1.2rem;
        }

        .mail_logo span svg {
            vertical-align: -6px;
        }

        .urlBtn {
            text-align: center;
        }

        .urlBtn button {
            border: none;
            border-radius: 30px;
            overflow: hidden;
            margin-top: 2rem;
        }

        .urlBtn button a {
            color: #fff;
            text-decoration: none;
            display: block;
            background-image: linear-gradient(45deg, #50b2ff, #03A9F4);
            padding: 0.5rem 2rem;
            font-size: 1rem;
            font-weight: 700;
        }
    </style>
    <div class="mail">
        <div class="mail_logo">
            <span>$info_title</span>
        </div>
        <div class="mail_warp">
            <div class="mail_cont">
                <h2 class="card_title">留言审核结果提醒</h2>
                <div class="leav_Message">
                    <p>您在<a style="text-decoration: none;color: #EC407A;" href="{$url}"
                            target="_blank">$info_title</a>小站填写的祝福留言</p>
                    $ProcessingStatus
                    <div class="message"><b>Message：</b>$mail_text</div>
                </div>
                <div class="line">
                    <p>时间：<span>$date</span></p>
                    <p>Name：<span>$mail_name</span></p>
                    <p>留言时间：<span>$release_time</span></p>
                    <p>审核状态：
                        <span class="SwStyle">$sw</span>
                    </p>
                    <div class="line"></div>
                    <div class="urlBtn"><button><a href="{$url}/leaving.php">查看详情</a></button>
                    </div>
                </div>

            </div>
            <div class="footer">
                <p>—— 欢迎使用 $info_title 小站邮件模块 ——</p>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
    // 管理员登录后台通知邮件模板
} else if (@$mail_type == "admin_login") {
    $Mail_Templat = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
</head>
<body>
    <style>
        * {
            margin: 0;
            padding: 0;
            /* font-family: "SimSun", sans-serif; */
        }

        @media screen and (max-width: 480px) {
            .mail {
                width: 100%;
                margin: 0 auto;
            }

            .mail_warp {
                width: 95% !important;
            }

            .mail_cont {
                width: 92% !important;
            }

            .bg_img {
                width: 100% !important;
                background-size: 100%;
            }
        }

        .mail {
            background-color: #f5f5f6;
            overflow: auto;
            /* height: 100vh; */
        }

        .mail_warp {
            width: 626px;
            margin: 30px auto;
        }

        .mail_cont {
            margin: 0 auto;
            box-sizing: border-box;
            padding: 40px 20px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 12px #e8eaed;
        }

        .mail_cont .card_title {
            margin: 0 0 20px;
            text-align: center;
            font-size: 28px;
            font-weight: normal;
            color: #333333;
            font-weight: 700;
            text-shadow: 0 2px 2px rgb(0 0 0 / 25%);
        }

        .leav_Message {
            margin: 10px 0;
            padding: 0;
            color: #696565;
            font-size: 1rem;
        }

        .leav_Message p {
            font-weight: 500;
            line-height: 2.3rem;
        }

        .leav_Message b {
            color: #55caff;
        }

        .leav_Message .message {
            padding: 10px;
            margin: 10px 0;
            color: #ff9270;
            font-weight: 400;
            border: 1px dashed #ff9270;
            border-radius: 5px;
            position: relative;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .mail .line {
            border-top: 1px dashed #c2c2c2;
            margin-top: 20px;
            line-height: 3rem;
        }

        .mail .line p {
            color: #333;
            font-weight: 700;
        }

        .mail .line p span {
            color: #2196F3;
            font-weight: 500;
        }

        .mail .line p .SwStyle {
            padding: 4px 15px;
            color: #2ec078;
            border: 1px solid #2ec078;
            border-radius: 30px;
            margin-left: 6px;
        }

        .footer {
            /* position: fixed;
            left: 0;
            bottom: 2rem; */
            margin:2rem 0;
            font-size: 14px;
            text-align: center;
            color: #bcbcbc;
            width: 100%;
            box-sizing: border-box;
        }

        .mail_logo {
            text-align: center;
            margin: 2rem 0;
        }

        .mail_logo span {
            background: #ff368b;
            border-radius: 5rem;
            color: #fff;
            font-weight: 700;
            padding: 0.5rem 1.2rem;
        }

        .mail_logo span svg {
            vertical-align: -6px;
        }

        .urlBtn {
            text-align: center;
        }

        .urlBtn button {
            border: none;
            border-radius: 30px;
            overflow: hidden;
            margin-top: 2rem;
        }

        .urlBtn button a {
            color: #fff;
            text-decoration: none;
            display: block;
            background-image: linear-gradient(45deg, #50b2ff, #03A9F4);
            padding: 0.5rem 2rem;

            font-size: 1rem;
            font-weight: 700;
        }


    </style>
    <div class="mail">
        <div class="mail_logo">
            <span>$info_title</span>
        </div>
        <div class="mail_warp">
            <div class="mail_cont">
                <h2 class="card_title">管理员登陆通知</h2>
                <div class="leav_Message">
                    <p>管理员<b>$login_name</b></a> 于 <b>$login_date</b>成功登录小站后台</p>
                </div>
                <div class="line">
                    <p>用户名：<span>$login_user</span></p>
                    <p>登录时间：<span>$date</span></p>
                    <p>登录IP：<span>$user_ip</span></p>
                    <p>登录地址：<span>$user_gsd</span></p>
                    <div class="line"></div>
                    <div class="urlBtn"><button><a href="{$url}">查看详情</a></button>
                    </div>
                </div>

            </div>
            <div class="footer">
                <p>—— 欢迎使用 $info_title 小站邮件模块 ——</p>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
    // 游客留言通知管理员邮件模板
} else {
    $Mail_Templat = <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    </head>
<body>
    <style>
        * {
            margin: 0;
            padding: 0;
            /* font-family: "SimSun", sans-serif; */
        }

        @media screen and (max-width: 480px) {
            .mail {
                width: 100%;
                margin: 0 auto;
            }

            .mail_warp {
                width: 95% !important;
            }

            .mail_cont {
                width: 92% !important;
            }

            .bg_img {
                width: 100% !important;
                background-size: 100%;
            }
        }

        .mail {
            background-color: #f5f5f6;
            overflow: auto;
            /* height: 100vh; */
        }

        .mail_warp {
            width: 626px;
            margin: 30px auto;
        }

        .mail_cont {
            margin: 0 auto;
            box-sizing: border-box;
            padding: 40px 20px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 12px #e8eaed;
        }

        .mail_cont .card_title {
            margin: 0 0 20px;
            text-align: center;
            font-size: 28px;
            font-weight: normal;
            color: #333333;
            font-weight: 700;
            text-shadow: 0 2px 2px rgb(0 0 0 / 25%);
        }

        .leav_Message {
            margin: 10px 0;
            padding: 0;
            color: #696565;
            font-size: 1rem;
        }

        .leav_Message p {
            font-weight: 500;
            line-height: 2.3rem;
        }

        .leav_Message b {
            color: #55caff;
        }

        .leav_Message .message {
            padding: 10px;
            margin: 10px 0;
            color: #ff9270;
            font-weight: 400;
            border: 1px dashed #ff9270;
            border-radius: 5px;
            position: relative;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .mail .line {
            border-top: 1px dashed #c2c2c2;
            margin-top: 20px;
            line-height: 3rem;
        }

        .mail .line p {
            color: #333;
            font-weight: 700;
        }

        .mail .line p span {
            color: #2196F3;
            font-weight: 500;
        }

        .mail .line p .SwStyle {
            padding: 4px 15px;
            color: #2ec078;
            border: 1px solid #2ec078;
            border-radius: 30px;
            margin-left: 6px;
        }

        .footer {
            /* position: fixed;
            left: 0;
            bottom: 2rem; */
            margin:2rem 0;
            font-size: 14px;
            text-align: center;
            color: #bcbcbc;
            width: 100%;
            box-sizing: border-box;
        }

        .mail_logo {
            text-align: center;
            margin: 2rem 0;
        }

        .mail_logo span {
            background: #ff368b;
            border-radius: 5rem;
            color: #fff;
            font-weight: 700;
            padding: 0.5rem 1.2rem;
        }

        .mail_logo span svg {
            vertical-align: -6px;
        }

        .urlBtn {
            text-align: center;
        }

        .urlBtn button {
            border: none;
            border-radius: 30px;
            overflow: hidden;
            margin-top: 2rem;
        }

        .urlBtn button a {
            color: #fff;
            text-decoration: none;
            display: block;
            background-image: linear-gradient(45deg, #50b2ff, #03A9F4);
            padding: 0.5rem 2rem;

            font-size: 1rem;
            font-weight: 700;
        }
        .leav_Message .bule{
            color: #2196F3;
        }

    </style>
    <div class="mail">
        <div class="mail_logo">
            <span>$info_title</span>
        </div>
        <div class="mail_warp">
            <div class="mail_cont">
                <h2 class="card_title">留言邮件提醒</h2>
                <div class="leav_Message">
                    <p><b class="bule">情侣小站管理员您好：</b></p>
                    <p>您的 <b class="bule">$info_title</b> 小站有一条新的祝福留言，其内容为：</p>
                    <div class="message"><b>Message：</b>$mail_text</div>
                </div>
                <div class="line">
                    <p>游客IP：<span>$mail_ip</span></p>
                    <p>游客地址：<span>$attr</span></p>
                    <p>留言时间：<span>$date</span></p>
                    <p>留言QQ：<span>$mail_qq</span></p>
                    <p>游客Name：<span>$mail_name</span></p>
                    <div class="line"></div>
                    <div class="urlBtn"><button><a href="{$url}/admin">查看详情</a></button>
                    </div>
                </div>

            </div>
            <div class="footer">
                <p>—— 欢迎使用 $info_title 小站邮件模块 ——</p>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
}


$Mail_params = [
    'manyPeople' => filter_var($mail_sending, FILTER_VALIDATE_BOOLEAN),
    'Host' => $Mail_data['host'],
    'port' => $Mail_data['port'],
    'Hostname' => 'localhost',
    'ssl' => $Mail_data['mail_ssl'],
    'FromName' => $Mail_data['from_name'],
    'Username' => $Mail_data['mail_user'],
    'Password' => $Mail_data['mail_pw'],
    'admin_mail' => $Mail_data['admin_mail'],
    'admin_name' => $Mail_data['admin_name'],
    'user_mail' => $Mail_user,
    'user_name' => $Mail_name,
    'title' => $title,
    'content' => $Mail_Templat,
];

$flag = sendMail($Mail_params);
if ($flag) {
    echo "success";
} else {
    echo "fail";
}
