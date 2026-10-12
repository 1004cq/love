<!--
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
-----------------------------------------------------------------
 *
 * Project: LG_NewUi
 * Author: Ki
 * Last Updated: 2024-12-21
 * Description: 小手一牵 岁岁年年
 * 
 * Copyright (c) 2022-2024 by Ki. All Rights Reserved.
 * 
 * License: This software is licensed for both personal and commercial use under specific terms.
 * Redistribution, modification, or resale without explicit written permission is strictly prohibited.
 * Unauthorized use, copying, or redistribution will result in immediate termination of support and updates.
 * 
 * Documentation and License: https://lguiy.kikiw.cn/wiki
 * 
 * * Warning: This is an original work, and all copyright belongs to LG_NewUi.
 * Any attempt to scrape, copy, sell, or redistribute this software without permission is strictly forbidden.
 * Violators will face immediate legal action, blacklisting, and permanent cessation of future updates.
 * 
 * Development requires substantial time and effort. We appreciate your respect for the work involved and your support.
 *
-->

<?php
header('Content-type: text/html; charset=UTF8');
include_once 'admin/connect.php';
include_once 'admin/Function.php';
include("ipjc.php");
include_once "ip.php";
error_reporting(0);

$sqlDiy = "select * from diySet";
$resultDiy = mysqli_query($connect, $sqlDiy);
if (mysqli_num_rows($resultDiy)) {
    $diy = mysqli_fetch_array($resultDiy);
}

if ($diy['StationMaintenanceSwitch']) {
    define('Maintain', true);
    include_once 'Maintain.php';
    exit;
}

$leavSet = "select * from leavSet order by id desc";
$Set = mysqli_query($connect, $leavSet);
$Setinfo = mysqli_fetch_array($Set);
$jiequ = $Setinfo['jiequ'];
$anonymousArr = $Setinfo['anonymousArr'];

$copy = $text['Copyright'];
$icp = $text['icp'];
$Animation = $text['Animation'];
$boy = $text['boy'];
$girl = $text['girl'];
$boyi = $text['boyimg'];
$girli = $text['girlimg'];
$title = $text['title'];
$policeICP = $text['policeICP'];
$Moelicp = $text['Moelicp'];
$if_aiv = $text['if_aiv'];
$b_url = $text['diyb_url'];
$seo_switch = $text['seo_switch'];
$site_description = $text['site_description'];
$global_keywords = $text['global_keywords'];
$loveIconLink = $text['loveIconLink'];



$diy_page_pw = $diy['diy_page_pw'];
$open_pw = $diy['open_pw'];
$url_pw = $diy['url_pw'];
$is_aboutActive = $diy['is_aboutActive'];
$TabTemplate = $diy['TabTemplate'];

$RandomSort = $diy['RandomSort'];
$pcCarouselHeight = $diy['pcCarouselHeight'];
$mobileCarouselHeight = $diy['mobileCarouselHeight'];
$pcPhotoCoverHeight = $diy['pcPhotoCoverHeight'];
$mobilePhotoCoverHeight = $diy['mobilePhotoCoverHeight'];
$pcImgMaxHeight = $diy['pcImgMaxHeight'];
$mobileImgMaxHeight = $diy['mobileImgMaxHeight'];


// 轮播图
$carousel = ExtractURL($diy['carousel']);
// 匿名头像
$anonymousList = ExtractURL($anonymousArr);

include_once 'EncryptCheck.php';

$MdPageid = md5("AllPage");

$md5pw = md5(md5($diy_page_pw) . KI_Eecrypt);

$cookiepwd = isset($_COOKIE['ki_Enc_' . $MdPageid]) ? addslashes(trim($_COOKIE['ki_Enc_' . $MdPageid])) : '';

if ($open_pw) {
    if ($cookiepwd <> $md5pw) {
        $_SESSION['encryption_data'] = [
            'logo' => '全局页面',
            'password' => $diy_page_pw,
            'pageid' => $MdPageid,
            'ToPage' => 'index.php',
            'url_pw' => $url_pw
        ];

        include_once 'GlobalPjax.php';

        require_once('Encrypt.php');

        exit;
    }
}

?>


