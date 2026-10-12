<?php
include_once 'head.php';

$loveImg = "select * from loveImg order by imgDatd desc";
$resImg = mysqli_query($connect, $loveImg);

$loveImg_Photolist = "select * from Photolist order by photo_date desc";
$res_Photolist = mysqli_query($connect, $loveImg_Photolist);

while ($photo_list = mysqli_fetch_array($res_Photolist)) {
    $list_p[] = array(
        "id" => $photo_list['id'],
        "p_id" => $photo_list['photo_id'],
        "code" => $photo_list['photo_code'],
        "text" => $photo_list['photo_text'],
        "url" => $photo_list['photo_url'],
        "date" => $photo_list['photo_date'],
        "byname" => $photo_list['photo_byname'],
        "type" => $photo_list['photo_type'],
        'VideoCover' => $photo_list['VideoCover']
    );
}

$data_row = mysqli_num_rows($resImg);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>
        <?php echo $text['title'] ?> — <?php echo $text['card3'] ?>
    </title>
    <meta name="description" content="<?php echo $seo_switch ? $site_description : '' ?>">
    <meta name="keywords" content="<?php echo $seo_switch ? $global_keywords : '' ?>">
    <meta name="author" content="Ki">
    <meta name="love-theme" content="LGNewUI-情侣小站">
    <meta name="copyright" content="2024 LG_NewUi Web All Rights Reserved">
    <!-- <link rel="stylesheet" href="Style/css/loveImg.css"> -->
</head>

