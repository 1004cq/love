<!--
/*
 *                        _oo0oo_
 *                       o8888888o
 *                       88" . "88
 *                       (| -_- |)
 *                       0\  =  /0
 *                     ___/`---'\___
 *                   .' \\|     |// '.
 *                  / \\|||  :  |||// \
 *                 / _||||| -:- |||||- \
 *                |   | \\\  - /// |   |
 *                | \_|  ''\---/''  |_/ |
 *                \  .-\__  '-'  ___/-. /
 *              ___'. .'  /--.--\  `. .'___
 *           ."" '<  `.___\_<|>_/___.' >' "".
 *          | | :  `- \`.;`\ _ /`;.`/ - ` : | |
 *          \  \ `_.   \_ __\ /__ _/   .-` /  /
 *      =====`-.____`.___ \_____/___.-`___.-'=====
 *                        `=---='
 * 
 * 
 *      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * 
 *            佛祖保佑     永不宕机     永无BUG
 * 
 *        佛曰:  
 *                写字楼里写字间，写字间里程序员；  
 *                程序人员写程序，又拿程序换酒钱。  
 *                酒醒只在网上坐，酒醉还来网下眠；  
 *                酒醉酒醒日复日，网上网下年复年。  
 *                但愿老死电脑间，不愿鞠躬老板前；  
 *                奔驰宝马贵者趣，公交自行程序员。  
 *                别人笑我忒疯癫，我笑自己命太贱；  
 *                不见满街漂亮妹，哪个归得程序员？
 */

 * @Author: Ki.
 * @Project: LG_NewUi
 * @LastEditors: Ki.
 * @LastEditTime: 2023-03-24 16:36:06
 * @Description: 小手一牵 岁岁年年
 * Copyright (c) 2022-2024 by Ki All Rights Reserved.
 * Document https://blog.kikiw.cn/index.php/archives/65/
 * Warning 当前版本为付费版 禁止出售倒卖 一旦发现 将停止后期维护与更新 永久拉入黑名单
 * 开发需要投入许多时间 请理解 开发不易 感谢您的购买 祝您使用愉快
-->



<?php
include($_SERVER['DOCUMENT_ROOT'] . '/ipjc.php');
error_reporting(0);
include_once "Database.php";
include_once 'connect.php';
include_once 'Function.php';
if (userHasPermission($_SESSION['loginadmin'], $conn)) {
    $sql = "select * from login where user = '" . $_SESSION['loginadmin'] . "' ";
    $loginresult = mysqli_query($connect, $sql);
    if (mysqli_num_rows($loginresult)) {
        $login_user = mysqli_fetch_array($loginresult);
    }
} else {
    session_destroy();
    header("Location:../admin/login.php");
    die("<script>alert('权限不足 请先认证')</script>");
}
$sql = "select * from login";
$result = mysqli_query($connect, $sql);
if (mysqli_num_rows($result)) {
    $login = mysqli_fetch_array($result);
}

$sql = "select * from text";
$result = mysqli_query($connect, $sql);
if (mysqli_num_rows($result)) {
    $text = mysqli_fetch_array($result);
}
$sql = "select * from diySet";
$result = mysqli_query($connect, $sql);
if (mysqli_num_rows($result)) {
    $diy = mysqli_fetch_array($result);
}

$AvatarFrames_sql = "select * from CoupleAvatarFrames";
$AvatarFrames_result = mysqli_query($connect, $AvatarFrames_sql);
$AvatarFrames = [];
while ($row = mysqli_fetch_assoc($AvatarFrames_result)) {
    $AvatarFrames[] = $row;
}

//查询数据
//留言
$nub = "select count(id) as shu from leaving";
$res = mysqli_query($connect, $nub);
$leav = mysqli_fetch_array($res);
$shu = $leav['shu'];
//点点滴滴
$dian = "select count(id) as dian from article";
$resdian = mysqli_query($connect, $dian);
$didi = mysqli_fetch_array($resdian);
$diannub = $didi['dian'];
//恋爱清单
$list = "select count(id) as list from lovelist";
$reslsit = mysqli_query($connect, $list);
$listlove = mysqli_fetch_array($reslsit);
$listnub = $listlove['list'];
//恋爱相册
$img = "select count(id) as img from loveImg";
$resimg = mysqli_query($connect, $img);
$loveImg = mysqli_fetch_array($resimg);
$imgnub = $loveImg['img'];
//纪念日
$loveday = "select count(id) as day from loveday";
$resday = mysqli_query($connect, $loveday);
$loveday = mysqli_fetch_array($resday);
$day_num = $loveday['day'];

