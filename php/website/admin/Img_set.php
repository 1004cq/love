<?php
session_start();
include_once 'Nav.php';
$imgSet = "select * from diySet";
$Set = mysqli_query($connect, $imgSet);
$Setinfo = mysqli_fetch_array($Set);
?>
<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">相册页自定义配置</h4>

                <form class="needs-validation" action="imgdiy_Post.php" method="post" novalidate>

                    <script>
                        function myOnClickHandler(obj) {
                            var input = document.getElementById("aiv_name");
                            if (obj.checked) {
                                input.setAttribute("value", "1");
                            } else {
                                input.setAttribute("value", "0");
                            }
                        }

                        function RandomSortClick(obj) {
                            let RandomSortInput = document.getElementById("RandomSort");
                            if (obj.checked) {
                                RandomSortInput.setAttribute("value", "1");
                            } else {
                                RandomSortInput.setAttribute("value", "0");
                            }
                        }
                    </script>

                    <div class="row">
                        <div class="form-group mb-3 col-lg-6">
                            <label for="validationCustom01">是否显示头像昵称模块</label>
                            <input type="checkbox" name="aiv_name_show" id="aiv_name" <?= $Setinfo['aiv_name_show'] ? 'checked' : '' ?> data-switch="success"
                                value="<?php echo $Setinfo['aiv_name_show'] ? 1 : 0 ?>"
                                onclick="myOnClickHandler(this)">
                            <label style="display:block;" for="aiv_name" data-on-label="Yes"
                                data-off-label="No"></label>
                        </div>

                        <div class="form-group mb-3 col-lg-6">
                            <label for="validationCustom01">是否随机排序子相册媒体</label>
                            <input type="checkbox" name="RandomSort" id="RandomSort" <?= $Setinfo['RandomSort'] ? 'checked' : '' ?> data-switch="success"
                                value="<?php echo $Setinfo['RandomSort'] ? 1 : 0 ?>"
                                onclick="RandomSortClick(this)">
                            <label style="display:block;" for="RandomSort" data-on-label="Yes"
                                data-off-label="No"></label>
                            <span class="help-block"><small>开启后子相册媒体则每次刷新都为随机排序 关闭后则自动使用媒体添加时间排序</small></span>
                        </div>
                    </div>


                    <div class="form-group mb-3">
                        <label for="validationCustom05"><b class="required_marker">*</b>自定义Loading加载GIF图
                        </label>
                        <input type="text" class="form-control" id="validationCustom05" placeholder="请输入URL链接"
                            name="diy_load" value="<?php echo $Setinfo['diy_load'] ?>" required>
                    </div>

                    <div class="row">
                        <div class="form-group mb-3 col-lg-6">
                            <label for="validationCustom05">自定义相册封面大图高度 PC</label>
                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                <input type="text" class="form-control" placeholder="请输入自定义相册封面大图高度"
                                    name="pcPhotoCoverHeight" value="<?php echo $diy['pcPhotoCoverHeight'] ?>" required>
                                <span class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                    <span class="input-group-text">单位</span>
                                </span>
                            </div>
                            <span class="help-block"><small>支持自定义单位 如vh 根据视口高度显示 100vh即占满全屏 留空则不设置</small></span>
                        </div>
                        <div class="form-group mb-3 col-lg-6">
                            <label for="validationCustom05">自定义相册封面大图高度 移动端</label>
                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                <input type="text" class="form-control" placeholder="请输入自定义相册封面大图高度"
                                    name="mobilePhotoCoverHeight" value="<?php echo $diy['mobilePhotoCoverHeight'] ?>"
                                    required>
                                <span class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                    <span class="input-group-text">单位</span>
                                </span>
                            </div>
                            <span class="help-block"><small>支持自定义单位 移动端适当调整 高度占满全屏会显的不太协调 留空则不设置</small></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group mb-3 col-lg-6">
                            <label for="validationCustom05">自定义相册图片最小高度 PC</label>
                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                <input type="text" class="form-control" placeholder="请输入自定义相册图片最小高度"
                                    name="pcImgMaxHeight" value="<?php echo $diy['pcImgMaxHeight'] ?>" required>
                                <span class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                    <span class="input-group-text">单位</span>
                                </span>
                            </div>
                            <span class="help-block"><small>由于部分图片尺寸为横屏 存在无法点击查看大图 请自行设置调整</small></span>
                        </div>
                        <div class="form-group mb-3 col-lg-6">
                            <label for="validationCustom05">自定义相册图片最小高度 移动端</label>
                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                <input type="text" class="form-control" placeholder="请输入自定义相册图片最小高度"
                                    name="mobileImgMaxHeight" value="<?php echo $diy['mobileImgMaxHeight'] ?>" required>
                                <span class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                    <span class="input-group-text">单位</span>
                                </span>
                            </div>
                            <span class="help-block"><small>由于部分图片尺寸为横屏 存在无法点击查看大图 请自行设置调整</small></span>
                        </div>
                    </div>

                    <div class="form-group mb-3 text_right">
                        <button class="btn btn-primary" type="button" id="imgdiy_Post">提交修改</button>
                    </div>
                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<?php
include_once 'Footer.php';
?>

</body>

</html>