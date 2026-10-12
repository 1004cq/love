</div> <!-- content End -->
<?php
include_once 'ajax.php';
?>

<div class="ImageUploadDiy_warp">
    <div class="ImageUploadDiy_close">
        <button type="button" id="ImageUploadDiy_close" class="btn btn-danger mb-2 mr-2">关闭 <i
                class="mdi mdi-backspace-outline"></i></button>
    </div>
    <div class="ImageUploadDiy card">

        <h4 class="header-title mb-3 size_18">图片直链提取</h4>
        <?php include_once "picture_upload.php"; ?>
        <div class="copyTitle">
            <button type="button" id="CopyPicture" class="btn btn-secondary btn-rounded">复制图片外链</button>
        </div>
        <div class="form-group">
            <textarea id="src_url" class="form-control" rows="5" placeholder="请在这里复制图片链接 支持多图上传"></textarea>
        </div>
    </div>
</div>

<!-- Footer Start -->
<footer class="footer">
    <div class="row footer_center">
        <div class="col-md-6">
            Copyright © 2023-<?php echo date("Y") ?> <a href="https://blog.kikiw.cn/index.php/archives/65/"
                target="_blank">LG_NewUi
                #Ki</a> All Rights
            Reserved.
        </div>
    </div>
</footer>
<!-- end Footer -->
</div>

<!-- content-page -->

</div>
<!-- Wap END -->

</div>
<!-- Mian END -->


<script src="../Style/toastr/toastr.js"></script>
<script src="../Style/js/clipboard.min.js"></script>
<!-- App js -->
<script src="/admin/assets/js/app.min.js"></script>

