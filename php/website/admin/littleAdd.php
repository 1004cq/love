<?php
session_start();
include_once 'Nav.php';
$time = date("Y-m-d H:i");
?>


<link href="/admin/editormd/css/editormd.css?=v<?php echo $versionCode ?>" rel="stylesheet">
<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">新增文章</h4>

                <form class="needs-validation" action="littleAddPost.php" method="post">
                    <div class="form-group mb-3">
                        <label for="validationCustom01">是否开启加密访问</label>
                        <input type="checkbox" name="encryptionSwitch" id="encryptionSwitch" data-switch="success"
                            value="0" onclick="openPrivacy(this)">
                        <label style="display:block;" for="encryptionSwitch" data-on-label="Yes"
                            data-off-label="No"></label>
                    </div>
                    <div class="elementHiding" id="OpenPw">
                        <div class="form-group mb-3">
                            <label for="validationCustom05"><b class="required_marker">*</b>自定义访问密码</label>
                            <input type="text" class="form-control" placeholder="请输入自定义访问密码" name="PasswordSee" value="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="validationCustom01"><b class="required_marker">*</b>发布者Name</label>
                            <select class="form-control" id="example-select" name="articlename">
                                <option value="<?php echo $text['boy'] ?>" <?php if ($User_InfoNU['name'] == $text['boy']) {
                                       echo "selected";
                                   } ?>>
                                    <?php echo $text['boy'];
                                    if ($User_InfoNU['name'] == $text['boy']) {
                                        echo "（当前登录）";
                                    } ?>
                                </option>
                                <option value="<?php echo $text['girl'] ?>" <?php if ($User_InfoNU['name'] == $text['girl']) {
                                       echo "selected";
                                   } ?>>
                                    <?php echo $text['girl'];
                                    if ($User_InfoNU['name'] == $text['girl']) {
                                        echo "（当前登录）";
                                    } ?>
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="validationCustom01"><b class="required_marker">*</b>日期</label>
                            <input class="form-control" id="example-date" type="datetime-local" name="little_day"
                                value="<?php echo $time ?>" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="validationCustom01"><b class="required_marker">*</b>标题</label>
                        <input type="text" class="form-control" id="validationCustom01" placeholder="请输入标题"
                            name="articletitle" required>
                    </div>

                    <div id="test-editor">
                        <textarea name="articletext"></textarea>
                    </div>


                    <input type="hidden" class="form-control" id="validationCustom01" name="city_ip"
                        value="<?php echo get_ips($ip_if) ?>">
                    <div class="form-group mb-3 text_right">
                        <button class="btn btn-primary" type="button" id="littleAddPost">发布文章</button>
                    </div>
                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>


<div class="ImageUploadDiy_Btn"><i class="dripicons-cloud-upload"></i></div>


<script src="/admin/editormd/editormd.js"></script>
<script type="text/javascript">
    $(function () {
        $("#OpenPw").hide()
        var userAgent = navigator.userAgent;
        userAgent = userAgent.toLowerCase();
        var isMobile = "false";
        if (userAgent.indexOf("android") >= 0 || userAgent.indexOf("mini 9.5") >= 0 || userAgent.indexOf("vx1000") >= 0 || userAgent.indexOf("lge") >= 0 || userAgent.indexOf("m800") >= 0 || userAgent.indexOf("3g_t") >= 0 || userAgent.indexOf("windows ce") >= 0 || userAgent.indexOf("opera mobi") >= 0 || userAgent.indexOf("windows ce; smartphone;") >= 0 || userAgent.indexOf("windows ce; iemobile") >= 0 || userAgent.indexOf("ipod") >= 0 || userAgent.indexOf("ipad") >= 0 || userAgent.indexOf("iphone") >= 0 || userAgent.indexOf("opera mini") >= 0 || userAgent.indexOf("blackberry") >= 0 || userAgent.indexOf("palm os") >= 0 || userAgent.indexOf("palm") >= 0 || userAgent.indexOf("mobile") >= 0 || userAgent.indexOf("hiptop") >= 0 || userAgent.indexOf("avantgo") >= 0 || userAgent.indexOf("fennec") >= 0 || userAgent.indexOf("plucker") >= 0 || userAgent.indexOf("xiino") >= 0 || userAgent.indexOf("blazer") >= 0) {
            isMobile = "true";
        }
        if (isMobile == "true") {
            var editor = editormd("test-editor", {
                watch: false,
                htmlDecode: false,
                path: "/admin/editormd/lib/"
            });
        } else {
            var editor = editormd("test-editor", {
                watch: true,
                htmlDecode: true,
                path: "/admin/editormd/lib/"
            });
        }

    });


</script>

<?php
include_once 'Footer.php';
?>
</body>

</html>