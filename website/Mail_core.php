<?php
/*
 * @Author: Ki.
 * @Project: LG_NewUi
 * @LastEditTime: 2023-09-18 22:31:25
 * @Description: 小手一牵 岁岁年年
 * Copyright (c) 2022-2023 by Ki All Rights Reserved.
 */

function sendMail($Mail_info)
{

    //引入PHPMailer的核心文件 使用require_once包含避免出现PHPMailer类重复定义的警告
    require_once("phpmailer/class.phpmailer.php");
    require_once("phpmailer/class.smtp.php");
    //实例化PHPMailer核心类
    $mail = new PHPMailer();

    //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    $mail->SMTPDebug = 1;

    //使用smtp鉴权方式发送邮件
    $mail->isSMTP();

    //smtp需要鉴权 这个必须是true
    $mail->SMTPAuth = true;

    //链接qq域名邮箱的服务器地址
    // $mail->Host = 'smtp.exmail.qq.com';
    $mail->Host = $Mail_info['Host'];

    if ($Mail_info['ssl']) {
        $mail->SMTPSecure = 'ssl';
    }

    $mail->Port = $Mail_info['port'];

    $mail->Hostname = $Mail_info['Hostname'];

    $mail->CharSet = 'UTF-8';

    //设置发件人姓名昵称
    $mail->FromName = $Mail_info['FromName'];

    $mail->Username = $Mail_info['Username'];

    $mail->Password = $Mail_info['Password'];

    $mail->From = $Mail_info['Username'];

    $mail->isHTML(true);

    if ($Mail_info['manyPeople']) {
        //收件人1
        $mail->addAddress($Mail_info['admin_mail'], $Mail_info['admin_name']);
        //收件人2
        $mail->addAddress($Mail_info['user_mail'], $Mail_info['user_name']);
    } else {
        $mail->addAddress($Mail_info['user_mail'], $Mail_info['user_name']);
    }
    // 设置发件标题
    $mail->Subject = $Mail_info['title'];

    $mail->Body = $Mail_info['content'];
    
    $mail->SMTPDebug = 0;

    try {
        $status = $mail->send();

        if ($status) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log("邮件发送过程中出现问题:". $mail->ErrorInfo . "\n", 3, 'admin/upload_errors.log');
    }
    
    
}
