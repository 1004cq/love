<?php
session_start();
$data = isset($_SESSION['encryption_data']) ? $_SESSION['encryption_data'] : null;
$BgUrl = $data['url_pw'] ?  $data['url_pw']  :'https://s1.locimg.com/2024/08/28/96c5dc373fa2b.webp';
include_once 'head.php';
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@700&display=swap" rel="stylesheet">
    <title>页面已加密 — 请输入密码继续访问</title>
</head>
<body>
    <div id="pjax-container">
        <style>
            body {
                background: url(<?php echo $BgUrl ?>) center;
                background-size: cover;
            }
        </style>
        <link rel="stylesheet" href="../Style/toastr/toastr.css">
        <link rel="stylesheet" href="../Style/css/pwey.css">
        <script src="../Style/jquery/jquery.min.js"></script>
        <script src="../Style/toastr/toastr.js"></script>
        <div class="main">
            <div class="topInfo">
                <span class="itps"><?php echo $data['logo'] ?></span>
            </div>
            <svg t="1679478949384" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"
                p-id="4370" style="width:8rem !important;height:8rem !important">
                <path
                    d="M785.2 1024h-580c-71.1 0-128.9-34.8-128.9-110V505.6c0-75.1 57.8-136.1 128.9-136.1h33.4c-3.4-17-5.3-34.5-5.3-52.4v-52.4C233.4 120.2 350.6 3 495.2 3S757 120.2 757 264.8v52.4c0 17.9-1.8 35.4-5.3 52.4h33.4c71.1 0 128.9 61.1 128.9 136.1V914c0.1 75.2-57.7 110-128.8 110zM442.9 721.8v119c0 28.8 23.5 52.4 52.4 52.4s52.4-23.5 52.4-52.4v-119c31.8-18 53.4-51.5 53.4-90.4 0-57.9-47.4-104.7-105.7-104.7-58.5 0-105.8 46.8-105.8 104.7-0.2 38.8 21.5 72.4 53.3 90.4z m209.4-457c0-86.8-70.3-157.1-157.1-157.1S338.1 178 338.1 264.8v104.7h314.2V264.8z"
                    fill="#515151" p-id="4371"></path>
            </svg>

            <!-- 表单 -->
            <form id="password-form" action="" method="post" class="mk-side-form">
                <h2 class="pw-tip">管理员已加密本页面</h2>
                <div class="import">
                    <input type="password" name="pagepwd" placeholder="请输入访问密码查看" required>
                    <button type="submit" id="submit-btn">提交</button>
                </div>
            </form>

            <a href="<?php echo $data['ToPage'] ?>" id="redirect-link" style="display:none;" data-pjax></a>

        </div>

        <script>
            $(document).ready(function () {
                $('body').addClass('loaded');

                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "timeOut": 5000
                };

                $('#password-form').on('submit', function (e) {
                    e.preventDefault();

                    const pagepwd = $('input[name="pagepwd"]').val();

                    $.ajax({
                        url: 'EncryptCheck.php',
                        type: 'POST',
                        data: {
                            pagepwd: pagepwd
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                toastr["success"]("密码验证通过 正在跳转...", "LG_NewUI");
                                $('#redirect-link')[0].click();
                            } else {
                                toastr["error"](response.message, "LG_NewUI");
                            }
                        },
                        error: function () {
                            toastr["error"]("请求失败，请稍后重试");
                        }
                    });
                });
            });
        </script>
    </div>

</body>

</html>