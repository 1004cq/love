<!DOCTYPE html>
<html>

<head>
    <?php
    include_once 'head.php';

    $love_day = "select * from loveday order by day_Weights desc";
    $result_day = mysqli_query($connect, $love_day);
    $LoveDay_rows = mysqli_num_rows($result_day);

    if (is_numeric($diy['index_essay_num'])) {
        $index_essay_num = $diy['index_essay_num'];
    }
    if (is_numeric($diy['index_leav_num'])) {
        $index_leav_num = $diy['index_leav_num'];
    }

    $leav = "select QQ from leaving where switch = 1 order by time desc limit $index_leav_num";
    $leav_qq = mysqli_query($connect, $leav);
    $leav_rows = mysqli_num_rows($leav_qq);

    $article = "select * from article where encryptionSwitch = 0 order by id desc limit $index_essay_num";
    $resarticle = mysqli_query($connect, $article);
    $article_rows = mysqli_num_rows($resarticle);


    $articles_list = [];
    while ($article_data = $resarticle->fetch_assoc()) {
        $articles_list[] = $article_data;
    }
    $sortedArticles = sortArticlesByTime($articles_list);


    include_once 'admin/Database.php';
    $liuyan = "SELECT * FROM leaving where switch = 1 order by time desc limit ?";
    $stmt = $conn->prepare($liuyan);
    $stmt->bind_param("i", $jiequ);
    $jiequ = $index_leav_num;
    $stmt->bind_result($id, $name, $qq, $text, $time, $ip, $city, $switch, $OS_info, $BR_info);
    $result = $stmt->execute();
    ?>

    <title>
        <?php echo $text['title'] ?> — <?php echo $text['writing'] ?>
    </title>
    <meta name="description" content="<?php echo $seo_switch ? $site_description : '' ?>">
    <meta name="keywords" content="<?php echo $seo_switch ? $global_keywords : '' ?>">
    <meta name="author" content="Ki">
    <meta name="love-theme" content="LGNewUI-情侣小站">
    <meta name="copyright" content="2024 LG_NewUi Web All Rights Reserved">
    <!-- <link rel="stylesheet" href="Style/css/index.css"> -->
</head>