<script>
    function show_date_time() {
        window.setTimeout("show_date_time()", 1000);

        const BirthDay = new Date("<?php echo $text['startTime'] ?>");
        const today = new Date();
        const timeold = today.getTime() - BirthDay.getTime();
        const secondsold = Math.floor(timeold / 1000);
        const msPerDay = 24 * 60 * 60 * 1000;
        const e_daysold = timeold / msPerDay;
        const daysold = Math.floor(e_daysold);
        const e_hrsold = (e_daysold - daysold) * 24;
        const hrsold = Math.floor(e_hrsold);
        const e_minsold = (e_hrsold - hrsold) * 60;
        const minsold = Math.floor(e_minsold);
        const seconds = Math.floor((e_minsold - minsold) * 60);

        const tian = document.getElementById('tian');
        const shi = document.getElementById('shi');
        const fen = document.getElementById('fen');
        const miao = document.getElementById('miao');

        if (tian && shi && fen && miao) {
            tian.innerHTML = padZero(daysold);
            shi.innerHTML = padZero(hrsold);
            fen.innerHTML = padZero(minsold);
            miao.innerHTML = padZero(seconds);
        }
    }

    function padZero(num) {
        return num < 10 ? '0' + num : num;
    }


    let pcCarouselHeight = "<?php echo $pcCarouselHeight; ?>";
    let mobileCarouselHeight = "<?php echo $mobileCarouselHeight; ?>";
    let pcPhotoCoverHeight = "<?php echo $pcPhotoCoverHeight; ?>";
    let mobilePhotoCoverHeight = "<?php echo $mobilePhotoCoverHeight; ?>";
    let pcImgMaxHeight = "<?php echo $pcImgMaxHeight; ?>";
    let mobileImgMaxHeight = "<?php echo $mobileImgMaxHeight; ?>";

    function adjustCarouselHeight() {

        if ($(window).width() <= 768) {
            if (mobileCarouselHeight) {
                $('#homePage').css('height', mobileCarouselHeight);
            }
        } else {
            if (pcCarouselHeight) {
                $('#homePage').css('height', pcCarouselHeight);
            }
        }
    }

    function adjustPhotoCoverHeight() {

        if ($(window).width() <= 768) {
            if (mobilePhotoCoverHeight) {
                $('.photo_bg').css('height', mobilePhotoCoverHeight);
            }
        } else {
            if (pcPhotoCoverHeight) {
                $('.photo_bg').css('height', pcPhotoCoverHeight);
            }
        }
    }

    function adjustImgMinHeight() {

        if ($(window).width() <= 768) {
            if (mobileImgMaxHeight) {
                $('.gallery img').css('min-height', mobileImgMaxHeight);
            }
        } else {
            if (pcImgMaxHeight) {
                $('.gallery img').css('min-height', pcImgMaxHeight);
            }
        }
    }

    function IsCurrentURL() {

        let currentURL = window.location.pathname;
        let previousPage = sessionStorage.getItem('previousPage');


        if (currentURL === "/page.php" || currentURL === "/imglist.php") {
            $('.PreviousPage').fadeIn(200);
            $('.PreviousPage a').attr('href', previousPage);
        } else {
            sessionStorage.setItem('previousPage', window.location.href);
            $('.PreviousPage').hide();
        }
    }

    function setActiveTab() {
        var currentPage = window.location.pathname;

        var navLinks = document.querySelectorAll('.LG_Tab_Item a');

        navLinks.forEach(function (link) {
            var linkPage = link.getAttribute('href');

            if (currentPage.includes(linkPage)) {
                link.classList.add('TabActive');
            } else {
                link.classList.remove('TabActive');
            }
        });
    }

    show_date_time();

    console.log("%c 未经授权 盗版必究 禁止抄袭 感谢配合", "color:#fadfa3;background:#333;padding:8px 15px;");
    console.log(
        "%c 购买地址: https://blog.kikiw.cn/index.php/archives/65/",
        "color: #fff; background-image:linear-gradient(to right, #1FA2FF 0%, #fdfdfd  21%, #fafafa  100%); padding: 8px 15px; border-radius: 5px;"
    );
    console.log("%c Contact Me: 3439780232 | mail@kikiw.cn", "color:#fff;background:#000;padding:8px 15px;border-radius:10px");
    console.log("%c LG_NewUi <?php echo $version ?> | Powered by Ki", "color:#fff;background:linear-gradient(to right, hsl(206.57deg 100% 61.11%) 0%, hsl(57deg 100% 85.15%) 100%);padding:8px 15px;border-radius:15px");
