<?php
session_start();

@$id = $_GET['code'];
if (!is_numeric($id) || $id == '') {
    $ErrMsg = '当前参数有误 请输入正确参数后访问';
    include_once '404.php';
}
include_once 'admin/connect.php';
$loveImg = "select * from loveImg";
$resImg = mysqli_query($connect, $loveImg);
while ($img_info = mysqli_fetch_array($resImg)) {
    if ($img_info['img_code'] == $id) {
        $p_text = $img_info['imgText'];
        $p_bgurl = $img_info['imgUrl'];
        $p_Author = $img_info['upload_name'];
        $p_Date = $img_info['imgDatd'];
        $encryptionSwitch = $img_info['encryptionSwitch'];
        $PasswordSee = $img_info['PasswordSee'];
    }
}

include 'EncryptCheck.php';

$MdPageid = md5($id . "LovePhoto");

$md5pw = md5(md5($PasswordSee) . KI_Eecrypt);

$cookiepwd = isset($_COOKIE['ki_Enc_' . $MdPageid ]) ? addslashes(trim($_COOKIE['ki_Enc_' . $MdPageid ])) : '';


//如果管理员未登录和开启页面加密 则显示加密页面

if ($encryptionSwitch && $_SESSION['loginadmin'] == '') {
    if ($cookiepwd <> $md5pw) {
        $_SESSION['encryption_data'] = [
            'logo' => '恋爱相册',
            'password' => $PasswordSee,
            'pageid' => $MdPageid,
            'ToPage' => 'imglist.php?code=' . $id
        ];

        include_once 'GlobalPjax.php';

        require_once('Encrypt.php');

        exit;
    }
}


include_once 'admin/Database.php';
if (is_numeric($id) == $id) {
    $imglist = "select * from Photolist where photo_code = ?";
    $stmt = $conn->prepare($imglist);
    $stmt->bind_param("i", $id);
    $id = $_GET['code'];
    $stmt->bind_result($id, $photo_id, $photo_code, $photo_text, $photo_url, $photo_date, $photo_byname, $photo_type, $VideoCover);
    $result = $stmt->execute();
} else {
    $ErrMsg = '页面不存在或已被移除 请返回首页继续浏览';
    include_once '404.php';
}
$PhotoListCount = 0;

while ($stmt->fetch()) {

    $listarr[] = array(
        "id" => $id,
        "photo_id" => $photo_id,
        "photo_code" => $photo_code,
        "photo_text" => $photo_text,
        "photo_url" => $photo_url,
        "photo_date" => $photo_date,
        "photo_byname" => $photo_byname,
        "photo_type" => $photo_type,
        "VideoCover" => $VideoCover
    );
    $PhotoListCount++;
}

if ($PhotoListCount == 0) {
    $ErrMsg = '当前相册暂无数据 请返回首页继续浏览';
    include_once '404.php';
}

include_once "head.php";

if ($RandomSort) {
    shuffle($listarr);
}
?>

<head>
    <title>
        <?php echo $text['title'] ?>相册详情 —
        <?php echo $p_text ?>
    </title>
    <meta name="description" content="<?php echo $seo_switch ? $text['title'] . ' - ' . $p_text : '' ?>">
    <meta name="keywords" content="<?php echo $seo_switch ? $global_keywords : '' ?>">
    <meta name="author" content="Ki">
    <meta name="love-theme" content="LGNewUI-情侣小站">
    <meta name="copyright" content="2024 LG_NewUi Web All Rights Reserved">
</head>

