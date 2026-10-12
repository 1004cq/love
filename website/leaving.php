<?php

include_once 'head.php';
$nub = "select count(id) as shu from leaving";
$res = mysqli_query($connect, $nub);
$leav = mysqli_fetch_array($res);
$shu = $leav['shu'];

$leavSet = "select * from leavSet";
$Set = mysqli_query($connect, $leavSet);
$Setinfo = mysqli_fetch_array($Set);
$jiequ = $Setinfo['jiequ'];
$CutOffSwitch = $Setinfo['CutOffSwitch'];
$randomWord = $Setinfo['randomWord'];
$MsgUserIpCity =  Get_City($ip_if);

include_once 'admin/Database.php';

if ($CutOffSwitch == 2) {
    $liuyan = "SELECT * FROM leaving where switch = 1 order by time desc";
} else {
    $liuyan = "SELECT * FROM leaving where switch = 1 order by time desc limit ?";
}


$stmt = $conn->prepare($liuyan);
$stmt->bind_param("i", $ctu);
$ctu = $jiequ;
$stmt->bind_result($id, $name, $qq, $Msgtext, $time, $ip, $city, $switch, $OS_info, $BR_info);
$result = $stmt->execute();
if (!$result)
    echo "错误信息：" . $stmt->error;

while ($stmt->fetch()) {
    $lyarr[] = array(
        "id" => $id,
        "qq" => $qq,
        "name" => $name,
        "time" => $time,
        "text" => $Msgtext,
        "city" => $city,
        "Os_info" => $OS_info,
        "Br_info" => $BR_info
    );
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>
        <?php echo $text['title'] ?> —
        <?php echo $text['card2'] ?>
    </title>
</head>

<body>

    <div id="pjax-container">
        <link href="../Style/css/leav.css?=v<?php echo $versionCode ?>" rel="stylesheet" type="text/css">
        <div class="mask" id="mask">
            <div class="close">
                <svg t="1682818912164" class="icon" viewBox="0 0 1024 1024" version="1.1"
                    xmlns="http://www.w3.org/2000/svg" p-id="2416" width="200" height="200">
                    <path
                        d="M550.848 502.496l308.64-308.896a31.968 31.968 0 1 0-45.248-45.248l-308.608 308.896-308.64-308.928a31.968 31.968 0 1 0-45.248 45.248l308.64 308.896-308.64 308.896a31.968 31.968 0 1 0 45.248 45.248l308.64-308.896 308.608 308.896a31.968 31.968 0 1 0 45.248-45.248l-308.64-308.864z"
                        p-id="2417"></path>
                </svg>
            </div>
        </div>
        <div class="Width_limit_10rem leav">
            <div class="central mar_t0" <?php if ($Animation == "1") { ?>aos="flip-up" <?php } ?>>
                <div class="nav_url shadow-blur" id="click_leav">
                    <span class="iconfont icon_sm">&#xe53e;</span>
                    <?php echo $text['deci2'] ?>
                </div>
            </div>
            <div class="central ContentCentered">
                <div class="ltps lyips shadow-blur"
                    style="justify-content: center;align-items: baseline;border-radius: 10rem;">
                    <span data-i18n="messages_count_prefix">祝福留言共</span><b class="nub_con">
                        <?php echo $shu ?>
                    </b><span data-i18n="messages_count_mid">条 当前显示</span> <b class="nub_con">
                        <?php if ($shu > count($lyarr)) {
                            echo count($lyarr);
                        } else {
                            echo $shu;
                        } ?>
                    </b><span data-i18n="messages_count_suffix">条</span>
                </div>
            </div>
            <?php if (count($lyarr) <= 0) {
                include_once "PageNulldata.php";
            } ?>

            <!-- 留言内容卡片 -->
            <div class="Message_Wrap">
                <div class="row Message_Main">
                    <?php foreach ($lyarr as $key => $leav) { ?>
                        <div id="comment_<?php echo $leav['id'] ?>"
                            class="MessageCard col-lg-6 col-md-6 col-sm-12 col-sm-x-12" <?php echo $Animation == 1 ? "aos='fade-up'" : "" ?>>
                            <div class="MsgTime">
                                <span><?php echo date('Y-m-d H:i', $leav['time']); ?></span>
                            </div>
                            <div class="UserAvatar">
                                <?php if ($leav['qq'] == "anon"): ?>
                                    <img class="lazy" data-src="<?php echo $anonymousList[array_rand($anonymousList)]; ?>"
                                        alt="">
                                <?php endif; ?>
                                <?php if ($leav['qq'] <> "anon"): ?>
                                    <img class="lazy" draggable="false"
                                        data-src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo $leav['qq'] ?>&s=100"
                                        alt="<?php echo $leav['name'] ?>">
                                <?php endif; ?>
                            </div>
                            <div class="UserName">
                                <h1><?php echo $leav['name'] ?></h1>
                            </div>
                            <div class="HeightCalc">
                                <div class="MsgContent">
                                    <p><?php echo parseEmojis($leav['text']) ?></p>
                                </div>
                                <div class="MsgFooter">
                                    <div class="UserInfo">

                                        <span class="InfoItem Round" data-title="<?php echo $leav['Br_info'] ?>"
                                            data-nowrap="1">
                                            <?php echo GetBrIcon($leav['Br_info']) ?>
                                        </span>
                                        <span class="InfoItem Round" data-title="<?php echo $leav['Os_info'] ?>"
                                            data-nowrap="1">
                                            <?php echo GetOsIcon($leav['Os_info']) ?>
                                        </span>
                                        <span class="InfoItem Round colorGree"
                                            data-title="<?php echo time_tran($leav['time']) ?>" data-nowrap="1">
                                            <svg t="1723358736660" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg" p-id="21968" width="100" height="100">
                                                <path
                                                    d="M510.476308 235.176577a48.701392 48.701392 0 0 0-48.526835 48.526834v244.379744a48.526835 48.526835 0 1 0 96.879113 0v-244.379744a48.526835 48.526835 0 0 0-48.352278-48.526834z"
                                                    fill="#FF517F" p-id="21969"></path>
                                                <path
                                                    d="M513.094662 486.713155a48.526835 48.526835 0 0 0-34.911392 82.739999l120.967973 120.967973a48.526835 48.526835 0 1 0 68.600885-68.600885l-120.967972-120.967973a48.352278 48.352278 0 0 0-34.911392-14.139114z"
                                                    fill="#FF517F" p-id="21970"></path>
                                                <path
                                                    d="M384.097069 622.867584a74.012151 74.012151 0 1 0 74.012151 74.01215A73.837594 73.837594 0 0 0 384.097069 622.867584z"
                                                    fill="#FF517F" opacity=".3" p-id="21971"></path>
                                                <path
                                                    d="M511.872764 1023.999476a508.833537 508.833537 0 0 1-173.335061-30.198354 43.63924 43.63924 0 1 1 29.674683-82.041771 425.046196 425.046196 0 1 0-163.908985-107.003416 43.63924 43.63924 0 1 1-63.189619 60.222151 511.975562 511.975562 0 1 1 370.758982 159.02139z"
                                                    fill="#FF517F" p-id="21972"></path>
                                            </svg>
                                        </span>
                                        <span class="InfoItem colorGree">
                                            <svg t="1723195642680" class="icon mr04" viewBox="0 0 1024 1024" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg" p-id="21783" width="200" height="200">
                                                <path d="M192 864a320 128 0 1 0 640 0 320 128 0 1 0-640 0Z" fill="#00A1E9"
                                                    opacity=".4" p-id="21784"></path>
                                                <path
                                                    d="M512 32a315.584 315.584 0 0 0-320 310.016A429.312 429.312 0 0 0 264.896 549.12a2219.84 2219.84 0 0 0 140.8 201.856c19.2 25.6 37.568 49.664 52.672 71.104l15.936 22.528a46.08 46.08 0 0 0 37.696 19.2 46.592 46.592 0 0 0 37.696-19.2l15.232-21.376q25.6-35.904 52.8-71.104a2193.728 2193.728 0 0 0 141.184-202.432A430.592 430.592 0 0 0 832 342.144 315.392 315.392 0 0 0 512 32z m0 433.536a123.456 123.456 0 1 1 127.488-123.392A125.568 125.568 0 0 1 512 465.536z m0 0"
                                                    fill="#00A1E9" p-id="21785"></path>
                                            </svg>
                                            <span
                                                style="color: #03a9f4;"><?php echo $leav['city'] ? $leav['city'] : "中国"; ?></span>
                                        </span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>



            <?php if ($Setinfo['MsgSwitch'] == 1): ?>

                <div class="central central-800 bg" id="show_mes" style="display: none">
                    <div class="row">
                        <div class="card leav_from col-lg-12 col-md-12 col-sm-12 col-sm-x-12">
                            <form action="admin/leavingPost.php" method="post">
                                <div>
                                    <input type="checkbox" name="anonymous" id="anonymous" data-switch="success" value="0"
                                        onclick="openAnonymous(this)">
                                    <label style="display:block;" for="anonymous" data-on-label="匿名"
                                        data-off-label="QQ" data-i18n-on="anonymous"></label>
                                </div>
                                <div class="see_city">
                                    <img src="https://q1.qlogo.cn/g?b=qq&nk=1234567&s=100" alt="" class="avatar">
                                    <span class="see_btn city">
                                        <span class="iconfont" style="font-size:1.5rem">&#xe628;</span>
                                        <?php echo $MsgUserIpCity ?>
                                    </span>
                                    <span class="see_btn">
                                        <?php echo GetOsIcon(GetOs()) . GetOs() ?>
                                    </span>
                                    <span class="see_btn">
                                        <?php echo GetBrIcon(GetBrowser()) . GetBrowser() ?>
                                    </span>
                                </div>
                                <div class="inputbox">
                                    <input id="QQ" type="text" name="qq" placeholder="请输入你的QQ" class="rig" data-i18n-placeholder="placeholder_qq">
                                    <input id="nickname" type="text" name="name" placeholder="昵称" class="let" data-i18n-placeholder="placeholder_nickname">
                                </div>

                                <div class="textareaRow">
                                    <textarea name="text" id="wenben" rows="8" placeholder="请输入留言内容..." data-i18n-placeholder="placeholder_message"></textarea>
                                    <?php if ($randomWord): ?>
                                        <div class="RandomContentBtn">
                                            <span class="RCB_Style shadow-blur">
                                                <svg t="1728123880080" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                                    xmlns="http://www.w3.org/2000/svg" p-id="4608" width="100" height="100">
                                                    <path
                                                        d="M500.6 599.8c19.7 24.5 41.5 47.7 67.1 67.9 56.4 44.5 122.9 67.1 203.1 69.1L744 763.5c-14.1 14.1-14.1 36.9 0 50.9 7 7 16.2 10.5 25.5 10.5s18.4-3.5 25.5-10.5l84-84c7.4-7.4 10.9-17.2 10.5-26.9 2.9-11.8-0.3-24.8-9.5-34l-84-84c-14.1-14.1-36.9-14.1-50.9 0s-14.1 36.9 0 50.9l28.4 28.4c-117.4-2.6-177.1-56.5-230-127.1-7.2 10.2-14.4 20.8-21.9 31.6-6.9 10.2-13.9 20.3-21 30.5zM382.8 339.6C323.4 292.8 252.6 270 166.5 270c-19.9 0-36 16.1-36 36s16.1 36 36 36c145.4 0 205.2 75 267.8 165.5 14.2-20.7 28.7-41.8 44.3-62.2-27.2-38.1-57.2-75.3-95.8-105.7z"
                                                        p-id="4609"></path>
                                                    <path
                                                        d="M891.5 338.4c0.4-9.7-3.1-19.5-10.5-26.9l-84-84c-14.1-14.1-36.9-14.1-50.9 0-14.1 14.1-14.1 36.9 0 50.9l26.7 26.7c-80.2 2-146.7 24.7-203.1 69.1-51.1 40.3-87.1 92.7-121.8 143.4C380.7 615.5 322.8 700 168.5 700c-19.9 0-36 16.1-36 36s16.1 36 36 36c86.1 0 156.9-22.8 216.3-69.6 51.4-40.5 87.5-93.1 122.4-144 65.5-95.4 122.2-178.1 268.2-181.3L747 405.5c-14.1 14.1-14.1 36.9 0 50.9 7 7 16.2 10.5 25.5 10.5s18.4-3.5 25.5-10.5l84-84c9.2-9.2 12.4-22.2 9.5-34z"
                                                        p-id="4610"></path>
                                                </svg>
                                                <span data-i18n="random_quote">随机一言</span></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="MsgFooterBtn">
                                    <div class="OwOBtn">
                                        <span class="OwOBtn_Style shadow-blur">OωO</span>
                                        <div class="TabCont ElHide">
                                            <ul class="OwO_Tab_Menu"></ul>
                                            <div class="OwO_Tab_Contents"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="input-sub">
                                    <input name="city" value="<?php echo $MsgUserIpCity ?>" type="hidden">
                                    <input name="ip" value="<?php echo $ip_if ?>" type="hidden">
                                    <button type="button" id="leavingPost" class="tijiao shadow-blur" data-i18n="submit_message">提交留言
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="message_btn" id="mes">
                    <span class="mesly shadow-blur">
                        <svg t="1728547622921" class="icon" viewBox="0 0 1117 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="5133" width="200" height="200">
                            <path d="M558.545455 13.963636C251.345455 13.963636 0 237.381818 0 512c0 79.127273 18.618182 153.6 55.854545 218.763636l-37.236363 116.363637c-23.272727 79.127273 32.581818 162.909091 116.363636 162.909091H558.545455c307.2 0 558.545455-223.418182 558.545454-498.036364S865.745455 13.963636 558.545455 13.963636zM311.854545 418.909091c51.2 0 93.090909 41.890909 93.09091 93.090909s-41.890909 93.090909-93.09091 93.090909-93.090909-41.890909-93.090909-93.090909 41.890909-93.090909 93.090909-93.090909z m246.69091 186.181818c-51.2 0-93.090909-41.890909-93.09091-93.090909s41.890909-93.090909 93.09091-93.090909 93.090909 41.890909 93.090909 93.090909-41.890909 93.090909-93.090909 93.090909z m246.690909 0c-51.2 0-93.090909-41.890909-93.090909-93.090909s41.890909-93.090909 93.090909-93.090909 93.090909 41.890909 93.090909 93.090909-41.890909 93.090909-93.090909 93.090909z" p-id="5134"></path>
                        </svg>
                    </span>
                </div>

            <?php endif; ?>

            <script>
                $(document).ready(function() {

                    function preventScroll(e) {
                        e.preventDefault();
                    }

                    $(".OwOBtn_Style").click(function() {
                        $(".TabCont").slideToggle(200);
                        if ($(".OwOBtn_Style").hasClass("Current")) {
                            $(".OwOBtn_Style").removeClass("Current");
                        } else {
                            $(".OwOBtn_Style").addClass("Current");
                        }
                    });


                    $(".close").click(function() {
                        $("#show_mes").slideUp();
                        $("#mask").slideUp();
                        document.removeEventListener('touchmove', preventScroll);
                        document.removeEventListener('wheel', preventScroll);
                    });

                    let isRequesting = false;

                    $(document).on('click', '.RandomContentBtn .RCB_Style', function() {

                        if (isRequesting) return;

                        let svgRondom = `<svg t="1728123880080" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg" p-id="4608" width="100" height="100">
                                                <path
                                                    d="M500.6 599.8c19.7 24.5 41.5 47.7 67.1 67.9 56.4 44.5 122.9 67.1 203.1 69.1L744 763.5c-14.1 14.1-14.1 36.9 0 50.9 7 7 16.2 10.5 25.5 10.5s18.4-3.5 25.5-10.5l84-84c7.4-7.4 10.9-17.2 10.5-26.9 2.9-11.8-0.3-24.8-9.5-34l-84-84c-14.1-14.1-36.9-14.1-50.9 0s-14.1 36.9 0 50.9l28.4 28.4c-117.4-2.6-177.1-56.5-230-127.1-7.2 10.2-14.4 20.8-21.9 31.6-6.9 10.2-13.9 20.3-21 30.5zM382.8 339.6C323.4 292.8 252.6 270 166.5 270c-19.9 0-36 16.1-36 36s16.1 36 36 36c145.4 0 205.2 75 267.8 165.5 14.2-20.7 28.7-41.8 44.3-62.2-27.2-38.1-57.2-75.3-95.8-105.7z"
                                                    p-id="4609"></path>
                                                <path
                                                    d="M891.5 338.4c0.4-9.7-3.1-19.5-10.5-26.9l-84-84c-14.1-14.1-36.9-14.1-50.9 0-14.1 14.1-14.1 36.9 0 50.9l26.7 26.7c-80.2 2-146.7 24.7-203.1 69.1-51.1 40.3-87.1 92.7-121.8 143.4C380.7 615.5 322.8 700 168.5 700c-19.9 0-36 16.1-36 36s16.1 36 36 36c86.1 0 156.9-22.8 216.3-69.6 51.4-40.5 87.5-93.1 122.4-144 65.5-95.4 122.2-178.1 268.2-181.3L747 405.5c-14.1 14.1-14.1 36.9 0 50.9 7 7 16.2 10.5 25.5 10.5s18.4-3.5 25.5-10.5l84-84c9.2-9.2 12.4-22.2 9.5-34z"
                                                    p-id="4610"></path>
                                            </svg>随机一言`;
                        let getRondomSvg = `<svg t="1728136161636" class="icon svg-rotate" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="18620" width="100" height="100"><path d="M923 570.8H805.6c-32.4 0-58.8-26.4-58.8-58.8s26.4-58.8 58.8-58.8H923c32.4 0 58.8 26.4 58.8 58.8s-26.4 58.8-58.8 58.8z m-161.8-225c-23 23-60.2 23-83 0-23-22.8-23-60.2 0-83l83-83c22.8-23 60-23 83 0 22.8 23 23 60.2 0 83l-83 83zM512 981.8c-32.4 0-58.8-26.4-58.8-58.8V805.6c0-32.4 26.2-58.8 58.8-58.8 32.4 0 58.8 26.4 58.8 58.8V923c0 32.4-26.4 58.8-58.8 58.8z m0-704.6c-32.4 0-58.8-26.4-58.8-58.8V101c0-32.4 26.2-58.8 58.8-58.8 32.4 0 58.8 26.4 58.8 58.8v117.4c0 32.4-26.4 58.8-58.8 58.8z m-249.2 567c-22.8 23-60.2 23-83 0-23-22.8-23-60.2 0-83l83-83c23-23 60.2-23 83 0 22.8 23 23 60 0 83l-83 83z m0-498.4l-83-83c-23-23-23-60.2 0-83 23-22.8 60.2-23 83 0l83 83c23 23 23 60.2 0 83-22.8 23-60 23-83 0zM277.2 512c0 32.4-26.2 58.8-58.8 58.8H101c-32.4 0-58.8-26.4-58.8-58.8s26.2-58.8 58.8-58.8h117.4c32.4 0 58.8 26.4 58.8 58.8z m484 166.2l83 83c23 22.8 23 60 0 83-23 22.8-60.2 23-83 0l-83-83c-23-23-23-60.2 0-83 22.8-23 60-23 83 0z" p-id="18621"></path></svg>请求中`;

                        $(this).html(getRondomSvg);

                        isRequesting = true;

                        $.ajax({
                            url: "admin/infoService.php",
                            type: "POST",
                            dataType: "json",
                            success: function(result) {
                                if (result.Status) {
                                    let randomContent = result.randomContent;
                                    let textareaMsg = document.getElementById('wenben');
                                    textareaMsg.focus();
                                    textareaMsg.setSelectionRange(randomContent.length, randomContent.length);

                                    toastr["success"](result.message, "<?php echo $title ?>");
                                    $('#wenben').val(randomContent);
                                } else {
                                    toastr["error"](result.message, "<?php echo $title ?>");
                                }
                            },
                            error: function(err) {
                                console.log(err);
                                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
                            },
                            complete: function() {
                                isRequesting = false;
                                $('.RandomContentBtn .RCB_Style').html(svgRondom);
                            }
                        });
                    });

                    $("#mes,#click_leav").click(function() {
                        $("#mask").fadeToggle();
                        $("#show_mes").fadeToggle(500);
                        document.addEventListener('touchmove', preventScroll, {
                            passive: false
                        });
                        document.addEventListener('wheel', preventScroll, {
                            passive: false
                        });
                    });

                    $(".TabCont").on("touchmove wheel", function(e) {
                        e.stopPropagation();
                    });

                });

                $('#QQ').blur(function() {
                    let QQ = $("#QQ").val();
                    if (!QQ) {
                        return false;
                    }

                    $.ajax({
                        url: "admin/infoService.php",
                        type: "POST",
                        data:{
                            action:'qq',
                            qq:QQ
                        },
                        timeout: 5000,
                        dataType: "json",
                        statusCode: {
                            500: function(response) {
                                loadingname();
                                setTimeout(function() {
                                    removeLoading('test');
                                    toastr["warning"]("获取失败 QQ号码错误", "<?php echo $title ?>");
                                }, 2000);
                            }
                        },
                        success: function(result) {
                            console.log(result);
                            if (result.code == 500) {
                                removeLoading('test');
                                toastr["warning"]("获取失败 QQ号码长度错误", "<?php echo $title ?>");
                            }
                            
                            loadingname(result);
                            
                            $("#nickname").val(result.data.nick);
                            $(".avatar").attr("src", result.data.avatar);
                            setTimeout(function() {
                                removeLoading('test');
                                if(result.Status){
                                    toastr["success"](result.message, "<?php echo $title ?>");
                                }else{
                                    toastr["warning"](result.message, "<?php echo $title ?>");
                                }
                                
                            }, 1200);
                        }
                    });
                });

                function openAnonymous(obj) {
                    var input = document.getElementById("anonymous");
                    if (obj.checked) {
                        input.setAttribute("value", 1);
                        $(".avatar").attr("src", "<?php echo $anonymousList[array_rand($anonymousList)]; ?>");
                        $("#nickname").val("匿名")
                        $("#QQ").fadeOut();
                        $("#QQ").val("anon");
                    } else {
                        input.setAttribute("value", 0);
                        $(".avatar").attr("src", "https://q1.qlogo.cn/g?b=qq&nk=1234567&s=100");
                        $("#nickname").val("")
                        $("#QQ").fadeIn();
                        $("#QQ").val("");
                    }
                }

                $("#leavingPost").click(function() {
                    var qq = $("input[name='qq']").val();
                    var name = $("input[name='name']").val();
                    var text = $("textarea[name='text']").val();
                    var city = $("input[name='city']").val();
                    var ip = $("input[name='ip']").val();


                    let filter = new RegExp("[<?php echo $Setinfo['lanjiezf'] ?>]");
                    if (filter.test(text)) {
                        toastr["warning"]("请注意你的言辞 <br/> 您输入的内容含有违禁词", "<?php echo $title ?>");
                        return false;
                    }

                    if (!qq || !name || !text) {
                        toastr["warning"]("留言提交失败 表单输入不完整！", "<?php echo $title ?>");
                        return false;
                    }

                    var _i18n = function(key, fallback) {
                        return (window.LGI18n && window.LGI18n.t) ? window.LGI18n.t(key) : fallback;
                    };
                    $('#leavingPost').text(_i18n('submitting', '留言提交中...'));
                    $("#leavingPost").attr("disabled", "disabled");

                    $.ajax({
                        url: "admin/leavingPost.php",
                        data: {
                            qq: qq,
                            name: name,
                            text: text,
                        },
                        type: "POST",
                        dataType: "json",
                        success: function(res) {
                            setInterval(() => {
                                $('#leavingPost').removeAttr("disabled");
                                $('#leavingPost').text(_i18n('submit_message', '提交留言'));
                            }, 5000);
                            if (res.Status) {
                                updatedText = res.MsgHtml;
                                let newMsgCard = generateMsgCard(qq, name, updatedText);

                                if (res.sign == 1) {
                                    Mail_sent(qq, name, text, ip, city);
                                    toastr["success"](res.message, "<?php echo $title ?>");
                                } else if (res.sign == 2) {
                                    Mail_sent(qq, name, text, ip, city, 2);
                                    toastr["success"](res.message, "<?php echo $title ?>");
                                } else if (res.sign == 3) {
                                    toastr["success"](res.message, "<?php echo $title ?>");
                                }
                                $('#leavingPost').text(_i18n('submit_ok', '留言成功'));

                                $(".close").trigger("click");
                                $("html,body").animate({
                                    scrollTop: 0
                                }, 800);

                                $(".Message_Main").prepend(newMsgCard);
                                lazyLoadInstance.update();

                                qq, name, text, ip, city = ''
                            } else {
                                toastr["error"](res.message, "<?php echo $title ?>");
                                $('#leavingPost').text(_i18n('submit_fail', '留言失败！'));
                            }
                        },
                        error: function(err) {
                            toastr["error"]("网络错误 请稍后重试！", "<?php echo $title ?>");
                            console.log(err);
                        }
                    })
                })

                function generateMsgCard(qq, name, text) {
                    return `
        <div class="MessageCard col-lg-6 col-md-6 col-sm-12 col-sm-x-12">
            <div class="toExamine">
                <img src="../Style/img/toExamine.svg" alt="">
            </div>
            <div class="UserAvatar">
                <img src="https://q1.qlogo.cn/g?b=qq&nk=${qq}&s=100" alt="">
            </div>
            <div class="UserName">
                <h1>${name}</h1>
            </div>
            <div class="MsgContent">
                <p>${text}</p>
            </div>
            <div class="MsgFooter">
                <div class="UserInfo">
                    <span class="InfoItem Round">
                        <?php echo GetBrIcon(GetBrowser()) ?>
                    </span>
                    <span class="InfoItem Round">
                        <?php echo GetOsIcon(GetOs()) ?>
                    </span>
                    <span class="InfoItem colorGree">
                        <svg t="1723195642680" class="icon mr04" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            p-id="21783" width="200" height="200">
                            <path d="M192 864a320 128 0 1 0 640 0 320 128 0 1 0-640 0Z" fill="#00A1E9" opacity=".4" p-id="21784"></path>
                            <path
                                d="M512 32a315.584 315.584 0 0 0-320 310.016A429.312 429.312 0 0 0 264.896 549.12a2219.84 2219.84 0 0 0 140.8 201.856c19.2 25.6 37.568 49.664 52.672 71.104l15.936 22.528a46.08 46.08 0 0 0 37.696 19.2 46.592 46.592 0 0 0 37.696-19.2l15.232-21.376q25.6-35.904 52.8-71.104a2193.728 2193.728 0 0 0 141.184-202.432A430.592 430.592 0 0 0 832 342.144 315.392 315.392 0 0 0 512 32z m0 433.536a123.456 123.456 0 1 1 127.488-123.392A125.568 125.568 0 0 1 512 465.536z m0 0"
                                fill="#00A1E9" p-id="21785"></path>
                        </svg>
                        <span style="color: #03a9f4;"><?php echo $MsgUserIpCity ?></span>
                    </span>
                    <span class="InfoItem colorGree">
                        <svg t="1723358736660" class="icon mr04" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            p-id="21968" width="100" height="100">
                            <path
                                d="M510.476308 235.176577a48.701392 48.701392 0 0 0-48.526835 48.526834v244.379744a48.526835 48.526835 0 1 0 96.879113 0v-244.379744a48.526835 48.526835 0 0 0-48.352278-48.526834z"
                                fill="#FF517F" p-id="21969"></path>
                            <path
                                d="M513.094662 486.713155a48.526835 48.526835 0 0 0-34.911392 82.739999l120.967973 120.967973a48.526835 48.526835 0 1 0 68.600885-68.600885l-120.967972-120.967973a48.352278 48.352278 0 0 0-34.911392-14.139114z"
                                fill="#FF517F" p-id="21970"></path>
                            <path
                                d="M384.097069 622.867584a74.012151 74.012151 0 1 0 74.012151 74.01215A73.837594 73.837594 0 0 0 384.097069 622.867584z"
                                fill="#FF517F" opacity=".3" p-id="21971"></path>
                            <path
                                d="M511.872764 1023.999476a508.833537 508.833537 0 0 1-173.335061-30.198354 43.63924 43.63924 0 1 1 29.674683-82.041771 425.046196 425.046196 0 1 0-163.908985-107.003416 43.63924 43.63924 0 1 1-63.189619 60.222151 511.975562 511.975562 0 1 1 370.758982 159.02139z"
                                fill="#FF517F" p-id="21972"></path>
                        </svg>
                        <span style="color: #ff517f;">
                            刚刚
                        </span>
                    </span>
                </div>
            </div>
        </div>`;
                }

                function Mail_sent(qq, name, text, ip, attr, MsgType = 1) {
                    let host = window.location.hostname;
                    $.ajax({
                        url: "admin/Mail_sent.php",
                        type: "POST",
                        data: {
                            mail_qq: stripHtmlTags(qq),
                            mail_name: stripHtmlTags(name),
                            mail_text: stripHtmlTags(text),
                            mail_ip: stripHtmlTags(ip),
                            mail_attr: stripHtmlTags(attr),
                            mail_sw: "待审核",
                            MsgType: MsgType,
                            url: host,
                            sending: true
                        },
                        timeout: 5000,
                        dataType: "text",
                        success: function(res) {
                            if (res == "success") {
                                toastr["success"]("邮件发送成功 已通知管理员审核留言", "<?php echo $title ?>");
                            } else {
                                toastr["error"](res, "<?php echo $title ?>");
                            }
                        }

                    });
                }

                function stripHtmlTags(htmlString) {
                    var strippedText = htmlString.replace(/<[^>]+>/g, '');
                    return strippedText;
                }

                function loadingname() {
                    $('body').loading({
                        loadingWidth: 240,
                        title: '获取昵称头像中',
                        name: 'test',
                        discription: '请稍等片刻',
                        direction: 'column',
                        type: 'origin',
                        // originBg:'#71EA71',
                        originDivWidth: 40,
                        originDivHeight: 40,
                        originWidth: 6,
                        originHeight: 6,
                        smallLoading: false,
                        loadingMaskBg: 'rgba(0,0,0,0.2)'
                    });

                }
            </script>
        </div>
    </div>

    <?php
    include_once 'footer.php';
    ?>


</body>

</html>