<?php
include_once 'head.php';
$article = "select * from article order by id desc";
$resarticle = mysqli_query($connect, $article);
$data_row = mysqli_num_rows($resarticle);
$articles_list = [];
while ($article_data = $resarticle->fetch_assoc()) {
    $articles_list[] = $article_data;
}
$sortedArticles = sortArticlesByTime($articles_list);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>
        <?php echo $text['title'] ?> —
        <?php echo $text['card1'] ?>
    </title>
    <meta name="description" content="<?php echo $seo_switch ? $site_description : '' ?>">
    <meta name="keywords" content="<?php echo $seo_switch ? $global_keywords : '' ?>">
    <meta name="author" content="Ki">
    <meta name="love-theme" content="LGNewUI-情侣小站">
    <meta name="copyright" content="2024 LG_NewUi Web All Rights Reserved">
</head>

<body>

    <div id="pjax-container">
        <div class="Width_limit_10rem">
            <div class="central mar_t0">
                <div style="margin: 0 auto;" <?php if ($Animation == "1") { ?>aos="flip-up" <?php } ?>>
                    <div class="nav_url shadow-blur">
                        <span class="iconfont icon_sm">&#xe53e;</span>
                        <?php echo $text['deci1'] ?>
                    </div>
                </div>


                <div class="central">
                    <div class="ltps shadow-blur">
                        <span class="hen"></span>
                        <span data-i18n="article_list">文章列表</span>
                    </div>
                </div>
                <?php if ($data_row <= 0) {
                    include_once "PageNulldata.php";
                } ?>
                <?php
                foreach ($sortedArticles as $info) {
                    ?>

                    <div class="ios_page article" <?php if ($Animation == "1") { ?> aos="fade-up" <?php } ?>>
                        <div class="pad_1rem mar_1rem">
                            <div class="article_list">
                                <div class="flex_a">
                                    <div class="article_list_head">
                                        <span class="aiv"
                                            style="background-image: url(<?php echo DetermineAvatarAddress($info['articlename'] == $boy ? 1 : 0); ?>)"></span>
                                    </div>
                                    <div class="article_list_info">
                                        <span class="name_a">
                                            <?php if ($info['encryptionSwitch']) {
                                                echo "*当前内容已加密*";
                                            } else {
                                                echo $info['articletitle'];
                                            } ?>
                                            <i class="time_a">
                                                <?php echo $info['articlename'] ?> —记录于
                                                <?php if (is_numeric($info['articletime'])) {
                                                    echo date('Y-m-d H:i', $info['articletime']);
                                                } else {
                                                    echo $info['articletime'];
                                                } ?>
                                            </i>
                                        </span>
                                    </div>
                                </div>
                                <div class="title_a">

                                </div>
                                <?php if ($info['encryptionSwitch']): ?>
                                    <div class="content_a Enceypt">
                                        <div class="EnceyptIcon">
                                            <svg t="1679478949384" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg" p-id="4370" width="80" height="80">
                                                <path
                                                    d="M785.2 1024h-580c-71.1 0-128.9-34.8-128.9-110V505.6c0-75.1 57.8-136.1 128.9-136.1h33.4c-3.4-17-5.3-34.5-5.3-52.4v-52.4C233.4 120.2 350.6 3 495.2 3S757 120.2 757 264.8v52.4c0 17.9-1.8 35.4-5.3 52.4h33.4c71.1 0 128.9 61.1 128.9 136.1V914c0.1 75.2-57.7 110-128.8 110zM442.9 721.8v119c0 28.8 23.5 52.4 52.4 52.4s52.4-23.5 52.4-52.4v-119c31.8-18 53.4-51.5 53.4-90.4 0-57.9-47.4-104.7-105.7-104.7-58.5 0-105.8 46.8-105.8 104.7-0.2 38.8 21.5 72.4 53.3 90.4z m209.4-457c0-86.8-70.3-157.1-157.1-157.1S338.1 178 338.1 264.8v104.7h314.2V264.8z"
                                                    fill="#515151" p-id="4371"></path>
                                            </svg>
                                        </div>
                                        <span class="EnceyptInfo">
                                            当前内容已被管理员加密
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <div class="content_a">
                                        <p>
                                            <?php echo strip_tags($info['articletext']); ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                <div class="see">
                                    <a href="page.php?id=<?php echo $info['id'] ?>"><span class="see_btn shadow-blur">
                                            查看详情
                                            <svg t="1682322548507" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg" p-id="3422" width="200" height="200">
                                                <path
                                                    d="M941.34 466.656a63.508 63.508 0 0 0-2.914-2.719L685.134 210.656c-24.882-24.881-65.599-24.881-90.482 0-24.885 24.883-24.885 65.602 0 90.485l146.861 146.855H127.996c-35.2 0-64 28.8-64 64s28.8 64 64 64h613.517l-146.86 146.855c-24.885 24.883-24.885 65.602 0 90.485 24.882 24.881 65.599 24.881 90.482 0l253.292-253.281a64.641 64.641 0 0 0 2.914-2.719c12.467-12.466 18.686-28.907 18.661-45.34 0.024-16.433-6.195-32.873-18.662-45.34z"
                                                    p-id="3423"></path>
                                            </svg></span></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <?php
    include_once 'footer.php';
    ?>

</body>

</html>