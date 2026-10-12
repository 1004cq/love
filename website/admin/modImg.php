<?php
session_start();
include_once 'Nav.php';
$id = $_GET['id'];
include_once 'connect.php';
$loveImg = "select * from loveImg WHERE id=$id limit 1";
$resImg = mysqli_query($connect, $loveImg);
$Imglist = mysqli_fetch_array($resImg);

?>

<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">修改相册—— ID：<?php echo $Imglist['id'] ?></h4>

                <form class="needs-validation" action="ImgUpdaPost.php" method="post" onsubmit="return check()"
                      novalidate>

                      <div class="form-group mb-3">
                        <label for="validationCustom01">是否开启加密访问</label>
                        <input type="checkbox" name="encryptionSwitch" id="encryptionSwitch" data-switch="success"
                            value="<?php echo $Imglist['encryptionSwitch'] ?>" onclick="openPrivacyUP(this)" <?php if ($Imglist['encryptionSwitch']) { echo"checked"; } ?>>
                        <label style="display:block;" for="encryptionSwitch" data-on-label="Yes"
                            data-off-label="No"></label>
                    </div>
                    <div class="<?php if (!$Imglist['encryptionSwitch']) { echo "elementHiding"; }  ?>" id="OpenPw">
                        <div class="form-group mb-3">
                            <label for="validationCustom05"><b class="required_marker">*</b>自定义访问密码</label>
                            <input type="text" class="form-control" placeholder="请输入自定义访问密码" name="PasswordSee" value="<?php echo $Imglist['PasswordSee'] ?>" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="validationCustom01"><b class="required_marker">*</b>日期</label>
                        <input class="form-control col-sm-4" id="example-date" type="date" name="imgDatd" class="form-control" placeholder="日期" value="<?php echo $Imglist['imgDatd'] ?>" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="validationCustom01"><b class="required_marker">*</b>图片描述</label>
                        <input name="imgText" type="text" class="form-control" placeholder="请输入图片描述" value="<?php echo $Imglist['imgText'] ?>" required>
                    </div>

                    <div class="form-group mb-3" id="img_url">
                        <label for="validationCustom01"><b class="required_marker">*</b>图片URL</label>
                        <input type="text" id="src_url"  name="imgUrl" class="form-control" placeholder="请输入图片URL地址" value="<?php echo $Imglist['imgUrl'] ?>" required>
                    </div>
                    <div class="form-group mb-3 text_right">
                        <input name="id" value="<?php echo $id ?>" type="hidden">
                        <button class="btn btn-primary" type="button" id="ImgUpdaPost">修改相册</button>
                    </div>
                </form>

                <?php
                include_once "picture_upload.php";
                ?>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>


<script>
    function check() {
        let title = document.getElementsByName('imgText')[0].value.trim();
        if (title.length == 0) {
            alert("描述不能为空");
            return false;
        }
    }

    $(document).ready(function () {
        let newImg = $("<img>");
        newImg.attr("src", "<?php echo $Imglist['imgUrl'] ?>");
        $("#image-preview").show().append(newImg);
        $(".dripicons-cloud-upload").hide();
        $(".img_info_s .file_name").hide();
        $('#file-input').on('change', function () {
            $(".img_info_s .file_name").show();
        })
    });
</script>
<?php
include_once 'Footer.php';
?>
</body>
</html>