<script>
    $(function () {
        $("#Loadanimation").fadeOut(1000);

        $(".popUpLayerMask .global_pop_up_layer .close_pop_up_layer").click(function (event) {
            closePopupLayer();
        });

        $(".popUpLayerMask .global_pop_up_layer .pop_up_layer_footer #SwitchLock").click(function (event) {
            toggleLockState();
        });

    });

    let currentListId, currentListDB, $addAvatarFrameinputs, AvatarFramId;

    const $switchLock = $('#SwitchLock');
    const $footerButtons = $('.popUpLayerMask .global_pop_up_layer .pop_up_layer_footer button');
    const $pop_up_layer_footer = $('.popUpLayerMask .global_pop_up_layer .pop_up_layer_footer');


    let lazyLoadInstance = new LazyLoad({
        threshold: 0,
        elements_selector: ".popUpLayerMask .global_pop_up_layer .pop_up_layer_content img,.MegAvatar img,.text_ov img,img.lazy"
    });



    let DeletePopUpTemplate = {
        titleHtml: `<h2><i class="mdi mdi-alert-octagon-outline" style="margin-right:.3rem"></i>删除数据</h2>
                    <p><span>你是否确定删除以下内容 删除后无法恢复！</span></p>`,
        footerHtml: `<span id="SwitchLock">
                        <i class="ri-fingerprint-line"></i>
                    </span>
                    <button data-toggle="tooltip" data-placement="top" data-original-title="请先点击左则图标进行确认" type="button"
                    class="btn btn-outline-danger btn-rounded" id="DeleteItem" disabled>确认删除</button>`,
    };

    let DeletePhototPopUpTemplate = {
        titleHtml: `<h2><i class="mdi mdi-alert-octagon-outline" style="margin-right:.3rem"></i>删除媒体</h2>
                    <p><span>你是否确定删除以下内容 删除后无法恢复！</span></p>`,
        footerHtml: `<span id="SwitchLock">
                        <i class="ri-fingerprint-line"></i>
                    </span>
                    <button data-toggle="tooltip" data-placement="top" data-original-title="请先点击左则图标进行确认" type="button"
                    class="btn btn-outline-danger btn-rounded" id="DeletePhoto" disabled>确认删除</button>`,
    };

    let LookMsgAllPopUpTemplate = {
        titleHtml: `<h2 class="lookText"><i class="mdi mdi-calendar-text-outline" style="margin-right:.3rem"></i>查看留言详情</h2>`,
        footerHtml: `<button type="button" id="CloseUp" class="btn btn-outline-dark btn-rounded">关闭弹窗</button>`,
    };

    let AddAvatarFramePopUpTemplate = {
        titleHtml: `<h2 class="lookText"><i class="ri-apps-2-add-line" style="margin-right:.3rem"></i>新增头像框</h2>`,
        footerHtml: `<button type="button" id="AddAF" class="btn btn-outline-dark btn-rounded">确认添加</button>`,
    };

    let UPAvatarFramePopUpTemplate = {
        titleHtml: `<h2 class="lookText"><i class="ri-settings-2-line" style="margin-right:.3rem"></i>更新头像框信息</h2>`,
        footerHtml: `<button type="button" id="UPAF" class="btn btn-outline-dark btn-rounded">确认更新</button>`,
    };

    let CodeAuthPopUpTemplate = {
        titleHtml: `<h2 class="lookText"><i class="ri-rotate-lock-fill" style="margin-right:.3rem"></i>安全码认证</h2>`,
        footerHtml: `<button type="button" id="userPost" class="btn btn-outline-dark btn-rounded">提交认证</button>`,
    };

    let ipBanListPopUpTemplate = {
        titleHtml: `<h2><i class="ri-user-unfollow-line" style="margin-right:.3rem"></i>IP快速封禁</h2>`,
        footerHtml: `<button type="button" id="BlockIP" class="btn btn-outline-dark btn-rounded">确认封禁</button>`,
    };

    let cloudStorageUpTemplate = {
        titleHtml: `<h2 class="lookText"><i class="ri-folder-5-line" style="margin-right:.3rem"></i>云存储信息配置</h2>`,
        footerHtml: `<button type="button" id="UpCloudStorage" class="btn btn-outline-dark btn-rounded">更新信息</button>`,
    };

    let FilesKeyDelTemplate = {
        titleHtml: `<h2><i class="mdi mdi-alert-octagon-outline" style="margin-right:.3rem"></i>批量删除媒体</h2>
                    <p><span>你是否确定删除以下内容 删除后无法恢复！</span></p>`,
        footerHtml: `<span id="SwitchLock">
                        <i class="ri-fingerprint-line"></i>
                    </span>
                    <button data-toggle="tooltip" data-placement="top" data-original-title="请先点击左则图标进行确认" type="button"
                    class="btn btn-outline-danger btn-rounded" id="FilesKeyDel" disabled>确认删除</button>`,
    };

    let AddAvatarFrameContent = `<div class="text-center" style="border-bottom: 1px dashed #dedede;margin-bottom: .5rem;">
                                    <h5>由于部分头像框会存在偏移非中心点的情况 可以进行微调<h5>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-3 col-lg-6">
                                        <label>Top距离</label>
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                            <input type="text" class="form-control" placeholder="请输入头像框偏移量"
                                                   name="upwardOffset" value="0" required>
                                            <span class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                                <span class="input-group-text">PX</span>
                                            </span>
                                        </div>
                                        <span class="help-block"><small>输入纯数字格式 正值往下调整 负值往上调整</small></span>

                                    </div>
                                    <div class="form-group mb-3 col-lg-6">
                                        <label>Left距离</label>
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                            <input type="text" class="form-control" placeholder="请输入头像框偏移量"
                                                   name="leftOffset" value="0" required>
                                            <span class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                                <span class="input-group-text">PX</span>
                                            </span>
                                        </div>
                                        <span class="help-block"><small>输入纯数字格式 正值往左调整 负值往右调整</small></span>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                        <label><b class="required_marker">*</b>头像框放大倍率</label>
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                            <input type="text" class="form-control" placeholder="请输入头像框URL地址"
                                                   name="avatarFrameScale" value="1.5" required>
                                            <span class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                                <span class="input-group-text">倍</span>
                                            </span>
                                        </div>
                                        <span class="help-block"><small>输入纯数字格式 由于头像框尺寸大小不一致 建议每次递增或递减0.1微调</small></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label><b class="required_marker">*</b>头像框标题/描述</label>
                                    <input type="text" class="form-control" placeholder="请输入头像框标题/描述"
                                        name="AFDescription" value="" required>
                                    <span class="help-block"><small>建议标题/描述在8字符以内 不允许重复</small></span>
                                </div>
                                <div class="form-group mb-3">
                                    <label><b class="required_marker">*</b>头像框URL地址</label>
                                    <input type="text" class="form-control" placeholder="请输入头像框URL地址"
                                        name="AFUrl" value="" required>
                                </div>
                                `;
    let CodeAuthContent = `<div class="form-group mb-3">
                                    <label><b class="required_marker">*</b>安全码</label>
                                    <input type="text" class="form-control" placeholder="请输入程序安装时设置的安全码"
                                        name="SCode" value="" required>
                                    <span class="help-block"><small>如忘记安全码请前往 admin/Config_DB.php中修改</small></span>
                            </div>`

    let ipBanListContent = `<div class="form-group mb-3">
                                    <label><b class="required_marker">*</b>封禁原因备注</label>
                                    <input type="text" class="form-control" placeholder="请输入封禁原因"
                                        name="Ipreason" value="" required>
                                    <span class="help-block"><small>封禁后请到IP封禁页面中查看详情信息</small></span>
                            </div>`

    function closePopupLayer() {
        $('.popUpLayerMask, .popUpLayerMask .global_pop_up_layer').fadeOut(200, function () {
            resetSwitchLockIcon();
        });
    }

    function toggleLockState() {
        $footerButtons.attr({
            "disabled": false,
            "data-original-title": ''
        });
        $switchLock.find("i").attr("class", "ri-shield-check-fill");
    }

    function resetSwitchLockIcon() {
        $('#SwitchLock i').attr("class", "ri-fingerprint-line");
        $footerButtons.attr({
            "disabled": true,
            "data-original-title": '请先点击左则图标进行确认'
        });
    }

    $('.bottom-tab').on('click', function () {
        let targetContent = '#' + $(this).attr('id') + '-content';

        $('.bottom-tab').removeClass('bottom-tab-active');
        $('.bottom-tab-content').removeClass('bottom-tab-content-active');

        $(this).addClass('bottom-tab-active');
        $(targetContent).addClass('bottom-tab-content-active');

        let currentBottomTab = $('.bottom-tab').index(this) + 1;
        $('#current-bottom-tab').val(currentBottomTab);
    });


    function showPopup(contentArray, Type = 1) {
        let contentHtml = '';
        let HTmlTemplate, AvatarFrameHtml;

        if (Type == 9) {
            HTmlTemplate = FilesKeyDelTemplate;
        }else if (Type == 7) {
            HTmlTemplate = cloudStorageUpTemplate;
        } else if (Type == 6) {
            HTmlTemplate = DeletePhototPopUpTemplate;
        } else if (Type == 5) {
            HTmlTemplate = ipBanListPopUpTemplate;
        } else if (Type == 4) {
            HTmlTemplate = CodeAuthPopUpTemplate;
            contentHtml = CodeAuthContent;
        } else if (Type == 3) {
            contentHtml = `<div class="AvatarFramesStyle diy">
                                <div class="AvatarFrames ml">
                                        <img draggable="false" class="Frame lazy">
                                        <img draggable="false" data-src="${contentArray.ml}" class="rounded-circle avatar-md lazy">
                                </div>
                                <i class="dripicons-heart" style="color: #F44336;font-size: 2rem;margin: 0 0.6rem;text-decoration: none;"></i>
                                <div class="AvatarFrames fl">
                                        <img draggable="false" class="Frame lazy">
                                        <img draggable="false" data-src="${contentArray.fl}" class="rounded-circle avatar-md lazy">
                                </div>
                            </div>`;

            if (currentListDB == 'UP') {
                HTmlTemplate = UPAvatarFramePopUpTemplate
            } else {
                HTmlTemplate = AddAvatarFramePopUpTemplate;
            }

            contentHtml += AddAvatarFrameContent;

        } else if (Type == 2) {
            HTmlTemplate = LookMsgAllPopUpTemplate;
        } else {
            HTmlTemplate = DeletePopUpTemplate;
        }

        if (Type !== 3 && Type !== 4) {
            if (contentArray) {
                if (Type == 5) {
                    contentArray.forEach(function (item) {
                        contentHtml += '<p>' + item.key + item.value + '</p>';
                    });
                    contentHtml += ipBanListContent;
                } else if (Type == 7) {
                    contentArray.forEach(function (item) {
                        contentHtml += item.value;
                    });
                }else if(Type == 9){
                    contentHtml = `<div class="DelKeys-wrapper" data-cloud="${contentArray[0].FileFrom}">`; 
                    contentArray.forEach(function (item) {
                        contentHtml += `<img class="lazy minDelKeys" data-src="${item.absolute_path}">`;
                    });
                    contentHtml += '</div>';
                } else {
                    contentArray.forEach(function (item) {
                        contentHtml += '<p>' + item.key + item.value + '</p>';
                    });
                }
            }
        }


        $('.popUpLayerMask .global_pop_up_layer .pop_up_layer_head .eventTitle').html(HTmlTemplate.titleHtml);
        $pop_up_layer_footer.html(HTmlTemplate.footerHtml);

        $('[data-toggle="tooltip"]').tooltip();

        $pop_up_layer_footer.on('click', '#CloseUp', function () {
            closePopupLayer();
        });

        $pop_up_layer_footer.on('click', '#SwitchLock', function () {
            $pop_up_layer_footer.find('button').attr({
                "disabled": false,
                "data-original-title": ''
            });
            $('#SwitchLock').find("i").attr("class", "ri-shield-check-fill");
        });

        $pop_up_layer_footer.on('click', '#DeleteItem', function () {
            if (!$(this).is(':disabled')) {
                DeleteItemData();
                $(this).attr("disabled", true);
            }
        });

        $('.popUpLayerMask .global_pop_up_layer .pop_up_layer_content').html(contentHtml);

        if (Type == 3) {
            UpdateAvatarFrameStyle();

            if (contentArray.title || contentArray.url) {
                AvatarFramId = contentArray.id
                $addAvatarFrameinputs.eq(0).val(contentArray.top_offset)
                $addAvatarFrameinputs.eq(1).val(contentArray.left_offset)
                $addAvatarFrameinputs.eq(2).val(contentArray.scale)
                $addAvatarFrameinputs.eq(3).val(contentArray.title)
                $addAvatarFrameinputs.eq(4).val(contentArray.url)
            }

            $pop_up_layer_footer.on('click', '#AddAF', function () {
                addAvatarFrameData();
            });

            $pop_up_layer_footer.on('click', '#UPAF', function () {
                addAvatarFrameData();
            });
        }

        if (Type == 4) {
            $pop_up_layer_footer.on('click', '#userPost', function () {
                processAdminAction();
            });
        }

        if (Type == 5) {
            $pop_up_layer_footer.on('click', '#BlockIP', function () {
                OnBlockIP();
            });
        }

        if (Type == 6) {
            $pop_up_layer_footer.on('click', '#DeletePhoto', function () {
                del();
            });
        }

        if (Type == 7) {
            $pop_up_layer_footer.on('click', '#UpCloudStorage', function () {
                StorageUpdate();
            });
        }

        // if (Type == 9) {
        //     $pop_up_layer_footer.on('click', '#FilesKeyDel', function () {
        //         del();
        //     });
        // }


        lazyLoadInstance.update();

        $(".popUpLayerMask").show();
        $('.popUpLayerMask .global_pop_up_layer').fadeIn(300);
        ClearClickEvent();
    }


    function ClearClickEvent() {
        $pop_up_layer_footer.off("click", '#DeletePhoto');
        $pop_up_layer_footer.off("click", '#BlockIP');
        $pop_up_layer_footer.off("click", '#UpCloudStorage');
        $pop_up_layer_footer.off("click", '#UPAF');
        $pop_up_layer_footer.on('click', '#DeletePhoto', function () {
            del();
        });
        $pop_up_layer_footer.on('click', '#FilesKeyDel', function () {
            del();
        })
        $pop_up_layer_footer.on('click', '#BlockIP', function () {
            OnBlockIP();
        });
        $pop_up_layer_footer.on('click', '#UpCloudStorage', function () {
            StorageUpdate();
        });
        $pop_up_layer_footer.on('click', '#UPAF', function () {
            addAvatarFrameData();
        });
    }

    function UpdateAvatarFrameStyle() {
        $addAvatarFrameinputs = $('input[name=upwardOffset], input[name=leftOffset], input[name=avatarFrameScale],input[name=AFDescription], input[name=AFUrl]');
        let AvatarFrameStyle = '';

        $addAvatarFrameinputs.off('blur').on('blur', function () {
            if ($addAvatarFrameinputs.eq(4).val()) {
                AvatarFrameStyle = `<img draggable="false" class="Frame lazy" data-src= "${$addAvatarFrameinputs.eq(4).val()}" style="transform: scale(${$addAvatarFrameinputs.eq(2).val()});top: ${$addAvatarFrameinputs.eq(0).val()}px;left: ${$addAvatarFrameinputs.eq(1).val()}px;">`;

                $('.AvatarFramesStyle.diy .AvatarFrames .Frame').remove();
                $('.AvatarFramesStyle.diy .AvatarFrames').append(AvatarFrameStyle);
                lazyLoadInstance.update();
            }

        });

    }

    function addAvatarFrameData() {
        let AFData = $addAvatarFrameinputs;
        let processingType = currentListDB;
        let AFId = parseInt(AvatarFramId);

        $('#AddAF').attr("disabled", true);

        if (AFData.length > 1) {

            if (!AFData.eq(3).val() || !AFData.eq(4).val()) {
                $('#AddAF').attr("disabled", false);
                toastr["warning"]("请检查标题描述或URL地址是否为空</br> 请重试！", "LG_NewUi");
                return false;
            }

            $.ajax({
                url: "addAvatarFrame_Post.php",
                data: {
                    PrType: processingType ? 1 : '',
                    id: AFId ? AFId : '',
                    title: AFData.eq(3).val(),
                    url: AFData.eq(4).val(),
                    scale: AFData.eq(2).val(),
                    top_offset: AFData.eq(0).val(),
                    left_offset: AFData.eq(1).val(),
                },
                type: "POST",
                dataType: "json",
                success: function (res) {
                    if (res.Status) {
                        toastr["success"](res.message, "LG_NewUi");
                        console.log(res);
                        location.reload();
                    } else {
                        $('#AddAF').attr("disabled", false);
                        toastr["error"](res.message, "LG_NewUi");
                    }
                },
                error: function (err) {
                    $('#AddAF').attr("disabled", false);
                    toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
                }
            })
        }
    }

    function PublicPopUpLayer(EventType, dbName, DataArray) {
        let ItemInfo, DataArr, UserMsgInfo;

        if (EventType == 2) {
            UserMsgInfo = [{
                key: '留言内容：',
                value: `<i id="MsgText">${getMsgText(DataArray) ? getMsgText(DataArray) : '加载中...'}</i>`
            },
            {
                key: '数据来源：',
                value: `<b>游客留言详情</b>`
            }
            ]
            showPopup(UserMsgInfo, EventType)
            return;
        }

        if (EventType == 4) {
            showPopup('', EventType)
            return;
        }

        if (DataArray) {
            DataArray = DataArray.replace(/[\r|\n|\t]/g, "")
            DataArr = JSON.parse(DataArray);
            currentListId = DataArr.id;
            currentListDB = dbName;
        } else {
            toastr["warning"]("无法获取当前列表数据 请重试！", "LG_NewUi");
            return;
        }

        // console.log(DataArr);


        if (EventType == 3) {
            showPopup(DataArr, EventType)
            return;
        }

        // console.log(dbName);

        if (dbName == 'article') {
            ItemInfo = [{
                key: '标题：',
                value: `<i>${DataArr.articletitle}</i>`
            },
            {
                key: '创建时间：',
                value: `<i>${formatTimestamp(DataArr.articletime)}</i>`
            },
            {
                key: '发布者：',
                value: `<i>${DataArr.articlename}</i>`
            },
            {
                key: '数据来源：',
                value: `<b>点点滴滴</b>`
            }
            ]
        } else if (dbName == 'leaving') {
            ItemInfo = [{
                key: '留言内容：',
                value: `<i id="MsgText">${getMsgText(DataArr.text) ? getMsgText(DataArr.text) : '加载中...'}</i>`
            },
            {
                key: '留言者昵称：',
                value: `<i>${DataArr.name}</i>`
            },
            {
                key: '留言者QQ：',
                value: `<i>${DataArr.qq}</i>`
            },
            {
                key: '留言状态：',
                value: `<i class="${!DataArr.switch ? 'reject' : ''}">${DataArr.switch ? '通过' : '未审核'}</i>`
            },
            {
                key: '留言时间：',
                value: `<i>${formatTimestamp(DataArr.time)}</i>`
            },
            {
                key: '数据来源：',
                value: `<b>留言管理</b>`
            }
            ]
        } else if (dbName == 'loveday') {
            ItemInfo = [{
                key: '日期标题：',
                value: `<i>${DataArr.day_title}</i>`
            },
            {
                key: '日期类型：',
                value: `<i>${DataArr.day_type ? '纪念日' : '倒计时'}</i>`
            },
            {
                key: '时间详情：',
                value: `<i>${DataArr.day}</i>`
            },
            {
                key: '数据来源：',
                value: `<b>恋爱日期</b>`
            }
            ]
        } else if (dbName == 'loveImg') {
            ItemInfo = [{
                key: '相册标题：',
                value: `<i>${DataArr.imgText}</i>`
            },
            {
                key: '上传数量：',
                value: `<i>${DataArr.cout}张</i>`
            },
            {
                key: '创建时间：',
                value: `<i>${DataArr.imgDatd}</i>`
            },
            {
                key: '创建者：',
                value: `<i>${DataArr.upload_name}</i>`
            },
            {
                key: '',
                value: `<img class="lazy" data-src="${DataArr.imgUrl}" alt="${DataArr.imgText}">`
            },
            {
                key: '数据来源：',
                value: `<b>恋爱相册</b>`
            }
            ]
        } else if (dbName == 'Photolist') {
            ItemInfo = [{
                key: '相册标题：',
                value: `<i>${DataArr.PhotoTitle}</i>`
            },
            {
                key: '当前描述：',
                value: `<i>${DataArr.photo_text}</i>`
            },
            {
                key: '创建时间：',
                value: `<i>${DataArr.photo_date}</i>`
            },
            {
                key: '创建者：',
                value: `<i>${DataArr.photo_byname}</i>`
            },
            {
                key: '',
                value: `<img class="lazy" data-src="${parseInt(DataArr.photo_type) == 1 ? DataArr.VideoCover : DataArr.photo_url}" alt="${DataArr.photo_text}">`
            },
            {
                key: '数据来源：',
                value: `<b>恋爱相册列表</b>`
            }
            ]
        } else if (dbName == 'lovelist') {
            ItemInfo = [{
                key: '事件标题：',
                value: `<i>${DataArr.eventname}</i>`
            },
            {
                key: '完成状态：',
                value: `<i class="${!parseInt(DataArr.icon) ? 'reject' : ''}">${parseInt(DataArr.icon) ? '已完成' : '未完成'}</i>`
            },
            {
                key: '图片状态：',
                value: `<i class="${DataArr.imgurl == 0 ? 'reject' : ''}">${DataArr.imgurl != 0 ? '已上传' : '未上传'}</i>`
            },
            {
                key: '数据来源：',
                value: `<b>恋爱清单</b>`
            }
            ]
        } else if (dbName == 'IPerror') {
            ItemInfo = [{
                key: 'IP地址：',
                value: `<i>${DataArr.State}</i>`
            },
            {
                key: 'IP归属地：',
                value: `<i>${DataArr.ipAdd}</i>`
            },
            {
                key: '封禁备注：',
                value: `<i>${DataArr.text}</i>`
            },
            {
                key: '封禁时间：',
                value: `<i>${DataArr.Time}</i>`
            },
            {
                key: '数据来源：',
                value: `<b>IP封禁</b>`
            }
            ]
        } else if (dbName == 'CoupleAvatarFrames') {
            ItemInfo = [{
                key: '头像框预览：',
                value: `<img class="Frames lazy" data-src="${DataArr.url}" alt="${DataArr.title}">`
            },
            {
                key: '头像框标题/描述：',
                value: `<i>${DataArr.title}</i>`
            },
            {
                key: '头像框上偏移量：',
                value: `<i>${DataArr.top_offset} PX</i>`
            },
            {
                key: '头像框左偏移量：',
                value: `<i>${DataArr.left_offset} PX</i>`
            },
            {
                key: '头像框放大倍率：',
                value: `<i>${DataArr.scale}</i>`
            },
            {
                key: '头像框添加时间：',
                value: `<i>${DataArr.created_at}</i>`
            },
            {
                key: '数据来源：',
                value: `<b>IP封禁</b>`
            }
            ]
        } else if (EventType == 5) {

            if (dbName == 1) {
                ItemInfo = [{
                    key: '游客昵称：',
                    value: `<i>${DataArr.name}</i>`
                },
                {
                    key: '留言IP：',
                    value: `<i id="UserIPText">${DataArr.ip}</i>`
                },
                {
                    key: '留言归属地：',
                    value: `<i>${DataArr.city ? DataArr.city : '未知'}</i>`
                },
                {
                    key: '数据来源：',
                    value: `<b>游客留言</b>`
                }
                ]
            } else {
                ItemInfo = [{
                    key: '非法访问路径：',
                    value: `<i>${DataArr.file}</i>`
                },
                {
                    key: ' IP地址：',
                    value: `<i id="UserIPText">${DataArr.ip}</i>`
                },
                {
                    key: 'IP归属地：',
                    value: `<i>${DataArr.gsd}</i>`
                },
                {
                    key: '访问时间：',
                    value: `<i>${DataArr.time}</i>`
                },
                {
                    key: '数据来源：',
                    value: `<b>非法请求</b>`
                }
                ]
            }

            showPopup(ItemInfo, EventType)
            return;
        } else if (EventType == 6) {
            console.log(DataArr);
            ItemInfo = [{
                key: '图片大小：',
                value: `<i>${DataArr.imageSize}</i>`
            },
            {
                key: '图片名称：',
                value: `<i id="PhotoKey" data-key="${DataArr.DeleteKey}" data-cloud="${DataArr.FileFrom}">${DataArr.filename}</i>`
            },
            {
                key: '绝对路径地址：',
                value: `<i>${DataArr.absolute_path}</i>`
            },
            {
                key: '',
                value: `<img class="lazy" data-src="${DataArr.absolute_path}">`
            },
            {
                key: '数据来源：',
                value: `<b>媒体管理</b>`
            }
            ]
            showPopup(ItemInfo, EventType);
            return;
        } else if (EventType == 7) {
            console.log(DataArr);
            let currentlyInUse = `<span class="badge badge-warning-lighten">使用中</span>`;
            ItemInfo = [{
                value: `<div class="form-group mb-3">
                                    <label>当前对象存储 ${parseInt(DataArr.Current) ? currentlyInUse : ''}</label>
                                    <input type="text" class="form-control"  value="${DataArr.Provider}" disabled>
                                    <input name="currentCloudId" value="${DataArr.id}" type="hidden">
                            </div>`
            },
            {
                value: `<div class="form-group mb-3">
                                    <label><b class="required_marker">*</b>AccessKey</label>
                                    <input type="text" class="form-control" placeholder="请输入对接的AccessKey"
                                        name="AccessKey" value="${DataArr.AccessKey ? DataArr.AccessKey : ''}" required>
                                    <span class="help-block"><small>AccessKey 与 SecretKey 是同时生成的 请勿填写错误</small></span>
                            </div>`
            },
            {
                value: `<div class="form-group mb-3">
                                    <label><b class="required_marker">*</b>SecretKey</label>
                                    <input type="text" class="form-control" placeholder="请输入对接的SecretKey"
                                        name="SecretKey" value="${DataArr.SecretKey ? DataArr.SecretKey : ''}" required>
                                    <span class="help-block"><small>AccessKey 与 SecretKey 是同时生成的 请勿填写错误</small></span>
                            </div>`
            },
            {
                value: `<div class="form-group mb-3">
                                    <label><b class="required_marker">*</b>Bucket</label>
                                    <input type="text" class="form-control" placeholder="请输入对接的Bucket"
                                        name="Bucket" value="${DataArr.BucketName ? DataArr.BucketName : ''}" required>
                                    <span class="help-block"><small>Bucket 是指创建时的存储桶名称</small></span>
                            </div>`
            },
            {
                value: `<div class="form-group mb-3">
                                    <label>Endpoint</label>
                                    <input type="text" class="form-control" placeholder="请输入对接的Endpoint"
                                        name="Endpoint" value="${DataArr.Endpoint ? DataArr.Endpoint : ''}" >
                                    <span class="help-block"><small>Endpoint 是用于访问对象存储服务的域名地址（官方）</small></span>
                            </div>`
            },
            {
                value: `<div class="form-group mb-3">
                                    <label>自定义加速域名</label>
                                    <input type="text" class="form-control" placeholder="云存储设置的自定义CDN加速域名"
                                        name="customCDN" value="${DataArr.customCDN ? DataArr.customCDN : ''}" >
                                    <span class="help-block"><small>Endpoint 是用于访问特定对象存储服务的域名地址（自定义）</small></span>
                            </div>`
            },
            {
                value: `<div class="form-group mb-3">
                                    <label>Region</label>
                                    <input type="text" class="form-control" placeholder="请输入对接的Region"
                                        name="Region" value="${DataArr.Region ? DataArr.Region : ''}" >
                                    <span class="help-block"><small>Region 是指存储资源所在的地理区域 例cos中： ap-guangzhou</small></span>
                            </div>`
            },
            ]

            showPopup(ItemInfo, EventType)
            return;
        }

        showPopup(ItemInfo)
        console.log(DeletePopUpTemplate);
    }


    function processMes(Msgcontent) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: "getMsgEmoji.php",
                data: {
                    MesgContent: Msgcontent,
                },
                type: "POST",
                dataType: "json",
                success: function (res) {
                    if (res.Status) {
                        resolve(res.Content);
                    } else {
                        toastr["error"](res.message, "LG_NewUi");
                        resolve(Msgcontent);
                    }
                },
                error: function (err) {
                    reject(err);
                    toastr["error"]("网络错误 处理留言内容失败 <br/> 请稍后重试！", "LG_NewUi");
                }
            });
        });
    }



    function getMsgText(Msgcontent) {
        processMes(Msgcontent)
            .then(content => {
                // console.log(content);
                $('.popUpLayerMask .global_pop_up_layer .pop_up_layer_content #MsgText').html(content)
                lazyLoadInstance.update();
            })
            .catch(err => {
                console.error("Error:", err);
            });
    }

    let isActiveCloudRequest = false;
    $(".activeVariable").click(function () {

        if (isActiveCloudRequest) {
            toastr["warning"]("当前设置云存储中 <br/> 请设置成功后重试！", SiteTitle);
            return;
        }

        isActiveCloudRequest = true;

        let cloudId = $(this).data('cloudid');
        $('.cloudStorageCard').removeClass('active');
        $(this).closest('.cloudStorageCard').addClass('active');
        // console.log(cloudId);

        $.ajax({
            url: "UpCloudStorage.php",
            data: {
                currentCloudId: cloudId,
                updateType: 1
            },
            type: "POST",
            dataType: "json",
            success: function (res) {
                if (res) {
                    if (res.States) {
                        toastr["success"](res.info, "LG_NewUi");
                    } else {
                        toastr["error"](res.info, "LG_NewUi");
                    }
                }
            },
            error: function (err) {
                $(this).attr("disabled", false);
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            },
            complete: function () {
                isActiveCloudRequest = false;
            }
        })
    })



    $("#DeleteItem").click(function () {
        $(this).attr("disabled", true);
        DeleteItemData();
    })

    function DeleteItemData() {

        $.ajax({
            url: "deleteEntry.php",
            data: {
                id: currentListId,
                DbNmae: currentListDB
            },
            type: "POST",
            dataType: "json",
            success: function (res) {
                if (res.Status) {
                    toastr["success"](res.message, "LG_NewUi");
                    location.reload();
                } else {
                    $(this).attr("disabled", false);
                    toastr["error"](res.message, "LG_NewUi");
                }
            },
            error: function (err) {
                $(this).attr("disabled", false);
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    }


    function formatTimestamp(timestampString) {
        const timestamp = parseInt(timestampString, 10) * 1000;
        const date = new Date(timestamp);
        if (isNaN(date.getTime())) {
            return '日期获取失败！';
        }

        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');

        return `${year}-${month}-${day} ${hours}:${minutes}`;
    }

    function openPrivacy(obj) {
        let input = document.getElementById("encryptionSwitch");
        if (obj.checked) {
            input.setAttribute("value", 1);
            $("#OpenPw").show('slow', 'linear');
        } else {
            input.setAttribute("value", 0);
            $("#OpenPw").hide('slow', 'linear');
        }
    }

    function openPrivacyUP(obj) {
        let input = document.getElementById("encryptionSwitch");
        if (obj.checked) {
            input.setAttribute("value", 1);
            $("#OpenPw").show('slow', 'linear');
        } else {
            input.setAttribute("value", 0);
            $("#OpenPw").hide('slow', 'linear');
        }
    }

    $('#updateEditingTime').click(function () {
        if ($(this).is(':checked')) {
            const now = new Date();
            const formattedDate = now.getFullYear() + '-' +
                String(now.getMonth() + 1).padStart(2, '0') + '-' +
                String(now.getDate()).padStart(2, '0') + ' ' +
                String(now.getHours()).padStart(2, '0') + ':' +
                String(now.getMinutes()).padStart(2, '0') + ':' +
                String(now.getSeconds()).padStart(2, '0');

            $('input[name=little_day]').val(formattedDate);
        }
    });


    var Copy_ImgUrl = new ClipboardJS('#CopyImgUrl', {
        target: function (trigger) {
            var tempInput = $('<input>').attr('type', 'text').addClass('temp-input').val($(trigger).attr('data-url'));
            $('body').append(tempInput); // 将输入框插入到 DOM 中
            return tempInput[0];
        }
    });

    Copy_ImgUrl.on('success', function (e) {
        toastr["success"]("复制图片URL成功！", "LG_NewUi");
        $('.temp-input').remove(); // 复制成功后移除输入框
    });

    Copy_ImgUrl.on('error', function (e) {
        toastr["error"]("复制失败 图片URL为空！", "LG_NewUi");
    });

    console.log("%c LG_NewUi <?php echo $version ?> | Powered by Ki", "color:#fff;background:linear-gradient(270deg,#986fee,#8695e6,#68b7dd,#18d7d3);padding:8px 15px;border-radius:15px");
    console.log("%c LG_NewUi Admin", "color:#fff;background:#000;padding:8px 15px;border-radius:15px");
</script>



<!-- third party js -->
<script src="/admin/assets/js/vendor/jquery.dataTables.min.js"></script>
<script src="/admin/assets/js/vendor/dataTables.bootstrap4.js"></script>
<script src="/admin/assets/js/vendor/dataTables.responsive.min.js"></script>
<script src="/admin/assets/js/vendor/responsive.bootstrap4.min.js"></script>
<script src="/admin/assets/js/vendor/dataTables.buttons.min.js"></script>
<script src="/admin/assets/js/vendor/buttons.bootstrap4.min.js"></script>
<script src="/admin/assets/js/vendor/buttons.html5.min.js"></script>
<script src="/admin/assets/js/vendor/buttons.flash.min.js"></script>
<script src="/admin/assets/js/vendor/buttons.print.min.js"></script>
<script src="/admin/assets/js/vendor/dataTables.keyTable.min.js"></script>
<script src="/admin/assets/js/vendor/dataTables.select.min.js"></script>
<!-- third party js ends -->
<!-- demo app -->
<script src="/admin/assets/js/pages/demo.datatable-init.js"></script>
