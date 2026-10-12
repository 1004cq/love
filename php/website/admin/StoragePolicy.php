<?php
session_start();
include_once 'Nav.php';

$ImageSet_sql = "select * from ImageSet";
$ImageSet_res = mysqli_query($connect, $ImageSet_sql);
$ImageSet_data = mysqli_fetch_array($ImageSet_res);


$CloudStorageConfig_sql = "SELECT * FROM CloudStorageConfig";
$CloudStorageConfig_res = mysqli_query($connect, $CloudStorageConfig_sql);
$CloudStorageConfig_data = mysqli_fetch_all($CloudStorageConfig_res, MYSQLI_ASSOC);

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
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">存储策略配置</h4>
                <div id="accordion" class="custom-accordion mb-4">

                    <div class="card mb-0">
                        <div class="card-header" id="headingOne">
                            <h4 class="m-0">
                                <a class="custom-accordion-title d-block pt-2 pb-2" data-toggle="collapse"
                                    href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <i class="ri-settings-2-fill mr-1"></i>媒体上传相关配置
                                    <span class="float-right"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <form class="needs-validation" action="ImgProcessing.php" method="post" novalidate>

                                    <div class="form-group mb-3">
                                        <label id="cloudStorageSwitchText"><?php echo $ImageSet_data['localOrCloud'] ? "当前为云存储" : "当前为本地存储"; ?></label>
                                        <input type="checkbox" name="cloudStorageSwitch" id="cloudStorageSwitch" <?php echo $ImageSet_data['localOrCloud'] ? "checked" : ""; ?> data-switch="success" value="<?php echo $ImageSet_data['localOrCloud'] ?>">
                                        <label style="display:block;" for="cloudStorageSwitch" data-on-label="Yes"
                                            data-off-label="No"></label>
                                        <span class="help-block"><small>打开后则启动全站资源上传云存储 具体配置请到下方进行填写相关内容</small></span>
                                    </div>

                                    <div class="row">
                                        <div class="form-group mb-3 col-lg-4">
                                            <label for="validationCustom02"><b
                                                    class="required_marker">*</b>设置上传目录 ｜ 以“/”结尾</label>
                                            <input type="text" class="form-control" placeholder="请输入指定的上传目录" name="UploadDirectory"
                                                value="<?php echo $ImageSet_data['UploadDirectory'] ?>" required>
                                            <span class="help-block"><small>支持自定义上传目录 本地/对象存储同时生效（如无法自动创建目录请手动开启目录权限）</small></span>

                                        </div>
                                    </div>

                                    <div class="line"></div>

                                    <div class="form-group mb-3">
                                        <label for="validationCustom01">开启图片文本水印</label>
                                        <input type="checkbox" name="TextWateSwitch" id="TextWateSwitch" <?php echo $ImageSet_data['TextWateSwitch'] == "true" ? "checked" : ""; ?>
                                            data-switch="success" value="<?php echo $ImageSet_data['TextWateSwitch'] ?>"
                                            onclick="myOnClickHandler(this)">
                                        <label style="display:block;" for="TextWateSwitch" data-on-label="Yes"
                                            data-off-label="No"></label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group mb-3 col-lg-4">
                                            <label for="validationCustom02"><b
                                                    class="required_marker">*</b>文本水印内容</label>
                                            <input type="text" class="form-control" placeholder="请填写文本内容" name="ImgText"
                                                value="<?php echo $ImageSet_data['ImgText'] ?>" required>
                                        </div>
                                        <div class="form-group mb-3 col-lg-4">
                                            <label><b class="required_marker">*</b>水印透明度（数值越大越暗）<span
                                                    id="ColorOpacityValue"
                                                    class="badge badge-primary-lighten right_10"></span></label>
                                            <input style="margin-top: 0.5rem;" class="custom-range" id="ColorOpacity"
                                                type="range" min="0" max="100"
                                                value="<?php echo $ImageSet_data['ColorOpacity'] ?>">
                                            <input type="hidden" id="OpacityValue" name="OpacityValue"
                                                value="<?php echo $ImageSet_data['ColorOpacity'] ?>">
                                        </div>
                                        <div class="form-group mb-3 col-lg-4">
                                            <label for="validationCustom03"><b class="required_marker">*</b>水印颜色</label>
                                            <input class="form-control" id="colorPicker" type="color"
                                                value="<?php echo rgbToHex($ImageSet_data['colorPicker']) ?>">
                                            <input type="hidden" id="rgbValue" name="colorPicker"
                                                value="<?php echo $ImageSet_data['colorPicker'] ?>">
                                        </div>
                                    </div>

                                    <div class="line"></div>

                                    <div class="form-group mb-3">
                                        <label for="validationCustom03"><b class="required_marker">*</b>压缩质量<span id="RangeValue"
                                                class="badge badge-primary-lighten right_10"></span></label>
                                        <input class="custom-range" id="example-range" type="range" name="range" min="0"
                                            max="100" value="<?php echo $ImageSet_data['PhotoQuality'] ?>">
                                        <input type="hidden" id="PhotoQuality" name="PhotoQuality"
                                            value="<?php echo $ImageSet_data['PhotoQuality'] ?>">
                                        <span class="help-block"><small>数值越小 图片文件体积越小（建议30-50之间） 压缩质量为100时 则关闭压缩</small></span>
                                    </div>
                                    <div class="form-group mb-3 text_right">
                                        <button class="btn btn-primary" type="button" id="ImgSetInfo">提交修改</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> <!-- end card-->

                    <div class="row mt-3">
                        <div class="col-md-6 col-xl-4">
                            <div class="card cloudStorageCard <?php echo $CloudStorageConfig_data[0]['Current'] == 1 ? 'active' : '' ?>">
                                <div class="card-body">
                                    <?php if ($CloudStorageConfig_data[0]['AccessKey'] && $CloudStorageConfig_data[0]['SecretKey']): ?>
                                        <div class="activeVariable" data-cloudid="1">
                                            <svg t="1728638243880" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="10773" width="200" height="200">
                                                <path d="M895.616384 347.812188q0 10.22977-0.511489 19.436563t-1.534466 19.436563q-9.206793 84.907093-37.338661 163.164835t-71.096903 150.377622-99.228771 138.613387-121.734266 127.872128q-9.206793 11.252747-23.528472 11.252747-15.344655 0-24.551449-11.252747-65.470529-61.378621-122.245754-128.895105t-100.251748-141.170829-71.608392-152.935065-36.315684-165.210789q0-8.183816-0.511489-15.344655t-0.511489-15.344655q0-71.608392 28.131868-135.032967t76.211788-110.481518 113.038961-74.677323 138.613387-27.62038 138.101898 27.62038 112.527473 74.677323 76.211788 110.481518 28.131868 135.032967zM540.643357 507.396603q33.758242 0 63.424575-12.787213t51.66034-34.26973 34.781219-50.637363 12.787213-61.89011-12.787213-61.89011-34.781219-50.637363-51.66034-34.26973-63.424575-12.787213-63.424575 12.787213-52.171828 34.26973-35.292707 50.637363-12.787213 61.89011 12.787213 61.89011 35.292707 50.637363 52.171828 34.26973 63.424575 12.787213z" p-id="10774"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                    <div class="cloudStorageContent">
                                        <div class="cloudStorageLogo">
                                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300.7 179.8" width="80" height="80">
                                                <path class="st0" d="M84.7 65.3c0 3.7.4 6.7 1.1 8.9.8 2.2 1.8 4.6 3.2 7.2.5.8.7 1.6.7 2.3 0 1-.6 2-1.9 3l-6.3 4.2c-.9.6-1.8.9-2.6.9-1 0-2-.5-3-1.4-1.4-1.5-2.6-3.1-3.6-4.7-1-1.7-2-3.6-3.1-5.9-7.8 9.2-17.6 13.8-29.4 13.8-8.4 0-15.1-2.4-20-7.2-4.9-4.8-7.4-11.2-7.4-19.2 0-8.5 3-15.4 9.1-20.6s14.2-7.8 24.5-7.8c3.4 0 6.9.3 10.6.8s7.5 1.3 11.5 2.2v-7.3c0-7.6-1.6-12.9-4.7-16-3.2-3.1-8.6-4.6-16.3-4.6-3.5 0-7.1.4-10.8 1.3-3.7.9-7.3 2-10.8 3.4-1.6.7-2.8 1.1-3.5 1.3-.7.2-1.2.3-1.6.3-1.4 0-2.1-1-2.1-3.1v-4.9c0-1.6.2-2.8.7-3.5s1.4-1.4 2.8-2.1c3.5-1.8 7.7-3.3 12.6-4.5C39.3.8 44.5.2 50 .2c11.9 0 20.6 2.7 26.2 8.1 5.5 5.4 8.3 13.6 8.3 24.6v32.4h.2zM44.1 80.5c3.3 0 6.7-.6 10.3-1.8 3.6-1.2 6.8-3.4 9.5-6.4 1.6-1.9 2.8-4 3.4-6.4s1-5.3 1-8.7V53c-2.9-.7-6-1.3-9.2-1.7s-6.3-.6-9.4-.6c-6.7 0-11.6 1.3-14.9 4-3.3 2.7-4.9 6.5-4.9 11.5 0 4.7 1.2 8.2 3.7 10.6 2.4 2.5 5.9 3.7 10.5 3.7zm80.3 10.8c-1.8 0-3-.3-3.8-1-.8-.6-1.5-2-2.1-3.9L95 9.1c-.6-2-.9-3.3-.9-4 0-1.6.8-2.5 2.4-2.5h9.8c1.9 0 3.2.3 3.9 1 .8.6 1.4 2 2 3.9L129 73.7l15.6-66.2c.5-2 1.1-3.3 1.9-3.9.8-.6 2.2-1 4-1h8c1.9 0 3.2.3 4 1 .8.6 1.5 2 1.9 3.9l15.8 67 17.3-67c.6-2 1.3-3.3 2-3.9.8-.6 2.1-1 3.9-1h9.3c1.6 0 2.5.8 2.5 2.5 0 .5-.1 1-.2 1.6-.1.6-.3 1.4-.7 2.5l-24.1 77.3c-.6 2-1.3 3.3-2.1 3.9s-2.1 1-3.8 1h-8.6c-1.9 0-3.2-.3-4-1s-1.5-2-1.9-4l-15.5-64.5-15.4 64.4c-.5 2-1.1 3.3-1.9 4-.8.7-2.2 1-4 1h-8.6zM252.9 94c-5.2 0-10.4-.6-15.4-1.8-5-1.2-8.9-2.5-11.5-4-1.6-.9-2.7-1.9-3.1-2.8-.4-.9-.6-1.9-.6-2.8v-5.1c0-2.1.8-3.1 2.3-3.1.6 0 1.2.1 1.8.3.6.2 1.5.6 2.5 1 3.4 1.5 7.1 2.7 11 3.5 4 .8 7.9 1.2 11.9 1.2 6.3 0 11.2-1.1 14.6-3.3 3.4-2.2 5.2-5.4 5.2-9.5 0-2.8-.9-5.1-2.7-7-1.8-1.9-5.2-3.6-10.1-5.2l-14.5-4.5c-7.3-2.3-12.7-5.7-16-10.2-3.3-4.4-5-9.3-5-14.5 0-4.2.9-7.9 2.7-11.1s4.2-6 7.2-8.2c3-2.3 6.4-4 10.4-5.2S251.8 0 256.2 0c2.2 0 4.5.1 6.7.4 2.3.3 4.4.7 6.5 1.1 2 .5 3.9 1 5.7 1.6 1.8.6 3.2 1.2 4.2 1.8 1.4.8 2.4 1.6 3 2.5.6.8.9 1.9.9 3.3v4.7c0 2.1-.8 3.2-2.3 3.2-.8 0-2.1-.4-3.8-1.2-5.7-2.6-12.1-3.9-19.2-3.9-5.7 0-10.2.9-13.3 2.8s-4.7 4.8-4.7 8.9c0 2.8 1 5.2 3 7.1 2 1.9 5.7 3.8 11 5.5l14.2 4.5c7.2 2.3 12.4 5.5 15.5 9.6s4.6 8.8 4.6 14c0 4.3-.9 8.2-2.6 11.6-1.8 3.4-4.2 6.4-7.3 8.8-3.1 2.5-6.8 4.3-11.1 5.6-4.5 1.4-9.2 2.1-14.3 2.1z" fill="#252f3e" />
                                                <path class="st1" d="M271.8 142.6c-32.9 24.3-80.7 37.2-121.8 37.2-57.6 0-109.5-21.3-148.7-56.7-3.1-2.8-.3-6.6 3.4-4.4 42.4 24.6 94.7 39.5 148.8 39.5 36.5 0 76.6-7.6 113.5-23.2 5.5-2.5 10.2 3.6 4.8 7.6z" fill="#f90" />
                                                <path class="st1" d="M285.5 127c-4.2-5.4-27.8-2.6-38.5-1.3-3.2.4-3.7-2.4-.8-4.5 18.8-13.2 49.7-9.4 53.3-5 3.6 4.5-1 35.4-18.6 50.2-2.7 2.3-5.3 1.1-4.1-1.9 4-9.9 12.9-32.2 8.7-37.5z" fill="#f90" />
                                            </svg>
                                        </div>
                                        <div class="cloudStorageName">
                                            <h4>AWS S3</h4>
                                        </div>
                                        <div class="cloudStorageDesc">
                                            <p>AWS S3 是亚马逊的对象存储服务，兼容多种客户端，能够与 MinIO 等存储解决方案无缝对接。</p>
                                        </div>
                                    </div>
                                    <div class="cloudStorageFooterBtn">
                                        <button data-cloudinfo='<?php echo htmlspecialchars(json_encode($CloudStorageConfig_data[0], JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>' type="button" class="btn btn-outline-dark btn-rounded" onclick="PublicPopUpLayer(7, '', this.dataset.cloudinfo)">
                                            <i class="mdi mdi-calendar-edit mr-1"></i>
                                            配置</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-4">
                            <div class="card cloudStorageCard <?php echo $CloudStorageConfig_data[1]['Current'] == 1 ? 'active' : '' ?>">
                                <div class="card-body">
                                    <?php if ($CloudStorageConfig_data[1]['AccessKey'] && $CloudStorageConfig_data[1]['SecretKey']): ?>
                                        <div class="activeVariable" data-cloudid="2">
                                            <svg t="1728638243880" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="10773" width="200" height="200">
                                                <path d="M895.616384 347.812188q0 10.22977-0.511489 19.436563t-1.534466 19.436563q-9.206793 84.907093-37.338661 163.164835t-71.096903 150.377622-99.228771 138.613387-121.734266 127.872128q-9.206793 11.252747-23.528472 11.252747-15.344655 0-24.551449-11.252747-65.470529-61.378621-122.245754-128.895105t-100.251748-141.170829-71.608392-152.935065-36.315684-165.210789q0-8.183816-0.511489-15.344655t-0.511489-15.344655q0-71.608392 28.131868-135.032967t76.211788-110.481518 113.038961-74.677323 138.613387-27.62038 138.101898 27.62038 112.527473 74.677323 76.211788 110.481518 28.131868 135.032967zM540.643357 507.396603q33.758242 0 63.424575-12.787213t51.66034-34.26973 34.781219-50.637363 12.787213-61.89011-12.787213-61.89011-34.781219-50.637363-51.66034-34.26973-63.424575-12.787213-63.424575 12.787213-52.171828 34.26973-35.292707 50.637363-12.787213 61.89011 12.787213 61.89011 35.292707 50.637363 52.171828 34.26973 63.424575 12.787213z" p-id="10774"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?>

                                    <div class="cloudStorageContent">
                                        <div class="cloudStorageLogo">
                                            <svg t="1728627454747" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4123" width="200" height="200">
                                                <path d="M512 170.666667c130.474667 0 240.938667 83.797333 277.930667 199.296a198.826667 198.826667 0 0 0-41.557334-0.597334 222.293333 222.293333 0 0 0-49.706666 10.624C668.202667 309.333333 596.096 259.754667 512 259.754667c-100.266667 0-183.466667 70.528-199.381333 163.029333a279.04 279.04 0 0 0-89.429334-3.84C241.28 278.954667 363.690667 170.666667 512 170.666667z" fill="#006DFE" p-id="4124"></path>
                                                <path d="M258.474667 417.322667c54.442667 0 104.192 20.181333 142.165333 53.418666 16.085333 14.08 45.226667 39.68 87.381333 76.8l-7.381333-6.528-61.568 60.885334-54.4-54.4c-34.218667-34.261333-66.090667-47.957333-106.197333-47.957334a133.589333 133.589333 0 0 0 0 267.221334c10.666667 0 29.312 0.768 56.064 2.346666l-90.453334 77.141334A215.893333 215.893333 0 0 1 258.432 417.28z" fill="#00CDD8" p-id="4125"></path>
                                                <path d="M674.346667 434.474667a215.808 215.808 0 0 1 168.618666 397.354666c-15.36 6.485333-38.186667 15.957333-63.146666 16.213334-72.106667 0.597333-244.181333 0.896-516.352 0.938666h-42.666667a206248.106667 206248.106667 0 0 0 397.013333-380.714666c18.261333-17.578667 41.130667-27.264 56.533334-33.792z m41.856 80.554666c-9.258667 3.925333-23.04 9.770667-34.048 20.352-30.165333 29.098667-109.952 105.642667-239.445334 229.632h53.418667c148.181333 0 242.773333-0.213333 283.733333-0.554666 15.061333-0.128 28.842667-5.845333 38.101334-9.813334a130.133333 130.133333 0 0 0-101.76-239.616z" fill="#00A2FF" p-id="4126"></path>
                                            </svg>
                                        </div>
                                        <div class="cloudStorageName">
                                            <h4>腾讯云 COS</h4>
                                        </div>
                                        <div class="cloudStorageDesc">
                                            <p>腾讯云 COS（对象存储）是腾讯提供的高可用、高性能的云存储服务，兼容 AWS S3 协议。</p>
                                        </div>
                                    </div>
                                    <div class="cloudStorageFooterBtn">
                                        <button data-cloudinfo='<?php echo htmlspecialchars(json_encode($CloudStorageConfig_data[1], JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>' type="button" class="btn btn-outline-dark btn-rounded" onclick="PublicPopUpLayer(7, '', this.dataset.cloudinfo)">
                                            <i class="mdi mdi-calendar-edit mr-1"></i>
                                            配置</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6 col-xl-4">
                            <div class="card cloudStorageCard <?php echo $CloudStorageConfig_data[2]['Current'] == 1 ? 'active' : '' ?>">
                                <div class="card-body">
                                    <div class="cloudStorageContent">
                                        <?php if ($CloudStorageConfig_data[2]['AccessKey'] && $CloudStorageConfig_data[2]['SecretKey']): ?>
                                            <div class="activeVariable" data-cloudid="3">
                                                <svg t="1728638243880" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="10773" width="200" height="200">
                                                    <path d="M895.616384 347.812188q0 10.22977-0.511489 19.436563t-1.534466 19.436563q-9.206793 84.907093-37.338661 163.164835t-71.096903 150.377622-99.228771 138.613387-121.734266 127.872128q-9.206793 11.252747-23.528472 11.252747-15.344655 0-24.551449-11.252747-65.470529-61.378621-122.245754-128.895105t-100.251748-141.170829-71.608392-152.935065-36.315684-165.210789q0-8.183816-0.511489-15.344655t-0.511489-15.344655q0-71.608392 28.131868-135.032967t76.211788-110.481518 113.038961-74.677323 138.613387-27.62038 138.101898 27.62038 112.527473 74.677323 76.211788 110.481518 28.131868 135.032967zM540.643357 507.396603q33.758242 0 63.424575-12.787213t51.66034-34.26973 34.781219-50.637363 12.787213-61.89011-12.787213-61.89011-34.781219-50.637363-51.66034-34.26973-63.424575-12.787213-63.424575 12.787213-52.171828 34.26973-35.292707 50.637363-12.787213 61.89011 12.787213 61.89011 35.292707 50.637363 52.171828 34.26973 63.424575 12.787213z" p-id="10774"></path>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        <div class="cloudStorageLogo">
                                            <svg t="1728627492805" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4443" width="200" height="200">
                                                <path d="M512 64a448 448 0 1 1 0 896A448 448 0 0 1 512 64z" fill="#FF6A00" p-id="4444"></path>
                                                <path d="M324.8 602.624a26.752 26.752 0 0 1-21.312-25.92v-142.72a27.712 27.712 0 0 1 21.376-25.984l132.416-28.672 13.952-56.896H317.312a97.6 97.6 0 0 0-98.24 96.96v169.344c0.384 54.08 44.16 97.856 98.24 98.176h153.92l-13.888-56.512-132.544-27.776zM710.4 322.432c54.016 0.128 97.92 43.584 98.56 97.6v170.176a98.368 98.368 0 0 1-98.56 98.048H555.328l14.08-56.832 132.608-28.736a27.84 27.84 0 0 0 21.376-25.92v-142.72a26.88 26.88 0 0 0-21.376-25.984l-132.544-28.8-14.08-56.832zM570.368 497.92v13.952H457.28v-13.952h113.088z" fill="#FFFFFF" p-id="4445"></path>
                                            </svg>
                                        </div>
                                        <div class="cloudStorageName">
                                            <h4>阿里云 OSS</h4>
                                        </div>
                                        <div class="cloudStorageDesc">
                                            <p>阿里云OSS 是阿里云提供的对象存储服务，提供可扩展性和安全的数据存储解决方案。</p>
                                        </div>
                                    </div>
                                    <div class="cloudStorageFooterBtn">
                                        <button data-cloudinfo='<?php echo htmlspecialchars(json_encode($CloudStorageConfig_data[2], JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>' type="button" class="btn btn-outline-dark btn-rounded" onclick="PublicPopUpLayer(7, '', this.dataset.cloudinfo)">
                                            <i class="mdi mdi-calendar-edit mr-1"></i>
                                            配置</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-4">
                            <div class="card cloudStorageCard <?php echo $CloudStorageConfig_data[3]['Current'] == 1 ? 'active' : '' ?>">
                                <div class="card-body">
                                    <?php if ($CloudStorageConfig_data[3]['AccessKey'] && $CloudStorageConfig_data[3]['SecretKey']): ?>
                                        <div class="activeVariable" data-cloudid="4">
                                            <svg t="1728638243880" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="10773" width="200" height="200">
                                                <path d="M895.616384 347.812188q0 10.22977-0.511489 19.436563t-1.534466 19.436563q-9.206793 84.907093-37.338661 163.164835t-71.096903 150.377622-99.228771 138.613387-121.734266 127.872128q-9.206793 11.252747-23.528472 11.252747-15.344655 0-24.551449-11.252747-65.470529-61.378621-122.245754-128.895105t-100.251748-141.170829-71.608392-152.935065-36.315684-165.210789q0-8.183816-0.511489-15.344655t-0.511489-15.344655q0-71.608392 28.131868-135.032967t76.211788-110.481518 113.038961-74.677323 138.613387-27.62038 138.101898 27.62038 112.527473 74.677323 76.211788 110.481518 28.131868 135.032967zM540.643357 507.396603q33.758242 0 63.424575-12.787213t51.66034-34.26973 34.781219-50.637363 12.787213-61.89011-12.787213-61.89011-34.781219-50.637363-51.66034-34.26973-63.424575-12.787213-63.424575 12.787213-52.171828 34.26973-35.292707 50.637363-12.787213 61.89011 12.787213 61.89011 35.292707 50.637363 52.171828 34.26973 63.424575 12.787213z" p-id="10774"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?>

                                    <div class="cloudStorageContent">
                                        <div class="cloudStorageLogo">
                                            <svg t="1728628805425" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4602" width="200" height="200">
                                                <path d="M512 938.667c235.648 0 426.667-191.019 426.667-426.667S747.648 85.333 512 85.333 85.333 276.352 85.333 512 276.352 938.667 512 938.667z m290.73-597.078c3.969-0.896 6.145 0.854 7.169 2.688 1.664 2.987 0.085 7.168 0.085 7.168-42.155 120.192-158.293 202.07-297.941 201.515h-0.086a339.883 339.883 0 0 1-81.365-9.43l35.541 119.723s1.963 16.342 20.95 16.342h57.002s24.875 1.92 30.464-19.328c5.334-20.182 25.6-67.414 25.6-67.414s6.315-19.2 25.088-32.512c18.774-13.312 28.032-13.013 29.227-12.288 0.981 0.64 0.64 2.176 0.64 2.176l-19.499 113.536c-2.176 37.078-28.8 61.568-64.341 61.568H464.939c-35.499 0-62.848-22.4-64.299-61.568l-21.376-137.301c-77.568-33.28-137.472-95.83-165.248-175.019 0 0-1.579-4.224 0.085-7.168 1.024-1.877 3.2-3.584 7.168-2.688 1.152 0.256 4.182 3.755 9.174 9.472 18.773 21.632 65.066 75.094 142.848 106.966l-7.979-56.747s-0.17-1.28 0.81-2.432c1.067-1.28 6.273-2.688 15.19 1.792a32.213 32.213 0 0 1 15.957 19.328l15.702 51.499a351.147 351.147 0 0 0 98.986 11.733h0.086c164.01 3.37 253.525-99.84 281.514-132.139 4.992-5.717 8.022-9.216 9.174-9.472z" fill="#56C2FF" p-id="4603"></path>
                                            </svg>
                                        </div>
                                        <div class="cloudStorageName">
                                            <h4>七牛云 Kodo</h4>
                                        </div>
                                        <div class="cloudStorageDesc">
                                            <p>七牛云对象存储系统（Kodo）是自主研发的非结构化数据存储管理平台，支持中心和边缘存储。</p>
                                        </div>
                                    </div>
                                    <div class="cloudStorageFooterBtn">
                                        <button data-cloudinfo='<?php echo htmlspecialchars(json_encode($CloudStorageConfig_data[3], JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>' type="button" class="btn btn-outline-dark btn-rounded" onclick="PublicPopUpLayer(7, '', this.dataset.cloudinfo)">
                                            <i class="mdi mdi-calendar-edit mr-1"></i>
                                            配置</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-4">
                            <div class="card cloudStorageCard <?php echo $CloudStorageConfig_data[4]['Current'] == 1 ? 'active' : '' ?>">
                                <div class="card-body">
                                    <?php if ($CloudStorageConfig_data[4]['AccessKey'] && $CloudStorageConfig_data[4]['SecretKey']): ?>
                                        <div class="activeVariable" data-cloudid="5">
                                            <svg t="1728638243880" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="10773" width="200" height="200">
                                                <path d="M895.616384 347.812188q0 10.22977-0.511489 19.436563t-1.534466 19.436563q-9.206793 84.907093-37.338661 163.164835t-71.096903 150.377622-99.228771 138.613387-121.734266 127.872128q-9.206793 11.252747-23.528472 11.252747-15.344655 0-24.551449-11.252747-65.470529-61.378621-122.245754-128.895105t-100.251748-141.170829-71.608392-152.935065-36.315684-165.210789q0-8.183816-0.511489-15.344655t-0.511489-15.344655q0-71.608392 28.131868-135.032967t76.211788-110.481518 113.038961-74.677323 138.613387-27.62038 138.101898 27.62038 112.527473 74.677323 76.211788 110.481518 28.131868 135.032967zM540.643357 507.396603q33.758242 0 63.424575-12.787213t51.66034-34.26973 34.781219-50.637363 12.787213-61.89011-12.787213-61.89011-34.781219-50.637363-51.66034-34.26973-63.424575-12.787213-63.424575 12.787213-52.171828 34.26973-35.292707 50.637363-12.787213 61.89011 12.787213 61.89011 35.292707 50.637363 52.171828 34.26973 63.424575 12.787213z" p-id="10774"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                    <div class="cloudStorageContent">
                                        <div class="cloudStorageLogo">
                                            <svg t="1728628830477" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4764" width="200" height="200">
                                                <path d="M819.733081 102.739233a479.288216 479.288216 0 0 0-59.60814-38.284903 33.923332 33.923332 0 0 0-43.131093 8.723143L589.054425 242.309513l-7.269285 13.084713a70.269759 70.269759 0 0 1-59.123521 27.623285h-25.20019a228.255561 228.255561 0 0 0-213.71699 239.886417 25.200189 25.200189 0 0 0 14.538571 21.807856 60.092759 60.092759 0 1 1-82.385234 71.723616v-3.876952a60.577378 60.577378 0 0 1 11.630856-48.461903 36.831046 36.831046 0 0 0 7.753905-26.654046 279.140558 279.140558 0 0 1 276.717463-303.37151 51.854236 51.854236 0 0 0 41.192617-20.838618l117.762423-156.531945a21.323237 21.323237 0 0 0-11.630856-33.923332A511.75769 511.75769 0 0 0 263.39044 959.54567a33.923332 33.923332 0 0 0 43.615712-9.207762L436.399432 780.721249l8.723143-12.115475a70.754378 70.754378 0 0 1 59.123521-27.623285 218.56318 218.56318 0 0 0 25.200189 0 230.194037 230.194037 0 0 0 168.162802-90.623757 225.832466 225.832466 0 0 0 45.554188-149.26266 25.200189 25.200189 0 0 0-14.538571-21.807856 60.092759 60.092759 0 0 1-35.861808-43.131093 60.092759 60.092759 0 0 1 117.277804-27.138666 14.053952 14.053952 0 0 0 0 4.361571 61.061997 61.061997 0 0 1-11.630856 48.461903 36.831046 36.831046 0 0 0-7.753905 26.654046 279.140558 279.140558 0 0 1-276.717463 303.37151 51.854236 51.854236 0 0 0-41.192617 20.838618L355.952674 969.23805a21.807856 21.807856 0 0 0 11.146238 33.923332 512.24231 512.24231 0 0 0 452.634169-900.422149z" fill="#009FFF" p-id="4765"></path>
                                            </svg>
                                        </div>
                                        <div class="cloudStorageName">
                                            <h4>又拍云 USS</h4>
                                        </div>
                                        <div class="cloudStorageDesc">
                                            <p>又拍云 提供高效的云存储和内容分发服务，专注于数据安全与性能优化，兼容 AWS S3 协议。</p>
                                        </div>
                                    </div>
                                    <div class="cloudStorageFooterBtn">
                                        <button data-cloudinfo='<?php echo htmlspecialchars(json_encode($CloudStorageConfig_data[4], JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>' type="button" class="btn btn-outline-dark btn-rounded" onclick="PublicPopUpLayer(7, '', this.dataset.cloudinfo)">
                                            <i class="mdi mdi-calendar-edit mr-1"></i>
                                            配置</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div> <!-- end custom accordions-->

            </div>
        </div>
    </div>