$default_info = [
    'user' => 'user123',
    'pw' => 'Love12345',
    'admin' => 'love123',
    'apw' => 'Love12345',
];

$User_InfoNU = [
    'name' => null,
    'url' => null,
    'diyUrl' => null,
    'diyType' => $text['if_aiv']
];

//判断当前登录用户的ID 赋值用户信息给数组
if ($login_user['id'] == 1) {
    $User_InfoNU['name'] = $text['boy'];
    $User_InfoNU['url'] = $text['boyimg'];
    $User_InfoNU['diyUrl'] = $text['diyb_url'];
} else if ($login_user['id'] == 2) {
    $User_InfoNU['name'] = $text['girl'];
    $User_InfoNU['url'] = $text['girlimg'];
    $User_InfoNU['diyUrl'] = $text['diyg_url'];
}

$AdminAvatarAddress = [
    'ml' => DetermineAvatarAddress(1),
    'fl' => DetermineAvatarAddress(0),
]

    ?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8" />
    <title>后台管理 —
        <?php echo $text['title'] ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Coderthemes" name="LGNewUi" />
    <meta content="Author" name="Ki" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Ma+Shan+Zheng&amp;family=Pacifico&amp;display=swap">
    <link rel="stylesheet" href="../icon/iconfont.css">
    <!-- App css -->
    <link href="/admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="/Style/css/loading.css" rel="stylesheet">
    <link href="../Style/toastr/toastr.css" rel="stylesheet">

    <link href="/admin/assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/css/vendor/buttons.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/css/vendor/select.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/Style/login/remixicon.css">
    <link rel="stylesheet" href="/admin/assets/css/modern-2026.css?v=<?php echo $versionCode ?>">

    <script src="../Style/Font/OsBr/OsBr.js"></script>
    <script src="../Style/jquery/jquery.min.js"></script>
    <script src="../Style/js/lazyload.min.js"></script>

