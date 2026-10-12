<!--
 * @Author: Ki.
 * @Project: LG_NewUi
 * @LastEditors: Ki.
 * @LastEditTime: 2024-05-01 16:36:06
 * @Description: 小手一牵 岁岁年年
 * Copyright (c) 2022-2023 by Ki All Rights Reserved. 
-->
<!DOCTYPE html>
<html lang="zh-CN">

<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/ipjc.php');
include_once "connect.php";
$LoginStatus = false;
if (@isset($_SESSION['loginadmin']) && @$_SESSION['loginadmin'] <> '') {
    $LoginStatus = true;
    // die("<script>alert('你已登陆 正在为你跳转到后台首页');location.href = 'index.php'</script>");
}
?>

<head>
    <meta charset="utf-8" />
    <title><?php echo $LoveTitle ?> — 后台管理登录</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta content="Ki" name="author" />
    <link rel="stylesheet" href="/Style/css/loading.css" rel="stylesheet">
    <link rel="stylesheet" href="/Style/toastr/toastr.css">
    <link rel="stylesheet" href="/Style/login/style.css">
    <link rel="stylesheet" href="/Style/login/remixicon.css">
</head>



<div id="Loadanimation" style="z-index:999999;">
    <div id="Loadanimation-center">
        <div id="Loadanimation-center-absolute">
            <div class="xccx_object" id="xccx_four"></div>
            <div class="xccx_object" id="xccx_three"></div>
            <div class="xccx_object" id="xccx_two"></div>
            <div class="xccx_object" id="xccx_one"></div>
        </div>
    </div>
</div>

<div id="page" class="site">
    <div class="container">
        <div class="login">
            <div class="hero">
                <h1>
                    <h1>
                        <a href="../index.php">
                            <?php echo $LoveTitle ?>
                        </a>
                    </h1>
                </h1>
                <h2>小手一牵 岁岁年年<i class="ri-heart-2-line"></i></h2>
            </div>
            <div class="main">
                <form action="##" method="post">
                    <?php if ($LoginStatus): ?>
                        <p class="LoginTrue">
                            你当前已登录后台管理 是否直接进入
                        </p>
                    <?php else: ?>
                        <p>
                            <input name="adminName" type="text" placeholder="用户名" required>
                        </p>
                        <p class="password">
                            <input id="passwordInput" name="pw" type="password" placeholder="密码" required>
                            <i id="togglePassword" class="ri-eye-off-line"></i>
                        </p>
                    <?php endif; ?>
                    <p class="mrt2 <?php echo $LoginStatus ? 'FlexRow' : '' ?>">
                        <?php if ($LoginStatus): ?>
                            <button id="clearLogin" class="submit shadow-blur" type="button">退出登录 <i
                                    class="ri-share-circle-line"></i></button>
                            <button id="toAdmin" class="submit shadow-blur" type="button">进入后台 <i
                                    class="ri-settings-4-line"></i></button>
                        <?php else: ?>
                            <button id="login_post" class="submit shadow-blur" type="button">登入管理 <i
                                    class="ri-navigation-line"></i></button>
                        <?php endif; ?>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="Copyright">
    <p>
        <a href="https://lguiy.kikiw.cn/wiki/" target="_blank">Copyright © <?php echo date("Y") ?> LG_NewUi
            <?php echo $version ?> & Ki All Rights Reserved.</a>
    </p>
</div>

<script src="/Style/jquery/jquery.min.js"></script>
<script src="/Style/toastr/toastr.js"></script>
<script>
    const LoveTitle = 'LG_NewUi';
    $(function() {
        let passwordInput = $('#passwordInput');
        let togglePassword = $('#togglePassword');

        togglePassword.click(function() {
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                togglePassword.removeClass('ri-eye-off-line').addClass('ri-eye-line');
            } else {
                passwordInput.attr('type', 'password');
                togglePassword.removeClass('ri-eye-line').addClass('ri-eye-off-line');
            }
        });

        $("#Loadanimation").fadeOut(1000);

        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "rtl": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": 300,
            "hideDuration": 1000,
            "timeOut": 5000,
            "extendedTimeOut": 1000,
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    })

    $(document).keydown(function(event) {
        if (event.which == 13 || event.keyCode == 13) {
            $("#login_post").click();
        }
    });

    $("#toAdmin").click(function() {
        location.href = '../admin/index.php';
    })

    $("#clearLogin").click(function() {
        location.href = '../admin/loginOut.php';
    })

    $("#login_post").click(function() {
        $("#Loadanimation").fadeIn(500);
        let adminName = $("input[name='adminName']").val();
        let pw = $("input[name='pw']").val();

        if (!adminName || !pw) {
            toastr["warning"]("用户名和密码不能为空！！", LoveTitle);
            $("#Loadanimation").fadeOut(300);
            return false
        }

        $("#login_post").attr("disabled", "disabled");
        $.ajax({
            url: "loginPost.php",
            data: {
                adminName: adminName,
                pw: pw,
            },
            type: "POST",
            dataType: "json",
            success: function(res) {
                console.log(res);
                if (res.state == 1) {
                    toastr["success"](res.message, LoveTitle);
                    // toastr["success"]("正在为你转到后台首页", LoveTitle);
                    if (res.LoginMailSwitch == "1") {
                        if (res.sendMail) {
                            mail_user(res)
                        }
                    }
                    setTimeout(function() {
                        location.href = '../admin/index.php';
                    }, 1500);
                } else if (res.state == 0) {
                    $("#login_post").attr("disabled", false);
                    toastr["error"](res.message, LoveTitle);
                } else {
                    toastr["error"]("未知错误 请联系开发者处理！", LoveTitle);
                }
            },
            error: function(err) {
                $("#login_post").attr("disabled", false);
                toastr["error"]("网络错误 请稍后重试！", LoveTitle);
            },
            complete: function() {
                $("#Loadanimation").fadeOut(1000);
            }
        })
    })


    function mail_user(admin_arr) {
        $.ajax({
            url: "Mail_sent.php",
            data: {
                login_name: admin_arr.user_name,
                user_gsd: admin_arr.user_gsd,
                login_user: admin_arr.user,
                login_date: admin_arr.date,
                user_ip: admin_arr.user_ip,
                sending: true,
                type: "admin_login"
            },
            type: "POST",
            dataType: "json",
            success: function(res) {
                toastr["info"]("登陆邮件已发送", LoveTitle);
            },
            error: function(err) {
                if (err.responseText == "success") {
                    toastr["success"]("登陆邮件已发送", LoveTitle);
                } else {
                    toastr["error"](err.responseText, LoveTitle);
                }
            }
        })
    }
</script>
</body>

</html>