<body>
    <div id="pjax-container">
        <div class="Width_limit_10rem">
            <div class="central mar_t0" <?php if ($Animation == "1") { ?>aos="flip-up" <?php } ?>>
                <div class="nav_url shadow-blur">
                    <span class="iconfont icon_sm">&#xe53e;</span>
                    <?php echo $text['deci3'] ?>
                </div>
            </div>
            <?php if ($data_row <= 0) {
                include_once "PageNulldata.php";
            } ?>


            <div class="Photo row<?php echo ($data_row <= 0) ? ' PhotoHide':''?>">
                <?php
                while ($list = mysqli_fetch_array($resImg)) {
                    ?>
                    <div class="PhotoCard col-lg-6 col-md-6 col-sm-12 col-sm-x-12" <?php if ($Animation == "1") { ?>
                            aos="fade-up" <?php } ?>>
                        <div class="pad_1rem mar_1rem">
                            <div class="img_list" view-image>
                                <div class="flex_a po_rid" <?php if (!$diy['aiv_name_show']) { ?> style="display: none"
                                    <?php } ?>>
                                    <div class="img_list_head">
                                        <span class="aiv"
                                            style="background-image: url(<?php echo DetermineAvatarAddress($list['upload_name'] == $boy ? 1 : 0); ?>)"></span>
                                    </div>
                                    <div class="img_list_info">
                                        <span class="name_a">
                                            <?php echo $list['upload_name'] ?>
                                            <i class="time_a">
                                                <?php if ($list['encryptionSwitch']) {
                                                    echo "*当前内容已被加密*";
                                                } else {
                                                    echo $list['imgText'];
                                                } ?>
                                            </i>
                                        </span>
                                    </div>
                                </div>
                                <?php
                                if ($list['encryptionSwitch']) { ?>
                                    <div class="encryStyle">
                                        <a href="imglist.php?code=<?php echo $list['img_code'] ?>">
                                            <div>
                                                <svg t="1679478949384" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                                    xmlns="http://www.w3.org/2000/svg" p-id="4370" width="80" height="80">
                                                    <path
                                                        d="M785.2 1024h-580c-71.1 0-128.9-34.8-128.9-110V505.6c0-75.1 57.8-136.1 128.9-136.1h33.4c-3.4-17-5.3-34.5-5.3-52.4v-52.4C233.4 120.2 350.6 3 495.2 3S757 120.2 757 264.8v52.4c0 17.9-1.8 35.4-5.3 52.4h33.4c71.1 0 128.9 61.1 128.9 136.1V914c0.1 75.2-57.7 110-128.8 110zM442.9 721.8v119c0 28.8 23.5 52.4 52.4 52.4s52.4-23.5 52.4-52.4v-119c31.8-18 53.4-51.5 53.4-90.4 0-57.9-47.4-104.7-105.7-104.7-58.5 0-105.8 46.8-105.8 104.7-0.2 38.8 21.5 72.4 53.3 90.4z m209.4-457c0-86.8-70.3-157.1-157.1-157.1S338.1 178 338.1 264.8v104.7h314.2V264.8z"
                                                        p-id="4371"></path>
                                                </svg>
                                            </div>
                                            <span>相册已被管理员加密</span>

                                        </a>
                                    </div>
                                <?php } else {
                                    $img_num = 0; ?>
                                    <div class="loveimg row">

                                        <?php foreach ($list_p as $key => $imglist) {
                                            if ($imglist['p_id'] == $list['id']) {

                                                $img_num++;
                                                if ($imglist['type'] == 1) { ?>
                                                    <img class="lazy img_img col-lg-4 col-md-4 col-sm-4 col-sm-x-4"
                                                        data-original="<?php echo $imglist['VideoCover'] ?>">
                                                <?php } else if ($imglist['type'] == 0) { ?>
                                                        <img class="lazy img_img col-lg-4 col-md-4 col-sm-4 col-sm-x-4"
                                                            data-original="<?php echo $imglist['url'] ?>">
                                                <?php }
                                                if ($img_num >= 9) {
                                                    break;
                                                }
                                            }
                                        } ?>
                                    </div>
                                    <?php if ($img_num == 0): ?>
                                        <img style="margin-bottom: 1rem;" class="lazy img_img col-lg-4 col-md-4 col-sm-4 col-sm-x-4"
                                            data-original="<?php echo $list['imgUrl'] ?>">
                                        <?php
                                    endif;
                                }
                                ?>

                                <div class="see">
                                    <span class="see_btn albumTime">
                                        <svg t="1728743905389" class="icon albumTimeIcon" viewBox="0 0 1024 1024" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg" p-id="29786" width="100" height="100">
                                            <path
                                                d="M512 0a512 512 0 0 1 512 512 512 512 0 0 1-512 512A512 512 0 0 1 0 512 512 512 0 0 1 512 0z"
                                                fill="#FFB248" p-id="29787"></path>
                                            <path
                                                d="M544 335.04a48 48 0 1 0-96 0v214.72a64 64 0 0 0 43.2 60.48l224 76.48a48 48 0 0 0 32-90.88L544 527.04z"
                                                fill="#FFFFFF" p-id="29788"></path>
                                        </svg>
                                        <?php echo $list['imgDatd'] ?>
                                    </span>
                                    <?php if (!$img_num == 0) { ?>
                                        <a class="svg_hover" href="imglist.php?code=<?php echo $list['img_code'] ?>">
                                            <svg t="1728548742038" class="icon click_svg" viewBox="0 0 1024 1024" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg" p-id="6692" width="200" height="200">
                                                <path
                                                    d="M517.36064 15.81056c-277.02272 0-501.54496 224.53248-501.54496 501.55008C15.81056 794.32192 240.3328 1018.88 517.36064 1018.88 794.32704 1018.88 1018.88 794.32192 1018.88 517.36064 1018.88 240.34304 794.07616 15.81056 517.36064 15.81056zM675.55328 555.56096l-199.66464 199.71072c-21.15584 21.18144-55.28576 21.18144-76.43136 0-21.16096-21.19168-21.16096-55.22944 0-76.41088l161.49504-161.50016L399.45728 355.85536c-21.16096-21.17632-21.16096-55.27552 0-76.43648 21.1456-21.1456 55.27552-21.1456 76.43136 0l199.66464 199.7056C696.74496 500.2752 696.74496 534.62528 675.55328 555.56096z"
                                                    p-id="6693"></path>
                                            </svg>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                }
                ?>
            </div>
            <script>
                $(document).ready(function () {
                    $(".lazy").lazyload({
                        threshold: 300,
                        placeholder_data_img: "<?php echo $diy['diy_load'] ?>",
                        effect: "fadeIn",
                        check_appear_throttle_time: 2000,
                    });

                    var grid = document.querySelector('.Photo');
                    var masonry = new Masonry(grid, {
                        itemSelector: '.PhotoCard',
                        columnWidth: '.PhotoCard',
                        fitWidth: true
                    });
                });
            </script>
        </div>
    </div>
    <?php
    include_once 'footer.php';
    ?>

</body>

</html>