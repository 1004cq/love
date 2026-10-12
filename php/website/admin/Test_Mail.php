<?php
/*
 * @Author: Ki.
 * @Project: LG_NewUi
 * @LastEditTime: 2024-02-06 15:57:56
 * @Description: 小手一牵 岁岁年年
 * Copyright (c) 2022-2023 by Ki All Rights Reserved.
 */
session_start();

include_once "Database.php";
include_once 'Function.php';
if (!userHasPermission($_SESSION['loginadmin'], $conn)) {
    die("<script>alert('参数错误 请先登陆');location.href = 'login.php'</script>");
}
require_once("../Mail_core.php");
include_once "../admin/connect.php";
@$code_mail = trim($_POST['code']);
@$manyPeople = trim($_POST['manyPeople']);

$Mail_sql = "select * from MailSet";
$Mail_res = mysqli_query($connect, $Mail_sql);
$Mail_data = mysqli_fetch_array($Mail_res);

$date = date("Y-m-d H:i");
$title = $Mail_data['title'];

$Test_Templat = <<<HTML
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
            overflow: hidden;
            height: 100vh;
        }

        .mail_warp {
            width: 626px;
            margin: 80px auto;
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
            margin: 20px 0;
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

        .SwStyle {
            padding: 4px 15px;
            color: #2ec078;
            border: 1px solid #2ec078;
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

        .ses{
            margin:1rem 0;
            text-align: center;
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
            <span>$title</span>
        </div>
        <div class="mail_warp">
            <div class="mail_cont">
                <h2 class="card_title">情侣小站邮箱配置测试</h2>
                <div class="leav_Message">
                    <p><b class="bule">当您正常收到这封邮件时：</b></p>
                    <div class="message"><b>$title</b>  邮箱信息已配置完成，接下来可以体验情侣小站的邮件提醒啦～</br>
                    </div>
                    <p class="line"> <b>测试时间：</b>$date</p>
                    <p class="ses"><span class="SwStyle">测试成功</span></p>
                    
                </div>

            </div>
            <div class="footer">
                <p>—— 欢迎使用 $title 小站邮件模块 ——</p>
            </div>
        </div>
    </div>
</body>

</html>
HTML;


$Mail_params = [
    'manyPeople' => $manyPeople,
    'mail_sending' => $mail_sending,
    'Host' => $Mail_data['host'],
    'port' => $Mail_data['port'],
    'Hostname' => 'localhost',
    'ssl' => filter_var($Mail_data['mail_ssl'], FILTER_VALIDATE_BOOLEAN),
    'FromName' => $Mail_data['from_name'],
    'Username' => $Mail_data['mail_user'],
    'Password' => $Mail_data['mail_pw'],
    'admin_mail' => $Mail_data['admin_mail'],
    'admin_name' => $Mail_data['admin_name'],
    'user_mail' => $Mail_data['user_mail'],
    'user_name' => $Mail_data['user_name'],
    'title' => $Mail_data['title'],
    'content' => $Test_Templat,
];

$flag = sendMail($Mail_params);

if ($flag) {
    echo "success";
} else {
    echo "fail";
}