</div>

<script>
    function myOnClickHandler(obj) {
        var input = document.getElementById("TextWateSwitch");
        if (obj.checked) {
            input.setAttribute("value", "true");
        } else {
            input.setAttribute("value", "false");
        }
    }

    $(document).ready(function() {
        $('#RangeValue').text($("#example-range").val())
        $('#ColorOpacityValue').text($("#ColorOpacity").val())
        $('#example-range').on('input', function() {
            $('#RangeValue').text($(this).val());
            $('#PhotoQuality').val($(this).val());
        });
        $('#ColorOpacity').on('input', function() {
            $('#ColorOpacityValue').text($(this).val());
            $('#OpacityValue').val($(this).val());
        });
        $('#colorPicker').on('input', function() {
            var colorHex = $(this).val();
            var rgb = hexToRgb(colorHex);
            $('#rgbValue').val(rgb.r + ',' + rgb.g + ',' + rgb.b);
        });

        function hexToRgb(hex) {
            hex = hex.replace('#', '');
            var r = parseInt(hex.substring(0, 2), 16);
            var g = parseInt(hex.substring(2, 4), 16);
            var b = parseInt(hex.substring(4, 6), 16);
            return {
                r: r,
                g: g,
                b: b
            };
        }

        var Copy_ImgUrl = new ClipboardJS('#CopyImgUrl', {
            target: function(trigger) {
                var tempInput = $('<input>').attr('type', 'text').addClass('temp-input').val($(trigger).attr('data-url'));
                $('body').append(tempInput); // 将输入框插入到 DOM 中
                return tempInput[0];
            }
        });

        $('#cloudStorageSwitch').on('click', function() {
            let cloudStorageSwitch = $('#cloudStorageSwitch');
            cloudStorageSwitch.val(this.checked ? 1 : 0);
            if (this.checked) {
                $('#cloudStorageSwitchText').text("当前为云存储")
            } else {
                $('#cloudStorageSwitchText').text("当前为本地存储")
            }

        });

    });
</script>

<?php
include_once 'Footer.php';
?>

</body>

</html>