<?php
session_start();
include_once 'Nav.php';
?>
<div class="row">

    <div class="col-xl-9">
        <div class="card">
            <div class="card-body">

                <h4 class="header-title mb-3">站点信息配置</h4>

                <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                    <li class="nav-item">
                        <a href="#home1" data-toggle="tab" aria-expanded="true" class="nav-link rounded-0 active">
                            <i class="mdi mdi-brightness-7 d-lg-none d-block mr-1"></i>
                            <span class="d-none d-lg-block">基本设置</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#profile1" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                            <i class="mdi  mdi mdi-account-heart d-lg-none d-block mr-1"></i>
                            <span class="d-none d-lg-block">情侣相关</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#settings1" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                            <i class=" mdi mdi-checkbox-multiple-blank d-lg-none d-block mr-1"></i>
                            <span class="d-none d-lg-block">卡片设置</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane show active" id="home1">
                        <!--基本设置-->
                        <form class="needs-validation" action="adminPost.php" method="post" onsubmit="return check()"
                            novalidate>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom01"><b class="required_marker">*</b>站点标题</label>
                                        <input type="text" class="form-control" placeholder="请输入站点标题"
                                            name="title" value="<?php echo $text['title'] ?>" required>
                                        <span class="help-block"><small>设置后全局页面使用当前标题</small></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom02"><b class="required_marker">*</b>站点LOGO</label>
                                        <input type="text" class="form-control" placeholder="请填写站点LOGO文字"
                                            name="logo" value="<?php echo $text['logo'] ?>" required>
                                        <span class="help-block"><small>仅支持纯文本LOGO内容</small></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom03"><b class="required_marker">*</b>站点文案</label>
                                <input type="text" class="form-control" placeholder="显示在顶部的文案"
                                    name="writing" value="<?php echo $text['writing'] ?>" required>
                                <span class="help-block"><small>跟随在站点标题后显示 首页同步显示 尽量设置短一些效果更佳</small></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="validationCustom03">SEO 开关</label>
                                <input type="checkbox" name="seo_switch" id="seo_switch" <?php echo $text['seo_switch'] ? 'checked':'' ?>
                                        data-switch="success"
                                        value="<?php echo $text['seo_switch'] ? 1 : 0 ?>"
                                        onclick="SeoSwitch(this)">
                                    <label style="display:block;" for="seo_switch" data-on-label="Yes" data-off-label="No"></label>
                            </div>

                            <div class="form-group mb-3">
                                <label for="validationCustom03">SEO 站点描述</label>
                                <textarea name="site_description" class="form-control" rows="5" 
                                          placeholder=""><?php echo $text['site_description'] ?></textarea>
                                <span class="help-block"><small>开启SEO后 当前内容生效 但优先级小于页面内描述</small></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="validationCustom03">SEO 站点关键词</label>
                                <textarea name="global_keywords" class="form-control" rows="5"
                                          placeholder=""><?php echo $text['global_keywords'] ?></textarea>
                                <span class="help-block"><small>开启SEO后 关键词前端全局生效</small></span>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="validationCustom06">顶部大图全屏轮播</label>
                                    <select class="form-control" id="example-select" name="Fullscreen">
                                        <option value="1" <?php if ($diy['Fullscreen'] == 1) { ?> selected <?php } ?>>开启</option>
                                        <option value="0" <?php if ($diy['Fullscreen'] == 0) { ?> selected <?php } ?>>关闭</option>
                                    </select>
                                    <span class="help-block"><small>开启后则轮播大图填满页面宽度 建议开启</small></span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="validationCustom06">顶部轮播大图背景遮罩</label>
                                    <select class="form-control" id="example-select" name="BackgroundMask">
                                        <option value="1" <?php if ($diy['BackgroundMask'] == 1) { ?> selected <?php } ?>>开启</option>
                                        <option value="2" <?php if ($diy['BackgroundMask'] == 2) { ?> selected <?php } ?>>关闭</option>
                                    </select>
                                    <span class="help-block"><small>开启后轮播图亮度降低 加上一层黑色半透明遮罩</small></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="validationCustom07">情侣头像背景高斯模糊</label>
                                    <select class="form-control" id="example-select" name="WebBlur">
                                        <option value="1" <?php if ($diy['Blurkg'] == 1) { ?> selected <?php } ?>>开启</option>
                                        <option value="2" <?php if ($diy['Blurkg'] == 2) { ?> selected <?php } ?>>关闭</option>
                                    </select>
                                    <span class="help-block"><small>开启后前端情侣头像模块背景区域高斯模糊效果</small></span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="validationCustom07">前端无刷新加载 #PJAX</label>
                                    <select class="form-control" id="example-select" name="WebPjax">
                                        <option value="1" <?php if ($diy['Pjaxkg'] == 1) { ?> selected <?php } ?>>开启</option>
                                        <option value="2" <?php if ($diy['Pjaxkg'] == 2) { ?> selected <?php } ?>>关闭</option>
                                    </select>
                                    <span class="help-block"><small>如需添加音乐或者其它JS效果 建议开启 增强用户体验</small></span>
                                </div>
                            </div>

                            <div class="form-group mb-3 text_right">
                                <button class="btn btn-primary" type="button" id="adminPost">提交修改</button>
                            </div>
                        </form>

                    </div>
                    <div class="tab-pane" id="profile1">
                        <!--情侣设置-->
                        <form class="needs-validation" action="loveadminPost.php" method="post" novalidate>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom01"><b class="required_marker">*</b>男主Name</label>
                                        <input type="text" class="form-control" placeholder="请输入男主Name"
                                            name="boy" value="<?php echo $text['boy'] ?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="validationCustom02"><b class="required_marker">*</b>女主Name</label>
                                        <input type="text" class="form-control" placeholder="请输入女主Name"
                                            name="girl" value="<?php echo $text['girl'] ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                    <label for="validationCustom03"><b class="required_marker">*</b>LoveDay 卡片标题</label>
                                    <input type="text" class="form-control" placeholder="请输入LoveDay卡片标题"
                                        name="loveDayTitle" value="<?php echo $text['loveDayTitle'] ?>" required>
                            </div>

                            <div class="form-group mb-3">
                                    <label for="validationCustom03"><b class="required_marker">*</b>情侣头像中心图标</label>
                                    <input type="text" class="form-control" placeholder="请输入情侣头像中心图标路径"
                                        name="loveIconLink" value="<?php echo $text['loveIconLink'] ?>" required>
                                    <span class="help-block"><small>顶部轮播大图情侣头像中心区域 爱心图标URL地址</small></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="validationCustom05">起始时间</label>
                                <input type="datetime-local" class="form-control" placeholder="请输入起始时间"
                                    name="startTime" value="<?php echo $text['startTime'] ?>" required>
                            </div>

                            <div class="form-group mb-3 loveaiv">
                                <div>
                                    <label for="validationCustom01">自定义头像URL</label>
                                    <input type="checkbox" name="if_aiv" id="aiv" <?php echo $text['if_aiv'] ? 'checked':'' ?>
                                        data-switch="success"
                                        value="<?php echo $text['if_aiv'] ? 1 : 0 ?>"
                                        onclick="myOnClickHandler(this)">
                                    <label style="display:block;" for="aiv" data-on-label="Yes" data-off-label="No"></label>
                                </div>
                            </div>


                            <div id="aiv_df">
                                <div class="form-group mb-3">
                                    <label for="validationCustom03"><b class="required_marker">*</b>男主QQ</label>
                                    <input type="text" class="form-control" placeholder="请输入男主QQ（用于显示头像）"
                                        name="boyimg" value="<?php echo $text['boyimg'] ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom04"><b class="required_marker">*</b>女主QQ</label>
                                    <input type="text" class="form-control" placeholder="请输入女主QQ（用于显示头像）"
                                        name="girlimg" value="<?php echo $text['girlimg'] ?>" required>
                                </div>
                            </div>


                            <div id="diy_aiv">
                                <div class="form-group mb-3">
                                    <label for="validationCustom03"><b class="required_marker">*</b>男主头像URL地址</label>
                                    <input type="text" class="form-control" placeholder="请输入自定义头像URL地址"
                                        name="b_diy" value="<?php echo $text['diyb_url'] ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom04"><b class="required_marker">*</b>女主头像URL地址</label>
                                    <input type="text" class="form-control" placeholder="请输入自定义头像URL地址"
                                        name="g_diy" value="<?php echo $text['diyg_url'] ?>" required>
                                </div>
                            </div>

                            <div class="setAvatarFrame">
                                <div class="addAvatarFrame">
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-rounded" onclick="PublicPopUpLayer(3,'','<?php echo htmlspecialchars(json_encode($AdminAvatarAddress, JSON_UNESCAPED_UNICODE),ENT_QUOTES) ?>')">
                                        <i class="mdi mdi-calendar-plus mr-1"></i>新增头像框</button>

                                    <a href="AvatarFramesList.php">
                                        <button type="button" class="btn btn-sm btn-outline-warning btn-rounded">
                                        <i class="ri-list-settings-line mr-1"></i>头像框管理</button>
                                        </a>
                                </div>
                            <div class="AvatarFramesStyle">
                                <div class="AvatarFrames ml">
                                        <img draggable="false" class="Frame lazy" <?php echo getAvatarFrames($text['ml_frame'] ) ?>>
                                        <img draggable="false" data-src="<?php echo DetermineAvatarAddress(1) ?>" class="rounded-circle avatar-md lazy">
                                </div>
                                <i class="dripicons-heart" style="color: #F44336;font-size: 2rem;margin: 0 0.6rem;"></i>
                                <div class="AvatarFrames fl">
                                        <img draggable="false" class="Frame lazy" <?php echo getAvatarFrames($text['fl_frame'] ) ?>>
                                        <img draggable="false" data-src="<?php echo DetermineAvatarAddress(0) ?>" class="rounded-circle avatar-md lazy">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>男主头像框设置</label>
                                    <select class="form-control" name="ml_frame">
                                        <option value="null">不显示头像框</option>
                                        <?php 
                                            foreach ($AvatarFrames as $frame) {
                                                echo "<option value='{$frame['id']}'" . ($text['ml_frame'] == $frame['id'] ? 'selected' : '') . ">{$frame['title']}</option>";
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label>女主头像框设置</label>
                                    <select class="form-control" name="fl_frame">
                                        <option value="null">不显示头像框</option>
                                        <?php 
                                            foreach ($AvatarFrames as $frame) {
                                                echo "<option value='{$frame['id']}'" . ($text['fl_frame'] == $frame['id'] ? 'selected' : '') . ">{$frame['title']}</option>";
                                            }
                                        ?>
                                    </select>
                                </div>

                                <input type="hidden" id="AvatarFramesData" value="<?php echo htmlspecialchars(json_encode($AvatarFrames, JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>">
                            </div>
                            
                        </div>

                            
                            <div class="form-group mb-3 text_right">
                                <button class="btn btn-primary" type="button" id="loveadminPost">提交修改</button>
                            </div>
                        </form>

                    </div>
                    <div class="tab-pane" id="settings1">

                        <div>
                            <div class="card-body">

                                <h4 class="header-title mb-3">卡片相关设置</h4>

                                <div class="row">
                                    <div class="col-sm-3 mb-2 mb-sm-0">
                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <a class="nav-link active show" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home"
                                                aria-selected="true">
                                                <i class="mdi mdi-circle-edit-outline d-lg-none d-block mr-1">全局相关</i>
                                                <span class="d-none d-lg-block">全局相关</span>
                                            </a>
                                            <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile"
                                                aria-selected="false">
                                                <i class="mdi mdi-book-open-page-variant d-lg-none d-block mr-1">页面 #1</i>
                                                <span class="d-none d-lg-block">页面 #1</span>
                                            </a>
                                            <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings"
                                                aria-selected="false">
                                                <i class="mdi mdi-book-open-page-variant d-lg-none  d-block mr-1">页面 #2</i>
                                                <span class="d-none d-lg-block">页面 #2</span>
                                            </a>

                                            <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-cards" role="tab" aria-controls="v-pills-settings"
                                                aria-selected="false">
                                                <i class="mdi mdi-book-open-page-variant d-lg-none d-block mr-1">页面 #3</i>
                                                <span class="d-none d-lg-block">页面 #3</span>
                                            </a>
                                            <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-cardt" role="tab" aria-controls="v-pills-settings"
                                                aria-selected="false">
                                                <i class="mdi mdi-book-open-page-variant d-lg-none d-block mr-1">页面 #4</i>
                                                <span class="d-none d-lg-block">页面 #4</span>
                                            </a>

                                        </div>
                                    </div> <!-- end col-->

                                    <div class="col-sm-9">
                                        <form class="needs-validation" action="CardadminPost.php" method="post" novalidate>
                                            <div class="tab-content" id="v-pills-tabContent">
                                                <div class="tab-pane fade active show" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                                    <div class="form-group mb-3">
                                                        <label for="validationCustom01">小程序背景图片URL地址</label>
                                                        <input type="text" class="form-control" placeholder="请输入图片URL地址"
                                                            name="bgimg" value="<?php echo $text['bgimg'] ?>" required>
                                                        <span class="help-block"><small>未购买配套小程序的用户可忽略当前设置</small></span>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="validationCustom05">萌ICP备案号</label>
                                                        <input type="text" class="form-control" placeholder="没有请留空..." name="Moelicp"
                                                            value="<?php echo $text['Moelicp'] ?>" required>
                                                        <span class="help-block"><small>设置后 则在站点底部显示当前域名ICP备案号 仅输入纯数字备案码即可</small></span>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="validationCustom05">ICP备案号</label>
                                                        <input type="text" class="form-control" placeholder="没有请留空..." name="icp"
                                                            value="<?php echo $text['icp'] ?>" required>
                                                        <span class="help-block"><small>设置后 则在站点底部显示当前域名ICP备案号 留空则不显示</small></span>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="validationCustom05">公安备案号</label>
                                                        <input type="text" class="form-control" placeholder="没有请留空..." name="policeICP"
                                                            value="<?php echo $text['policeICP'] ?>" required>
                                                        <span class="help-block"><small>设置后 则在站点底部显示当前域名公网安备案号 留空则不显示</small></span>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="validationCustom05">站点版权信息（留空则不显示）</label>
                                                        <input type="text" class="form-control" placeholder="请输入站点版权信息"
                                                            name="Copyright" value="<?php echo $text['Copyright'] ?>" required>
                                                        <span class="help-block"><small>设置后 则在站点底部显示当前站点版权信息 留空则不显示</small></span>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                                    <div class="form-group mb-3">
                                                        <label for="validationCustom01"><b class="required_marker">*</b>卡片 1Name</label>
                                                        <input type="text" class="form-control" placeholder="请输入卡片Name"
                                                            name="card1" value="<?php echo $text['card1'] ?>" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="validationCustom02"><b class="required_marker">*</b>卡片 1描述</label>
                                                        <input type="text" class="form-control" placeholder="请输入卡片描述"
                                                            name="deci1" value="<?php echo $text['deci1'] ?>" required>
                                                    </div>

                                                </div>
                                                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                                    <div class="form-group mb-3">
                                                        <label for="validationCustom03"><b class="required_marker">*</b>卡片 2Name</label>
                                                        <input type="text" class="form-control" placeholder="请输入卡片Name"
                                                            name="card2" value="<?php echo $text['card2'] ?>" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="validationCustom04"><b class="required_marker">*</b>卡片 2描述</label>
                                                        <input type="text" class="form-control" placeholder="请输入卡片描述"
                                                            name="deci2" value="<?php echo $text['deci2'] ?>" required>
                                                    </div>

                                                </div>
                                                <div class="tab-pane fade" id="v-pills-cards" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                                    <div class="form-group mb-3">
                                                        <label for="validationCustom05"><b class="required_marker">*</b>卡片 3Name</label>
                                                        <input type="text" class="form-control" placeholder="请输入卡片Name"
                                                            name="card3" value="<?php echo $text['card3'] ?>" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="validationCustom05"><b class="required_marker">*</b>卡片 3描述</label>
                                                        <input type="text" class="form-control" placeholder="请输入卡片描述"
                                                            name="deci3" value="<?php echo $text['deci3'] ?>" required>
                                                    </div>

                                                </div>
                                                <div class="tab-pane fade" id="v-pills-cardt" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                                    <div class="form-group mb-3">
                                                        <label for="validationCustom05"><b class="required_marker">*</b>卡片 4Name</label>
                                                        <input type="text" class="form-control" placeholder="请输入卡片Name"
                                                            name="card4" value="<?php echo $text['card4'] ?>" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="validationCustom05"><b class="required_marker">*</b>卡片 4描述</label>
                                                        <input type="text" class="form-control" placeholder="请输入卡片描述"
                                                            name="deci4" value="<?php echo $text['deci4'] ?>" required>
                                                    </div>
                                                </div>
                                            </div> <!-- end tab-content-->
                                            <div class="form-group mb-3 text_right">
                                                <button class="btn btn-primary" type="button" id="CardadminPost">提交修改</button>
                                            </div>
                                        </form>
                                    </div> <!-- end col-->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->

    <div class="ImageUploadDiy_Btn"><i class="dripicons-cloud-upload"></i></div>


</div>

<script>
    var aiv_diy = document.getElementById("aiv_df");
    var diy = document.getElementById("diy_aiv");
    if (<?php echo $text['if_aiv'] ?>) {
        diy.style.display = "block"
        aiv_diy.style.display = "none"
    } else {
        diy.style.display = "none"
        aiv_diy.style.display = "block"
    }

    function SeoSwitch(obj) {
        if (obj.checked) {
            $('#seo_switch').val(1)
        }else{
            $('#seo_switch').val(0)
        }
    }

    function myOnClickHandler(obj) {
        var input = document.getElementById("aiv");
        var aiv_diy = document.getElementById("aiv_df");
        var diy = document.getElementById("diy_aiv");

        if (obj.checked) {
            input.setAttribute("value", "1");
            aiv_diy.style.display = "none";
            diy.style.display = "block";
        } else {
            input.setAttribute("value", "0");
            aiv_diy.style.display = "block";
            diy.style.display = "none";
        }
    }

    let AvatarFramesData = $("#AvatarFramesData").val();
    let AvatarFramesDataArray = '';

    if (AvatarFramesData) {
            AvatarFramesDataArray = AvatarFramesData.replace(/[\r|\n|\t]/g, "")
            AvatarFramesDataArray = JSON.parse(AvatarFramesDataArray);
    }

    function handleAvatarFrameChange(selectName, containerClass) {
        $(`select[name='${selectName}']`).on('change', function () {
            let AvatarFrameID = $(this).val();
            let AtrStr = '';

            if (AvatarFrameID === 'null') {
                AvatarFrameID = null;
                $(`.AvatarFrames.${containerClass} .Frame`).remove();
                return;
            } else {
                AvatarFrameID = parseInt(AvatarFrameID);
            }

            for (const element of AvatarFramesDataArray) {
                if (AvatarFrameID === parseInt(element.id)) {
                    AtrStr = `<img draggable="false" class="Frame" src="${element.url}" style="transform: scale(${element.scale}); top: ${element.top_offset}px; left: ${element.left_offset}px;">`;
                    $(`.AvatarFrames.${containerClass} .Frame`).remove();
                    $(`.AvatarFrames.${containerClass}`).append(AtrStr);
                    break;
                }
            }
        });
    }

    handleAvatarFrameChange('ml_frame', 'ml');
    handleAvatarFrameChange('fl_frame', 'fl');

</script>
<style>
    .row {
        justify-content: center;
    }

    div#v-pills-tab .nav-link {
        margin: 0.5rem 0;
        text-align: center;
    }
</style>

<?php
include_once 'Footer.php';
?>

</body>

</html>