</script>

<link rel="shortcut icon" href="/favicon.ico" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<link href="https://fonts.googleapis.com/css?family=Concert+One|Pacifico" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../Style/css/leaving.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="../Style/css/index.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="../Style/css/little.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="../Style/css/about.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="../Botui/botui.min.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="../Botui/botui-theme-default.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="../Style/css/loveImg.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="../Style/css/list.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="../Style/Font/font_list/iconfont.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="../Style/toastr/toastr.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="../Style/css/loadinglike.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="../Style/static/css/aos.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="../Style/css/plyr.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="../icon/iconfont.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="../Style/css/kicode.css?=v<?php echo $versionCode ?>">
<link rel="stylesheet" href="/Style/css/modern-2026.css?v=<?php echo $versionCode ?>">

<script src="../Style/Font/font_leav/iconfont.js"></script>
<script src="../Style/Font/OsBr/OsBr.js"></script>
<script src="../Botui/botui.min.js"></script>
<script src="https://cdn.staticfile.org/vue/2.2.2/vue.min.js"></script>
<script src="../Style/jquery/jquery.min.js"></script>
<script src="../Style/js/jquery.pjax.js" type="text/javascript"></script>
<script src="../Style/js/plyr.js"></script>
<script src="../Style/js/lazyload.js"></script>
<script src="../Style/static/js/aos.js"></script>
<script src="../Style/js/highlight.min.js"></script>
<script src="../Style/js/lazyload.min.js"></script>
<script src="../Style/js/masonry.pkgd.min.js"></script>
<script src="../Style/js/loading.js?=v<?php echo $versionCode ?>"></script>
<script src="../Style/js/LGNewUiOwO.js?=v<?php echo $versionCode ?>"></script>


<?php
echo htmlspecialchars_decode($diy['headCon'], ENT_QUOTES);

if ($diy['Pjaxkg'] == 1) {
    include_once 'GlobalPjax.php';
} ?>

<script src="../Style/js/nprogress.js"></script>
<link href="../Style/css/nprogress.css" rel="stylesheet" type="text/css">

<div id="loader-wrapper">
    <div id="loader"></div>
    <div class="loader-section"></div>
</div>

<div class="header-wrap">
    <div class="header">
        <div class="PreviousPage">
            <a href="index.php">
                <svg t="1728284489376" class="icon" viewBox="0 0 1024 1024" version="1.1"
                    xmlns="http://www.w3.org/2000/svg" p-id="4159" width="100" height="100">
                    <path
                        d="M425.25729 510.464l260.954839-248.121806a42.941935 42.941935 0 0 0-59.193806-62.232775L333.757935 478.93471a42.941935 42.941935 0 0 0-0.396387 61.852903L626.621935 826.913032a42.941935 42.941935 0 0 0 59.986581-61.473032L425.25729 510.464z"
                        p-id="4160"></path>
                </svg>
            </a>
        </div>
        <div class="logo">
            <h1><a class="alogo" href="index.php">
                    <?php echo $text['logo'] ?>
                </a></h1>
        </div>
    </div>
</div>

