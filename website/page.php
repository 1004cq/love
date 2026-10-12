<?php
session_start();
include_once 'admin/Database.php';
$time = gmdate("Y-m-d", time() + 8 * 3600);
$id = $_GET['id'];
if (is_numeric($id) == $id) {
    $article = "SELECT * FROM article WHERE id=? limit 1";
    $stmt = $conn->prepare($article);
    $stmt->bind_param("i", $id);
    $id = $_GET['id'];
    $stmt->bind_result($id, $articletext, $articletime, $articletitle, $articlename, $ipcity, $encryptionSwitch, $PasswordSee);
    $result = $stmt->execute();
    if (!$result)
        echo "错误信息：" . $stmt->error;

    if (!$stmt->fetch()) {
        $ErrMsg = '页面不存在或已被移除 请返回首页继续浏览';
        include_once '404.php';
    }
} else {
    $ErrMsg = '当前参数有误 请输入正确参数后访问';
    include_once '404.php';
}



include 'EncryptCheck.php';

$MdPageid = md5($id . "article");

$md5pw = md5(md5($PasswordSee) . KI_Eecrypt);

$cookiepwd = isset($_COOKIE['ki_Enc_' . $MdPageid]) ? addslashes(trim($_COOKIE['ki_Enc_' . $MdPageid])) : '';


//如果管理员未登录和开启页面加密 则显示加密页面

if ($encryptionSwitch && $_SESSION['loginadmin'] == '') {
    if ($cookiepwd <> $md5pw) {
        $_SESSION['encryption_data'] = [
            'logo' => '点点滴滴',
            'password' => $PasswordSee,
            'pageid' => $MdPageid,
            'ToPage' => 'page.php?id=' . $id
        ];

        include_once 'GlobalPjax.php';

        require_once('Encrypt.php');

        exit;
    }
}


include_once 'head.php';

?>
<!DOCTYPE html>
<html>

<head>
    <title>
        <?php echo $text['title'] ?> —
        <?php echo $articletitle ?>
    </title>
    <meta name="description" content="<?php echo $seo_switch ? $text['title'] . ' - ' . $articletitle : '' ?>">
    <meta name="keywords" content="<?php echo $seo_switch ? $global_keywords : '' ?>">
    <meta name="author" content="Ki">
    <meta name="love-theme" content="LGNewUI-情侣小站">
    <meta name="copyright" content="2024 LG_NewUi Web All Rights Reserved">
</head>


