<?php
session_start();
include_once 'Nav.php';
$sql = "select * from login";
$result_login = mysqli_query($connect, $sql);
?>
<div class="row">

    <div class="col-xl-9">
        <div class="card">
            <div class="card-body">

                <h4 class="header-title mb-3">全局相关配置</h4>

                <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                    <li class="nav-item">
                        <a href="#home1" data-toggle="tab" aria-expanded="true" class="nav-link rounded-0 active">
                            <i class=" mdi mdi-auto-fix d-lg-none d-block mr-1"></i>
                            <span class="d-none d-lg-block">个性化设置</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#profile1" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                            <i class="mdi mdi-animation d-lg-none d-block mr-1"></i>
                            <span class="d-none d-lg-block">前端相关设置</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#settings1" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                            <i class="mdi mdi-account-multiple-check d-lg-none d-block mr-1"></i>
                            <span class="d-none d-lg-block">后端用户设置</span>
                        </a>
                    </li>
                </ul>
                <form class="needs-validation" action="userPost.php" method="post" novalidate>
                <div class="tab-content">
                    <div class="tab-pane show active" id="home1">
                        <form class="needs-validation" action="userPost.php" method="post" novalidate>
                            <div class="form-group">
                                <label for="validationCustom01">是否开启前端加载动画（元素渐显）</label>
                                <select class="form-control" id="example-select" name="Webanimation">
                                    <option value="1" <?php if ($text['Animation'] == "1") { ?> selected <?php } ?>>开启
                                    </option>
                                    <option value="2" <?php if ($text['Animation'] == "2") { ?> selected <?php } ?> >关闭
                                    </option>
                                </select>
                            </div>
                            <hr class="User_Hr">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">开启前端所有页面维护</label>
                                <input type="checkbox" name="siteMaintenance" id="siteMaintenance" 
                                <?php if ($diy['StationMaintenanceSwitch']) { echo"checked"; } ?>
                                       data-switch="success"
                                       value="<?php if ($diy['StationMaintenanceSwitch']) { ?> 1<?php } else { ?>0<?php } ?>"
                                       onclick="OnSiteMaintenance(this)">
                                <label style="display:block;" for="siteMaintenance" data-on-label="Yes" data-off-label="No"></label>
                            </div>
                            <div class="<?php if (!$diy['StationMaintenanceSwitch']){echo "elementHiding"; } ?>" id="Maintenance">
                                <div class="form-group mb-3">
                                    <label for="validationCustom05"><b class="required_marker">*</b>自定义维护页标题</label>
                                    <input type="text" class="form-control" placeholder="请输入自定义标题"
                                           name="SmTitie" value="<?php echo $diy['SmTitie'] ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom05"><b class="required_marker">*</b>自定义逐字显示文本</label>
                                    <input type="text" class="form-control" placeholder="请输入逐字显示文本"
                                           name="SmText" value="<?php echo $diy['SmText'] ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom05"><b class="required_marker">*</b>自定义背景图</label>
                                    <input type="text" class="form-control" placeholder="请输入维护页背景图片URL地址"
                                           name="SmBgUrl" value="<?php echo $diy['SmBgUrl'] ?>" required>
                                </div>
                            </div>
                            <hr class="User_Hr">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">开启前端全局密码访问</label>
                                <input type="checkbox" name="open_pw" id="open_pw" 
                                <?php if ($diy['open_pw']) { echo"checked"; } ?>
                                       data-switch="success"
                                       value="<?php if ($diy['open_pw']) { ?> 1<?php } else { ?>0<?php } ?>"
                                       onclick="myOnClickHandler(this)">
                                <label style="display:block;" for="open_pw" data-on-label="Yes" data-off-label="No"></label>
                            </div>
                            <div class="<?php if (!$diy['open_pw']){echo "elementHiding"; } ?>" id="mima">
                                <div class="form-group mb-3">
                                    <label for="validationCustom05"><b class="required_marker">*</b>自定义访问密码</label>
                                    <input type="text" class="form-control" placeholder="请输入自定义访问密码"
                                           name="diy_page_pw" value="<?php echo $diy['diy_page_pw'] ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom05"><b class="required_marker">*</b>自定义背景图</label>
                                    <input type="text" class="form-control" placeholder="请输入图片URL地址"
                                           name="url_pw" value="<?php echo $diy['url_pw'] ?>" required>
                                </div>
                            </div>
                            <hr class="User_Hr">
                            <div class="form-group mb-3">
                                <label for="validationCustom05">自定义前端全局CSS样式</label>
                                <textarea name="cssCon" class="form-control" rows="5"
                                          placeholder=""><?php echo $diy['cssCon'] ?></textarea>
                                <span class="help-block"><small>请按CSS格式填写你的代码</small></span>
                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom05">自定义头部标签</label>
                                <textarea name="headCon" class="form-control" rows="5"
                                          placeholder=""><?php echo $diy['headCon'] ?></textarea>
                                 <span class="help-block"><small>可填写HTML标签 CSS外链</small></span>
                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom05">自定义底部标签</label>
                                <textarea name="footerCon" class="form-control" rows="5"
                                          placeholder=""><?php echo $diy['footerCon'] ?></textarea>
                                <span class="help-block"><small>可填写HTML标签 JavaScript外链</small></span>
                            </div>
                    </div>
                    <div class="tab-pane" id="profile1">
                    <div class="form-group mb-3">
                                <label for="validationCustom01">是否显示前端关于页面按钮</label>
                                <input type="checkbox" name="is_aboutActive" id="is_aboutActive" 
                                <?php if ($diy['is_aboutActive']) { echo"checked"; } ?>
                                       data-switch="success"
                                       value="<?php if ($diy['is_aboutActive']) { ?> 1<?php } else { ?>0<?php } ?>"
                                       onclick="myOnClickaboutActive(this)">
                                <label style="display:block;" for="is_aboutActive" data-on-label="Yes" data-off-label="No"></label>
                            </div>
                        <div class="form-group mb-3">
                            <label for="validationCustom05"><b class="required_marker">*</b>首页留言内容显示数量</label>
                            <input type="text" class="form-control"  placeholder="请输入显示数量 纯数字"
                                   name="index_leav_num" value="<?php echo $diy['index_leav_num'] ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="validationCustom05"><b class="required_marker">*</b>首页文章显示数量</label>
                            <input type="text" class="form-control"  placeholder="请输入显示数量 纯数字"
                                   name="index_essay_num" value="<?php echo $diy['index_essay_num'] ?>" required>
                        </div>
                        <div class="row">
                            <div class="form-group mb-3 col-lg-6">
                                <label for="validationCustom05"><b class="required_marker">*</b>自定义轮播图高度 PC</label>
                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="form-control" placeholder="请输入PC轮播图自定义高度"
                                           name="pcCarouselHeight" value="<?php echo $diy['pcCarouselHeight'] ?>" required>
                                    <span class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                        <span class="input-group-text">单位</span>
                                    </span>
                                </div>
                                <span class="help-block"><small>支持自定义单位 如vh 根据视口高度显示 100vh即占满全屏 留空则不设置</small></span>
                            </div>
                            <div class="form-group mb-3 col-lg-6">
                                <label for="validationCustom05"><b class="required_marker">*</b>自定义轮播图高度 移动端</label>
                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="form-control" placeholder="请输入移动端轮播图自定义高度"
                                           name="mobileCarouselHeight" value="<?php echo $diy['mobileCarouselHeight'] ?>" required>
                                    <span class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                        <span class="input-group-text">单位</span>
                                    </span>
                                </div>
                                <span class="help-block"><small>支持自定义单位 移动端适当调整 高度占满全屏会显的不太协调 留空则不设置</small></span>
                            </div>
                        </div>
                        

                        <div class="form-group mb-3">
                                <label for="validationCustom05"><b class="required_marker">*</b>自定义轮播图 </label>
                                <textarea name="carousel" class="form-control" rows="5"
                                          placeholder=""><?php echo $diy['carousel'] ?></textarea>
                            <span class="help-block"><small>一行一个URL地址 请按格式填写 否则会导致错误</small></span>

                        </div>

                        <div class="form-group mb-3">
                                <label for="validationCustom05">移动端底部Tab栏模版 </label>
                                <div class="bottom-tab-container">
                                    <div class="bottom-tab <?= $diy['TabTemplate'] == 1 ? 'bottom-tab-active':'' ?> " id="bottom-tab-template1">
                                        <span><i class="ri-layout-bottom-fill mr-1"></i>模板 1</span>
                                    </div>
                                    <div class="bottom-tab <?= $diy['TabTemplate'] == 2 ? 'bottom-tab-active':'' ?>" id="bottom-tab-template2">
                                        <span><i class="ri-layout-bottom-fill mr-1"></i>模板 2</span>
                                    </div>
                                    <div class="bottom-tab <?= $diy['TabTemplate'] == 3 ? 'bottom-tab-active':'' ?>" id="bottom-tab-template3">
                                        <span><i class="ri-layout-bottom-fill mr-1"></i>模板 3</span>
                                    </div>
                                </div>

                                <input type="hidden" name="TabTemplate" id="current-bottom-tab" value="<?php echo $diy['TabTemplate']?>">

                                <!-- <div id="bottom-tab-template1-content" class="bottom-tab-content <?= $diy['TabTemplate'] == 1 ? 'bottom-tab-content-active':'' ?> ">
                                    <p></p>
                                </div>

                                <div id="bottom-tab-template2-content" class="bottom-tab-content <?= $diy['TabTemplate'] == 2 ? 'bottom-tab-content-active':'' ?>">
                                    <p></p>
                                </div>

                                <div id="bottom-tab-template3-content" class="bottom-tab-content <?= $diy['TabTemplate'] == 3 ? 'bottom-tab-content-active':'' ?>">
                                    <p></p>
                                </div> -->
                        </div>
                        

                    </div>
                    <div class="tab-pane" id="settings1">

                        <?php
                        while ($login = mysqli_fetch_array($result_login)) {
                            ?>
                            <div class="form-group mb-3">
                                <label for="validationCustom04"><b class="required_marker">*</b><?php if ($login['id'] == 1){ ?>男主<?php }else{ ?>女主<?php }  ?>登录帐号
                                <i class="user_name"><?php if ($login['id'] == 1){ echo $text['boy']; }else{ echo $text['girl']; }  ?></i></label>
                                <input type="text" class="form-control" placeholder="请输入需修改的管理员账号"
                                       name="adminName_<?php echo $login['id'] ?>" value="<?php echo $login['user'] ?>"
                                       required>

                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom05"><?php if ($login['id'] == 1){ ?>男主<?php }else{ ?>女主<?php }  ?>登录密码</label>
                                
                                    <span class="badge badge-warning-lighten"
                                          style="font-size: 0.8rem;">需满足大小写字母与7位数字</span>
                                <input class="form-control" name="pw_<?php echo $login['id'] ?>" type="password" value=""
                                       placeholder="不修改请留空">
                            </div>

                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php if (@$_SESSION['securityCodeDetection'] == ''|| @!isset($_SESSION['securityCodeDetection'])): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong><i class="dripicons-warning mr-2 font-18"></i>温馨提示 - </strong> 安全码未认证！
                </div>
                <?php endif; 
                if (@$_SESSION['securityCodeDetection'] == $LGNew_UI_Code): ?>
                <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
                    <strong><i class="dripicons-checkmark mr-2 font-18"></i>温馨提示 - </strong> 安全码已通过认证 管理员退出登录后自动清除
                </div>
                <?php endif; ?>

                <div class="form-group mb-3 text_right">
                    <button class="btn btn-primary" <?php echo isset($_SESSION['securityCodeDetection']) ? "onclick='processAdminAction()'" : "onclick=\"PublicPopUpLayer(4,'','')\""; ?> type="button">提交修改</button>
                </div>
                </form>


            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->


<div class="ImageUploadDiy_Btn"><i class="dripicons-cloud-upload"></i></div>

</div>

<script>
function myOnClickHandler(obj) {
    let input = document.getElementById("open_pw");
    var mima = document.getElementById("mima");
    if (obj.checked) {
        input.setAttribute("value", 1);
        $("#mima").show('slow', 'linear');
    } else {
        input.setAttribute("value", 0);
        $("#mima").hide('slow', 'linear');
    }
}             

function myOnClickaboutActive(obj) {
    let input = document.getElementById("is_aboutActive");
    if (obj.checked) {
        input.setAttribute("value", 1);
    } else {
        input.setAttribute("value", 0);
    }
}             

function OnSiteMaintenance(obj) {
    let input = document.getElementById("siteMaintenance");
    if (obj.checked) {
        input.setAttribute("value", 1);
        $("#Maintenance").show('slow', 'linear');
    } else {
        input.setAttribute("value", 0);
        $("#Maintenance").hide('slow', 'linear');
    }
} 

</script>
<style>
    .row{
        justify-content: center;
    }
</style>

<?php
include_once 'Footer.php';
?>

</body>
</html>