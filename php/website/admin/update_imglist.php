<?php
session_start();
include_once 'Nav.php';
$id = max(1, (int)($_GET['id'] ?? 0));
include_once 'connect.php';
$Photo_data = "select * from Photolist WHERE id=$id limit 1";
$res_data = mysqli_query($connect, $Photo_data);
$photo_info = mysqli_fetch_array($res_data);

?>

<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_16">
                    <?php echo $photo_info['photo_text'] ?>
                </h4>

                <form class="needs-validation" action="Photoup_Post.php" method="post" onsubmit="return check()"
                    novalidate>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="validationCustom01">日期</label>
                            <input class="form-control" id="example-date" type="date" name="photoDatd"
                                class="form-control" placeholder="日期" value="<?php echo $photo_info['photo_date'] ?>"
                                required>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="validationCustom01">拍摄者By—Name</label>
                            <select class="form-control" id="byname" name="by_name">
                                <option value="<?php echo $text['boy'] ?>" <?php if ($text['boy'] == $photo_info['photo_byname']) { ?>selected<?php } ?>>
                                    <?php echo $text['boy'] ?>
                                </option>
                                <option value="<?php echo $text['girl'] ?>" <?php if ($text['girl'] == $photo_info['photo_byname']) { ?>selected<?php } ?>>
                                    <?php echo $text['girl'] ?>
                                </option>
                                <option value="0">自定义Name</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-4" id="diy_name">
                            <label for="validationCustom01" style="color:#ff5076;">自定义拍摄者Name</label>
                            <input type="text" id="day_n" class="form-control" placeholder="请输入图片描述"
                                style="border-color:#ff5076;" value="<?php echo $photo_info['photo_byname'] ?>"
                                required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom01"><b class="required_marker">*</b>图片描述</label>
                        <input name="photoText" type="text" class="form-control" placeholder="请输入图片描述"
                            value="<?php echo $photo_info['photo_text'] ?>" required>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label for="validationCustom01"><b class="required_marker">*</b>URL类型</label>
                            <select class="form-control" name="photo_type">
                                <option value="0">图片</option>
                                <option value="1" <?php if ($photo_info['photo_type']) { ?>selected<?php } ?>>视频
                                </option>
                            </select>
                            <span class="help-block"><small>上传后会默认判断媒体类型 如判断有误 请手动选择媒体URL类型</small></span>
                        </div>

                        <?php if ($photo_info['photo_type']): ?>
                        <div class="form-group col-lg-8 upPhotoCover" id="isVideoCoverInput">
                            <label for="validationCustom01"><b class="required_marker">*</b>视频封面URL直链</label>
                            <input type="text" name="VideoCover" id="VideoCoverUrl" class="form-control"
                                placeholder="请输入视频封面URL" value="<?php echo $photo_info['VideoCover'] ?>" required>
                        </div>
                      <?php endif; ?>          
                    </div>

                    <div class="form-group mb-3" id="img_url">
                        <label for="validationCustom01">图片URL/视频URL
                        <input type="text" id="src_url" name="photoUrl" class="form-control" placeholder="请输入图片/视频URL地址"
                            value="<?php echo $photo_info['photo_url'] ?>" required>
                    </div>
                    <div class="form-group mb-3 text_right">
                        <input name="id" value="<?php echo $id ?>" type="hidden">
                        <button class="btn btn-primary" type="button" id="Photoup_Post">修改相册信息</button>
                    </div>
                </form>

                <span class="row_line_x"></span>
                <?php
                include_once "picture_upload.php";
                ?>


            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>



<script>
    $(document).ready(function () {
        let newImg = $("<img>");
        newImg.attr("src", "<?php echo $photo_info['photo_url'] ?>");
        $("#image-preview").show().append(newImg);
        $(".dripicons-cloud-upload").hide();
        $(".img_info_s .file_name").hide();
        $(".img_info_s .file_size").hide();
        $('#file-input').on('change', function () {
            $(".img_info_s .file_name").show();
            $(".img_info_s .file_size").show();
        })
    });

    $('.upPhotoCover').show();

    $("#diy_name").hide('slow', 'linear');
    $('#byname').change(() => {
        var by_name = $('select option:selected').val();
        if (by_name == 0) {
            $("#diy_name").hide('slow', 'linear');
            $("#diy_name").show('slow', 'linear');
        } else {
            $("#diy_name").hide('slow', 'linear');
        }
    });

    $("#day_n").blur(function () {
        var name = $("#day_n").val();
        $('select option:selected').val(name)
    });
</script>
<?php
include_once 'Footer.php';
?>
</body>

</html>
