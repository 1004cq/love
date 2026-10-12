<!--
 * @Page: 安装向导
 * @Author: Ki.
 * @Project: LG_NewUi
 * @LastEditors: Ki.
 * @Description: 小手一牵 岁岁年年
 * Copyright (c) 2024 by Ki All Rights Reserved.
 * Document https://blog.kikiw.cn/index.php/archives/65/
-->

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8" />
    <title>LG_NewUI情侣小站安装向导</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Coderthemes" name="Ki" />
    <link href="/admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="/Style/css/loading.css" rel="stylesheet">
    <link href="/admin/assets/css/app-modern.min.css" rel="stylesheet">
    <link href="../Style/toastr/toastr.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400&display=swap" rel="stylesheet">
</head>


<?php

checkLock();

function checkLock()
{
    if (file_exists(__DIR__ . '/install.lock')) {
        include_once "AlreadyInstalled.php";
        exit;
    }
}


function checkPHPVersion()
{
    $current_php_version = phpversion();
    if (version_compare($current_php_version, '8.2', '>=') && version_compare($current_php_version, '8.6', '<')) {
        return array(
            'status' => 'success',
            'info' => ' <strong><i class="dripicons-checkmark mr-2 font-18"></i>PHP： </strong>PHP版本符合程序要求 当前 PHP 版本为：' . $current_php_version
        );
    } else {
        return array(
            'status' => 'danger',
            'info' => '<strong><i class="dripicons-warning mr-2 font-18"></i>PHP： </strong>PHP版本不符合要求，请使用 PHP 8.2–8.5。当前 PHP 版本：' . $current_php_version
        );
    }
}

function checkExif()
{
    if (extension_loaded('exif')) {
        return array(
            'status' => 'success',
            'info' => ' <strong><i class="dripicons-checkmark mr-2 font-18"></i>EXIF： </strong>当前PHP版本已安装exif扩展'
        );
    } elseif (!extension_loaded('exif')) {
        return array(
            'status' => 'danger',
            'info' => '<strong><i class="dripicons-warning mr-2 font-18"></i>EXIF：  </strong>当前PHP版本未检测到exif扩展 请安装后重启PHP环境！'
        );
    }
}

function checkPDOExtension()
{
    if (extension_loaded('pdo')) {
        return array('status' => 'success', 'info' => '<strong><i class="dripicons-checkmark mr-2 font-18"></i>POD：  </strong>PDO 扩展已安装。');
    } else {
        return array('status' => 'danger', 'info' => '<strong><i class="dripicons-warning mr-2 font-18"></i>POD：  </strong>未安装 PDO 扩展。');
    }
}

function checkGDExtension()
{
    if (extension_loaded('gd')) {
        return array('status' => 'success', 'info' => '<strong><i class="dripicons-checkmark mr-2 font-18"></i>GD：  </strong>当前PHP版本 GD 库已安装。');
    } else {
        return array('status' => 'danger', 'info' => '<strong><i class="dripicons-warning mr-2 font-18"></i>GD：  </strong>当前PHP版本未安装 GD 库。');
    }
}

function checkSessionSupport()
{
    if (extension_loaded('session')) {
        return array('status' => 'success', 'info' => '<strong><i class="dripicons-checkmark mr-2 font-18"></i>Session：  </strong>当前环境支持 Session。');
    } else {
        return array('status' => 'danger', 'info' => '<strong><i class="dripicons-warning mr-2 font-18"></i>Session：  </strong>未启用 Session 支持。');
    }
}


function checkFilePermissions()
{
    $results = array();

    $sql_file = dirname(__DIR__) . '/install/LGNewUiDBsql.sql';
    if (file_exists($sql_file)) {
        if (is_readable($sql_file)) {
            $results[] = array('status' => 'success', 'info' => '<strong><i class="dripicons-checkmark mr-2 font-18"></i>SQL文件：  </strong>数据库 SQL 文件存在且允许读取。');
        } else {
            $results[] = array('status' => 'danger', 'info' => '<strong><i class="dripicons-warning mr-2 font-18"></i>SQL文件：  </strong>数据库 SQL 文件存在但不允许读取。');
        }
    } else {
        $results[] = array('status' => 'danger', 'info' => '<strong><i class="dripicons-warning mr-2 font-18"></i>SQL文件：  </strong>数据库 SQL 文件不存在。');
    }

    $install_lock = 'install.lock';
    $temp_file = tempnam(sys_get_temp_dir(), 'install.lock');
    if ($temp_file && @copy($temp_file, $install_lock)) {
        unlink($install_lock);
        $results[] = array('status' => 'success', 'info' => '<strong><i class="dripicons-checkmark mr-2 font-18"></i>安装锁：  </strong>可以创建 install.lock 文件。');
    } else {
        $results[] = array('status' => 'danger', 'info' => '<strong><i class="dripicons-warning mr-2 font-18"></i>安装锁：  </strong>无法创建 install.lock 文件，请先设置目录读写权限。');
    }
    @unlink($temp_file);

    $config_db = dirname(__DIR__) . '/admin/Config_DB.php';
    if (file_exists($config_db)) {
        if (is_readable($config_db) && is_writable($config_db)) {
            $results[] = array('status' => 'success', 'info' => '<strong><i class="dripicons-checkmark mr-2 font-18"></i>数据库信息配置文件：  </strong>Config_DB.php 文件允许读写。');
        } elseif (is_readable($config_db)) {
            $results[] = array('status' => 'danger', 'info' => '<strong><i class="dripicons-warning mr-2 font-18"></i>数据库信息配置文件：  </strong>Config_DB.php 文件可读但不可写。');
        } elseif (is_writable($config_db)) {
            $results[] = array('status' => 'danger', 'info' => '<strong><i class="dripicons-warning mr-2 font-18"></i>数据库信息配置文件：  </strong>Config_DB.php 文件可写但不可读。');
        } else {
            $results[] = array('status' => 'danger', 'info' => '<strong><i class="dripicons-warning mr-2 font-18"></i>数据库信息配置文件：  </strong>Config_DB.php 文件不允许读写。');
        }
    } else {
        $results[] = array('status' => 'danger', 'info' => '<strong><i class="dripicons-warning mr-2 font-18"></i>数据库信息配置文件：  </strong>Config_DB.php 文件不存在。');
    }

    return $results;
}