<div id="homePage" class="wrap" <?php echo $diy['Fullscreen'] == 1 ? 'data-Fullscreen' : '' ?>>
    <ul class="list <?php echo $diy['BackgroundMask'] == 1 ? 'mask_black' : '' ?>">
        <?php
        $carousel_num = 0;
        foreach ($carousel as $array_c) {
            $carousel_num = $carousel_num + 1;
            ?>
            <li class="item<?php echo $carousel_num == 1 ? ' active' : '' ?>">
                <img class="lazy CarouselImage" data-src="<?php echo $array_c ?>">
            </li>
            <?php
        }
        ?>
    </ul>

    <div class="bg-wrap central limg">
        <div class="bg-img">
            <div class="middle<?php echo $diy['Blurkg'] == 2 ? ' Blurkg' : '' ?>">
                <div class="img-male">
                    <div class="avatarArea">
                        <img draggable="false" class="avatarFrame lazy" <?php echo getAvatarFrames($text['ml_frame']) ?>>
                        <img draggable="false" class="aiv_touxiang" data-src="<?php echo DetermineAvatarAddress(1) ?>">
                    </div>
                    <span class="shadow-blur">
                        <?php echo $text['boy'] ?>
                    </span>
                </div>
                <div class="love-icon">
                    <img draggable="false" src="<?php echo $loveIconLink ?>">
                </div>
                <div class="img-female">
                    <div class="avatarArea">
                        <img draggable="false" class="avatarFrame lazy" <?php echo getAvatarFrames($text['fl_frame']) ?>>
                        <img draggable="false" class="aiv_touxiang" data-src="<?php echo DetermineAvatarAddress(0) ?>">
                    </div>
                    <span class="shadow-blur">
                        <?php echo $text['girl'] ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
        viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
        <defs>
            <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
        </defs>
        <g class="parallax">
            <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />
            <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
            <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
            <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
        </g>
    </svg>
    <ul class="pointList">
        <?php
        $point_num = 0;
        $arr_num = -1;
        foreach ($carousel as $array_c) {
            ?>
            <li class="point <?php if ($arr_num == -1) { ?>active<?php } ?>" data-index=<?php echo $arr_num = $arr_num + 1 ?>>
            </li>
            <?php
            $point_num = $carousel_num + 1;
        }
        ?>
    </ul>

</div>

<div class="Width_limit_10re lg_20rem">
    <div class="row central nav_k">
        <a href="little.php" class="col-lg-3 col-md-6 col-sm-6 col-sm-x-12 nav_card shadow-blur">
            <div class="card_content">
                <div class="icon">
                    <span class="iconfont icon-a-wenzhang1x nav_icon"></span>
                </div>
                <div class="title">
                    <h1>
                        <?php echo $text['card1'] ?>
                    </h1>
                </div>
                <div class="describe">
                    <span>
                        <?php echo $text['deci1'] ?>
                    </span>
                </div>
            </div>
        </a>
        <a href="leaving.php" class="col-lg-3 col-md-6 col-sm-6 col-sm-x-12 nav_card shadow-blur">
            <div class="card_content">
                <div class="icon">
                    <span class="iconfont icon-liuyan1 nav_icon"></span>
                </div>
                <div class="title">
                    <h1>
                        <?php echo $text['card2'] ?>
                    </h1>
                </div>
                <div class="describe">
                    <span>
                        <?php echo $text['deci2'] ?>
                    </span>
                </div>
            </div>
        </a>
        <a href="loveImg.php" class="col-lg-3 col-md-6 col-sm-6 col-sm-x-12 nav_card shadow-blur">
            <div class="card_content">
                <div class="icon">
                    <span class="iconfont icon-tukuxiangce nav_icon"></span>
                </div>
                <div class="title">
                    <h1>
                        <?php echo $text['card3'] ?>
                    </h1>
                </div>
                <div class="describe">
                    <span>
                        <?php echo $text['deci3'] ?>
                    </span>
                </div>
            </div>
        </a>
        <a href="list.php" class="col-lg-3 col-md-6 col-sm-6 col-sm-x-12 nav_card shadow-blur">
            <div class="card_content">
                <div class="icon">
                    <span class="iconfont icon-liebiao1 nav_icon"></span>
                </div>
                <div class="title">
                    <h1>
                        <?php echo $text['card4'] ?>
                    </h1>
                </div>
                <div class="describe">
                    <span>
                        <?php echo $text['deci4'] ?>
                    </span>
                </div>
            </div>
        </a>
    </div>

</div>