<body>
    <div id="pjax-container">
        <div class="Width_limit_10rem">
            <div class="central page_card" style="margin-top:0;">
                <div class="contentDetails-warp" <?php if ($Animation == "1") { ?> aos="slide-down" <?php } ?>>
                    <div class="contentDetails">
                        <div class="AdminAvatar">
                            <img src="<?php echo DetermineAvatarAddress($articlename == $boy ? 1 : 0); ?>" alt="">
                        </div>
                        <div class="AdminInfo">
                            <div class="top-title">
                                <span class="head_name"><?php echo $articlename ?></span>
                                <h1 class="title"><?php echo $articletitle ?></h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row central central-800" style="margin-top:0;">
                    <div <?php if ($Animation == "1") { ?> aos="fade-up" <?php } ?>
                        class="card col-lg-12 col-md-12 col-sm-12 col-sm-x-12">
                        <div class="little_texts">
                            <div class="mian_cont">
                                <div class="top-title f2">
                                    <h1 class="title"><?php echo $articletitle ?></h1>
                                </div>
                            </div>
                            <div id="md-view" class="file">
                                <?php echo nl2br($articletext) ?>
                            </div>

                            <div class="line">
                                <div class="head_flex footer_text">
                                    <div>
                                        <span class="PageIcon">
                                            <svg t="1728744589755" class="icon PageCityIcon" viewBox="0 0 1024 1024"
                                                version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="53922"
                                                width="100" height="100">
                                                <path d="M512 0A512 512 0 1 1 0 512 512 512 0 0 1 512 0z" fill="#03A9F4"
                                                    p-id="53923"></path>
                                                <path
                                                    d="M712.110756 467.288605c-41.43983 140.677316-139.041534 333.699681-200.110756 333.69968s-163.578275-193.022364-200.110756-333.69968a196.29393 196.29393 0 0 1 200.110756-244.822151 194.658147 194.658147 0 0 1 200.110756 244.822151z m-200.110756-109.052183a66.521832 66.521832 0 1 0 66.521832 66.521831 66.521832 66.521832 0 0 0-66.521832-68.702875z"
                                                    fill="#FFFFFF" p-id="53924"></path>
                                            </svg>
                                            <?php if ($ipcity) {
                                                echo $ipcity;
                                            } else {
                                                echo "未知";
                                            } ?>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="PageIcon">
                                            <svg t="1728744407275" class="icon PageDataIcon" viewBox="0 0 1024 1024"
                                                version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="32926"
                                                width="100" height="100">
                                                <path
                                                    d="M512 0C229.003636 0 0 229.003636 0 512s229.003636 512 512 512 512-229.003636 512-512S794.996364 0 512 0z"
                                                    fill="#fb6e6e" p-id="32927"></path>
                                                <path
                                                    d="M512 742.4a230.4 230.4 0 1 0 0-460.8 230.4 230.4 0 0 0 0 460.8z m0 51.2a281.6 281.6 0 1 1 0-563.2 281.6 281.6 0 0 1 0 563.2z"
                                                    fill="#FFFFFF" p-id="32928"></path>
                                                <path
                                                    d="M537.693091 358.4v153.739636h-25.6l18.106182-18.152727 108.544 108.544q1.768727 1.815273 3.165091 3.909818 1.396364 2.094545 2.327272 4.421818 1.024 2.327273 1.489455 4.794182 0.465455 2.466909 0.465454 4.980364t-0.465454 5.026909q-0.465455 2.466909-1.442909 4.794182-0.930909 2.327273-2.327273 4.421818-1.442909 2.094545-3.258182 3.863273-1.768727 1.768727-3.863272 3.211636-2.094545 1.396364-4.421819 2.327273-2.327273 0.977455-4.794181 1.489454-2.466909 0.465455-4.980364 0.465455t-5.026909-0.465455q-2.466909-0.465455-4.794182-1.489454-2.327273-0.930909-4.421818-2.327273-2.094545-1.396364-3.863273-3.211636L493.986909 530.199273a25.6 25.6 0 0 1-7.493818-18.106182V358.4q0-2.513455 0.465454-4.980364 0.512-2.466909 1.489455-4.794181 0.930909-2.327273 2.373818-4.468364 1.396364-2.094545 3.165091-3.863273 1.815273-1.768727 3.909818-3.165091 2.094545-1.396364 4.421818-2.373818 2.327273-0.930909 4.794182-1.442909 2.466909-0.512 4.980364-0.512t5.026909 0.465455q2.466909 0.512 4.794182 1.489454 2.327273 0.930909 4.421818 2.327273 2.094545 1.396364 3.863273 3.258182 1.815273 1.722182 3.211636 3.863272 1.396364 2.094545 2.327273 4.421819 0.977455 2.327273 1.489454 4.794181 0.465455 2.466909 0.465455 4.980364z"
                                                    fill="#FFFFFF" p-id="32929"></path>
                                            </svg>
                                            <?php echo page_time($articletime) ?>
                                        </span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                hljs.initHighlightingOnLoad();
            </script>

            <style>
                #LGNewUI-Tab-Page-Wrap {
                    display: none;
                }

                .contentDetails-warp {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .contentDetails-warp .contentDetails {
                    display: flex;
                    align-items: center;
                    padding: .5rem;
                    padding-right: 2rem;
                    background: #f8f8f8;
                    border-radius: 10rem;
                    max-width: 260px;
                    border: 1px solid #e9e9e9;
                }

                .contentDetails-warp .contentDetails .AdminInfo {
                    margin-left: .5rem;
                }

                .contentDetails-warp .contentDetails .AdminInfo .head_name {
                    margin-bottom: 0.3rem;
                    display: block;
                    font-size: 1.2rem;
                    color: #333333;
                    font-weight: 700;
                    font-family: 'Noto Serif SC', serif;
                }

                .AdminInfo .title {
                    text-align: left;
                    color: #989898;
                    font-family: 'Noto Serif SC', serif;
                    line-height: 1.5rem;
                    overflow: hidden;
                    white-space: nowrap;
                    text-overflow: ellipsis;
                    max-width: 200px;
                }

                .contentDetails-warp .contentDetails .AdminAvatar img {
                    width: 4rem;
                    border-radius: 50%;
                }

                .top-title.f2 {
                    line-height: 2rem !important;
                    text-align: left;
                }

                .central.page_card {
                    margin: 0;
                    padding: 0.5rem;
                }

                .mian_cont {
                    display: flex;
                    margin: 2rem auto;
                    padding-bottom: .2rem;
                    border-bottom: 1px solid #03A9F4;
                }

                .central .little_texts .info {
                    padding-left: 2rem;
                }


                .head_flex.footer_text {
                    display: flex;
                    justify-content: end;
                    width: 100%;
                    box-sizing: border-box;
                    align-items: center;
                    border-top: 1px solid #dedede;
                    font-family: 'Noto Serif SC', serif;
                    padding-top: 1rem;
                    font-size: 1.1rem;
                    color: #8799a3;
                }

                .head_flex.footer_text .PageIcon:first-child {
                    margin-right: .3rem;
                }

                .head_flex.footer_text .PageIcon {
                    border-radius: 5rem;
                    user-select: none;
                    position: relative;
                    padding: 0.5rem 1rem .5rem 2.2rem;
                    position: relative;
                    background: #f2f2f2;
                    color: #a8a8a8;
                    display: block;
                }

                .head_flex.footer_text .PageDataIcon,
                .head_flex.footer_text .PageCityIcon {
                    position: absolute;
                    top: 50%;
                    left: 2px;
                    width: 1.6rem;
                    height: 1.6rem;
                    border: 1px solid #fff;
                    border-radius: 50rem;
                    transform: translate(0, -50%);
                }


                .head_flex.footer_text i {
                    font-style: normal;
                    color: #8799a3;
                }

                #md-view p {
                    text-indent: 2em;
                    line-height: 2rem;
                    margin: 1rem 0;
                }

                .row.mian_cont {
                    gap: 0rem;
                    width: 100%;
                    margin: 1rem 0 0;
                }

                ::selection {
                    background: #000000c6;
                    color: #ffffff;
                }

                .little_texts h1.title {
                    font-size: 1.4rem;
                }

                .code {
                    background: #000;
                    color: #1fe32b;
                    letter-spacing: 1px;
                    font-family: "Microsoft YaHei", 微软雅黑;
                }

                div.code {
                    white-space: pre;
                }

                .hljs {
                    line-height: 1.6;
                }

                pre {
                    position: relative;
                    background: #020202;
                    border-radius: 1rem;
                    box-shadow: 0 0 10px #18181894;
                    margin-top: 1rem;
                    margin-bottom: 2rem;
                }

                pre:before {
                    content: ' ';
                    display: inline-block;
                    width: 12px;
                    height: 12px;
                    border-radius: 50%;
                    background-color: #fd6458;
                    box-shadow: 20px 0 0 #ffbf2b, 40px 0 0 #24cc3d;
                    position: absolute;
                    left: 20px;
                    top: 15px;
                }

                button#btn {
                    position: absolute;
                    right: 12px;
                    top: 5px;
                    outline: none;
                    border: none;
                    padding: 0.3rem 1rem;
                    background: transparent;
                    color: #fff;
                    border: 1px solid #fff;
                    border-radius: 2rem;
                    display: none;
                }

                button#btn:hover {
                    border: 1px solid #ddd;
                    background: #000;
                    cursor: pointer;
                }

                pre code.hljs {
                    display: block;
                    padding: 0;
                    overflow-x: auto;
                    font-weight: 400;
                    padding: 0 1rem;
                }

                pre {
                    display: block;
                    font-size: 87.5%;
                    color: #212529;
                    /*width: 90%;*/
                }

                pre code.hljs {
                    display: block;
                    overflow-x: auto;
                    padding: 0 1.5rem !important;
                    margin-top: 1.5rem;
                    font-size: 1.1rem;
                }

                #md-view .plyr--video {
                    position: relative;
                    z-index: 999;
                    border-radius: 1rem;
                    box-shadow: 0px -1px 30px rgb(127 130 132 / 39%);
                    margin-bottom: 2rem;
                }

                .little_texts a {
                    width: auto;
                    display: inline-block;
                    /* background-color: #d2f1f0; */
                    color: #03A9F4;
                    padding: 8px 10px;
                    border-radius: 6px;
                    font-style: normal;
                    font-size: 14px;
                    font-weight: 700;
                    font-size: 1.2rem;
                    text-decoration: underline;
                    text-underline-offset: 4px;
                    transition: all 0.2s;
                    font-weight: 500;
                }

                .little_texts a:hover {
                    color: #ffcc35;
                }

                /*音乐样式*/

                .mian_music {
                    width: 300px;
                    height: auto;
                    background: #f5f5f6;
                    border-radius: 5px;
                    overflow: hidden;
                    position: relative;
                    background: url("https://p3.music.126.net/PNNpIr9mQY98jS9H9PV9eA==/109951163525904130.jpg?param=300y300") no-repeat center;
                    background-size: cover;
                    margin: 1.5rem auto;
                    box-shadow: 3px 4px 15px #e4e4e487;
                }

                .mian_music::before {
                    content: "";
                    position: absolute;
                    background: rgb(245 245 246 / 22%);
                    width: 100%;
                    height: 100%;
                    z-index: -1;
                }

                .music_card {
                    display: flex;
                    align-items: center;
                    background: #ffffffcf;
                    backdrop-filter: saturate(2) blur(50px);
                    -webkit-backdrop-filter: saturate(2) blur(50px);
                }

                .little_texts .file .music_card .cover {
                    width: 30%;
                    display: block;
                    margin: 0;
                    border-radius: 0;
                    box-shadow: none;
                    border: none !important;
                }

                .music_card .author {
                    margin-left: 0.9rem;
                    width: calc(60% - 2rem);
                }

                .music_card .author span {
                    display: block;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                .music_card .author .music_name {
                    color: #302e2e;
                    font-weight: bold;
                }

                .music_card .author .user {
                    color: #333;
                    opacity: 0.7;
                    font-size: 0.8rem;
                }

                .music_card .music_play {
                    width: 2rem;
                    height: 2rem;
                    position: absolute;
                    top: 50%;
                    right: 1rem;
                    transform: translateY(-50%);
                    background-image: url("Style/img/loading.gif");
                    background-size: 100%;
                    background-repeat: no-repeat;
                    /*transition: all 0.1s;*/
                }

                .music_card .music_play:hover {
                    cursor: pointer;
                }

                #md-view ul li {
                    line-height: 2em;
                    list-style: disc;
                    margin-left: 2rem;
                }

                br {
                    content: "";
                    margin: 0em;
                    display: block;
                }

            </style>

        </div>

    </div>

    <?php
    include_once 'footer.php';
    ?>
</body>

</html>