$checkAuthoritys = checkFilePermissions();

function checkAll()
{
    $results = array_merge(
        array(checkPHPVersion()),
        array(checkExif()),
        array(checkPDOExtension()),
        array(checkGDExtension()),
        array(checkSessionSupport()),
        checkFilePermissions()
    );

    $all_success = true;
    foreach ($results as $result) {
        if ($result['status'] !== 'success') {
            $all_success = false;
            break;
        }
    }

    return array('results' => $results, 'all_success' => $all_success);
}

$currentDateTime = new DateTime();
$datetimeLocal = $currentDateTime->format('Y-m-d\TH:i');
?>



<body>
    <div id="Loadanimation" style="z-index:999999;">
        <div id="Loadanimation-center">
            <div id="Loadanimation-center-absolute">
                <div class="xccx_object" id="xccx_four"></div>
                <div class="xccx_object" id="xccx_three"></div>
                <div class="xccx_object" id="xccx_two"></div>
                <div class="xccx_object" id="xccx_one"></div>
            </div>
        </div>
    </div>

    <style>
        #Loadanimation {
            display: none;
        }

        .btn-danger.disabled,
        .btn-danger:disabled {
            color: #fff;
            background-color: #fa5c7c;
            border-color: #fa5c7c;
            box-shadow: 0 2px 6px 0 rgba(250, 92, 124, .5);
        }

        .card .title h2,
        .copy,
        h3 {
            font-family: 'Noto Serif SC', serif;
        }

        .LatestV {
            background: #ff7272;
            color: #fff;
            padding: .1rem .3rem;
            font-size: .7rem;
            border-radius: .2rem;
            font-style: normal;
        }

        .copy {
            border-top: 1px solid #ddd;
            padding-top: 1rem;
        }

        .HiddenElements {
            display: none;
        }

        .timeline-item-info ul li {
            font-family: 'Noto Serif SC', serif;
            line-height: 2em
        }

        a.font-weight-bold.mb-1.d-block {
            font-family: 'Noto Serif SC', serif;
            font-size: 1.2rem;
            margin-left: 0.5rem;
        }

        .required_marker {
            color: #fe4001;
            vertical-align: baseline;
            margin-right: 2px;
        }

        .timeline-alt .timeline-item .timeline-icon {
            float: left;
            height: 30px;
            width: 30px;
            border-radius: 50%;
            border: 2px solid transparent;
            font-size: 16px;
            text-align: center;
            line-height: 26px;
            background-color: #fff;
        }

        .card.Install {
            border-radius: 15px;
            max-width: 1000px;
            margin: 3rem auto 5rem;
        }

        .card-header.pt-4.pb-4.text-center.bg-primary {
            border-radius: 15px 15px 0 0;
        }

        .bg-primary {
            background-color: #e91e63 !important;
        }

        .card .title {
            text-align: center;
            padding: 2rem 0 1rem;
            border-bottom: 1px dashed #b7b7b7;
        }

        .BtnCenter {
            text-align: center;
            font-family: 'Noto Serif SC', serif;
        }

        /* .btn-primary {
            padding: 10px 25px;
            border-radius: 20px;
        } */

        .bg-success {
            background-color: #0acf97 !important;
        }

        .info {
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .mar_top {
            margin-top: 20px;
        }

        .btn-success {
            border-radius: 20px;
        }

        .btn-rounded {
            border-radius: 2em;
        }

        .file_url {
            border-radius: 10px;
            margin: 0 10px;
        }

        .lengthLimit {
            height: 450px;
            overflow: auto;
            background: #f9f9f9;
            padding: 2rem 0.5rem 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .lead {
            border-bottom: 1px solid #bdbdbd;
        }

        .timeline-alt .timeline-item:before {
            background-color: #e8e8e8;
            width: 1px;
            left: 15px;
            top: 30px;
        }

        .pd-tb20 {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .font-5rem {
            font-size: 5rem;
            color: #ffc53c;
        }

        .adminInfo ul li,
        .adminInfo {
            margin: 1em 0;
            font-family: 'Noto Serif SC', serif;
        }

        .popUpLayerMask {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
            background: #00000036;
        }

        .popUpLayerMask .global_pop_up_layer {
            display: none;
            position: absolute;
            padding: 2rem;
            border-radius: 1rem;
            top: 5vh;
            left: 50%;
            background: #fff;
            max-width: 700px;
            transform: translate(-50%);
            width: 95%;
            box-sizing: border-box;
            font-family: 'Noto Serif SC';
        }

        .popUpLayerMask .global_pop_up_layer .pop_up_layer_head {
            border-bottom: 1px dashed #c9c9c9;
        }

        .popUpLayerMask .global_pop_up_layer .pop_up_layer_head .eventTitle h2 {
            font-size: 1.5rem;
            color: #fa5c7c;
            margin: 5px 0;
        }

        .popUpLayerMask .global_pop_up_layer .pop_up_layer_head .eventTitle h2.lookText {
            color: #03A9F4;
        }

        .popUpLayerMask .global_pop_up_layer .pop_up_layer_content {
            margin: 1rem 0 2rem;
            font-size: 1rem;
            line-height: 1.8rem;
            overflow: auto;
            overflow-x: hidden;
            max-height: 50vh;
            color: #aaa3a3;
        }

        .popUpLayerMask .global_pop_up_layer .pop_up_layer_content p {
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .popUpLayerMask .global_pop_up_layer .pop_up_layer_content i {
            text-decoration: underline;
            text-decoration-style: solid;
            text-underline-offset: 3px;
            color: #26c387;
            word-break: break-all;
            margin-right: 1rem;
            font-style: normal;
            font-weight: 700
        }

        .popUpLayerMask .global_pop_up_layer .pop_up_layer_content i.err {
            color: #fb5d5d;
        }

        .popUpLayerMask .global_pop_up_layer .close_pop_up_layer {
            position: absolute;
            right: 0;
            top: 0;
            font-size: 1.8rem;
            transform: translate(-50%);
            border-radius: 1rem;
            color: #a9a9a9;
            transition: .2s all;
        }

        .popUpLayerMask .global_pop_up_layer .close_pop_up_layer:hover {
            color: #fa5c7c;
            cursor: pointer;
        }

        .popUpLayerMask .global_pop_up_layer .pop_up_layer_footer {
            display: flex;
            align-items: center;
            justify-content: end;
        }

        .popUpLayerMask .global_pop_up_layer .pop_up_layer_footer .btn {
            padding: .25rem 1rem;
            font-weight: bold;
        }

        ::-webkit-scrollbar-track {
            background: rgba(135, 135, 135, .1)
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(135, 135, 135, .4);
            border-radius: 10px
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #727272;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px
        }

        ::-webkit-scrollbar-corner {
            background: unset
        }

        .float-end {
            font-family: 'Noto Serif SC', serif;
            font-weight: 700;
        }

        .Wizard {
            font-size: 1.1rem;
            font-family: 'Noto Serif SC', serif;
            font-weight: 700;
        }

        .LGNewUI {
            position: relative;
        }

        .ProText {
            position: absolute;
            top: 2px;
            margin-left: .3rem;
            font-size: .8rem;
            background: #4298fd;
            color: #fff;
            padding: .1rem .2rem;
            border-radius: .3rem;
        }

        .text-info {
            color: #4298fd !important;
            transition: all .2s;
        }

        a.text-info:focus,
        a.text-info:hover {
            color: #7c7c7c !important;
        }
    </style>

    <div class="popUpLayerMask">
        <div class="global_pop_up_layer">
            <div class="close_pop_up_layer">
                <i class="mdi mdi-close"></i>
            </div>
            <div class="pop_up_layer_head">
                <div class="eventTitle">
                    <h2 class="lookText"><i class="mdi mdi-alert-octagon-outline" style="margin-right:.3rem"></i>版本更新 —> 冬至限定
                    </h2>
                    <div style="line-height: 2em;"><span>你是否确定进行版本更新 建议操作前先进行备份数据库！</span></div>
                    <div style="line-height: 2em;"><span>当前更新仅支持 <b style="color:#ffd044">LG_NewUi 秋季限定</b> 其他版本请勿执行更新</span></div>
                </div>
            </div>
            <div class="pop_up_layer_content">

            </div>
            <div class="pop_up_layer_footer">
                <button id="popUpLayerClose" type="button" class="btn btn-outline-danger btn-rounded"
                    style="margin-right:.3rem">取消</button>
                <button type="button" class="btn btn-outline-secondary btn-rounded" id="confirmExecution">确认更新</button>
            </div>
        </div>
    </div>


    <div class="card Install">
        <div class="title">
            <h2><span class="LGNewUI">LG_NewUI<b class="ProText">Pro</b></span></h2>
            <p class="mb-3 Wizard">安装向导</p>
            <a href="https://lguiy.kikiw.cn/wiki/#/" target="_blank">
                <button type="button" class="btn btn-outline-warning btn-rounded">LGNewUi — WiKi</button>
            </a>
        </div>
        <div class="card-body">
            <form method="post" action="#" class="form-horizontal was-validated">
                <div id="progressbarwizard">
                    <ul class="nav nav-pills nav-justified form-wizard-header mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="#account-2" style="pointer-events: none;user-select: none;" data-bs-toggle="tab" data-toggle="tab"
                                class="nav-link rounded-0 py-2 active" aria-selected="true" role="tab">
                                <i class=" mdi mdi-content-save-edit-outline font-18 align-middle me-1"></i>
                                <span class="d-none d-sm-inline">程序声明</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#profile-tab-2" style="pointer-events: none;user-select: none;" data-bs-toggle="tab" data-toggle="tab"
                                class="nav-link rounded-0 py-2" aria-selected="false" tabindex="-1" role="tab">
                                <i class=" mdi mdi-database-refresh font-18 align-middle me-1"></i>
                                <span class="d-none d-sm-inline">信息配置</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#finish-2" style="pointer-events: none;user-select: none;" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2"
                                aria-selected="false" tabindex="-1" role="tab">
                                <i class="mdi mdi-checkbox-marked-circle-outline font-18 align-middle me-1"></i>
                                <span class="d-none d-sm-inline">安装完成</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content b-0 mb-0">

                        <div id="bar" class="progress mb-3" style="height: 7px;">
                            <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success"
                                style="width: 33.3333%;"></div>
                        </div>

                        <div class="tab-pane active show" id="account-2" role="tabpanel">
                            <div class="row lengthLimit">
                                <div class="col-12">
                                    <div class="BtnCenter">
                                        <h3>程序使用声明</h3>
                                    </div>
                                    <p class="lead">
                                        <i class="dripicons-warning mr-2 font-16"
                                            style="vertical-align: top;"></i>购买情侣小站程序后，您将获得程序的最新版本。后续所有的升级版本均为免费。本程序所有代码无加密，无需授权域名，购买程序后可随意使用，但不允许有任何泄露源代码行为。包括但不限于分发、转售、上传至开源平台、代替他人购买。如有以上行为，将不再提供程序更新等售后服务。因程序属于可复制的商品，在代码发出后不予退款，敬请谅解。
                                    </p>

                                    <div class="BtnCenter pd-tb20">
                                        <h3>程序环境要求</h3>
                                    </div>
                                    <div class="CheckSetting">

                                        <div class="alert alert-<?php echo checkPHPVersion()['status'] ?> alert-dismissible bg-<?php echo checkPHPVersion()['status'] ?> text-white border-0 fade show"
                                            role="alert">
                                            <?php echo checkPHPVersion()['info'] ?>
                                        </div>

                                        <div class="alert alert-<?php echo checkPDOExtension()['status'] ?> alert-dismissible bg-<?php echo checkPDOExtension()['status'] ?> text-white border-0 fade show"
                                            role="alert">
                                            <?php echo checkPDOExtension()['info'] ?>
                                        </div>

                                        <div class="alert alert-<?php echo checkGDExtension()['status'] ?> alert-dismissible bg-<?php echo checkGDExtension()['status'] ?> text-white border-0 fade show"
                                            role="alert">
                                            <?php echo checkGDExtension()['info'] ?>
                                        </div>

                                        <div class="alert alert-<?php echo checkSessionSupport()['status'] ?> alert-dismissible bg-<?php echo checkSessionSupport()['status'] ?> text-white border-0 fade show"
                                            role="alert">
                                            <?php echo checkSessionSupport()['info'] ?>
                                        </div>

                                        <div class="alert alert-dan<?php echo checkExif()['status'] ?>ger alert-dismissible bg-<?php echo checkExif()['status'] ?> text-white border-0 fade show"
                                            role="alert">
                                            <?php echo checkExif()['info'] ?>
                                        </div>
                                        <hr>
                                        <div class="BtnCenter pd-tb20">
                                            <h3>文件读写权限</h3>
                                        </div>

                                        <?php foreach ($checkAuthoritys as $result): ?>
                                            <div class="alert alert-dan<?php echo $result['status'] ?>ger alert-dismissible bg-<?php echo $result['status'] ?> text-white border-0 fade show"
                                                role="alert">
                                                <?php echo $result['info'] ?>
                                            </div>

                                        <?php endforeach; ?>

                                    </div>

                                    <hr>

                                    <div class="timeline-alt pb-0">
                                        <div class="BtnCenter">
                                            <h3>程序更新详情</h3>
                                        </div>
                                        <div id="updateContainer">

                                        </div>

                                        <p class="copy BtnCenter">Copyright © <?php echo date("Y") ?> LG_NewUi安装向导 All
                                            Rights Reserved.</p>

                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->

                            <ul class="list-inline wizard mb-0 BtnCenter">
                                <?php if (checkAll()['all_success']): ?>
                                    <li class="next list-inline-item">
                                        <a href="javascript:void(0);" class="btn btn-dark">开始安装<i
                                                class="mdi mdi-arrow-right ms-1"></i></a>
                                    </li>
                                <?php endif ?>
                                <?php if (!checkAll()['all_success']): ?>
                                    <button type="button" class="btn btn-danger" id="noInstall"><i
                                            class="mdi mdi-alert-circle-outline"></i>请先检查当前环境</button>
                                <?php endif ?>

                            </ul>
                        </div>

                        <div class="tab-pane" id="profile-tab-2" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label">
                                            <b class="required_marker">*</b> 数据库地址</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="localhost" value="localhost"
                                                placeholder="请输入数据库地址" required="">
                                            <span class="help-block"><small>数据库地址
                                                    通常使用<code>localhost</code>或<code>127.0.0.1</code></small></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label"><b class="required_marker">*</b>
                                            数据库用户名</label>
                                        <div class="col-md-9">
                                            <input type="text" name="userName" class="form-control" value="root"
                                                placeholder="请输入数据库用户名" required>
                                            <span class="help-block"><small>使用宝塔面板创建的数据库 用户名和库名默认一致</small></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label"><b class="required_marker">*</b>
                                            数据库密码</label>
                                        <div class="col-md-9">
                                            <input type="text" name="password" class="form-control" value="123456"
                                                placeholder="请输入数据库密码" required="">
                                            <span class="help-block"><small>需自行前往数据库管理面板查看</small></span>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label"><b class="required_marker">*</b>
                                            数据库名</label>
                                        <div class="col-md-9">
                                            <input type="text" name="DBName" class="form-control" value="DB_Name"
                                                placeholder="请输入数据库名" required>
                                            <span class="help-block"><small>使用宝塔面板创建的数据库
                                                    用户名和库名默认一致。<br><code>请注意，可能数据库会不支持纯数字库名</code></small></span>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label"><b class="required_marker">*</b>
                                            自定义安全码</label>
                                        <div class="col-md-9">
                                            <input type="text" name="SecureCode" class="form-control" value="love"
                                                placeholder="请输入安全码" required>
                                            <span class="help-block"><small>自定义安全码 管理员修改后台敏感内容时需填写</small></span>
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                            <hr>
                            <div class="BtnCenter pd-tb20">
                                <button type="button" id="checkBtn" class="btn btn-outline-warning btn-rounded">
                                    <i class="mdi mdi-autorenew"></i>
                                    检测连接状态</button>
                            </div>


                            <ul class="pager wizard mb-0 list-inline">
                                <li class="previous list-inline-item disabled">
                                    <button type="button" class="btn btn-light"><i class="mdi mdi-arrow-left me-1"></i>
                                        上一步</button>
                                </li>

                                <li id="InstallNext" class="list-inline-item float-end">
                                    <button type="button" class="btn btn-outline-success btn-rounded" id="UpdateVPost"
                                        disabled>更新版本 <i class="mdi mdi-database-settings ms-1"></i></button>
                                    <button type="button" class="btn btn-dark btn-rounded" id="SubmitPost"
                                        disabled>全量安装 <i class="mdi mdi-arrow-right ms-1"></i></button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-pane" id="finish-2" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-center mb-3">
                                        <h2 class="mt-0"><i class="mdi mdi-check-decagram font-5rem"></i></h2>
                                        <h3 class="mt-0">欢迎使用 LGNewUi情侣小站</h3>
                                    </div>

                                    <div id="InsSuccess">
                                        <div class="card cta-box bg-danger text-white">
                                            <div class="card-body">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <h2 class="mt-0"><i class="mdi mdi-bullhorn-outline"></i></h2>
                                                        <h3 class="m-0 font-weight-normal cta-box-title">
                                                            <b>#温馨提示 —</b> 请先填写数据库信息进行安装！
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card-body -->
                                        </div>
                                    </div>

                                    <div id="SetDataInfo">

                                    </div>

                                    <div class="BtnCenter pd-tb20" id="BtnHide">
                                        <button type="button" id="skipUpdates" class="btn btn-light btn-rounded mr-2">
                                            <i class="mdi mdi-apps"></i>
                                            跳过设置</button>
                                        <button type="button" id="SetUpdates" class="btn btn-dark btn-rounded">
                                            <i class="mdi mdi-circle-edit-outline"></i>
                                            更新设置</button>
                                    </div>
                                    <hr>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                            <ul class="pager wizard mb-0 list-inline mt-1 pd-tb20">
                                <li class="previous list-inline-item disabled">
                                    <button type="button" class="btn btn-light"><i class="mdi mdi-arrow-left me-1"></i>
                                        上一步</button>
                                </li>

                                <li class="next list-inline-item float-end HiddenElements">
                                    <a href="/index.php"><button type="button"
                                            class="btn btn-outline-secondary btn-rounded"><i
                                                class="mdi mdi-book-open me-1"></i>小站首页</button></a>
                                    <a href="/admin">
                                        <button type="button" class="btn btn-outline-primary btn-rounded"><i
                                                class="mdi mdi-account-key me-1"></i>登录后台</button>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div> <!-- tab-content -->
                </div> <!-- end #progressbarwizard-->
            </form>
        </div> <!-- end card-body -->
    </div>




    <footer class="footer footer-alt">
        Copyright © <?php echo date("Y") ?> Ki. && <a href="https://blog.kikiw.cn/index.php/archives/65/"
            target="_blank">LG_NewUi</a> All
        Rights Reserved.
    </footer>
    <script src="../Style/jquery/jquery.min.js"></script>
    <script src="/admin/assets/js/pages/demo.form-wizard.js"></script>
    <script src="/admin/assets/js/app.min.js"></script>
    <script src="../Style/toastr/toastr.js"></script>
    <script>
        $(".popUpLayerMask .global_pop_up_layer .close_pop_up_layer,#popUpLayerClose").click(function(event) {
            $('.popUpLayerMask, .popUpLayerMask .global_pop_up_layer').fadeOut(200);
        });

        $(document).ready(function() {
            const DataFrom = `<div class="row">
                                        <div class="col-12">
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label">
                                                    <b class="required_marker mdi mdi-cards-heart"></b>
                                                    小站标题</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="title"
                                                        value="LG_NewUi" placeholder="请输入小站标题">
                                                    <span class="help-block"><small>标题设置后站点全局使用 建议八个字符以内</small></span>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label"><b
                                                        class="required_marker mdi mdi-cards-heart"></b>
                                                    小站文案</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="writing" class="form-control"
                                                        value="小手一牵 岁岁年年" placeholder="请输入小站文案">
                                                    <span class="help-block"><small>文案设置后 显示在标题后面</small></span>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label"><b
                                                        class="required_marker mdi mdi-cards-heart"></b>
                                                    文字LOGO</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="logo" class="form-control" value="LG_Demo"
                                                        placeholder="请输入小站文字LOGO">
                                                    <span class="help-block"><small>设置后显示在前端页面顶部 建议六个字符以内</small></span>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label"><b
                                                        class="required_marker mdi mdi-cards-heart"></b>
                                                    男主昵称</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="boy" class="form-control" value="BoyName"
                                                        placeholder="请输入男主昵称">
                                                    <span class="help-block"><small>设置后全局使用 建议五个字符以内</small></span>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label"><b
                                                        class="required_marker mdi mdi-cards-heart"></b>
                                                    男主QQ</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="boyimg" class="form-control"
                                                        value="12345678" placeholder="请输入男主QQ号码">
                                                    <span class="help-block"><small>获取QQ头像使用 如需自定义头像地址
                                                            请安装完成后前往后台设置</small></span>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label"><b
                                                        class="required_marker mdi mdi-cards-heart"></b>
                                                    女主昵称</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="girl" class="form-control" value="GirlName"
                                                        placeholder="请输入女主昵称">
                                                    <span class="help-block"><small>设置后全局使用 建议五个字符以内</small></span>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label"><b
                                                        class="required_marker mdi mdi-cards-heart"></b>
                                                    女主QQ</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="girlimg" class="form-control"
                                                        value="87654321" placeholder="请输入女主QQ号码">
                                                    <span class="help-block"><small>获取QQ头像使用 如需自定义头像地址
                                                            请安装完成后前往后台设置</small></span>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label class="col-md-3 col-form-label"><b
                                                        class="required_marker mdi mdi-cards-heart"></b>
                                                    恋爱起始时间</label>
                                                <div class="col-md-9">
                                                    <input type="datetime-local" class="form-control"
                                                        placeholder="请输入起始时间" name="startTime"
                                                        value="<?php echo $datetimeLocal; ?>">
                                                    <span class="help-block"><small>双方恋爱开始时间
                                                            支持选择<code>年/月/日/时:分</code></small></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`

            const InsSuccessHtml = `<div class="card cta-box bg-success text-white">
                                            <div class="card-body">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <h2 class="mt-0"><i class="mdi mdi-bullhorn-outline"></i></h2>
                                                        <h3 class="m-0 font-weight-normal cta-box-title">
                                                            <b>#安装成功 —</b> 当您看到以下信息时 则代表程序已安装成功！
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card-body -->
                                        </div>

                                        <hr>

                                        <div class="card adminInfo">
                                            <h3 class="card-header">男主登录信息</h3>
                                            <div class="card-body">
                                                <h5 class="card-title">程序安装完成后 管理员默认登录账号密码为</h5>
                                                <ul class="text">
                                                    <li><b>默认管理员登录账号（男主）：</b><span
                                                            class="badge badge-primary-lighten font-14">love123</span>
                                                    </li>
                                                    <li><b>默认管理员登录密码（男主）：</b><span
                                                            class="badge badge-danger-lighten font-14">Love12345</span>
                                                    </li>
                                                </ul>
                                                <p class="card-text">温馨提示：请尽快修改默认密码 防止被恶意登录修改信息.</p>

                                            </div>
                                        </div>

                                        <div class="card adminInfo">
                                            <h3 class="card-header">女主登录信息</h3>
                                            <div class="card-body">
                                                <h5 class="card-title">程序安装完成后 管理员默认登录账号密码为</h5>
                                                <ul class="text">
                                                    <li><b>默认管理员登录账号（女主）：</b><span
                                                            class="badge badge-primary-lighten font-14">user123</span>
                                                    </li>
                                                    <li><b>默认管理员登录密码（女主）：</b><span
                                                            class="badge badge-danger-lighten font-14">Love12345</span>
                                                    </li>
                                                </ul>
                                                <p class="card-text">温馨提示：请尽快修改默认密码 防止被恶意登录修改信息.</p>

                                            </div>
                                        </div>`

            const UpdateSuccessHtml = `<div class="card cta-box bg-success text-white">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="media-body">
                                <h2 class="mt-0"><i class="mdi mdi-bullhorn-outline" style="color: #fff;"></i></h2>
                                <h3 class="m-0 font-weight-normal cta-box-title">
                                    <b>#更新成功 —</b> 当您看到当前提示时 则代表程序已更新成功！
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="card adminInfo">
                    <h3 class="card-header">感谢使用 LGNewUI</h3>
                    <div class="card-body">
                        <h5 class="card-title">本次更新内容请前往 wiki 查看详情</h5>
                        <a href="/index.php"><button type="button"
                                class="btn btn-outline-secondary btn-rounded"><b
                                    class="mdi mdi-book-open me-1"></b>小站首页</button></a>
                        <a href="/admin">
                            <button type="button" class="btn btn-outline-primary btn-rounded"><b
                                    class="mdi mdi-account-key me-1"></b>登录后台</button>
                        </a>
                        <p class="card-text" style="margin-top: 1rem;white-space: break-spaces;">现在你可以登入后台或查看首页内容 如数据有误可联系开发者 Ki 进行排查问题</p>

                    </div>
                </div> `


            $("#BtnHide").hide();

            $("#noInstall").click(function() {
                toastr["warning"]("请先检查当前环境<br/>是否符合以上程序要求！", "LG_NewUi");
            })


            $.getJSON('https://www.kikiw.cn/Love/newui/updates.json', function(data) {
                let updatesHtml = '';

                data.updates.forEach(function(update, index) {

                    let LatestV = `<i class="LatestV">New</i>`;

                    updatesHtml += `
                    <div class="timeline-item">
                        <i class="mdi mdi-apple-keyboard-caps bg-info-lighten text-info timeline-icon"></i>
                        <div class="timeline-item-info">
                            <a href="##" class="text-info font-weight-bold mb-1 d-block">${update.title}</a>
                            <p class="text-muted">
                                版本号：${update.version} ${(index === 0) ? LatestV : ''}
                            </p>
                            <ul>`;

                    update.content.forEach(function(item) {
                        updatesHtml += `<li>${item}</li>`;
                    });

                    updatesHtml += `</ul>
                            <p class="mb-0 pb-2">
                                <small class="text-muted">${update.date}</small>
                            </p>`;

                    if (index < data.updates.length - 1) {
                        updatesHtml += `<hr>`;
                    }

                    updatesHtml += `
                        </div>
                    </div>`;
                });

                $('#updateContainer').html(updatesHtml);
            });


            function showAjaxError(err) {
                let message = "请求失败，请检查 PHP 错误日志和数据库配置。";
                if (err.responseJSON) {
                    const payload = Array.isArray(err.responseJSON) ? err.responseJSON[0] : err.responseJSON;
                    message = payload.info || payload.message || message;
                } else if (err.responseText) {
                    try {
                        const payload = JSON.parse(err.responseText);
                        const item = Array.isArray(payload) ? payload[0] : payload;
                        message = item.info || item.message || message;
                    } catch (e) {
                        message += " HTTP " + (err.status || "未知");
                    }
                }
                toastr["error"](message, "LG_NewUi");
            }

            $("#checkBtn").click(function() {
                let localhost = $("input[name='localhost']").val();
                let userName = $("input[name='userName']").val();
                let password = $("input[name='password']").val();
                let DBName = $("input[name='DBName']").val();
                let SecureCode = $("input[name='SecureCode']").val();


                if (!(localhost && userName && password)) {
                    toastr["warning"]("请填写完整数据库配置信息！", "LG_NewUi");
                    return false
                }

                $.ajax({
                    url: "installPost.php",
                    data: {
                        localhost,
                        userName,
                        password,
                        DBName,
                        SecureCode,
                    },
                    type: "POST",
                    dataType: "json",
                    success: function(res) {
                        // console.log(res);
                        if (res.status == 1) {
                            $("#SubmitPost").attr("disabled", false);
                            $("#UpdateVPost").attr("disabled", false);
                            toastr["success"](res.info, "LG_NewUi");
                        } else {
                            toastr["error"](res.info, "LG_NewUi");
                        }
                    },
                    error: function(err) {
                        showAjaxError(err);
                    }
                })
            })

            $("#UpdateVPost").click(function() {
                $(".popUpLayerMask").show();
                $('.popUpLayerMask .global_pop_up_layer').fadeIn(300);
            })

            $("#confirmExecution").click(function() {
                UpdateVPost();
            })

            function UpdateVPost() {
                $.ajax({
                    url: "installPost.php",
                    data: {
                        Type: 'SetUpdates',
                        SqlUpdate: 1
                    },
                    type: "POST",
                    dataType: "json",
                    success: function(res) {
                        console.log(res);
                        let RunningState = 1;
                        let allInfo = '';
                        res.forEach(item => {

                            if (Array.isArray(item.data)) {
                                item.data.forEach(infoItem => {
                                    allInfo += `<p style="display: none;">${infoItem}</p>`;
                                });
                            }

                            $('.popUpLayerMask .global_pop_up_layer .pop_up_layer_content').html(allInfo);

                            let fadeInPromise = new Promise((resolve) => {
                                let paragraphs = $('.popUpLayerMask .global_pop_up_layer .pop_up_layer_content p');
                                let totalParagraphs = paragraphs.length;

                                paragraphs.each(function(index) {
                                    $(this).delay(300 * index).fadeIn(400, function() {
                                        if (index === totalParagraphs - 1) {
                                            resolve();
                                        }
                                    });
                                });
                            });

                            RunningState = item.status;

                            let resText = `<p>${item.info}</p>`;
                            if (item.status == 1) {
                                toastr["success"](item.info, "LG_NewUi");
                            } else {
                                toastr["error"](item.info, "LG_NewUi");
                            }

                            fadeInPromise.then(() => {
                                if (RunningState) {
                                    $(UpdateSuccessHtml).hide().prependTo('.popUpLayerMask .global_pop_up_layer .pop_up_layer_content').fadeIn(300);
                                }
                            });
                        });

                    },
                    error: function(err) {
                        showAjaxError(err);
                    }
                })
            }



            $("#SubmitPost").click(function() {
                $("#Loadanimation").show(300);
                let localhost = $("input[name='localhost']").val();
                let userName = $("input[name='userName']").val();
                let password = $("input[name='password']").val();
                let DBName = $("input[name='DBName']").val();
                let SecureCode = $("input[name='SecureCode']").val();

                if (!(localhost && userName && password && DBName && SecureCode)) {
                    toastr["warning"]("请填写完整数据库配置信息！", "LG_NewUi");
                    $("#Loadanimation").fadeOut(300);
                    return false
                }
                if (SecureCode.length < 8) {
                    toastr["warning"]("自定义安全码至少需要 8 位，例如 love2026。", "LG_NewUi");
                    $("#Loadanimation").fadeOut(300);
                    return false
                }

                $.ajax({
                    url: "installPost.php",
                    data: {
                        localhost,
                        userName,
                        password,
                        DBName,
                        SecureCode,
                        Type: 'install'
                    },
                    type: "POST",
                    dataType: "json",
                    success: function(res) {
                        let installSucceeded = res.length > 0 && res.every(item => item.status == 1);
                        res.forEach(item => {
                            if (item.status == 1) {
                                toastr["success"](item.info, "LG_NewUi");
                            } else {
                                toastr["error"](item.info, "LG_NewUi");
                            }
                        });
                        $("#Loadanimation").fadeOut(1000);
                        if (installSucceeded) {
                            $("#SubmitPost").attr("disabled", true);
                            $("#BtnHide").show();
                            $("#InsSuccess").html(DataFrom)
                            $('a[href="#finish-2"]').tab('show');
                        } else {
                            $("#SubmitPost").attr("disabled", false);
                        }

                    },
                    error: function(err) {
                        showAjaxError(err);
                        $("#Loadanimation").fadeOut(300);
                    }
                })
            })

            $("#skipUpdates").click(function() {
                $("#skipUpdates").attr("disabled", true);
                $("#Loadanimation").show(300);

                $.ajax({
                    url: "installPost.php",
                    data: {
                        Type: "skipUpdates"
                    },
                    type: "POST",
                    dataType: "json",
                    success: function(res) {
                        res.forEach(item => {
                            if (item.status == 1) {
                                $("#BtnHide").hide();
                                $('.HiddenElements').fadeIn(300);
                                $("#InsSuccess").html(InsSuccessHtml)
                                toastr["success"](item.info, "LG_NewUi");
                            } else {
                                toastr["error"](item.info, "LG_NewUi");
                            }
                        });

                        $("#Loadanimation").fadeOut(1000);
                        $("#skipUpdates").attr("disabled", false)
                    },
                    error: function(err) {
                        showAjaxError(err);
                        $("#skipUpdates").attr("disabled", false)
                    }
                })
            })


            $("#SetUpdates").click(function() {
                $("#SetUpdates").attr("disabled", true);
                $("#Loadanimation").show(300);

                let title = $("input[name='title']").val();
                let writing = $("input[name='writing']").val();
                let logo = $("input[name='logo']").val();
                let boy = $("input[name='boy']").val();
                let boyimg = $("input[name='boyimg']").val();
                let girl = $("input[name='girl']").val();
                let girlimg = $("input[name='girlimg']").val();
                let startTime = $("input[name='startTime']").val();

                if (!(title && writing && logo && boy && boyimg && girl && girlimg && startTime)) {
                    toastr["warning"]("请先填写需更新的小站内容<br/>如暂不修改可选择跳过更新！", "LG_NewUi");
                    return false
                }

                $.ajax({
                    url: "installPost.php",
                    data: {
                        title,
                        writing,
                        logo,
                        boy,
                        boyimg,
                        girl,
                        girlimg,
                        startTime,
                        Type: "SetUpdates"
                    },
                    type: "POST",
                    dataType: "json",
                    success: function(res) {
                        $("#BtnHide").hide();

                        res.forEach(item => {
                            if (item.status == 1) {
                                $('.HiddenElements').fadeIn(300);
                                toastr["success"](item.info, "LG_NewUi");
                                $("#InsSuccess").html(InsSuccessHtml)
                            } else {
                                toastr["error"](item.info, "LG_NewUi");
                            }
                        });

                        $("#Loadanimation").fadeOut(1000);
                        $("#SetUpdates").attr("disabled", false)
                    },
                    error: function(err) {
                        showAjaxError(err);
                        $("#SetUpdates").attr("disabled", false)
                    }
                })
            })
        });
    </script>
</body>

</html>
