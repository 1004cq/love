<?php
session_start();
include_once 'Nav.php';
$inv_date = date("Y-m-d");
?>

<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">新增相册</h4>

                <form class="needs-validation" action="ImgAddPost.php" method="post" onsubmit="return check()"
                    novalidate>

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
                            <label for="validationCustom01"><b class="required_marker">*</b>上传者Name</label>
                            <select class="form-control" id="example-select" name="upload_name">
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
                            <input class="form-control" id="example-date" type="date" name="imgDatd"
                                class="form-control" placeholder="日期" value="<?php echo $inv_date ?>" required>
                        </div>

                    </div>

                    <div class="form-group mb-3">
                        <label for="validationCustom01"><b class="required_marker">*</b>相册描述<span
                                class="margin_left badge badge-success-lighten">尽量控制在25个字符以内 </span></label>
                        <input name="imgText" type="text" class="form-control" placeholder="请输入图片描述" value="" required>
                    </div>

                    <input type="hidden" name="img_code" value="<?php echo $date = date("YmdHis"); ?>">
                    <div class="form-group mb-3" id="img_url">
                        <label for="validationCustom01"><b class="required_marker">*</b>相册封面大图URL</label>
                        <input type="text" id="src_url" name="imgUrl" class="form-control" placeholder="请输入图片URL地址"
                            value="" required>
                    </div>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="dripicons-warning mr-2 font-18"></i>
                        <strong>温馨提示 - </strong> 当前为新增相册封面 只允许上传一张图片 封面不支持视频！
                    </div>
                    <div class="form-group mb-3 text_right">
                        <button class="btn btn-primary" type="button" id="ImgAddPost">新增相册</button>
                    </div>
                </form>

                <?php
                include_once "picture_upload.php";
                ?>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<?php
include_once 'Footer.php';
?>

</body>

</html>