<?php
include_once 'admin/connect.php';
$lovelist = "select * from lovelist order by id desc";
$reslist = mysqli_query($connect, $lovelist);
$data_row = mysqli_num_rows($reslist);
?>
<!DOCTYPE html>
<html>

<head>
    <?php
    include_once 'head.php';
    ?>
    <title>
        <?php echo $text['title'] ?> —
        <?php echo $text['card4'] ?>
    </title>
    <meta name="description" content="<?php echo $seo_switch ? $site_description : '' ?>">
    <meta name="keywords" content="<?php echo $seo_switch ? $global_keywords : '' ?>">
    <meta name="author" content="Ki">
    <meta name="love-theme" content="LGNewUI-情侣小站">
    <meta name="copyright" content="2024 LG_NewUi Web All Rights Reserved">
</head>


<body>
    <div id="pjax-container">
        <link rel="stylesheet" href="../Style/LoveListStyle/styleCarousel.css" />
        <style>
            .bg_heise {
                background: #535353;
            }

            .bg_heise .img_list .content_a {
                background: #6b6b6b !important;
            }

            .Search_warp {
                margin-bottom: 1.5rem;
            }

            .position {
                position: relative;
                width: 80%;
                margin: 0 auto;
            }

            .Search_warp #search {
                padding: 1rem 1.5rem;
                border: 1px solid #ddddddb8;
                box-shadow: 0 2px 10px 0 rgb(177 173 173 / 19%);
                border-radius: 2rem;
                width: 100%;
                background: #fff;
                font-family: 'Noto Serif SC', serif;
                font-weight: 400;
                box-sizing: border-box;
                color: #777;
            }

            .Search_warp #search:focus {
                border-color: #4babf7;
                color: #41c5ff;
            }

            .Search_warp #search_btn {
                padding: 0.4rem 2.6rem;
                border: none;
                border-radius: 2rem;
                color: #fff;
                font-size: 1.3rem;
                background-image: linear-gradient(45deg, #41c5ff, #4babf7);
                font-family: 'Noto Serif SC', serif;
                font-weight: 700;
                position: absolute;
                top: 50%;
                right: 6px;
                transform: translateY(-50%);
            }

            .Search_warp #search_btn:hover {
                cursor: pointer;
                opacity: .7;
            }

            .toast-title {
                font-size: 1.3rem;
                font-family: 'Noto Serif SC', serif;
                font-weight: 700;
                margin-bottom: 0.6rem;
            }

            .toast-message {
                line-height: 1.4rem;
            }

            select {
                border: none;
                outline: none;
            }

            svg {
                display: inline;
            }


            #whether {
                padding: 0.2rem 2rem;
                border: 1px dashed #2196f39c;
                box-shadow: 0 2px 10px 0 rgb(177 173 173 / 19%);
                border-radius: 0.7rem;
                background: #fff;
                font-family: 'Noto Serif SC', serif;
                font-weight: 400;
                box-sizing: border-box;
                font-size: 1.5rem;
                color: #03A9F4;
                text-align: center;
            }

            #whether:hover,
            #whether:focus {
                border-color: #ff5722a3;
                color: #FF5722;
            }

            .screen {
                text-align: center;
                margin-bottom: 1rem;
            }



            .ListItem .InfoIcon {
                font-size: 2.5rem;
                border: 5px solid #fff;
                border-radius: 50%;
                position: absolute;
                top: 0;
                left: 0;
                transform: translate(-50%, -50%);
            }

            .ListItem .content_a {
                overflow: unset;
                position: relative;
                margin: 0 !important;
                margin-left: 1rem !important;
            }

            .ListItem .see {
                position: absolute;
                /* top: 50%; */
                right: 0rem;
                /* transform: translate(-50%, -50%); */
                margin-top: 0;
            }

            .img_list .content_a img:hover {
                transform: none;
            }

            .f-carousel__viewport {
                border-radius: 1rem;
            }

            .Tab_Warp {
                padding: .35rem;
                background: #fff;
                border-radius: 4.8rem;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                max-width: 380px;
                margin: 1rem auto;
            }

            .LgLoveList-tab-container {
                font-family: 'Noto Serif SC', serif;
                display: flex;
                background-color: #f2f2f2;
                /* border: 1px solid #e4e4e4; */
                border-radius: 30px;
                padding: 3px;
                max-width: 380px;
                margin: 0 auto;
                user-select: none;
            }

            .LgLoveList-tab {
                flex: 1;
                text-align: center;
                padding: 10px 0;
                border-radius: 30px;
                cursor: pointer;
                position: relative;
            }

            .LgLoveList-tab-active {
                background-color: #fff;
            }

            .LgLoveList-tab span {
                color: #8b8b8b;
                font-weight: 700;
            }

            .LgLoveList-tab-active span {
                color: #333;
            }
        </style>

        <div class="Width_limit_10rem">
            <div class="central mar_t0">
                <div class="nav_url shadow-blur marb_3rem" <?php if ($Animation == "1") { ?>aos="flip-up" <?php } ?>>
                    <span class="iconfont icon_sm">&#xe53e;</span>
                    <?php echo $text['deci4'] ?>
                </div>
                <?php if ($data_row <= 0) {
                    include_once "PageNulldata.php";
                } ?>

                <?php if ($data_row) { ?>
                    <div class="Search_warp">
                        <div class="Tab_Warp">
                            <div class="LgLoveList-tab-container">
                                <div class="LgLoveList-tab" data-id="1" id="bottom-tab-template1">
                                    <span>已完成</span>
                                </div>
                                <div class="LgLoveList-tab LgLoveList-tab-active" data-id="2" id="bottom-tab-template2">
                                    <span>全部</span>
                                </div>
                                <div class="LgLoveList-tab" data-id="3" id="bottom-tab-template3">
                                    <span>未完成</span>
                                </div>
                            </div>
                        </div>



                        <div class="position">
                            <input id="search" name="search" type="text" placeholder="请输入需查找的事件关键词">
                            <button id="search_btn" class="shadow-blur"><svg t="1690375391067" class="icon"
                                    viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="23639"
                                    width="100" height="100">
                                    <path
                                        d="M938.286 879.729 708.553 649.993c48.577-60.956 77.624-138.171 77.624-222.179C786.177 230.754 626.428 71 429.363 71 232.296 71 72.548 230.754 72.548 427.814c0 197.067 159.754 356.82 356.815 356.82 81.403 0 156.42-27.278 216.461-73.163l230.36 230.36c8.58 8.578 19.816 12.868 31.052 12.868 11.236 0 22.472-4.29 31.05-12.868C955.44 924.677 955.44 896.881 938.286 879.729L938.286 879.729zM154.575 427.814c0-151.758 123.029-274.783 274.788-274.783 151.762 0 274.787 123.025 274.787 274.783 0 151.764-123.025 274.794-274.787 274.794C277.604 702.608 154.575 579.578 154.575 427.814L154.575 427.814zM282.153 290.771c-9.172-4.961-20.621-1.543-25.578 7.628-20.646 38.193-33.226 79.643-37.392 123.209-0.995 10.375 6.614 19.591 16.989 20.586 0.612 0.055 1.219 0.085 1.822 0.085 9.614 0 17.83-7.311 18.764-17.079 3.683-38.505 14.795-75.128 33.021-108.857C294.737 307.178 291.318 295.728 282.153 290.771L282.153 290.771zM282.153 290.771"
                                        fill="#ffffff" p-id="23640"></path>
                                </svg>
                                查询</button>
                        </div>
                    </div>


                    <div class="query_data">
                        <!-- 数据展示 -->
                    </div>
                <?php } ?>
                <script>
                    $('.LgLoveList-tab').on('click', function() {
                        let screen_code = $(this).data('id');
                        $('.LgLoveList-tab').removeClass('LgLoveList-tab-active');
                        $(this).addClass('LgLoveList-tab-active');

                        if (screen_code == 2) {
                            toastr["info"]("正在为你展示 已完成 事件", "<?php echo $title ?>");
                            Query_List(screen_code, "All")
                        } else if (screen_code == 1) {
                            toastr["info"]("正在为你展示 全部 事件", "<?php echo $title ?>");
                            Query_List(screen_code, "Success")
                        } else if (screen_code == 3) {
                            toastr["info"]("正在为你展示 未完成 事件", "<?php echo $title ?>");
                            Query_List(screen_code, "Fail")
                        }
                    });


                    $("#search_btn").click(function() {
                        let search_info = $("input[name='search']").val();
                        if (!search_info || search_info.length == 0) {
                            toastr["warning"]("查询失败 请先输入查询关键词", "<?php echo $title ?>");
                            return false;
                        }
                        Query_List(4, search_info)
                    })

                    function Query_List(type, code) {

                        if (type == 4) {
                            $('#search_btn').text('查询中...');
                            $("#search_btn").attr("disabled", "disabled");
                        }

                        $.ajax({
                            url: "search_list.php",
                            data: {
                                search_info: code,
                            },
                            type: "POST",
                            dataType: "json",
                            success: function(res) {
                                setInterval(() => {
                                    $('#search_btn').attr("disabled", false);
                                }, 3000);
                                if (res) {
                                    toastr["success"]("查询成功 已为你展示相关结果", "<?php echo $title ?>");
                                    $('#search_btn').text('查询成功');
                                    $(".query_data").children().remove();
                                    $("#list_data").empty();
                                    res.forEach(function(item, index, array) {
                                        let imgurl_icon, imgurl, icon_sw, heightDiy;

                                        if (item.icon) {
                                            icon_sw = `<i class="InfoIcon iconfont icon-chenggong2 com"></i>`;
                                        } else {
                                            icon_sw = `<i class="InfoIcon iconfont icon-chenggong2 air"></i>`;
                                        }


                                        if (item.imgurl && item.imgurl.length > 0) {
                                            imgurl_icon = `<div class="see">
                                                    <span class="see_btn">
                                                        <span class="iconfont">&#xe89f;</span>
                                                    </span>
                                                </div>`;
                                            imgurl = '';
                                            item.imgurl.filter(Boolean).forEach(function(url) {
                                                imgurl += `<div class="f-carousel__slide" data-src="${url}" >
                                                                <img class="lazy" data-src="${url}" />
                                                            </div>`;

                                                if (url == "0") {
                                                    heightDiy = 'height: auto !important;';
                                                    imgurl = `<span class="weisc"><svg style="margin-right: 0.3rem;" t="1682820316109" class="icon"
                                viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="11285"
                                width="200" height="200">
                                <path
                                    d="M512 51.2C257.4336 51.2 51.2 257.536 51.2 512s206.336 460.8 460.8 460.8 460.8-206.336 460.8-460.8S766.464 51.2 512 51.2z m72.192 630.3744c-34.7136 52.224-70.144 92.4672-129.6384 92.4672-40.6528-6.656-57.344-35.84-48.5376-65.4336l76.5952-253.5424c1.9456-6.2464-1.2288-12.8-6.8608-14.848s-16.6912 5.3248-26.3168 15.7696l-46.1824 55.7056c-1.3312-9.3184-0.2048-24.7808-0.2048-31.0272 34.816-52.224 91.8528-93.3888 130.6624-93.3888 36.864 3.7888 54.272 33.28 47.8208 65.536L504.4224 707.584c-1.024 5.8368 2.048 11.5712 7.2704 13.4144 5.632 2.048 17.6128-5.3248 27.136-15.872l46.1824-55.6032c1.2288 9.3184-0.8192 25.8048-0.8192 32.0512z m-10.24-331.264c-29.2864 0-52.9408-21.2992-52.9408-52.736 0-31.4368 23.7568-52.736 52.9408-52.736 29.2864 0 52.9408 21.2992 52.9408 52.736 0 31.4368-23.6544 52.736-52.9408 52.736z"
                                    fill="#ffbc00" p-id="11286"></path>
                            </svg>暂未上传</span>`;
                                                    imgurl_icon = '';
                                                }
                                            });
                                        } else {

                                            imgurl = `<span class="weisc"><svg style="margin-right: 0.3rem;" t="1682820316109" class="icon"
                                viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="11285"
                                width="200" height="200">
                                <path
                                    d="M512 51.2C257.4336 51.2 51.2 257.536 51.2 512s206.336 460.8 460.8 460.8 460.8-206.336 460.8-460.8S766.464 51.2 512 51.2z m72.192 630.3744c-34.7136 52.224-70.144 92.4672-129.6384 92.4672-40.6528-6.656-57.344-35.84-48.5376-65.4336l76.5952-253.5424c1.9456-6.2464-1.2288-12.8-6.8608-14.848s-16.6912 5.3248-26.3168 15.7696l-46.1824 55.7056c-1.3312-9.3184-0.2048-24.7808-0.2048-31.0272 34.816-52.224 91.8528-93.3888 130.6624-93.3888 36.864 3.7888 54.272 33.28 47.8208 65.536L504.4224 707.584c-1.024 5.8368 2.048 11.5712 7.2704 13.4144 5.632 2.048 17.6128-5.3248 27.136-15.872l46.1824-55.6032c1.2288 9.3184-0.8192 25.8048-0.8192 32.0512z m-10.24-331.264c-29.2864 0-52.9408-21.2992-52.9408-52.736 0-31.4368 23.7568-52.736 52.9408-52.736 29.2864 0 52.9408 21.2992 52.9408 52.736 0 31.4368-23.6544 52.736-52.9408 52.736z"
                                    fill="#ffbc00" p-id="11286"></path>
                            </svg>暂未上传</span>`;
                                        }

                                        $(".query_data").prepend(
                                            `
<div class="ListItem">
    <div class="pad_1rem">
        <div class="img_list">
            <div class="content_a">
                ${icon_sw}
                    <li class="cike data">
                        ${imgurl_icon}
                        <span style="font-size: 1.3rem;margin-left: 0.5rem;color: #6c6c6c;">
                            ${item.eventname}
                        </span>
                    </li>
                <ul class="data">
                
                    <li style="border-top: 1px solid #fefefe;">
                        <div class="f-carousel ConventionPhoto" style="${heightDiy}" id="ConventionPhoto">
                            ${imgurl}
                        </div>

                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>`
                                        );
                                    })
                                    $(".content_a ul").hide();

                                    lazyLoadInstance.update();

                                    $(".img_list .cike").bind("click", function() {
                                        var $currentUl = $(this).next("ul");
                                        var isVisible = $currentUl.is(":visible");
                                        $(".img_list .cike").not($(this)).next("ul").slideUp();
                                        if (!isVisible) {
                                            $currentUl.slideDown();
                                        }
                                    });

                                    const carouselContainers = document.getElementsByClassName("ConventionPhoto");
                                    Array.from(carouselContainers).forEach(container => {
                                        new Carousel(container, {
                                            Dots: false
                                        }, {
                                            Thumbs
                                        });
                                    });
                                } else if (res == 0) {
                                    toastr["error"]("查询失败 暂未查询到相关信息", "<?php echo $title ?>");
                                    $('#search_btn').text('查询失败');
                                }
                            },
                            error: function(err) {
                                toastr["error"]("网络错误 请稍后重试！", "<?php echo $title ?>");
                            }
                        })
                    }
                </script>


                <div id="list_data">
                    <?php
                    while ($list = mysqli_fetch_array($reslist)) {
                    ?>
                        <div class="ListItem" <?php if ($Animation == "1") { ?> aos="fade-up" <?php } ?>>

                            <div class="pad_1rem">
                                <div class="img_list">
                                    <div class="content_a">
                                        <i class="InfoIcon iconfont icon-chenggong2 <?= $list['icon'] ? 'com' : 'air'; ?>"></i>
                                        <li class="cike">
                                            <?php if ($list['imgurl']) { ?>
                                                <div class="see">
                                                    <span class="see_btn">
                                                        <span class="iconfont">&#xe89f;</span>
                                                    </span>
                                                </div>
                                            <?php } ?>

                                            <span style="font-size: 1.3rem;margin-left: 0.5rem;color: #6c6c6c;">
                                                <?php echo $list['eventname']; ?>
                                            </span>
                                            <?php if ($list['imgurl']) { ?>
                                                <svg class="icon" aria-hidden="true">
                                                    <use xlink:href="#icon-tupian"></use>
                                                </svg>
                                            <?php } ?>
                                        </li>
                                        <ul view-image>
                                            <li style="border-top: 1px solid #fefefe;">
                                                <?php if ($list['imgurl']) { ?>
                                                    <div class="f-carousel ConventionPhoto" id="ConventionPhoto">
                                                        <?php
                                                        $LoveListData = ExtractURL($list['imgurl']);
                                                        foreach ($LoveListData as $photo) { ?>
                                                            <div class="f-carousel__slide">
                                                                <img class="lazy" data-src="<?php echo $photo ?>" />
                                                            </div>
                                                        <?php } ?>
                                                    </div>

                                                <?php } ?>
                                                <?php if (!$list['imgurl']) { ?>
                                                    <span class="weisc"><svg style="margin-right: 0.3rem;" t="1682820316109"
                                                            class="icon" viewBox="0 0 1024 1024" version="1.1"
                                                            xmlns="http://www.w3.org/2000/svg" p-id="11285" width="200"
                                                            height="200">
                                                            <path
                                                                d="M512 51.2C257.4336 51.2 51.2 257.536 51.2 512s206.336 460.8 460.8 460.8 460.8-206.336 460.8-460.8S766.464 51.2 512 51.2z m72.192 630.3744c-34.7136 52.224-70.144 92.4672-129.6384 92.4672-40.6528-6.656-57.344-35.84-48.5376-65.4336l76.5952-253.5424c1.9456-6.2464-1.2288-12.8-6.8608-14.848s-16.6912 5.3248-26.3168 15.7696l-46.1824 55.7056c-1.3312-9.3184-0.2048-24.7808-0.2048-31.0272 34.816-52.224 91.8528-93.3888 130.6624-93.3888 36.864 3.7888 54.272 33.28 47.8208 65.536L504.4224 707.584c-1.024 5.8368 2.048 11.5712 7.2704 13.4144 5.632 2.048 17.6128-5.3248 27.136-15.872l46.1824-55.6032c1.2288 9.3184-0.8192 25.8048-0.8192 32.0512z m-10.24-331.264c-29.2864 0-52.9408-21.2992-52.9408-52.736 0-31.4368 23.7568-52.736 52.9408-52.736 29.2864 0 52.9408 21.2992 52.9408 52.736 0 31.4368-23.6544 52.736-52.9408 52.736z"
                                                                fill="#ffbc00" p-id="11286"></path>
                                                        </svg>暂未上传</span>
                                                <?php } ?>
                                            </li>
                                        </ul>
                                        </p>
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
    </div>
    <?php
    include_once 'footer.php';
    ?>
    <script>
        $(function() {
            $(".content_a ul").hide();

            $(".img_list .cike").bind("click", function() {
                var $currentUl = $(this).next("ul");
                var isVisible = $currentUl.is(":visible");
                $(".img_list .cike").not($(this)).next("ul").slideUp();
                if (!isVisible) {
                    $currentUl.slideDown();
                }
            });
        });

        const carouselContainers = document.getElementsByClassName("ConventionPhoto");
        Array.from(carouselContainers).forEach(container => {
            new Carousel(container, {
                Dots: false
            }, {
                Thumbs
            });
        });
    </script>
</body>

</html>