<body>
    <div id="pjax-container">
        <link rel="stylesheet" href="../Style/dplayer/DPlayer.min.css">
        <link rel="stylesheet" href="../Style/css/photo.css?=v<?php echo $versionCode ?>">
        <div class="photo_bg" style="background: url(<?php echo $p_bgurl ?>)no-repeat center;background-size: cover;">
            <h1 class="photo_title">
                <div class="TitleStyle">
                    <?php echo $p_text ?>
                </div>
            </h1>
            <div class="Photo_Data">
                <p class="Ftext"> <svg t="1728278643061" class="icon" viewBox="0 0 1024 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="13637" width="100" height="100">
                        <path
                            d="M480 640l64 0C561.92 640 576 625.92 576 608S561.92 576 544 576l-64 0C462.08 576 448 590.08 448 608S462.08 640 480 640zM480 768l64 0C561.92 768 576 753.92 576 736 576 718.08 561.92 704 544 704l-64 0C462.08 704 448 718.08 448 736 448 753.92 462.08 768 480 768zM832 192l-128 0L704 160C704 142.08 689.92 128 672 128 654.08 128 640 142.08 640 160L640 192 384 192 384 160C384 142.08 369.92 128 352 128S320 142.08 320 160L320 192 192 192C121.6 192 64 249.6 64 320l0 512c0 70.4 57.6 128 128 128l640 0c70.4 0 128-57.6 128-128L960 320C960 249.6 902.4 192 832 192zM896 832c0 35.2-28.8 64-64 64L192 896c-35.2 0-64-28.8-64-64L128 448l768 0L896 832zM896 384 128 384 128 320c0-35.2 28.8-64 64-64l128 0 0 32C320 305.92 334.08 320 352 320S384 305.92 384 288L384 256l256 0 0 32C640 305.92 654.08 320 672 320 689.92 320 704 305.92 704 288L704 256l128 0c35.2 0 64 28.8 64 64L896 384zM288 768l64 0C369.92 768 384 753.92 384 736 384 718.08 369.92 704 352 704l-64 0C270.08 704 256 718.08 256 736 256 753.92 270.08 768 288 768zM672 640l64 0c17.92 0 32-14.08 32-32S753.92 576 736 576l-64 0C654.08 576 640 590.08 640 608S654.08 640 672 640zM288 640l64 0C369.92 640 384 625.92 384 608S369.92 576 352 576l-64 0C270.08 576 256 590.08 256 608S270.08 640 288 640zM672 768l64 0c17.92 0 32-14.08 32-32 0-17.92-14.08-32-32-32l-64 0c-17.92 0-32 14.08-32 32C640 753.92 654.08 768 672 768z"
                            p-id="13638" fill="#ffffff"></path>
                    </svg><?php echo $p_Date ?></p>
                <p class="Ftext"><svg t="1728278460028" class="icon" viewBox="0 0 1024 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="4279" width="100" height="100">
                        <path
                            d="M956.96497373 783.22135977c-3.28732207 17.50931455-14.60263008 28.85922598-28.7208123 28.85922598h-2.35303067c-27.68271065 0.06920684-50.10570703 22.49220234-50.17491299 50.17491386 0.3806376 6.57464414 1.90318623 13.04547715 4.42923311 19.10107002 6.74766035 15.70993857 1.59175547 33.94592402-12.35340967 43.80789024l-1.38413496 0.9342914-58.23750322 32.04273779a37.37165977 37.37165977 0 0 1-15.9521625 3.66795967c-10.93467041 0-21.73092803-4.60225107-28.58239863-12.24960029-7.50893555-8.13179619-27.37128076-24.94904326-41.35104932-24.94904238-13.66833867 0-33.87671719 16.81724707-40.93580919 24.49919883a39.34405283 39.34405283 0 0 1-43.1850287 8.96227733l-57.58003828-32.00813437c-15.15628388-10.51943028-20.45060244-29.41288066-13.56452842-44.77678506 1.21111875-2.83747763 4.39463057-11.83435927 4.39463056-18.96265635 0-27.64810722-22.5960126-50.17491387-50.2441207-50.17491386h-1.97239306c-14.46421641 0-25.71031758-11.38451484-29.06684649-28.92843281-0.51905039-2.66446055-4.94828438-26.99064316-4.94828437-46.5069542 0-19.58551787 4.42923398-43.98090732 4.94828437-46.57616104 3.35652891-17.50931455 14.60263008-28.92843281 28.7208123-28.92843281h2.35303067c27.68271065 0 50.17491387-22.38839209 50.24412071-50.07110362a56.40352383 56.40352383 0 0 0-4.46383741-19.17027685 36.26435127 36.26435127 0 0 1 12.4226165-43.84249365l1.38413584-0.83048116 59.69084502-32.80401211 1.14191192-0.44984443a39.69008701 39.69008701 0 0 1 42.80439111 8.92767393c7.26671162 7.6127458 26.81762607 23.49570059 40.34755195 23.49570146 13.32230449 0 32.76940869-15.53692148 40.03612031-23.11506386 11.31530801-11.17689434 28.20176192-14.53342325 42.90820137-8.54703721l58.61814082 32.42337539c15.22549073 10.51943028 20.5544127 29.4820875 13.63373526 44.8459919a59.06798438 59.06798438 0 0 0-4.32542373 18.99725976c0.06920684 27.64810722 22.49220234 50.0365002 50.17491299 50.07110362h2.00699648c14.42961299 0 25.71031758 11.38451484 29.06684649 28.96303623 0.48444785 2.62985713 4.94828438 26.95603974 4.94828524 46.57616102 0 20.58901612-4.94828438 46.33393711-4.87907841 46.54155763z m-43.70407998-77.30396983a95.74757666 95.74757666 0 0 1-83.29035586-94.88249209c0.17301709-9.89656875 1.90318623-19.65472471 5.12130146-28.99763965l-41.48946299-23.04585615a170.87153291 170.87153291 0 0 1-17.12867695 13.84135488c-18.1321752 12.73404727-35.43387011 19.17027685-51.6974625 19.17027774-16.50581631 0-34.01513086-6.43623047-52.21651376-19.51631103a149.83267237 149.83267237 0 0 1-17.16328038-14.22199336l-43.39265009 23.84173476c2.4222375 7.19750479 5.15590488 17.82074531 5.15590488 28.96303623a95.85138604 95.85138604 0 0 1-83.35956269 94.95169893 226.82521231 226.82521231 0 0 0-2.87208106 30.65860195c0.27682734 10.24260292 1.21111875 20.45060244 2.87208106 30.58939512a95.71297324 95.71297324 0 0 1 83.35956269 94.95169892c0 11.07308408-2.83747763 21.76553144-5.19050831 28.99763965l40.13993145 22.42299551c5.43273223-5.1905083 11.14229092-10.03498242 17.16328037-14.49881982 18.40900253-13.42611474 36.29895469-20.34679219 52.97778809-20.34679219 16.92105732 0 34.94942227 7.05909112 53.53144189 20.79663662 6.05559287 4.53304424 11.83435927 9.48132862 17.26709151 14.77564628l42.00851338-23.1496664a95.02090489 95.02090489 0 0 1-5.08669805-28.96303623 95.67836982 95.67836982 0 0 1 83.29035586-94.95169893c1.17651533-7.92417568 2.87208106-20.72742979 2.87208193-30.69320537 0-10.00037901-1.69556573-22.73442627-2.87208193-30.69320537z m-190.73387724 118.9664499a88.75769151 88.75769151 0 0 1-88.61927872-88.58467529 88.65388125 88.65388125 0 0 1 177.23855655 0 88.75769151 88.75769151 0 0 1-88.61927783 88.58467529z m0-129.76270752c-23.04585703 0-42.00851338 18.20138203-42.97740909 41.21263565a42.9774082 42.9774082 0 0 0 85.92021387 0 43.01201162 43.01201162 0 0 0-42.94280478-41.21263565z m-128.58619219-225.61409355l-14.42961299 11.21149776c-29.37827724 26.12555859-85.43576602 34.84561201-122.4959959 37.71769394l-4.74066387-0.10381026a344.64975029 344.64975029 0 0 0-31.21225664 1.48794522h-0.44984443l0.06920683 0.06920684c-167.65341768 15.81374883-299.28470801 156.30350625-299.284708 326.93281611v50.14030957h367.38417656a298.76565674 298.76565674 0 0 0 35.81450771 58.61814082H91.77644902A29.4820875 29.4820875 0 0 1 62.15594785 926.30637178v-79.4839834c0-159.10638047 96.47424756-300.18439599 245.85707637-359.42539746l16.92105732-6.6784544-14.36040615-11.21149775c-56.12669648-43.77328682-88.23864112-109.06988115-88.23864112-179.31475986 0-125.74871455 103.11809766-228.03633106 229.87031046-228.03633106 126.78681622 0 229.90491386 102.28761651 229.9741207 228.03633106 0 70.24487871-32.2503583 135.54147305-88.23864111 179.31475986zM452.23994815 120.80869209c-94.08661347 0-170.62930898 76.02364511-170.62930899 169.38358682 0 93.42914941 76.5426955 169.45279365 170.62930899 169.45279453 94.1212169 0 170.6639124-75.9890417 170.6639124-169.45279453 0-93.42914941-76.5426955-169.38358682-170.6639124-169.38358682z"
                            p-id="4280" fill="#ffffff"></path>
                    </svg> <?php echo $p_Author ?></p>
                <p class="Ftext"> <svg t="1728394655213" class="icon" viewBox="0 0 1024 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="15952" width="100" height="100">
                        <path
                            d="M668.1 227.3H207.8C93.2 227.3 0 320.6 0 435.1v296.4c0 114.6 93.2 207.8 207.8 207.8H668c114.6 0 207.8-93.2 207.8-207.8V435.1c0.1-114.5-93.1-207.8-207.7-207.8z m139.8 504.2c0 77.1-62.7 139.8-139.8 139.8H207.8c-57.3 0-106.7-34.7-128.3-84.2l217.8-217.8 134.2 134.2c13.3 13.3 34.8 13.3 48.1 0l88.2-88.2 96.6 96.6c13.3 13.3 34.8 13.3 48.1 0 13.3-13.3 13.3-34.8 0-48.1L592 543.2c-13.3-13.3-34.8-13.3-48.1 0l-88.2 88.2-134.3-134.2c-13.3-13.3-34.8-13.3-48.1 0L68 702.6V435.1c0-77.1 62.7-139.8 139.8-139.8H668c77.1 0 139.8 62.7 139.8 139.8v296.4z"
                            fill="#ffffff" p-id="15953"></path>
                        <path
                            d="M627.440143 485.154298a53.1 53.1 0 1 0 75.093429-75.096051 53.1 53.1 0 1 0-75.093429 75.096051Z"
                            fill="#ffffff" p-id="15954"></path>
                        <path
                            d="M675.1 84.6h-288c-18.8 0-34 15.2-34 34s15.2 34 34 34h288c154.9 0 280.9 126 280.9 280.9v149.8c0 18.8 15.2 34 34 34s34-15.2 34-34V433.6c0-192.4-156.5-349-348.9-349z"
                            fill="#ffffff" p-id="15955"></path>
                    </svg>已上传<?php echo $PhotoListCount ?>张</p>
            </div>

        </div>
        <!-- 主容器 -->
        <div class="gallery mar_top2">
            <?php foreach ($listarr as $key => $imglist_s) { ?>
                <div class="photo_content">
                    <?php if ($imglist_s['photo_type']) { ?>
                        <div class="video-player" data-url="<?php echo $imglist_s['photo_url'] ?>"
                            data-cover="<?php echo $imglist_s['VideoCover'] ?>">
                            <span>
                                <svg t="1723714879421" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" p-id="2577" width="200" height="200">
                                    <path
                                        d="M512 0C230.4 0 0 230.4 0 512s230.4 512 512 512 512-230.4 512-512S793.6 0 512 0z m0 981.333333C253.866667 981.333333 42.666667 770.133333 42.666667 512S253.866667 42.666667 512 42.666667s469.333333 211.2 469.333333 469.333333-211.2 469.333333-469.333333 469.333333z"
                                        fill="#ffffff" p-id="2578"></path>
                                    <path
                                        d="M672 441.6l-170.666667-113.066667c-57.6-38.4-106.666667-12.8-106.666666 57.6v256c0 70.4 46.933333 96 106.666666 57.6l170.666667-113.066666c57.6-42.666667 57.6-106.666667 0-145.066667z"
                                        fill="#ffffff" p-id="2579"></path>
                                </svg>
                            </span>
                        </div>

                    <?php } else { ?>
                        <img class="lazy photo_style" data-src="<?php echo $imglist_s['photo_url'] ?>">
                    <?php } ?>

                    <div class="photo_info">
                        <div class="photo_le">
                            <span class="by_img" style="background-image: url(
                            <?php if ($imglist_s['photo_byname'] == $boy) {
                                echo DetermineAvatarAddress(1);
                            } elseif ($imglist_s['photo_byname'] == $girl) {
                                echo DetermineAvatarAddress(0);
                            } else {
                                echo $anonymousList[array_rand($anonymousList)];
                            }
                            ?>);background-size: cover;"></span>

                            <span class="by_name"><b>By：</b>
                                <?php echo $imglist_s['photo_byname'] ?>
                            </span>
                        </div>
                        <div class="photo_text">
                            <?php echo $imglist_s['photo_text'] ?>
                        </div>
                        <div class="photo_rg">
                            <span class="img_date">
                                <span class="iconfont">&#xe60f;</span>
                                <?php echo $imglist_s['photo_date'] ?>
                            </span>
                        </div>
                    </div>

                </div>

            <?php } ?>
        </div>

        <div id="videoModal" class="modal">
            <div class="modal-content">
                <button class="close-button">
                    <svg t="1682818912164" class="icon" viewBox="0 0 1024 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="2416" width="200" height="200">
                        <path
                            d="M550.848 502.496l308.64-308.896a31.968 31.968 0 1 0-45.248-45.248l-308.608 308.896-308.64-308.928a31.968 31.968 0 1 0-45.248 45.248l308.64 308.896-308.64 308.896a31.968 31.968 0 1 0 45.248 45.248l308.64-308.896 308.608 308.896a31.968 31.968 0 1 0 45.248-45.248l-308.64-308.864z"
                            p-id="2417"></path>
                    </svg>
                </button>
                <div id="dplayer"></div>
            </div>
        </div>

        <style>
            @media screen and (max-width: 960px) {
                .gallery {
                    column-count: 3;
                    column-gap: 10px;
                    padding: 0rem 1rem;
                }
            }

            @media screen and (max-width: 768px) {
                .gallery {
                    column-count: 2;
                    column-gap: 10px;
                    padding: 0rem 1rem;
                }
                #LGNewUI-Tab-Page-Wrap{
                    display: none;
                }
            }
            
            
            
            .gallery img{
                border-radius: 1rem;
                will-change: transform;
            }
            
            .gallery img:hover{
                clip-path: inset(0 0 0 0 round 1rem);
            }
                
            
            .photo_content{
                height: auto;
            }
            
            .photo_content:hover .photo_info {
                transform: translateY(0);
                background: linear-gradient(to top, #795548, transparent);
            }
            

            .video-player {
                min-height: 360px;
                max-height: 500px;
                cursor: pointer;
                background-color: #000;
                position: relative;
                background-size: cover;
            }

            .modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(255, 255, 255, 0.602);
                z-index: 1000;
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                transition: all .5s;
                z-index: 99999;
            }

            .modal-content {
                width: 95%;
                max-width: 1000px;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                box-shadow: 0 0 20px rgb(147 147 147 / 53%);
                border-radius: .5rem;
                overflow: hidden;
            }

            .close-button {
                position: absolute;
                top: 10px;
                right: 10px;
                color: #fff;
                background: none;
                border: none;
                font-size: 20px;
                cursor: pointer;
                z-index: 100;
                background: #ffffff96;
                border-radius: 1rem;
                padding: .5rem;
                backdrop-filter: blur(18px);
                -webkit-backdrop-filter: blur(18px);
                transition: .2s all;
            }

            .close-button:hover {
                background: #78787869;
            }


            .video-player span {
                background: #ffffff36;
                display: inline-block;
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                padding: .8rem 1rem;
                border-radius: 1rem;
                transition: all .3s;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 10;
            }

            .video-player:hover span {
                background: #6868686b;
            }

            .dplayer-notice-list {
                width: 100%;
            }

            .dplayer-notice-list .dplayer-notice {
                background: rgb(28 28 28 / 57%) !important;
                backdrop-filter: saturate(180%) blur(20px) !important;
                border-radius: 5px !important;
            }

            .video-player span svg {
                width: 3rem !important;
                height: 3rem !important;
            }

            .dplayer-controller .dplayer-bar-wrap .dplayer-bar .dplayer-played {
                position: absolute;
                left: 1px !important;
                top: 1px !important;
                height: 6px !important;
                border-radius: 1rem !important;
                max-width: calc(100% - 1px);
            }

            .dplayer-controller .dplayer-bar-wrap .dplayer-bar {
                height: 8px !important;
                border-radius: 1rem !important;
            }

            .dplayer-controller .dplayer-bar-wrap .dplayer-bar .dplayer-loaded {
                left: 1px !important;
                top: 1px !important;
                height: 6px !important;
                border-radius: 1rem !important;
            }

            .dplayer-controller .dplayer-bar-wrap .dplayer-bar .dplayer-played .dplayer-thumb {
                border: 2px solid #fff;
            }

            .dplayer-controller .dplayer-bar-wrap {
                bottom: 40px !important;
            }
        </style>


        <script src="Style/dplayer/DPlayer.min.js"></script>
        <script>
            const videoContainers = document.querySelectorAll('.video-player');
            const videoModal = document.getElementById('videoModal');
            const dplayerContainer = document.getElementById('dplayer');
            const modalContent = document.querySelector('.modal-content');
            let vidoeCover;
            let dp;

            videoContainers.forEach((container) => {
                vidoeCover = container.getAttribute('data-cover');
                container.style.background = `url(${vidoeCover}) no-repeat center`
                container.style.backgroundSize = "cover"

                container.addEventListener('click', function (event) {
                    event.preventDefault();

                    if (dp) {
                        dp.destroy();
                    }
                    dp = new DPlayer({
                        autoplay: true,
                        theme: "#6e8bff",
                        container: dplayerContainer,
                        video: {
                            url: container.getAttribute('data-url'),
                        },
                    });

                    dp.on('loadedmetadata', () => {
                        const videoWidth = dp.video.videoWidth;
                        const videoHeight = dp.video.videoHeight;

                        if (videoWidth > videoHeight) {
                            modalContent.style.height = 'auto';
                            modalContent.style.maxWidth = '1000px';
                        } else {
                            modalContent.style.maxWidth = '460px';
                            modalContent.style.height = 'auto';
                        }
                    });

                    videoModal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            });

            document.querySelector('.close-button').addEventListener('click', function () {
                videoModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                dp.pause();
            });
        </script>

    </div>
</body>

</html>

<?php
include_once "footer.php";
?>