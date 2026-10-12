<?php
session_start();
include_once 'Nav.php';
$Mail_sql = "select * from MailSet";
$Mail_res = mysqli_query($connect, $Mail_sql);
$Mail_data = mysqli_fetch_array($Mail_res);
?>
<div class="row">

    <div class="col-xl-9">
        <div class="card">
            <div class="card-body">

                <h4 class="header-title mb-3">邮箱信息配置</h4>

                <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                    <li class="nav-item">
                        <a href="#home1" data-toggle="tab" aria-expanded="true" class="nav-link rounded-0 active">
                            <i class="dripicons-inbox d-lg-none d-block mr-1"></i>
                            <span class="d-none d-lg-block">发信配置</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#profile1" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                            <i class="mdi  dripicons-user d-lg-none d-block mr-1"></i>
                            <span class="d-none d-lg-block">接收配置</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane show active" id="home1">
                        <form class="needs-validation" action="" method="post" onsubmit="return check()" novalidate>
                            <div class="row">
                                <div class="form-group mb-3 col-lg-6" style="text-align:center">
                                    <label for="validationCustom01">留言邮件提醒</label>
                                    <input type="checkbox" name="SendingSwitch" id="SendingSwitch" <?php if ($Mail_data['SendingSwitch'] == "true") { ?> checked<?php } ?>
                                        data-switch="primary" value="<?php echo $Mail_data['SendingSwitch'] ?>"
                                        onclick="MailSwitch(this)">
                                    <label style="display: block;margin: auto;" for="SendingSwitch" data-on-label="Yes"
                                        data-off-label="No"></label>
                                </div>
                                <div class="col-lg-6"></div>
                                <div class="form-group mb-3 col-lg-6" style="text-align:center">
                                    <label for="validationCustom01">登录邮件提醒</label>
                                    <input type="checkbox" name="LoginMailSwitch" id="LMailSwitch" <?php if ($Mail_data['LoginMailSwitch']) { ?> checked<?php } ?> data-switch="primary"
                                        value="<?php echo $Mail_data['LoginMailSwitch'] ?>"
                                        onclick="OnLMailSwitch(this)">
                                    <label style="display: block;margin: auto;" for="LMailSwitch" data-on-label="Yes"
                                        data-off-label="No"></label>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="example-helping">管理员登录邮件发送频率</label>
                                    <input type="text" name="LoginSendlMinute" id="example-helping" class="form-control" value="<?php echo $Mail_data['LoginSendlMinute'] ?>"
                                        placeholder="请输入时间（分钟）">
                                    <span class="help-block"><small>设置后则在指定时间内不重复发送邮件通知 #单位为（分钟） </small></span>
                                </div>
                            </div>
                            
                            <hr class="User_Hr">
                            <div class="SendSW" <?php if ($Mail_data['SendingSwitch'] == "false" && !$Mail_data['LoginMailSwitch']) { ?>
                                    style="filter: blur(20px);transition: 0.1s all;<?php } ?>">
                                <div class="form-group mb-3">
                                    <label for="validationCustom01"><b class="required_marker">*</b>自定义标题</label>
                                    <input type="text" class="form-control" placeholder="请输入发信标题" name="title"
                                        value="<?php echo $Mail_data['title'] ?>" required>
                                    <div class="alert alert-info" role="alert" style="margin-top:0.3rem;">
                                        <i class="dripicons-information mr-2"></i>模版参考： 您在 <strong>「xxx」</strong>
                                        情侣小站留言的内容
                                        管理员已审核通过 （其中“「xxx」” 为自定义标题）
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom01"><b class="required_marker">*</b>自定义发件人昵称</label>
                                    <input type="text" class="form-control" placeholder="请输入发件人昵称" name="from_name"
                                        value="<?php echo $Mail_data['from_name'] ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom01"><b class="required_marker">*</b>SMTP服务器地址</label>
                                    <input type="text" class="form-control" placeholder="请输入SMTP服务器地址" name="host"
                                        value="<?php echo $Mail_data['host'] ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom01">是否开启SSL加密</label>
                                    <input type="checkbox" name="mail_ssl" id="ssl" <?php if ($Mail_data['mail_ssl'] == "true") { ?> checked<?php } ?> data-switch="success"
                                        value="<?php echo $Mail_data['mail_ssl'] ?>" onclick="myOnClickHandler(this)">
                                    <label style="display:block;" for="ssl" data-on-label="Yes"
                                        data-off-label="No"></label>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom02"><b class="required_marker">*</b>端口</label>
                                    <input type="text" class="form-control" placeholder="请填写发信端口 通常为25 ssl加密的为465"
                                        name="port" value="<?php echo $Mail_data['port'] ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom02"><b class="required_marker">*</b>邮箱用户名</label>
                                    <input type="text" class="form-control" placeholder="请填写发信邮箱用户名" name="mail_user"
                                        value="<?php echo $Mail_data['mail_user'] ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom02"><b class="required_marker">*</b>邮箱密码</label>
                                    <input type="password" class="form-control" placeholder="请填写发信邮箱密码 QQ邮箱的为授权码"
                                        name="mail_pw" value="<?php echo $Mail_data['mail_pw'] ?>" required>
                                </div>
                                <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show"
                                    role="alert">

                                    <strong> Info：</strong>配置邮件信息保存后请点击 发送测试 判断是否可以正常发送测试邮件
                                </div>
                                <button type="button" id="click_test" class="btn btn-secondary btn-rounded">
                                    <i class="dripicons-clockwise"></i> 发送测试</button>
                            </div>
                        </form>

                    </div>
                    <div class="tab-pane" id="profile1">

                        <form class="needs-validation" action="loveadminPost.php" method="post" novalidate>
                            <div class="SendSW" <?php if ($Mail_data['SendingSwitch'] == "false" && !$Mail_data['LoginMailSwitch']) { ?>
                                    style="filter: blur(20px);transition: 0.1s all;<?php } ?>">
                                <div class="alert alert-info bg-info text-white border-0" role="alert">
                                    <strong> Info：</strong>请按提示填写 男主女主的接收信息填写顺序不可乱
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom01"><b class="required_marker">*</b>接收邮箱 男主</label>
                                    <input type="text" class="form-control" placeholder="请填写接收邮件的邮箱" name="admin_mail"
                                        value="<?php echo $Mail_data['admin_mail'] ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom02"><b class="required_marker">*</b>自定义 男主 Name</label>
                                    <input type="text" class="form-control" placeholder="自定义接收者名字" name="admin_name"
                                        value="<?php echo $Mail_data['admin_name'] ?>" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="validationCustom01"><b class="required_marker">*</b>接收邮箱 女主</label>
                                    <input type="text" class="form-control" placeholder="请填写接收邮件的邮" name="user_mail"
                                        value="<?php echo $Mail_data['user_mail'] ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom02"><b class="required_marker">*</b>自定义 女主 Name</label>
                                    <input type="text" class="form-control" placeholder="自定义接收者名字" name="user_name"
                                        value="<?php echo $Mail_data['user_name'] ?>" required>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="form-group mb-3 text_right">
                        <button class="btn btn-primary" type="button" id="Mail_Post">提交修改</button>
                    </div>
                </div>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->

</div>

<script>

    //测试邮件发送
    $("#click_test").click(function () {
        let load = `<div class="spinner-border spinner-border-sm" role="status" style="margin: 0.2rem 0.5rem;"></div>发送中...`;
        let success = `<i class="dripicons-checkmark"></i>发送成功`;
        $("#click_test").html(load)
        $("#click_test").attr("disabled", "disabled");
        $.ajax({
            url: "Test_Mail.php",
            data: {
                code: "test_mail",
                manyPeople: true,
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == "success") {
                    toastr["success"]("测试邮件发送成功！", "LG_NewUi");
                    $("#click_test").html(success)
                    $("#click_test").removeAttr("disabled");
                } else if (res == "fail") {
                    toastr["error"]("测试邮件发送失败 请重试！", "LG_NewUi");
                    $("#click_test").text("发送失败")
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        }
        )
    })

    //留言邮件提醒开关
    function MailSwitch(obj) {
        var send = document.getElementById("SendingSwitch");

        if (obj.checked) {
            $(".SendSW").css("filter", "blur(0px)");
            send.setAttribute("value", "true");
        } else {
            send.setAttribute("value", "false");
            let LeavMailSwitch = $("#SendingSwitch").val();
            let LoginMailSwitch = $("#LMailSwitch").val();
            if ((LeavMailSwitch == "false") && LoginMailSwitch == 0) {
                $(".SendSW").css("filter", "blur(20px)");
            }

        }
    }

    //登录邮件提醒开关
    function OnLMailSwitch(obj) {
        var Loginsend = document.getElementById("LMailSwitch");

        if (obj.checked) {
            $(".SendSW").css("filter", "blur(0px)");
            Loginsend.setAttribute("value", 1);
        } else {
            Loginsend.setAttribute("value", 0);
            let LeavMailSwitch = $("#SendingSwitch").val();
            let LoginMailSwitch = $("#LMailSwitch").val();
            if ((LeavMailSwitch == "false") && LoginMailSwitch == 0) {
                $(".SendSW").css("filter", "blur(20px)");
            }
        }
    }

    //ssl开关
    function myOnClickHandler(obj) {
        var ssl = document.getElementById("ssl");

        if (obj.checked) {
            ssl.setAttribute("value", "true");
        } else {
            ssl.setAttribute("value", "false");
        }
    }


</script>
<style>
    .row {
        justify-content: center;
    }
</style>

<?php
include_once 'Footer.php';
?>

</body>

</html>