<script>
    var items = document.querySelectorAll(".item");
    var points = document.querySelectorAll(".point")
    var all = document.querySelector(".wrap")
    var index = 0;
    var time = 0;
    var clearActive = function () {
        for (i = 0; i < items.length; i++) {
            items[i].className = 'item';
        }
        for (j = 0; j < points.length; j++) {
            points[j].className = 'point';
        }
    }
    var goIndex = function () {
        clearActive();
        items[index].className = 'item active';
        points[index].className = 'point active'
    }
    var goLeft = function () {
        if (index == 0) {
            index = points.length - 1;
        } else {
            index--;
        }
        goIndex();
    }
    var goRight = function () {
        if (index < points.length - 1) {
            index++;
        } else {
            index = 0;
        }
        goIndex();
    }
    for (i = 0; i < points.length; i++) {
        points[i].addEventListener('click', function () {
            var pointIndex = this.getAttribute('data-index')
            index = pointIndex;
            goIndex();
            time = 0;
        })
    }
    var timer;

    function play() {
        timer = setInterval(() => {
            time++;
            if (time == 20) {
                goRight();
                time = 0;
            }
        }, 300)
    }

    play();
    all.onmousemove = function () {
        clearInterval(timer)
    }
    all.onmouseleave = function () {
        play();
    }

    window.onscroll = function () {
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        if (scrollTop > 500) {

            $('.alogo').css({
                'color': '#181818'
            });

            $('.PreviousPage').css({
                'border-color': '#999'
            });
            $('.PreviousPage .icon').css({
                'fill': '#999999'
            });

            $('.header-wrap').css({
                'backdropFilter': 'saturate(5) blur(20px)'
            });
        }

        if (scrollTop < 500) {

            $('.alogo').css({
                'color': '#777'
            });

            $('.PreviousPage').css({
                'border-color': '#fff'
            });
            $('.PreviousPage .icon').css({
                'fill': '#ffffff'
            });

            $('.header-wrap').css({
                'backdropFilter': 'blur(20px)'
            });
        }
    }
</script>

