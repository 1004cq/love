<?php
session_start();
include_once 'Nav.php';
?>

<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">新增事件</h4>

                <form class="needs-validation" action="listaddPost.php" method="post" onsubmit="return check()"
                    novalidate>
                    <div class="form-group mb-3">
                        <label for="validationCustom01"><b class="required_marker">*</b>事件标题</label>
                        <input name="eventname" type="text" class="form-control" id="validationCustom01"
                            placeholder="请输入事件标题" value="" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom01"><b class="required_marker">*</b>完成状态</label>
                        <input type="checkbox" name="icon" id="switch3" value="1" data-switch="success"
                            onclick="myOnClickHandler(this)" checked>
                        <label id="switchurl" style="display:block;" for="switch3" data-on-label="Yes"
                            data-off-label="No"></label>
                    </div>
                    <div class="form-group mb-3" id="img_url">
                        <label for="validationCustom01"><b class="required_marker">*</b>图片URL地址</label>
                        <textarea id="src_url" name="img"  class="form-control"  rows="5"
                            placeholder="请输入图片地址（没有则留空）"></textarea>
                        <span class="help-block"><small>一行一个URL链接 请按格式填写。本地上传自动写入 </small></span>
                    </div>
                    <div class="form-group mb-3 text_right">
                        <button class="btn btn-primary" type="button" id="listaddPost">新增事件</button>
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

    function myOnClickHandler(obj) {
        var input = document.getElementById("switch3");
        var imgurl = document.getElementById("img_url")
        console.log(input);
        if (obj.checked) {
            input.setAttribute("value", "1");
            imgurl.style.display = "block";
            $("#upload-form").fadeIn();
        } else {
            input.setAttribute("value", "0");
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