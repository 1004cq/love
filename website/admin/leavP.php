<?php
session_start();
include_once 'Nav.php';
$leavSet = "select * from leavSet order by id desc";
$Set = mysqli_query($connect, $leavSet);
$Setinfo = mysqli_fetch_array($Set);
?>
<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">前端留言页配置</h4>

                <form class="needs-validation" action="leavPPost.php" method="post" novalidate>

                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label>留言祝福开关</label>
                            <select class="form-control" name="MsgSwitch">
                                <option value="1" <?php echo $Setinfo['MsgSwitch'] == 1 ? "selected" : '' ?>>开启</option>
                                <option value="2" <?php echo $Setinfo['MsgSwitch'] == 2 ? "selected" : '' ?>>关闭</option>
                            </select>
                            <span class="help-block"><small>关闭后前端隐藏留言模块 留言亦不再开放写入接口</small></span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label>留言审核开关</label>
                            <select class="form-control" name="MsgVerify">
                                <option value="1" <?php echo $Setinfo['MsgVerify'] == 1 ? 'selected' : '' ?>>开启</option>
                                <option value="2" <?php echo $Setinfo['MsgVerify'] == 2 ? 'selected' : '' ?>>关闭</option>
                            </select>
                            <span class="help-block"><small>关闭后前端游客留言则无需管理员审核</small></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label>留言截取开关</label>
                            <select class="form-control" name="CutOffSwitch">
                                <option value="1" <?php echo $Setinfo['CutOffSwitch'] == 1 ? 'selected' : '' ?>>开启</option>
                                <option value="2" <?php echo $Setinfo['CutOffSwitch'] == 2 ? 'selected' : '' ?>>关闭</option>
                            </select>
                            <span class="help-block"><small>关闭后前端截取则不再生效 展示留言页中的所有留言数据</small></span>
                        </div>

                        <div class="form-group mb-3 col-lg-6">
                            <label for="validationCustom05"><b class="required_marker">*</b>显示最新N条留言 </label>
                            <input type="number" class="form-control" id="validationCustom05"
                                placeholder="请输入需要截取的数量 如：20" name="jiequ" value="<?php echo $Setinfo['jiequ'] ?>"
                                required>
                            <span class="help-block"><small>格式为纯数字 截取数量过多会影响加载页面速度 </small></span>
                        </div>

                    </div>


                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label>留言表情模块开关</label>
                            <select class="form-control" name="EmojiSwitch">
                                <option value="1" <?php echo $Setinfo['EmojiSwitch'] == 1 ? 'selected' : '' ?>>开启</option>
                                <option value="2" <?php echo $Setinfo['EmojiSwitch'] == 2 ? 'selected' : '' ?>>关闭</option>
                            </select>
                            <span class="help-block"><small>开启后前端留言祝福则显示表情选择模块</small></span>
                        </div>

                        <div class="form-group mb-3 col-lg-6">
                            <label for="validationCustom05"><b class="required_marker">*</b>限制留言次数 </label>
                            <input type="number" class="form-control" id="validationCustom05"
                                placeholder="请输入需要限制的次数 如：5" name="leav_day_num"
                                value="<?php echo $Setinfo['leav_day_num'] ?>" required>
                            <span class="help-block"><small>限制一个游客一天内最多留言N次 </small></span>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="validationCustom05"><b class="required_marker">*</b>拦截违禁词 </label>
                        <textarea name="lanjiezf" class="form-control" rows="5"
                            placeholder="请输入违禁词"><?php echo $Setinfo['lanjiezf'] ?></textarea>
                        <span class="help-block"><small>可以填入需要拦截的词语 无需分隔符 </small></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="validationCustom05"><b class="required_marker">*</b>自定义随机头像URL</label>
                        <textarea name="anonymousArr" class="form-control" rows="5"
                            placeholder="请输入随机头像URL地址"><?php echo $Setinfo['anonymousArr'] ?></textarea>
                        <span class="help-block"><small>一行一个URL链接 请按格式填写 本地上传自动写入 </small></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="validationCustom05">自定义随机祝福文案</label>
                        <textarea name="randomWord" class="form-control" rows="5"
                            placeholder="请输入随机祝福文案"><?php echo $Setinfo['randomWord'] ?></textarea>
                        <span class="help-block"><small>一行一条祝福文案 不支持API链接 请填写纯文本格式 留空则不显示按钮 </small></span>
                    </div>

                    <div class="form-group mb-3 text_right">
                        <button class="btn btn-primary" type="button" id="leavPPost">提交修改</button>
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