</head>

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

    <div class="popUpLayerMask">
        <div class="global_pop_up_layer">
            <div class="close_pop_up_layer">
                <i class="mdi mdi-close"></i>
            </div>
            <div class="pop_up_layer_head">
                <div class="eventTitle">
                    <h2><i class="mdi mdi-alert-octagon-outline" style="margin-right:.3rem"></i>删除数据</h2>
                    <p><span>你是否确定删除以下内容 删除后无法恢复！</span></p>
                </div>
            </div>
            <div class="pop_up_layer_content">

            </div>
            <div class="pop_up_layer_footer">
                <span id="SwitchLock">
                    <i class="ri-fingerprint-line"></i>
                </span>
                <!-- <button type="button" class="btn btn-secondary btn-rounded">稍后删除</button> -->
                <button data-toggle="tooltip" data-placement="top" data-original-title="请先点击左则图标进行确认" type="button"
                    class="btn btn-outline-danger btn-rounded" id="DeleteItem" disabled>确认删除</button>
            </div>
        </div>
    </div>


    <!--顶部栏 Start-->

    <div class="navbar-custom topnav-navbar">
        <div class="container-fluid">
            <!-- LOGO -->
            <a href="/admin/index.php" class="topnav-logo">
                <span class="topnav-logo-lg">
                    <?php echo $text['title'] ?>
                </span>
                <span class="topnav-logo-sm">
                    <?php echo $text['title'] ?>
                </span>
            </a>

            <ul class="list-unstyled topbar-right-menu float-right mb-0">

                <li class="dropdown notification-list">
                    <a href="../index.php" class="goToHome nav-link right-bar-toggle">
                        <svg t="1734598504478" class="icon" viewBox="0 0 1024 1024" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" p-id="5567" width="200" height="200">
                            <path
                                d="M971.66222222 961.64977778c0 30.72-24.91733333 55.52355555-55.52355555 55.52355555h-807.82222222c-30.72 0-55.52355555-24.91733333-55.52355556-55.52355555V452.03911111c0-1.024 0-1.93422222 0.11377778-2.95822222-2.73066667-23.32444445 5.34755555-47.55911111 24.34844444-65.42222222L451.92533333 30.26488889c33.10933333-31.28888889 86.81244445-31.28888889 119.92177778 0L946.63111111 383.65866667c17.97688889 16.95288889 26.16888889 39.59466667 24.68977778 61.78133333 0.22755555 2.16177778 0.45511111 4.43733333 0.45511111 6.59911111v509.61066667zM658.65955555 773.12c0-72.59022222-61.32622222-131.41333333-136.98844444-131.41333333s-136.98844445 58.82311111-136.98844444 131.41333333V1017.17333333h273.86311111V773.12z"
                                p-id="5568" data-spm-anchor-id="a313x.manage_type_mylikes.0.i7.670b3a81ElsoIU" class=""
                                fill="#ffffff"></path>
                        </svg>
                    </a>
                </li>

                <!-- <li class="dropdown notification-list">
                    <a class="nav-link right-bar-toggle" href="/admin/user.php">
                        <i class="dripicons-gear noti-icon"></i>
                    </a>
                </li> -->

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown"
                        id="topbar-userdrop" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="account-user-avatar">
                            <?php if ($User_InfoNU['diyType'] == 1) { ?>
                                <img src="<?php echo $User_InfoNU['diyUrl'] ?>" alt="user-image" class="rounded-circle">
                            <?php } else { ?>
                                <img src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo $User_InfoNU['url'] ?>&s=640"
                                    alt="user-image" class="rounded-circle">

                            <?php } ?>
                        </span>
                        <span>
                            <span class="account-user-name">
                                <?php echo $User_InfoNU['name'] ?>
                            </span>
                            <span class="account-position">操作</span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown"
                        aria-labelledby="topbar-userdrop">

                        <!-- item-->
                        <a href="../admin/user.php" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-circle mr-1"></i>
                            <span>全局设置</span>
                        </a>

                        <!-- item-->
                        <a href="../admin/loginOut.php" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-edit mr-1"></i>
                            <span>退出登录</span>
                        </a>


                    </div>
                </li>

            </ul>
            <a class="button-menu-mobile disable-btn">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </a>
            <div class="app-search">
            </div>
        </div>
    </div>

    <!--顶部栏 End-->


    <div class="container-fluid">

        <div class="wrapper">
            <!--侧边导航栏 Start-->
            <div class="left-side-menu">
                <div class="leftbar-user">
                    <a href="#">
                        <div class="AdminAvatar">
                            <?php if ($User_InfoNU['diyType'] == 1) { ?>
                                <img src="<?php echo $User_InfoNU['diyUrl'] ?>" alt="user-image"
                                    style="height: 4.5rem;box-shadow: 0 0 30px rgb(98 94 94 / 19%) !important;"
                                    class="rounded-circle shadow-sm">
                            <?php } else { ?>
                                <img src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo $User_InfoNU['url'] ?>&s=640"
                                    alt="user-image" height="42" class="rounded-circle shadow-sm">
                            <?php } ?>
                        </div>


                        <span class="leftbar-user-name">
                            <!-- <i class="mdi mdi mdi-account-circle"></i> -->
                            <?php echo $User_InfoNU['name'] ?>
                        </span>
                    </a>
                </div>

                <!--- Sidemenu -->
                <ul class="metismenu side-nav">

                    <li class="side-nav-item">
                        <a href="/admin/index.php" class="side-nav-link">
                            <i class="dripicons-direction"></i>
                            <span> 后台首页 </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/VisitLog.php" class="side-nav-link">
                            <i class="dripicons-graph-line"></i>
                            <span> 访问统计 </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/LoginInfo.php" class="side-nav-link">
                            <i class="dripicons-graph-bar"></i>
                            <span> 登录行为 </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/Set.php" class="side-nav-link">
                            <i class="dripicons-meter"></i>
                            <span class="badge badge-success float-right"><i class="dripicons-gear margin_0"></i></span>
                            <span> 基本设置 </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/Mail.php" class="side-nav-link">
                            <i class="dripicons-rocket"></i>
                            <span> 邮箱配置 </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/wx_app_config.php" class="side-nav-link">
                            <i class="dripicons-pin"></i>
                            <span> 小程序配置 </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="javascript: void(0);" class="side-nav-link">
                            <i class="dripicons-copy"></i>
                            <span> 页面管理 </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="side-nav-second-level" aria-expanded="false"
                            style="background: #fcfcfc;padding-left: 1rem;box-sizing: border-box;border-left: 2px solid #727df5;">

                            <li>
                                <a href="/admin/littleSet.php" class="side-nav-link">
                                    <i class="dripicons-document"></i>
                                    <span> 点点滴滴
                                        <span class="badge badge-danger float-right">
                                            <?php echo $diannub ?>
                                        </span>
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="/admin/leavSet.php" class="side-nav-link">
                                    <i class=" dripicons-user-group"></i>
                                    <span> 留言管理
                                        <span class="badge badge-danger float-right">
                                            <?php echo $shu ?>
                                        </span>
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="/admin/loveImgSet.php" class="side-nav-link">
                                    <i class="dripicons-photo-group"></i>
                                    <span> 恋爱相册
                                        <span class="badge badge-danger float-right">
                                            <?php echo $imgnub ?>
                                        </span>
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="/admin/lovelist.php" class="side-nav-link">
                                    <i class="dripicons-checklist"></i>
                                    <span> 恋爱清单
                                        <span class="badge badge-danger float-right">
                                            <?php echo $listnub ?>
                                        </span>
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="/admin/list_day.php" class="side-nav-link">
                                    <i class="dripicons-alarm"></i>
                                    <span> 恋爱日期
                                        <span class="badge badge-danger float-right">
                                            <?php echo $day_num ?>
                                        </span>
                                    </span>
                                </a>
                            </li>


                            <?php if ($diy['is_aboutActive']): ?>
                                <li>
                                    <a href="/admin/aboutSet.php" class="side-nav-link">
                                        <i class="dripicons-tags"></i>
                                        <span> 关于页面</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/StoragePolicy.php" class="side-nav-link">
                            <i class=" dripicons-browser-upload"></i>
                            <span> 存储策略</span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/photoList.php" class="side-nav-link">
                            <i class=" dripicons-folder"></i>
                            <span> 媒体管理</span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/ipList.php" class="side-nav-link">
                            <i class="dripicons-location"></i>
                            <span> IP/拉黑</span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/feifa.php" class="side-nav-link">
                            <i class="dripicons-time-reverse"></i>
                            <span> 非法请求</span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/system_status.php" class="side-nav-link">
                            <i class="dripicons-pulse"></i>
                            <span> 系统状态 </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/AboutLG.php" class="side-nav-link">
                            <i class="dripicons-information"></i>
                            <span> 关于项目</span>
                        </a>
                    </li>
                </ul>


                <!-- Help Box -->
                <div class="help-box text-center">
                    <a href="javascript: void(0);" class="float-right close-btn text-body">
                        <i class="mdi mdi-close"></i>
                    </a>
                    <img src="../Style/img/AdminLogo.webp" height="100" alt="Ki·设计" />
                    <h5 class="mt-3">LG_NewUi
                        <?php echo $version ?>
                    </h5>
                    <p class="mb-3">小手一牵 岁岁年年</p>
                    <a href="https://www.kikiw.cn/" target="_blank"
                        class="btn btn-outline-primary btn-sm copyKi">By~Ki.</a>
                </div>
                <!-- end Help Box -->
                <!-- End Sidebar -->


                <div class="clearfix"></div>
                <!-- Sidebar -left -->
            </div>
            <!--侧边导航栏 End-->
            <!--内容页面 Start-->
            <div class="content-page">
                <!--内容 Start-->
                <div class="content">

                    <!-- 内容  -->
                    <style>
                        @media (max-width: 767.98px) {
                            .left-side-menu {
                                z-index: 15 !important;
                            }
                        }

                        @font-face {
                            font-family: 'CodeFont';
                            src: url('assets/fonts/monospace.ttf') format('ttf'),
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

                        @keyframes IconRotate {
                            from {
                                transform: rotate(0deg);
                            }

                            to {
                                transform: rotate(360deg);
                            }
                        }

                        .FileTitle {
                            font-size: .9em;
                            color: #909090;
                        }

                        .Provider {
                            text-align: center;
                        }

                        .HiddenElements {
                            display: none !important;
                        }

                        .NoDataCard-warp {
                            margin: 3rem auto;
                            max-width: 750px;
                            background: #f6f6f6;
                            padding: 1rem;
                            border-radius: 1rem;
                        }

                        .NoDataCard-warp .NoDataInfo h3 {
                            font-size: 1.3rem;
                        }

                        .NoDataCard {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        .NoDataInfo {
                            text-align: center;
                        }

                        .bg-info {
                            background-color: #52a2ff !important;
                        }

                        .leftbar-user {
                            padding: 20px 5px;
                            background: #f3f3f3;
                            margin: 1rem;
                            border-radius: 1rem;
                        }

                        .AdminAvatar {
                            position: relative;
                            display: inline-block;
                        }

                        .AdminAvatar::before {
                            content: '';
                            transition: 1s;
                            width: 1.2rem;
                            height: 1.2rem;
                            background: #57bd6a;
                            position: absolute;
                            border-radius: 50%;
                            border: 3px solid #fff;
                            bottom: -4px;
                            right: 8px;
                            z-index: 2;
                        }

                        .goToHome {
                            font-size: 22px;
                            vertical-align: middle;
                            line-height: 70px;
                        }

                        .AminIndexCardIcon {
                            width: 80px;
                            background: #fff;
                            box-shadow: 0 0 30px rgb(184 177 177 / 14%);
                            border-radius: 1rem;
                            position: absolute;
                            top: 50%;
                            right: 2rem;
                            transform: translate(0, -50%);
                            user-select: none;
                        }

                        .AminIndexCardIcon img {
                            width: 100%;
                            transition: all .3s;
                        }

                        .card-body:hover .AminIndexCardIcon img {
                            transform: scale(1.3);
                        }

                        .CodeMirror-selected {
                            background-color: #fefbec !important;
                        }

                        .CodeMirror-focused .CodeMirror-selected {
                            background-color: #fefbec !important;
                        }

                        .svg-rotate {
                            animation: IconRotate 2s linear infinite;
                            transform-origin: center;
                        }

                        .btn-outline-primary:not(:disabled):not(.disabled).active:focus,
                        .btn-outline-primary:not(:disabled):not(.disabled):active:focus,
                        .show>.btn-outline-primary.dropdown-toggle:focus,
                        .btn-outline-primary.focus,
                        .btn-outline-primary:focus {
                            -webkit-box-shadow: none;
                            box-shadow: none;
                        }

                        .btn-outline-warning:hover {
                            color: #ffffff;
                        }

                        .AvatarFramesPreview {
                            width: 100px;
                            background: #f5f5f5;
                            border-radius: .5rem;
                        }

                        .AvatarFramesPreview img {
                            width: 100%;
                            height: 100%;
                        }

                        img.lazy {
                            opacity: 0;
                            -webkit-transition: .8s ease-in-out opacity;
                            transition: .8s ease-in-out opacity;
                            filter: blur(35px);
                            overflow: hidden;
                            box-sizing: border-box;
                        }

                        img.loaded {
                            filter: blur(0);
                            opacity: 1;
                            transition: .5s filter linear, .5s -webkit-filter linear;
                        }

                        .userProcessing {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            /* flex-wrap: wrap; */
                        }


                        .bottom-tab-container {
                            display: flex;
                            background-color: #fff;
                            border-radius: 30px;
                            padding: 5px;
                            max-width: 380px;
                            margin: 10px 0;
                            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                        }

                        .bottom-tab {
                            flex: 1;
                            text-align: center;
                            padding: 5px 0;
                            border-radius: 30px;
                            cursor: pointer;
                            position: relative;
                        }

                        .bottom-tab-active {
                            background-color: #eef3ff;
                        }

                        .bottom-tab span {
                            color: #5f5f5f;
                            font-weight: 700;
                        }

                        .bottom-tab-active span {
                            color: #2563eb;
                        }

                        .bottom-tab-content {
                            display: none;
                        }

                        .bottom-tab-content-active {
                            display: block;
                        }

                        .input-group-text {
                            background: #eaeaea;
                            color: #919191;
                            font-weight: bold;
                            border-radius: 0 5px 5px 0 !important;
                        }

                        .setAvatarFrame .addAvatarFrame {
                            text-align: center;
                            padding: 1rem 0;
                        }

                        .AvatarFramesStyle {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }

                        .AvatarFramesStyle .AvatarFrames {
                            margin: 1rem;
                            position: relative;
                        }

                        .AvatarFramesStyle .AvatarFrames .Frame {
                            position: absolute;
                            width: 100%;
                            top: 0;
                            left: 0;
                            transform: scale(1.5);
                        }

                        .leftbar-user img {
                            transition: all .5s;
                        }

                        .leftbar-user img:hover {
                            transform: rotate(-360deg);
                        }

                        .popUpLayerMask {
                            display: none;
                            position: fixed;
                            top: 0;
                            left: 0;
                            z-index: 99999;
                            width: 100%;
                            height: 100%;
                            background: #00000036;
                        }

                        .popUpLayerMask .global_pop_up_layer {
                            display: none;
                            position: absolute;
                            padding: 2rem;
                            border-radius: 1rem;
                            top: 13vh;
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

                        .popUpLayerMask .global_pop_up_layer .pop_up_layer_content label {
                            margin-bottom: 0;
                        }

                        .popUpLayerMask .global_pop_up_layer .pop_up_layer_content img {
                            width: 100%;
                            max-height: 300px;
                            object-fit: cover;
                            border-radius: 10px;
                        }

                        .popUpLayerMask .global_pop_up_layer .pop_up_layer_content .DelKeys-wrapper {
                            display: flex;
                            gap: 10px;
                            flex-wrap: wrap;
                            justify-content: center;
                        }

                        .popUpLayerMask .global_pop_up_layer .pop_up_layer_content .DelKeys-wrapper img.minDelKeys {
                            max-width: 130px;
                            height: 130px;
                        }

                        .popUpLayerMask .global_pop_up_layer .pop_up_layer_content .Frames {
                            width: 100px;
                            background: #f5f5f5;
                        }

                        .popUpLayerMask .global_pop_up_layer .pop_up_layer_content #MsgText img {
                            width: 2rem;
                            margin: 0 .2rem;
                        }

                        .popUpLayerMask .global_pop_up_layer .pop_up_layer_content i {
                            text-decoration: underline;
                            text-decoration-style: solid;
                            text-underline-offset: 3px;
                            color: #ffbc00;
                            word-break: break-all;
                        }

                        .popUpLayerMask .global_pop_up_layer .pop_up_layer_content i.reject {
                            color: #ff6f6f;
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

                        .popUpLayerMask .global_pop_up_layer .pop_up_layer_footer #SwitchLock i {
                            font-size: 2rem;
                            margin-right: .3rem;
                            color: #cdcdcd;
                            cursor: pointer;
                            transition: .3s all;
                        }

                        .popUpLayerMask .global_pop_up_layer .pop_up_layer_footer #SwitchLock i:hover {
                            color: #009fff;
                        }

                        .popUpLayerMask .global_pop_up_layer .pop_up_layer_footer #SwitchLock i.ri-shield-check-fill {
                            color: #009fff;
                        }

                        .popUpLayerMask .global_pop_up_layer .pop_up_layer_footer .btn {
                            padding: .25rem 1rem;
                            font-weight: bold;
                        }

                        .badge.badge-success-lighten.F_title {
                            max-width: 13em;
                            overflow: hidden;
                            text-overflow: ellipsis;
                        }

                        .CodeMirror-code {
                            font-family: "CodeFont";
                            font-size: 16px;
                            letter-spacing: 1px;
                        }

                        .CodeMirror-linenumber {
                            padding: 3px 0px 0 0px;
                            font-size: 10px;
                            text-align: center;
                            /* font-weight: bold; */
                            color: #565656;
                            -moz-box-sizing: content-box;
                            box-sizing: content-box;
                        }


                        video#LGNewUiPlayerVideo {
                            width: 100%;
                            border-radius: 1rem;
                        }

                        .elementHiding {
                            display: none;
                        }

                        .LeftCopy {
                            position: absolute;
                            top: 0;
                            left: 0;
                        }

                        .required_marker {
                            color: #fe4001;
                            vertical-align: middle;
                            margin-right: 2px;
                        }

                        .loveaiv {
                            display: flex;
                            align-items: center;
                        }

                        .ImageUploadDiy_warp {
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            z-index: 9999;
                            /* padding: 2rem; */
                            display: none;
                        }

                        /* .ImageUploadDiy_warp::after {
                            content: "";
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background: #ffffffab;
                            z-index: -1;
                        } */

                        .ImageUploadDiy_warp .ImageUploadDiy {
                            padding: 2rem 1rem;
                            background: #fff;
                            box-sizing: border-box;
                            width: 100%;
                            height: 100%;
                            overflow: auto;
                            border-radius: 0;
                        }

                        .ImageUploadDiy_warp .ImageUploadDiy .media-tab-container {
                            width: 100%;
                        }

                        .ImageUploadDiy .copyTitle {
                            margin-top: 1rem;
                            margin-bottom: 0.5rem;
                        }

                        .ImageUploadDiy_warp .ImageUploadDiy_close {
                            position: absolute;
                            top: .3rem;
                            right: .3rem;
                            z-index: 10;
                        }

                        .ImageUploadDiy_Btn i {
                            font-size: 2rem;
                            padding: 0 1rem 0.5rem;
                            background: #fff;
                            box-shadow: 0 0 28px 0 rgb(122 196 255 / 29%);
                            color: #2196F3;
                            border-radius: 1rem;
                            position: fixed;
                            bottom: 3rem;
                            right: 2rem;
                            z-index: 999;
                            border: 1px solid transparent;
                            transition: all 0.2s;
                        }

                        .ImageUploadDiy_Btn i:hover {
                            cursor: pointer;
                            background: #2196F3;
                            color: #fff;
                            border-color: #fff;
                        }

                        .copyKi {
                            font-family: 'Pacifico', Arial, 'Ma Shan Zheng', Helvetica;
                            font-weight: 400;
                            border-radius: 1rem !important;
                        }

                        .btn-info {
                            color: #fff;
                            background-color: #03A9F4;
                            border-color: #03A9F4;
                            border-radius: 10px;
                        }

                        .btn-info:hover {
                            color: #fff;
                            background-color: #2196F3;
                            border-color: #2196F3;
                            opacity: 0.7;
                        }

                        .CodeMirror-scroll {
                            overflow: auto !important;
                        }

                        ::selection {
                            background: #6f6f6fc7 !important;
                            color: #ffffff !important;
                        }

                        span.cm-tag.cm-bracket {
                            color: #383838;
                        }

                        .cm-s-default .cm-tag {
                            color: #009fff;
                        }

                        .CodeMirror-code {
                            color: #796565;
                        }

                        .cm-s-default .cm-attribute {
                            color: #ffa00c;
                        }

                        .cm-s-default .cm-string {
                            color: #2cc47d;
                        }

                        .cm-s-default .cm-link {
                            color: #0acf97;
                        }

                        .navbar-custom {
                            background-color: #363636;
                        }

                        .textRight {
                            text-align: right;
                        }

                        .pagination-rounded {
                            justify-content: center;
                        }

                        .poR {
                            position: relative;
                        }

                        .poA {
                            position: absolute;
                            top: 0;
                            right: 0;
                        }


                        .nav-user {
                            background-color: #363636;
                            border: none;
                        }

                        .bg-nav-pills {
                            background-color: #f0f0f0;
                            border-radius: 2rem;
                        }

                        .nav-pills .nav-link.active,
                        .nav-pills .show>.nav-link {
                            color: #fff;
                            background-color: #ff7393;
                            border-radius: 2rem !important;
                        }

                        .imgSize {
                            width: 100%;
                            height: 200px;
                            overflow: hidden;
                        }

                        .pad03 {
                            padding: 0.3rem 1rem;
                        }

                        .imgSize img {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            /* transition: all 0.3s; */
                        }

                        .row {
                            overflow: auto;
                        }

                        .user_name {
                            background-color: #3589fa;
                            color: #ffffff;
                            padding: 0px 10px;
                            border-radius: 20px;
                            font-style: normal;
                            font-size: 14px;
                            margin: 0 0.3rem;
                        }

                        .color {
                            color: #999;
                        }

                        .form-control:focus {
                            border-color: #00b0ff;
                            color: #00b0ff;
                        }

                        .form-control {
                            color: #b6b6b6;
                        }

                        .side-nav .side-nav-link i {
                            height: 20px;
                        }

                        span.badge.badge-danger-lighten,
                        span.badge-warning-lighten,
                        span.badge.badge-primary-lighten {
                            font-size: 0.9rem;
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 400;
                        }


                        ul.text {
                            line-height: 2rem;
                            font-size: 0.9rem;
                        }

                        ul,
                        .alert-warning,
                        .help-block {
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 400;
                        }

                        h3.mt-0,
                        h5 {
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 700;
                        }

                        .hhtext {
                            white-space: normal !important;
                        }

                        .notification-list .dropdown-menu.dropdown-menu-right {
                            border-radius: 0px 0px 10px 10px;
                            box-shadow: 0 8px 12px #efefef;
                        }

                        button.btn.btn-success.mb-2.mr-2 {
                            border-radius: 10px;
                        }

                        svg.icon {
                            width: 1.2rem;
                            height: 1.2rem;
                            vertical-align: -4px;
                            margin-right: 0.2rem;
                        }

                        .badge-success-lighten {
                            font-size: 0.9rem;
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 400;
                            padding: 0.4rem;
                        }

                        i {
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 400;
                            font-style: normal;
                        }

                        .form-group.col-sm-4 {
                            margin-left: 0;
                            padding-left: 0;
                        }

                        #spotlight .header div {
                            padding-right: 40px;
                        }

                        div#test-editor {
                            z-index: 10;
                        }

                        .table td,
                        .table th {
                            padding: 0.95rem;
                            vertical-align: middle;
                            border-top: 1px solid #e3eaef;
                        }

                        #img_url {
                            display: block;
                        }

                        .margin_0 {
                            margin: 0 !important;
                        }

                        .account-user-name {
                            text-transform: capitalize;
                        }

                        .leftbar-user .leftbar-user-name {
                            margin-left: 0px;
                        }

                        span.topnav-logo-lg,
                        span.topnav-logo-sm {
                            color: #fff;
                            font-size: 1.5rem;
                            /*text-transform: capitalize;*/
                            font-weight: bold;
                        }

                        .card {
                            border-radius: 0.80rem;
                        }

                        .content-page {
                            padding-top: 20px;
                        }

                        h3.my-2.py-1 {
                            font-size: 2rem;
                        }

                        h3.my-2.py-1 i {
                            font-style: normal;
                            font-weight: 200;
                            font-size: 1.2rem;
                            margin-left: 0.5rem;
                        }

                        .Operation-Area {
                            text-align: right;
                        }

                        .row.footer_center {
                            width: 100%;
                            display: flex;
                            justify-content: center;
                            text-align: center;
                        }

                        .DeleteSelection {
                            position: absolute;
                            left: 50%;
                            bottom: 0;
                            z-index: 100;
                            font-size: 30px;
                            color: #cecece;
                            transform: translateX(-50%);
                        }

                        .DeleteSelection i {
                            position: absolute;
                            left: 50%;
                            top: 50%;
                            transition: all .2s;
                            transform: translate(-50%, -50%)
                        }

                        .DeleteSelection i:hover,
                        .DeleteSelection i.active {
                            cursor: pointer;
                            color: #ff7393;
                            transform: scale(1.3) translate(-50%, -50%);
                        }

                        .topnav-navbar {
                            padding: 0px 30px;
                            margin: 0;
                            min-height: 70px;
                        }

                        button.btn.btn-danger.mb-2.mr-2 {
                            border-radius: 0.4rem;
                        }

                        .table .action-icon:hover {
                            color: #ee7c74;
                        }

                        .form-group.mb-3.text_right {
                            text-align: right;
                        }

                        i.mdi.mdi-account-circle.mr-1.rihjt-0 {
                            margin-right: 0rem !important;
                        }

                        button.btn.btn-primary {
                            border-radius: 0.4rem;
                        }

                        h4.header-title {
                            text-align: center;
                            font-size: 1.2rem;
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 700;
                        }

                        label {
                            font-family: 'Noto Serif SC', serif;
                            font-weight: 700;
                            width: 100%;
                            overflow: auto;
                        }

                        h4.header-title.size_18,
                        h4.header-title.mb-3 {
                            font-size: 1.8rem;
                        }

                        table.dataTable tbody td.focus,
                        table.dataTable tbody th.focus {
                            outline: none !important;
                            outline-offset: 0;
                            background-color: white;
                        }

                        .text-muted {
                            white-space: nowrap;
                        }

                        .right_10 {
                            margin-left: 10px;
                            border-radius: 0.6rem;
                        }

                        iframe {
                            width: 100%;
                            height: 260px;
                            border-radius: 15px;
                            border: 2px solid #d9d9d9d1;
                            box-shadow: 2px 1px 15px rgb(36 37 38 / 44%);
                        }

                        quote {
                            padding: 5px 0px 5px 15px;
                            width: 100%;
                            display: block;
                            border-left: 3px solid #856f6f;
                            background: #f2f2f2;
                            border-radius: 0px 6px 6px 0px;
                            margin: 15px 0;
                        }

                        default-Code {
                            text-align: center;
                            width: 100%;
                            display: block;
                            color: #ff876c;
                        }

                        .editormd {
                            border-radius: 10px;
                            box-shadow: 0 4px 13px 0 rgb(185 185 185 / 14%);
                            height: 450px !important;
                            margin: 40px 0 !important;
                        }

                        .markdown-body h1 {
                            color: #383838 !important;
                            padding-bottom: 0.3em;
                            font-size: 2.25em;
                            line-height: 1.2;
                            border-bottom: 1px solid #eee;
                        }

                        .editormd-form ::-webkit-input-placeholder {
                            color: #979595;
                        }

                        .margin_left {
                            margin-left: 10px;
                        }

                        hr.User_Hr {
                            border-top: 2px dashed #d8d8d8;
                            margin: 2rem 0;
                        }

                        body {
                            cursor: url(../Style/cur/cursor.cur), default;
                            font-family: 'Noto Serif SC', serif;
                        }

                        a:hover {
                            cursor: url(../Style/cur/hover.cur), pointer;
                        }

                        @media (min-width: 992px) {
                            .text-lg-right {
                                text-align: left !important;
                            }
                        }

                        @media (max-width: 767.98px) {
                            .navbar-custom {
                                padding: 0px;
                            }

                            .topnav-navbar .topnav-logo {
                                min-width: 50px;
                                display: none;
                            }
                        }

                        .CodeMirror-gutters {
                            border-right: 1px solid #c0c0c094;
                            background-color: #ffffff;
                            white-space: nowrap;
                        }

                        .CodeMirror-activeline-background {
                            background: #edededa8;
                        }
                    </style>

</body>

</html>
