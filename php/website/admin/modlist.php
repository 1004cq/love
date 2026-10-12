<?php
session_start();
include_once 'Nav.php';
$id = max(1, (int)($_GET['id'] ?? 0));
$list_data = "SELECT * FROM lovelist WHERE id = $id";
$query = mysqli_query($connect, $list_data);
$res_data = mysqli_fetch_array($query);
$icon = $res_data['icon'];
$name = $res_data['eventname'];
$imgurl = $res_data['imgurl'];
?>

<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">修改事件—— <?php echo $name ?></h4>

                <form class="needs-validation" action="listupda.php" method="post" onsubmit="return check()" novalidate>
                    <div class="form-group mb-3">
                        <label for="validationCustom01"><b class="required_marker">*</b>事件标题</label>
                        <input type="text" name="eventname" class="form-control" id="validationCustom01"
                            placeholder="请输入事件标题" value="<?php echo $name ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom01">完成状态</label>
                        <input type="checkbox" name="icon" id="switch3" <?php if ($icon) { ?> checked<?php } ?>
                            data-switch="success" value="<?php if ($icon) { ?> 1<?php } else { ?>0<?php } ?>"
                            onclick="myOnClickHandler(this)">
                        <label style="display:block;" for="switch3" data-on-label="Yes" data-off-label="No"></label>
                    </div>
                    <div class="form-group mb-3" id="img_url"
                        style="display: <?php if ($icon) { ?> block<?php } else { ?>none<?php } ?>">
                        <label for="validationCustom01"><b class="required_marker">*</b>图片地址</label>
                        <textarea id="src_url" name="imgurl"  class="form-control"  rows="5"
                            placeholder="请输入图片地址（没有则留空）"><?php echo $imgurl ?></textarea>
                        <span class="help-block"><small>一行一个URL链接 请按格式填写。本地上传自动写入 </small></span>
                    </div>
                    <div class="form-group mb-3 text_right">
                        <input name="id" value="<?php echo $id ?>" type="hidden">
                        <button class="btn btn-primary" type="button" id="listupda">提交修改</button>
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
        let title = document.getElementsByName('eventname')[0].value.trim();
        if (title.length == 0) {
            alert("事件不能为空");
            return false;
        }
    }
    <?php if (!$icon) { ?>
        $("#upload-form").hide();
    <?php } ?>
    function myOnClickHandler(obj) {
        var input = document.getElementById("switch3");
        var imgurl = document.getElementById("img_url")
        console.log(input);
        if (obj.checked) {
            input.setAttribute("value", 1);
            imgurl.style.display = "block";
            $("#upload-form").fadeIn();
        } else {
            input.setAttribute("value", 0);
            imgurl.style.display = "none";
            $("#upload-form").fadeOut();
        }
    }

</script>
<?php
include_once 'Footer.php';
?>
</body>

</html>
