<?php
session_start();
include_once 'Nav.php';

$wx_app_config = "select * from wx_app_config";
$wx_app_config_res = mysqli_query($connect, $wx_app_config);
$wx_c_data = mysqli_fetch_array($wx_app_config_res);

?>

<style>
    .line {
        margin: 2rem 0;
        border-top: 2px dashed #e0e0e0;
    }

    .cloudStorageCard {
        margin-top: 2rem;
        padding-top: 1rem;
    }

    .cloudStorageCard .activeVariable {
        position: absolute;
        top: .7rem;
        left: .7rem;
    }

    .cloudStorageCard .activeVariable .icon {
        width: 1.8rem;
        height: 1.8rem;
        fill: #b7b7b7;
        transition: all .2s;
    }

    .cloudStorageCard .activeVariable .icon:hover {
        transform: scale(1.3);
        cursor: pointer;
        fill: #ff7878;
    }


    .cloudStorageCard.active .activeVariable .icon {
        fill: #4298fd;
    }

    .cloudStorageContent .cloudStorageLogo .icon {
        width: 3rem;
        height: 3rem;
    }

    .cloudStorageContent .cloudStorageLogo {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        padding: .5rem;
        border-radius: 1rem;
        box-shadow: 0px 5px 35px 0 rgb(145 145 145 / 14%);
    }

    .cloudStorageContent .cloudStorageName {
        text-align: center;
    }

    .cloudStorageCard .cloudStorageFooterBtn {
        text-align: right;
    }

    .cloudStorageCard .cloudStorageFooterBtn button {
        padding: .2rem 1rem;
        white-space: nowrap;
    }

    .border-b {
        color: #808080;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }

    .template-cont {
        background: #f7f7f7;
        border-radius: 10px;
    }

    .template-cont .help-block {
        color: #999;
    }

    .template-cont input,
    .template-cont textarea {
        border: none !important;
    }

    h3.Item-Title {
        font-size: 1.1rem;
        padding: 15px 0;
        border-bottom: 1px dashed #c7c7c7;
        color: #939393;
        transition: all .3s;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
    }

    h3.Item-Title:hover,
    .Item-Title.active {
        color: #ffffff;
        cursor: pointer;
        background: #858585;
        border-bottom-color: transparent;
        padding-left: 10px;
        border-radius: 10px;
    }

    .JsonData-item {
        margin-bottom: 0 !important;
    }

    .text_right {
        text-align: right;
    }

    .btnSticky {
        position: sticky;
        bottom: 10px;
        z-index: 1000;
        pointer-events: none;
    }

    .btnSticky button {
        pointer-events: auto;

    }

    .btnSticky span {
        display: inline-flex;
        padding: 8px 12px;
        background: #fff;
        border-radius: 30px;
    }

    span.genderStyle {
        background: #fff;
        padding: 5px;
        display: inline-flex;
        border-radius: 10px;
    }

    .MsgModel {
        border: 1px dashed #ddd;
        padding: 10px;
        border-radius: 10px;
    }

    .SwitchGender {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .template-cont.maxHeight {
        max-height: 700px;
        overflow: auto;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div id="accordion" class="custom-accordion mb-4">
                <div class="card-body">
                    <h4 class="header-title mb-3 size_18">小程序配置</h4>
                    <form class="needs-validation" action="ImgProcessing.php" method="post" novalidate>

                        <div class="form-group mb-3">
                            <label>开启审核模式</label>
                            <input type="checkbox" name="audit_mode" id="audit_mode" <?php echo $wx_c_data['audit_mode'] ? "checked" : ""; ?> data-switch="success"
                                value="<?php echo $wx_c_data['audit_mode'] ?>">
                            <label style="display:block;" for="audit_mode" data-on-label="Yes"
                                data-off-label="No"></label>
                            <span class="help-block"><small>开启后小程序则进入审核模式 个人开发者认证必须开启再进行提交审核</small></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="validationCustom05">审核模版选择 </label>
                            <div class="bottom-tab-container">
                                <div class="bottom-tab <?= $wx_c_data['template_id'] == 1 ? 'bottom-tab-active' : '' ?> "
                                    id="bottom-tab-template1">
                                    <span><i class="ri-layout-bottom-fill mr-1"></i>模板 1</span>
                                </div>
                                <div class="bottom-tab <?= $wx_c_data['template_id'] == 2 ? 'bottom-tab-active' : '' ?>"
                                    id="bottom-tab-template2">
                                    <span><i class="ri-layout-bottom-fill mr-1"></i>模板 2</span>
                                </div>
                                <div class="bottom-tab <?= $wx_c_data['template_id'] == 3 ? 'bottom-tab-active' : '' ?>"
                                    id="bottom-tab-template3">
                                    <span><i class="ri-layout-bottom-fill mr-1"></i>模板 3</span>
                                </div>
                            </div>

                            <span class="help-block"><small>请自行选择 开发者无法保证百分百通过审核 只是存在几率
                                    剩下的只能靠用户和审核人员斗智斗勇</small></span>

                            <input type="hidden" name="TabTemplate" id="current-bottom-tab"
                                value="<?php echo $wx_c_data['template_id'] ?>">
                        </div>

                        <div class="line"></div>
                        <div id="bottom-tab-template1-content"
                            class="bottom-tab-content <?= $wx_c_data['template_id'] == 1 ? 'bottom-tab-content-active' : '' ?> ">
                            <div class="card-body template-cont">
                                <h3 class="mb-3 border-b">模版 1 — 配置</h3>

                                <div class="form-group mb-3">
                                    <label>虚拟数据封面</label>
                                    <input type="text" class="form-control" placeholder="请输入字体URL地址 需绝对路径"
                                        name="test_cover" value="<?php echo $wx_c_data['test_cover'] ?>" required>
                                    <span class="help-block"><small>请勿设置违反社区规定的封面 正能量相关 随意的人像、风景照</small></span>
                                </div>

                                <div class="form-group mb-3">
                                    <label>虚拟数据标题</label>
                                    <input type="text" class="form-control" placeholder="请输入字体URL地址 需绝对路径"
                                        name="sample_title" value="<?php echo $wx_c_data['sample_title'] ?>" required>
                                    <span class="help-block"><small>仅作为审核时生成的虚拟数据 可以写一些现实生活的记录
                                            但是不用太详细的内容</small></span>
                                </div>

                                <div class="form-group mb-3">
                                    <label>虚拟数据描述</label>
                                    <textarea name="sample_description" class="form-control" rows="5"
                                        placeholder=""><?php echo $wx_c_data['sample_description'] ?></textarea>
                                    <span class="help-block"><small>仅作为审核时生成的虚拟数据 可以写一些现实生活的记录
                                            但是不用太详细的内容</small></span>
                                </div>
                            </div>

                        </div>

                        <div id="bottom-tab-template2-content"
                            class="bottom-tab-content <?= $wx_c_data['template_id'] == 2 ? 'bottom-tab-content-active' : '' ?>">
                            <div class="card-body template-cont">
                                <h3 class="mb-3 border-b">模版 2 — 配置</h3>

                                <div class="form-group mb-3" id="jsonData">

                                </div>

                                <div class="form-group mt-3 text_right">
                                    <button style="white-space: nowrap;" id="saveJsonData" type="button"
                                        class="btn btn-outline-dark btn-rounded">
                                        <i class="mdi mdi-calendar-edit mr-1"></i>保存配置
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="bottom-tab-template3-content"
                            class="bottom-tab-content <?= $wx_c_data['template_id'] == 3 ? 'bottom-tab-content-active' : '' ?>">
                            <div class="card-body template-cont maxHeight">
                                <span class="help-block"><small>请注意 使用模板 — 3前 需将模板 — 1 、模板 — 2的数据配置完整</small></span>

                                <h3 class="mb-3 border-b">模版 3 — 配置</h3>

                                <div class="form-group mb-3">
                                    <label>自定义对话昵称</label>
                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                        <input type="text" class="form-control" id="chatUserName"
                                            placeholder="请输入自定义联系人昵称" value="" required>
                                    </div>
                                    <span class="help-block"><small>显示在顶部中心位置 类似于微信联系人昵称</small></span>
                                </div>

                                <div class="form-group mb-3" id="messagesContainer">

                                </div>

                                <div class="form-group mt-3 text_right btnSticky">
                                    <span>
                                        <button style="white-space: nowrap;" id="saveData" type="button"
                                            class="btn btn-outline-dark btn-rounded">
                                            <i class="mdi mdi-calendar-edit mr-1"></i>保存配置
                                        </button>
                                    </span>

                                </div>
                            </div>
                        </div>
                        <div class="line"></div>


                        <div class="row">
                            <div class="form-group mb-3 col-lg-4">
                                <label>自定义轮播图高度</label>
                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="form-control" placeholder="请输入轮播图自定义高度"
                                        name="swiperHeight" value="<?php echo $wx_c_data['swiperHeight'] ?>" required>
                                    <span class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                        <span class="input-group-text">rpx</span>
                                    </span>
                                </div>
                                <span class="help-block"><small>无需输入单位 仅输入纯数字值即可</small></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group mb-3 col-lg-4">
                                <label>自定义分享标题</label>
                                <input type="text" class="form-control" placeholder="请输入自定义分享标题" name="share_title"
                                    value="<?php echo $wx_c_data['share_title'] ?>" required>
                                <span class="help-block"><small>部分未认证的小程序即使设置亦无法生效</small></span>
                            </div>

                            <div class="form-group mb-3 col-lg-8">
                                <label>自定义分享封面图URL</label>
                                <input type="text" class="form-control" placeholder="请输入自定义分享封面图URL地址"
                                    name="share_cover" value="<?php echo $wx_c_data['share_cover'] ?>" required>
                                <span class="help-block"><small>部分未认证的小程序即使设置亦无法生效</small></span>
                            </div>
                        </div>



                        <div class="form-group mb-3">
                            <label>自定义字体URL</label>
                            <input type="text" class="form-control" placeholder="请输入字体URL地址 需绝对路径"
                                name="custom_font_url" value="<?php echo $wx_c_data['custom_font_url'] ?>" required>
                            <span class="help-block"><small>微信小程序仅支持部分格式字体 如设置后无法生效 请更换字体URL地址</small></span>
                        </div>




                        <div class="form-group mb-3 text_right">
                            <button class="btn btn-primary" type="button" id="wxAppPost">提交修改</button>
                        </div>
                    </form>
                </div>
            </div> <!-- end custom accordions-->

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $("#audit_mode").on("change", function () {
            let input = $(this);

            if (input.prop("checked")) {
                input.val(1);
            } else {
                input.val(0);
            }
        });


        var Copy_ImgUrl = new ClipboardJS('#CopyImgUrl', {
            target: function (trigger) {
                var tempInput = $('<input>').attr('type', 'text').addClass('temp-input').val($(trigger).attr('data-url'));
                $('body').append(tempInput); // 将输入框插入到 DOM 中
                return tempInput[0];
            }
        });




        // 模拟获取 JSON 数据
        $.ajax({
            url: '../api/ctplTow.json', // 这里是你的 JSON 文件的路径，或者 API 接口
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                generateProductForms(data);
            },
            error: function (error) {
                console.error('获取数据失败', error);
            }
        });

        // 生成商品编辑表单
        function generateProductForms(data) {
            data.forEach(function (product) {
                const productDiv = $('<div class="JsonData-item form-group mb-3"></div>');

                // 商品标题，点击时展开/折叠
                const title = $('<h3 class="Item-Title">' + product.title + '</h3>');
                productDiv.append(title);

                // 默认折叠表单内容
                const formContent = $('<div class="form-content" style="display:none;"></div>');

                // 处理 tags 字段（转换为英文逗号分隔的字符串）
                const tagsStr = product.tags ? product.tags.join(', ') : ''; // 确保 tags 存在
                formContent.append('<label>Tags: </label><input class="form-control" type="text" name="tags" value="' + tagsStr + '"><br>');

                // 添加日期字段
                formContent.append('<label>Date: </label><input class="form-control" type="datetime-local" name="date" value="' + product.date + '"><br>');

                // 其他字段（生成 input 输入框）
                for (let key in product) {
                    if (key !== 'tags' && key !== 'date') { // tags 和 date 已经处理过了
                        const inputField = $('<input class="form-control">').attr('type', 'text').attr('name', key).val(product[key]);
                        formContent.append('<label>' + key + ': </label>').append(inputField).append('<br>');
                    }
                }

                // 折叠/展开功能：点击标题时展开或折叠内容
                title.on('click', function () {
                    $('.form-content').not(formContent).slideUp(500);
                    // 移除所有其他标题的自定义 class
                    $('.Item-Title').removeClass('active');

                    // 给当前点击的标题添加自定义 class
                    title.addClass('active');

                    // 切换当前表单内容的显示状态
                    formContent.stop(true, true).slideToggle(500); // 500ms 动画过渡
                });

                // 将生成的内容插入到 #jsonData 容器
                productDiv.append(formContent);
                $('#jsonData').append(productDiv);
            });
        }



        // 点击保存按钮，获取修改后的数据并发送到后端
        $('#saveJsonData').click(function () {
            let updatedData = [];

            // 获取修改后的数据
            $('#jsonData .JsonData-item').each(function () {
                let product = {};
                $(this).find('input').each(function () {
                    const key = $(this).attr('name');
                    const value = $(this).val();
                    if (key === 'tags') {
                        // 处理 tags 字段，确保它是数组，避免丢失
                        product[key] = value.split(',').map(tag => tag.trim());
                    } else {
                        product[key] = value;
                    }
                });
                updatedData.push(product);
            });

            // 发送更新后的数据到后端
            $.ajax({
                url: 'save_data.php', // 后端处理文件，保存修改后的数据
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(updatedData, function (key, value) {
                    // 这个 replacer 函数可以确保处理字段时不进行 Unicode 编码
                    if (typeof value === 'string') {
                        return value; // 直接返回原值，避免 Unicode 编码
                    }
                    return value;
                }),
                success: function (response) {
                    if (response.status) {
                        toastr["success"](response.message, "LG_NewUi");
                    } else {
                        toastr["error"](response.message, "LG_NewUi");
                    }
                },
                error: function (error) {
                    console.error('保存数据失败', error);
                }
            });
        });



        $.ajax({
            url: '../api/chatData.json', // 数据文件路径
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // 处理加载的数据
                populateForm(data);
            },
            error: function (error) {
                console.error('获取数据失败', error);
            }
        });




        // 动态生成表单内容
        function populateForm(chatData) {

            let girlSVG = `<svg t="1734514085014" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6587" width="200" height="200"><path d="M512 577.4336c-123.6992 0-224.3072-100.608-224.3072-224.3072s100.608-224.256 224.3072-224.256 224.3072 100.608 224.3072 224.3072-100.608 224.256-224.3072 224.256z m0-364.8c-77.4656 0-140.544 63.0272-140.544 140.544S434.5344 493.6704 512 493.6704s140.544-63.0272 140.544-140.544S589.4656 212.6336 512 212.6336z" fill="#ef897c" p-id="6588"></path><path d="M512 895.1296c-23.1424 0-41.8816-18.7392-41.8816-41.8816V535.552c0-23.1424 18.7392-41.8816 41.8816-41.8816s41.8816 18.7392 41.8816 41.8816v317.7472c0 23.0912-18.7392 41.8304-41.8816 41.8304z" fill="#ef897c" p-id="6589"></path><path d="M670.8736 736.256H353.1776c-23.1424 0-41.8816-18.7392-41.8816-41.8816s18.7392-41.8816 41.8816-41.8816h317.696c23.1424 0 41.8816 18.7392 41.8816 41.8816s-18.7392 41.8816-41.8816 41.8816z" fill="#ef897c" p-id="6590"></path></svg>`

            let boySVG = `<svg t="1734513848939" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3327" width="200" height="200"><path d="M511.99997252 512m-0.07837466 0a0.07837467 0.07837467 0 1 0 0.15674933 0 0.07837467 0.07837467 0 1 0-0.15674934 0Z" fill="#FFFFFF" p-id="3328"></path><path d="M424.111301 818.825061c-59.328223 0-115.140367-23.107277-157.101038-65.081251-86.620823-86.620823-86.620823-227.581252 0-314.215378 41.960671-41.973974 97.771791-65.081251 157.101038-65.081251 59.355853 0 115.140367 23.12058 157.101037 65.081251 41.960671 41.973974 65.081251 97.771791 65.081251 157.11434s-23.12058 115.140367-65.081251 157.101038-97.745185 65.081251-157.101037 65.081251z m0-360.620268c-36.97103 0-71.733765 14.409175-97.881285 40.543392-53.957913 53.984518-53.957913 141.804656 0 195.775872 26.14752 26.14752 60.910255 40.543392 97.881285 40.543391s71.733765-14.395872 97.881284-40.543391c26.14752-26.14752 40.543392-60.910255 40.543392-97.881285s-14.395872-71.733765-40.543392-97.894587c-26.146497-26.14752-60.909232-40.543392-97.881284-40.543392z" fill="#75B9EB" p-id="3329"></path><path d="M551.602973 511.016603c-10.715039 0-21.430078-4.090155-29.609365-12.269442-16.358573-16.358573-16.358573-42.874483 0-59.219753L672.577209 288.943808h-42.833551c-23.12058 0-41.878806-18.744923-41.878806-41.878806s18.758226-41.878806 41.878806-41.878806h143.958716c16.931624 0 32.200376 10.210549 38.689161 25.847691 6.488785 15.650445 2.889817 33.67189-9.078773 45.641503L581.212338 498.747161c-8.179286 8.179286-18.894326 12.269441-29.609365 12.269442z" fill="#75B9EB" p-id="3330"></path><path d="M773.703397 288.943808h-143.958716c-23.12058 0-41.878806-18.744923-41.878806-41.878806s18.758226-41.878806 41.878806-41.878806h143.958716c23.12058 0 41.878806 18.744923 41.878806 41.878806s-18.758226 41.878806-41.878806 41.878806z" fill="#75B9EB" p-id="3331"></path><path d="M779.864724 439.050548c-23.12058 0-41.878806-18.744923-41.878806-41.878806V253.226329c0-23.133883 18.758226-41.878806 41.878806-41.878807s41.878806 18.744923 41.878806 41.878807v143.945413c0 23.133883-18.758226 41.878806-41.878806 41.878806z" fill="#75B9EB" p-id="3332"></path><path d="M779.864724 439.050548c-23.12058 0-41.878806-18.744923-41.878806-41.878806V253.226329c0-23.133883 18.758226-41.878806 41.878806-41.878807s41.878806 18.744923 41.878806 41.878807v143.945413c0 23.133883-18.758226 41.878806-41.878806 41.878806z" fill="#75B9EB" p-id="3333"></path></svg>`


            // 设置联系人名称
            $('#chatUserName').val(chatData.chatUserName);

            // 动态生成每条消息的编辑字段
            chatData.messages.forEach(function (message, index) {
                const messageDiv = $('<div class="form-group MsgModel"></div>');

                // 消息类型标签 (使用 SVG 图标替代 select)
                const messageTypeLabel = message.type === 1 ? boySVG : girlSVG;
                const genderSpan = $('<span class="genderStyle" data-gender="' + message.type + '">' + messageTypeLabel + '</span>');
                messageDiv.append('<div class="SwitchGender">' + genderSpan[0].outerHTML + '<button type="button" class="btn btn-outline-warning btn-sm btn-rounded">切换性别</button></div>');



                // 创建消息内容的 textarea
                const messageTextarea = $('<textarea class="form-control" rows="3"></textarea>');
                messageTextarea.val(message.content); // 填充现有消息内容
                messageDiv.append(messageTextarea);

                // 消息时间
                const messageTimeInput = $('<input type="datetime-local" class="form-control">');
                messageTimeInput.val(message.time); // 填充现有消息时间
                messageDiv.append(messageTimeInput);

                // 将每条消息添加到表单中
                $('#messagesContainer').append(messageDiv);
            });

            $('.SwitchGender').on('click', function () {
                const genderSpan = $(this).find('.genderStyle'); // 获取点击的性别图标

                const currentGender = genderSpan.attr('data-gender'); // 使用 .attr() 获取当前性别

                // 切换性别
                const newGender = currentGender == 1 ? 2 : 1; // 如果是 1，切换为 2，反之亦然

                // 更新 data-gender 和图标
                genderSpan.attr('data-gender', newGender); // 使用 .attr() 更新 data-gender
                const newIcon = newGender === 1 ? boySVG : girlSVG; // 根据性别选择相应的图标
                genderSpan.html(newIcon); // 更新图标
            });
        }

        // 点击保存按钮，获取修改后的数据并发送到后端
        $('#saveData').click(function () {
            let updatedData = {
                "chatUserName": $('#chatUserName').val(), // 获取修改后的联系人名称
                "messages": []
            };

            // 获取修改后的每条消息
            $('#messagesContainer .form-group').each(function () {
                const message = {};

                // 获取消息类型（通过 data-gender 获取）
                message.type = Number($(this).find('.genderStyle').attr('data-gender')); // 从 data-gender 获取类型

                // 获取消息时间
                message.time = $(this).find('input[type="datetime-local"]').val();

                // 获取消息内容
                message.content = $(this).find('textarea').val();

                updatedData.messages.push(message);
            });

            // 发送更新后的数据到后端
            $.ajax({
                url: 'save_chat_data.php', // 后端处理文件，保存修改后的数据
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(updatedData),
                success: function (response) {
                    if (response.status) {
                        toastr["success"](response.message, "LG_NewUi");
                    } else {
                        toastr["error"](response.message, "LG_NewUi");
                    }
                },
                error: function (error) {
                    console.error('保存数据失败', error);
                }
            });
        });




    });
</script>



<?php
include_once 'Footer.php';
?>

</body>

</html>