<style>
    @media screen and (max-width: 768px) {
        .row.central.nav_k {
            display: none;
        }

        .swiper-container {
            margin-top: 3rem;
        }

        .swiper-wrap {
            width: 100%;
        }


        .introduce img {
            height: 8em;
        }

        .introduce {
            padding: 0;
        }

        iframe {
            height: 270px;
        }

        .wrap {
            width: 95%;
            height: 320px;
            position: relative;
            margin: 0 auto 1rem;
            margin-top: 5.2rem;
        }

        .list {
            height: 320px;
        }
    }

    .CarouselImage {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .tooltip {
        display: none;
        position: absolute;
        background-color: #282828e3;
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        bottom: 100%;
        left: 0;
        z-index: 10;
        word-break: break-all;
        text-align: center;
        max-width: 100%;
    }

    .tooltip.Textnowrap {
        max-width: none;
        white-space: nowrap;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #282828e3 transparent transparent transparent;
    }

    .left_info {
        position: relative;
    }


    .div_off {
        padding: 3rem 2rem;
        background: #fff;
        border-radius: 1rem;
        text-align: center;
        font-size: 1.8rem;
        font-family: 'Noto Serif SC', serif;
        font-weight: 700;
        letter-spacing: 3px;
        color: #eb2c14;
        box-shadow: 0 6px 15px #d3d1d159;
    }

    .po_rid {
        position: relative;
    }

    .nub_1 b {
        position: absolute;
        right: 0;
        top: 0;
        padding: 0.5rem 2rem;
        background: #f9d7ea;
        color: #e03997;
        border-radius: 1rem;
    }

    .central.bg .row .card .leavform .textinfo .time {
        text-align: right;
        display: inherit;
        font-family: 'Noto Serif SC', serif;
        font-weight: 400;
        color: #423a3a;
    }

    .bg-img {
        background: url(<?php echo $text['bgimg'] ?>) no-repeat center !important;
        background-size: cover !important;
    }


    .alogo {
        color: #181818;
        transition: all 0.2s linear;
        text-shadow: 0 2px 2px rgb(0 0 0 / 25%);
    }

    code {
        padding: 0.2rem 0.5rem;
        border-radius: 0.4rem;
        font-size: 1rem;
        color: #ff5c28;
        background-color: rgb(255 186 200 / 18%);
        font-family: 'Noto Serif SC', serif;
        /*font-weight: 700;*/
        margin: 0 4px;
    }


    /* webkit, opera, IE9 （谷歌浏览器）*/
    ::selection {
        background: #6f6f6fc7;
        color: #ffffff;
    }

    /* mozilla firefox（火狐浏览器） */
    ::-moz-selection {
        background: #6f6f6fc7;
        color: #ffffff;
    }

    .delay-03s {
        -webkit-animation-delay: .3s;
        animation-delay: .3s;
    }

    .Blurkg {
        backdrop-filter: blur(0px) !important;
        -webkit-backdrop-filter: blur(0px) !important;
        background: transparent !important;
        border: none !important;
    }

    .cpt-loading-mask.column {
        background: transparent !important;
    }

    .view-image-lead img {
        border-radius: 10px !important;
    }

    .view-image-btn {
        border-radius: 8px !important;
    }

    .view-image {
        z-index: 10000 !important;
    }

    .music_info {
        position: fixed;
        top: 6rem;
        left: 1rem;
        width: 6.5rem;
        height: 8rem;
        background-image: url("https://p3.music.126.net/PNNpIr9mQY98jS9H9PV9eA==/109951163525904130.jpg?param=300y300");
        background-size: cover;
        border-radius: 0.8rem;
        background-position: center;
        overflow: hidden;
        z-index: 999999;
        display: none;
        cursor: move;
    }

    .music_info_dragging {
        cursor: move;
        opacity: 0.9;
    }

    .music_info .music_info_btn {
        padding: 0.4rem 0.6rem;
        background: #d0d0d073;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        backdrop-filter: saturate(3) blur(30px);
        -webkit-backdrop-filter: saturate(3) blur(30px);
        box-sizing: border-box;
    }

    .music_info .music_info_btn span {
        width: 1.3rem;
        height: 1.3rem;
        display: block;
        background-size: 100%;
    }

    .music_info .music_info_btn span:hover {
        cursor: pointer;
    }

    .music_info .music_info_btn .music_info_btn_play {
        background-image: url("Style/img/pause2.svg");
    }

    .music_info .music_info_btn .music_info_btn_close {
        background-image: url("Style/img/close.svg");
    }

    .div_off h1 {
        font-size: 1.8rem;
    }

    #loader-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        overflow: hidden;
    }

    .no-js #loader-wrapper {
        display: none;
    }

    img.photo_style,
    img.aiv_touxiang {
        opacity: 0;
        -webkit-transition: .8s ease-in-out opacity;
        transition: .8s ease-in-out opacity;
        filter: blur(35px);
        overflow: hidden;
        box-sizing: border-box;
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
        transition: .7s filter linear, .7s -webkit-filter linear;
    }

    #loader {
        display: block;
        position: relative;
        left: 50%;
        top: 50%;
        width: 150px;
        height: 150px;
        margin: -75px 0 0 -75px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #16a085;
        -webkit-animation: spin 1.7s linear infinite;
        animation: spin 1.7s linear infinite;
        z-index: 11;
    }

    #loader:before {
        content: "";
        position: absolute;
        top: 5px;
        left: 5px;
        right: 5px;
        bottom: 5px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #e74c3c;
        -webkit-animation: spin-reverse 0.6s linear infinite;
        animation: spin-reverse 0.6s linear infinite;
    }

    #loader:after {
        content: "";
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        bottom: 15px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #f9c922;
        -webkit-animation: spin 1s linear infinite;
        animation: spin 1s linear infinite;
    }

    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @-webkit-keyframes spin-reverse {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(-360deg);
        }
    }

    @keyframes spin-reverse {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(-360deg);
        }
    }



    #loader-wrapper .loader-section {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
        background: #ffffffd6;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);

    }

    .loaded #loader {
        opacity: 0;
        transition: all 0.3s ease-out;
    }

    .loaded #loader-wrapper {
        animation: MaskFadeOut .5s linear forwards;
        display: none;
    }

    @keyframes MaskFadeOut {
        0% {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        95% {
            backdrop-filter: blur(0px);
            -webkit-backdrop-filter: blur(0px);
        }

        100% {
            opacity: 0;
            display: none;
        }
    }
</style>
<!--自定义css样式区域-->
<style>
    <?php echo htmlspecialchars_decode($diy['cssCon'], ENT_QUOTES) ?>
</style>
<div class="music_info">
    <div class="music_info_btn">
        <span class="music_info_btn_play" data-music></span>
        <span class="music_info_btn_close"></span>
    </div>
</div>
<audio id="music"></audio>


<!--Head End-->