<body>
    <div id="pjax-container">
        <link rel="stylesheet" href="Style/css/home.css?=v<?php echo $versionCode ?>">

        <div class="Width_limit_10rem">
            <div class="central" style="margin: 0 auto;" <?php echo $Animation == "1" ? 'aos="flip-up"' : '' ?>>
                <div class="nav_url shadow-blur">
                    <span class="iconfont icon_sm">&#xe53e;</span>
                    <?php echo $text['writing'] ?>
                </div>
            </div>

            <div class="central">
                <div class="ltps shadow-blur">
                    <span class="hen"></span>
                    Love Day
                </div>
            </div>

            <div class="ios_page central maxW95" <?php if ($text['Animation'] == "1") { ?>aos="fade-down"
                aos-easing="linear"
                aos-duration="1000" <?php } ?>>
                <div class="time">
                    <span id="span_dt_dt"><?php echo $text['loveDayTitle'] ?></span>
                    <b id="tian"></b>天
                    <b id="shi"></b>时
                    <b id="fen"></b>分
                    <b id="miao"></b>秒
                </div>
            </div>

            <?php if ($LoveDay_rows > 0): ?>
                <div class="row central">
                    <?php
                    while ($info_day = mysqli_fetch_array($result_day)) {
                        if ($info_day['isDateHidden']) {
                            if (isPastDate($info_day['day'])) {
                                continue;
                            }
                        }
                    ?>
                        <div class="time_card col-lg-6 col-md-6 col-sm-12 col-sm-x-12 shadow-blur" <?php if ($Animation == "1") { ?>aos="flip-up" aos-duration="500" <?php } ?>>
                            <div class="day_cont">
                                <div class="left_info" data-title="<?php echo $info_day['day_title'] ?>">
                                    <?php if ($info_day['day_type']): ?>
                                        <svg t="1715052082901" style="margin-right:0.5rem!important;" class="icon" viewBox="0 0 1024 1024"
                                            version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4508" width="100" height="100">
                                            <path
                                                d="M470.4 204.8l44.8 44.8 44.8-44.8c99.2-99.2 262.4-99.2 361.6 0 48 48 73.6 112 73.6 179.2 0 19.2-12.8 32-32 32s-32-12.8-32-32c0-51.2-19.2-99.2-57.6-134.4-73.6-73.6-195.2-73.6-272 0l-67.2 67.2c-12.8 12.8-32 12.8-44.8 0l-67.2-67.2c-73.6-73.6-195.2-73.6-272 0-73.6 73.6-73.6 195.2 0 272L512 883.2c12.8 12.8 12.8 32 0 44.8s-32 12.8-44.8 0L105.6 566.4c-99.2-99.2-99.2-262.4 0-361.6 102.4-102.4 262.4-102.4 364.8 0z m176 710.4L425.6 694.4c-57.6-57.6-57.6-147.2 0-204.8 57.6-57.6 147.2-57.6 204.8 0l57.6 57.6 57.6-57.6c57.6-57.6 147.2-57.6 204.8 0 57.6 57.6 57.6 147.2 0 204.8L729.6 915.2c-9.6 9.6-25.6 16-38.4 16-19.2 0-32-6.4-44.8-16z m256-265.6c32-32 32-83.2 0-112-32-32-83.2-32-112 0l-80 80c-12.8 12.8-32 12.8-44.8 0l-80-80c-32-32-83.2-32-112 0-32 32-32 83.2 0 112L688 864l214.4-214.4z"
                                                p-id="4509" fill="#ffffff"></path>
                                        </svg>
                                    <?php else: ?>
                                        <svg t="1715052607396" style="margin-right:0.5rem!important;" class="icon" viewBox="0 0 1024 1024"
                                            version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="9366" width="100" height="100">
                                            <path
                                                d="M810 249.5c-38.6-38.6-83.5-68.8-133.5-90-51.8-21.9-106.8-33-163.5-33s-111.7 11.1-163.5 33c-50 21.2-94.9 51.4-133.5 90-38.6 38.6-68.8 83.5-90 133.5-21.9 51.8-33 106.8-33 163.5s11.1 111.7 33 163.5c21.2 50 51.4 94.9 90 133.5s83.5 68.8 133.5 90c51.8 21.9 106.8 33 163.5 33s111.7-11.1 163.5-33c50-21.2 94.9-51.4 133.5-90S878.8 760 900 710c21.9-51.8 33-106.8 33-163.5S921.9 434.8 900 383c-21.2-50-51.5-94.9-90-133.5z m-297 657c-198.5 0-360-161.5-360-360s161.5-360 360-360 360 161.5 360 360-161.5 360-360 360zM357 96.5c-42.3-49.6-141-53.3-208.1 4s-77.3 153.9-35 203.5L357 96.5zM877.2 100.5C810 43.2 711.3 47 669 96.5L912.2 304c42.3-49.6 32.1-146.2-35-203.5z"
                                                p-id="9367" fill="#ffffff"></path>
                                            <path
                                                d="M667.1 558.6H543V351c0-17.9-14.5-32.4-32.4-32.4-15.2 0-27.6 12.3-27.6 27.6v272.4h182.2c17.1 0 30.9-13.8 30.9-30.9 0-16.1-13-29.1-29-29.1z"
                                                p-id="9368" fill="#ffffff"></path>
                                        </svg>
                                    <?php endif; ?>
                                    <span class="day_title"><?php echo $info_day['day_title'] ?></span>
                                </div>
                                <div class="time_m">
                                    <?php if ($info_day['day_type']) { ?>
                                        <b class="source"><?php echo $info_day['day'] ?></b>
                                        <i class="format">过了<?php echo day_down($info_day['day'], date("Y-m-d"), false)[0] ?>天</i>
                                    <?php } else { ?>
                                        <b class="source"><?php echo add_one_year($info_day['day']) ?></b>
                                        <i class="format">还有<?php echo day_down(date("Y-m-d"), $info_day['day'], true)[0] ?>天</i>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>

            <?php endif ?>

            <?php if ($leav_rows > 0): ?>
                <div class="central">
                    <div class="ltps shadow-blur">
                        <span class="hen"></span>
                        最新留言
                    </div>
                </div>
                <div class="ios_page central maxW95">
                    <div class="pad_1rem padT_2rem">
                        <div class="package">
                            <?php while ($leava = mysqli_fetch_array($leav_qq)) {

                                if ($leava['QQ'] == "anon") : ?>
                                    <span class="aiv" style="background-image:url(<?php echo $anonymousList[array_rand($anonymousList)] ?>);" <?php if ($text['Animation'] == "1") {
                                                                                                                                                    echo "aos='fade-left'";
                                                                                                                                                } ?>></span>
                                <?php elseif ($leava['QQ'] <> "anon") : ?>
                                    <span class="aiv" style="background-image:url(https://q1.qlogo.cn/g?b=qq&nk=<?php echo $leava['QQ'] ?>&s=640);" <?php if ($text['Animation'] == "1") {
                                                                                                                                                        echo "aos='fade-left'";
                                                                                                                                                    } ?>></span>
                            <?php endif;
                            } ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>


            <?php
            while ($stmt->fetch()) {
                $lyarr[] = array(
                    "id" => $id,
                    "qq" => $qq,
                    "name" => $name,
                    "time" => $time,
                    "text" => $text,
                    "city" => $city,
                );
            }
            ?>

            <?php if ($leav_rows > 0): ?>
                <div class="ios_page sm-x-block">
                    <div class="pad_1rem mar_1rem">
                        <?php
                        foreach ($lyarr as $key => $leav_s) {
                        ?>
                            <div class="leav_list" <?php if ($Animation == "1") { ?> aos="fade-up" <?php } ?>>
                                <div class="headPortrait">
                                    <?php if ($leav_s['qq'] == "anon") : ?>
                                        <span class="qqavi"
                                            style="background-image: url(<?php echo $anonymousList[array_rand($anonymousList)] ?>);"></span>
                                    <?php endif; ?>

                                    <?php if ($leav_s['qq'] <> "anon") : ?>
                                        <span class="qqavi"
                                            style="background-image: url(https://q1.qlogo.cn/g?b=qq&nk=<?php echo $leav_s['qq'] ?>&s=640);"></span>
                                    <?php endif; ?>

                                    <div class="list_info">
                                        <div class="MsgHead">
                                            <b class="name"><?php echo $leav_s['name'] ?></b>
                                            <span
                                                style="font-size: 1rem;color: #8799a3;background: #e7ebed;padding: 0.3rem 0.5rem;border-radius: 0.4rem;font-weight: 400"><?php echo $leav_s['city'] ? $leav_s['city'] : '中国' ?></span>
                                        </div>
                                        <p class="content"><?php echo parseEmojis($leav_s['text']) ?></p>
                                    </div>
                                </div>
                                <div class="list_time">
                                    <span><?php echo time_tran($leav_s['time']) ?></span>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>


            <div class="row lg_card_block central">
                <?php
                foreach ($lyarr as $key => $leav_s) {
                ?>
                    <div class="leav_card lg_leav col-lg-4 col-md-6 col-sm-6" <?php if ($Animation == "1") { ?> aos="fade-up" <?php } ?>>
                        <div class="headPortrait">
                            <?php if ($leav_s['qq'] == "anon") : ?>
                                <span class="qqavi"
                                    style="background-image: url(<?php echo $anonymousList[array_rand($anonymousList)] ?>);"></span>
                            <?php endif; ?>
                            <?php if ($leav_s['qq'] <> "anon") : ?>
                                <span class="qqavi"
                                    style="background-image: url(https://q1.qlogo.cn/g?b=qq&nk=<?php echo $leav_s['qq'] ?>&s=640);"></span>
                            <?php endif; ?>
                        </div>
                        <b class="name"><?php echo $leav_s['name'] ?></b>
                        <div class="list_info">
                            <p class="content"><?php echo parseEmojis($leav_s['text']) ?></p>
                        </div>
                        <div class="footer_info">
                            <span style="font-size: 1rem;color: #8799a3;background: #e7ebed;padding: 0.3rem 0.5rem;border-radius: 0.4rem;font-weight: 400;margin:1rem 0;display:block;"><?php echo $leav_s['city'] ? $leav_s['city'] : '中国' ?></span>

                            <span class="list_time"><?php echo time_tran($leav_s['time']) ?></span>
                        </div>
                        <a href="leaving.php#comment_<?php echo $leav_s['id'] ?>" class="leva_icon_svg">
                            <svg t="1682322548507" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" p-id="3422" width="200" height="200">
                                <path d="M941.34 466.656a63.508 63.508 0 0 0-2.914-2.719L685.134 210.656c-24.882-24.881-65.599-24.881-90.482 0-24.885 24.883-24.885 65.602 0 90.485l146.861 146.855H127.996c-35.2 0-64 28.8-64 64s28.8 64 64 64h613.517l-146.86 146.855c-24.885 24.883-24.885 65.602 0 90.485 24.882 24.881 65.599 24.881 90.482 0l253.292-253.281a64.641 64.641 0 0 0 2.914-2.719c12.467-12.466 18.686-28.907 18.661-45.34 0.024-16.433-6.195-32.873-18.662-45.34z"
                                    p-id="3423"></path>
                            </svg>
                        </a>
                    </div>
                <?php
                }
                ?>

            </div>

            <?php if ($article_rows > 0): ?>
                <div class="central">
                    <div class="ltps shadow-blur">
                        <span class="hen"></span>
                        最新动态
                    </div>
                </div>
            <?php
            endif;
            foreach ($sortedArticles as $info) {
            ?>
                <div class="ios_page article_card central maxW95" <?php if ($Animation == "1") { ?> aos="fade-up" <?php } ?>>
                    <a href="page.php?id=<?php echo $info['id'] ?>" class="article_icon_svg">
                        <svg t="1682322548507" class="icon" viewBox="0 0 1024 1024" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" p-id="3422" width="200" height="200">
                            <path d="M941.34 466.656a63.508 63.508 0 0 0-2.914-2.719L685.134 210.656c-24.882-24.881-65.599-24.881-90.482 0-24.885 24.883-24.885 65.602 0 90.485l146.861 146.855H127.996c-35.2 0-64 28.8-64 64s28.8 64 64 64h613.517l-146.86 146.855c-24.885 24.883-24.885 65.602 0 90.485 24.882 24.881 65.599 24.881 90.482 0l253.292-253.281a64.641 64.641 0 0 0 2.914-2.719c12.467-12.466 18.686-28.907 18.661-45.34 0.024-16.433-6.195-32.873-18.662-45.34z"
                                p-id="3423"></path>
                        </svg>
                    </a>
                    <div class="pad_1rem mar_1rem">
                        <div class="article_list">
                            <div class="flex_a">
                                <div class="article_list_head">
                                    <span class="aiv" style="background-image: url(<?php echo DetermineAvatarAddress($info['articlename'] == $boy ? 1 : 0); ?>)"></span>
                                </div>
                                <div class="article_list_info">
                                    <span class="name_a"><?php echo $info['articlename'] ?><span
                                            style="font-size: 1rem;font-weight: 400;color: #058bf3;background: #ffffff;padding: 0.3rem 0.6rem;border-radius: 1rem;margin: 0 0.5rem;display: inline-block;"><span
                                                class="iconfont icon-diqu"
                                                style="font-size: 1rem;color: #058bf3"></span><?php if ($info['ip_city']) {
                                                                                                    echo $info['ip_city'];
                                                                                                } else {
                                                                                                    echo "未知";
                                                                                                } ?></span>
                                    </span>
                                    <i class="time_a"><?php if (is_numeric($info['articletime'])) {
                                                            echo date('Y-m-d H:i', $info['articletime']);
                                                        } else {
                                                            echo $info['articletime'];
                                                        } ?></i></span>
                                </div>
                            </div>
                            <div class="title_a">
                                <?php echo $info['articletitle'] ?>
                            </div>
                            <div class="content_a">
                                <p><?php echo strip_tags($info['articletext']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>
    </div>

    <?php
    include_once 'footer.php';
    ?>

</body>

</html>