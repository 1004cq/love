<script src="../Style/toastr/toastr.js"></script>
<script src="../Style/js/clipboard.min.js"></script>
<script src="../Style/js/view-image.min.js"></script>
<script src="../Style/js/mian.js?=v<?php echo $versionCode ?>"></script>
<script src="../Style/LoveListStyle/carousel.umd.js"></script>
<script src="../Style/LoveListStyle/carousel.thumbs.umd.js"></script>
<script src="../Style/LoveListStyle/fancybox.umd.js"></script>
<div id="pjax-container">
    
<?php if (isset($_SESSION['loginadmin'])): ?>
        <div id="isAdminIcon">
            <a class="AdminIconStyle" href="admin/index.php">
                <svg t="1734593714534" class="icon" viewBox="0 0 1084 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4125" width="200" height="200">
                    <path d="M1080.09609 434.500756c-4.216302-23.731757-26.9241-47.945376-50.595623-53.185637l-17.648235-4.095836a175.940257 175.940257 0 0 1-101.612877-80.832531 177.807476 177.807476 0 0 1-18.732427-129.801867l5.541425-16.684509c7.10748-23.129428-2.108151-54.992624-20.599646-70.833873 0 0-16.624276-14.094495-63.244529-41.199293-46.800951-26.984332-66.858502-34.513443-66.858502-34.513443-22.76803-8.372371-54.631227-0.361397-71.255503 17.407304l-12.287509 13.251234a173.470708 173.470708 0 0 1-120.465769 48.065842A174.13327 174.13327 0 0 1 421.329029 33.590675L409.583617 20.761071C393.140039 2.99237 361.096144-4.898138 338.267881 3.353767c0 0-20.358715 7.529111-67.099434 34.513443-46.800951 27.34573-63.244529 41.440225-63.244529 41.440225-18.431263 15.66055-27.646894 47.222582-20.539413 70.592941l5.059562 16.865207a178.048407 178.048407 0 0 1-18.672194 129.621169 174.916297 174.916297 0 0 1-102.275439 81.073463l-17.045906 3.854904c-23.310126 5.42096-46.258856 29.333415-50.595623 53.185637 0 0-3.854905 21.382674-3.854905 75.712737 0 54.330062 3.854905 75.712736 3.854905 75.712736 4.216302 23.972688 26.9241 47.945376 50.595623 53.185637l16.624276 3.854905a174.253736 174.253736 0 0 1 102.395904 81.314394c23.310126 40.837896 28.911785 87.337683 18.732427 129.801867l-4.81863 16.443578c-7.10748 23.129428 2.108151 54.992624 20.599646 70.833872 0 0 16.624276 14.094495 63.244529 41.199293 46.800951 27.104798 66.918735 34.513443 66.918735 34.513443 22.707798 8.372371 54.631227 0.361397 71.255503-17.407303l11.624947-12.588673a175.096996 175.096996 0 0 1 242.256662 0.120465l11.624947 12.648906c16.383345 17.708468 48.427239 25.598976 71.255503 17.347071 0 0 20.358715-7.529111 67.159666-34.513443 46.740719-27.104798 63.124063-41.199293 63.124064-41.199293 18.491496-15.600317 27.707127-47.463513 20.599646-70.833873l-5.059562-17.106139a176.723284 176.723284 0 0 1 18.672194-129.139305 176.060722 176.060722 0 0 1 102.395904-81.314394l16.68451-3.854905c23.310126-5.42096 46.258856-29.333415 50.595623-53.185637 0 0 3.854905-21.382674 3.854904-75.712737-0.240932-54.330062-4.095836-75.833202-4.095836-75.833202z m-537.819428 293.334149c-119.261112 0-216.175824-97.336342-216.175824-217.621412a216.657687 216.657687 0 0 1 216.236057-217.320249c119.200879 0 216.115591 97.276109 216.11559 217.56118-0.240932 120.044139-96.974945 217.320248-216.175823 217.320249z" fill="#ffffff" p-id="4126"></path>
                </svg>
            </a>
        </div>
    <?php endif; ?>

    <div id="NavBtnList">
        <a style="overflow:hidden" href="javascript:void(0)" id="scrollTopBtn">
            <svg t="1714115902938" class="icon NavItem TopBtn" viewBox="0 0 1024 1024" version="1.1"
                xmlns="http://www.w3.org/2000/svg" p-id="6692" width="100" height="100">
                <path
                    d="M95.658667 562.645333a71.637333 71.637333 0 0 1 0-101.290666l365.696-365.696a71.637333 71.637333 0 0 1 101.290666 0l365.696 365.696a71.637333 71.637333 0 1 1-101.248 101.290666L512 247.552l-315.093333 315.093333a71.594667 71.594667 0 0 1-101.248 0z"
                    p-id="6693"></path>
                <path
                    d="M95.658667 928.341333a71.637333 71.637333 0 0 1 0-101.248l365.696-365.738666a71.594667 71.594667 0 0 1 101.290666 0l365.696 365.696a71.637333 71.637333 0 0 1-101.248 101.290666L512 613.290667l-315.093333 315.050666a71.637333 71.637333 0 0 1-101.248 0z"
                    p-id="6694"></path>
            </svg>
        </a>
        <a class="mrt2 NavItemBtnHide" href="little.php">
            <span class="iconfont icon-a-wenzhang1x NavItem"></span>
        </a>
        <a class="mrt2 NavItemBtnHide" href="leaving.php">
            <span class="iconfont icon-liuyan1 NavItem"></span>
        </a>
        <a class="mrt2 NavItemBtnHide" href="loveImg.php">
            <span class="iconfont icon-tukuxiangce NavItem"></span>
        </a>
        <a class="mrt2 NavItemBtnHide" href="list.php">
            <span class="iconfont icon-liebiao1 NavItem"></span>
        </a>
        <?php if ($is_aboutActive): ?>
            <a class="mrt2" href="about.php">
                <span class="iconfont icon-guanyuwomen NavItem"></span>
            </a>
        <?php endif; ?>

    </div>

    <script>
        $(window).scroll(function() {
            if ($(this).scrollTop() > 1000) {
                $('#NavBtnList').fadeIn();
            } else {
                $('#NavBtnList').fadeOut();
            }
        });

        $('#scrollTopBtn').on('click', function() {
            $('html, body').scrollTop(0);
        });


        $(function() {

            const iframes = document.getElementsByTagName("iframe");

            for (let i = 0; i < iframes.length; i++) {
                let iframe = iframes[i];
                iframe.style.height = iframe.scrollWidth * 0.76 + "px";
            }

            window.onload = function() {
                setActiveTab();
            };

            sessionStorage.setItem('previousPage', window.location.href);

            $('.LG_Tab_Item').on('click', function() {
                $('.LG_Tab_Item a').removeClass('TabActive');
                $(this).find('a').addClass('TabActive');
            });

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            adjustCarouselHeight();
            adjustPhotoCoverHeight()
            adjustImgMinHeight();

            const debouncedRefresh = debounce(() => {
                AOS.refresh();
            }, 100);

            window.addEventListener('resize', debouncedRefresh);

            $(window).resize(debounce(adjustCarouselHeight, 100));
            $(window).resize(debounce(adjustPhotoCoverHeight, 100));
            $(window).resize(debounce(adjustImgMinHeight, 100));



            $('#NavBtnList').hide();
            $("quote").addClass("shadow-blur");

            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "rtl": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": 300,
                "hideDuration": 1000,
                "timeOut": 5000,
                "extendedTimeOut": 1000,
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        })

        AOS.init({
            offset: 50,
            duration: 600,
            easing: 'ease-in-sine',
            delay: 30,
            once: true,
        });

        IsCurrentURL();

        $(document).ready(function() {
            $('body').addClass('loaded');
            window.ViewImage && ViewImage.init('.loveimg img, .img_list img, #md-view img,.leav_card .aiv_qq img, img.photo_style');

            let GlobalImgLazy = new LazyLoad({
                threshold: 0,
                elements_selector: ".photo_style, .aiv_touxiang, .MessageCard img, .Item_OwO img, .leav_card img, .sm-x-block img, .CarouselImage, .ConventionPhoto img, .avatarArea .avatarFrame"
            });
            GetEm();

            $('.left_info,.Message_Wrap .MessageCard .MsgFooter .InfoItem').hover(function() {
                let title = $(this).data('title');
                let nowrap = $(this).data('nowrap');
                let Diyclass;
                if (!title) {
                    return false;
                }
                if (nowrap == 1) {
                    Diyclass = 'tooltip Textnowrap'
                } else {
                    Diyclass = 'tooltip'
                }

                let tooltipElement = $('<span>').addClass(Diyclass).text(title);
                tooltipElement.appendTo($(this));
                tooltipElement.fadeIn(130);
            }, function() {
                let tooltipElement = $(this).find('.tooltip');
                tooltipElement.fadeOut(80, function() {
                    tooltipElement.remove();
                });
            });

            // 如图片加载失败 则替换预定图片
            $('.photo_content img').on('error', function() {
                $(this).attr('src', 'https://img.gejiba.com/images/a91fe82bbcaa2c3d2982d14fd6d9436b.png');
                $(this).addClass('loaded');
            });
            const player = new Plyr('#LGNewUiPlayerVideo', {
                i18n: {
                    speed: '速度',
                    normal: '正常'
                },
                keyboard: {
                    global: true,
                },
                seekTime: 2,
                controls: [
                    'play-large',
                    'play',
                    'mute',
                    'volume',
                    'progress',
                    'current-time',
                    'settings',
                    'fullscreen',
                ]
            });
        });
    </script>

    <style>
        .NotAbout {
            display: none;
        }

        .about_y {
            font-size: 2rem;
            background: #ffffff;
            padding: 0.8rem;
            margin-left: 1rem;
            border-radius: 1rem;
            color: #03A9F4;
            position: fixed;
            right: 1rem;
            bottom: 7.5rem;
            z-index: 100;
            box-shadow: 0 3px 10px #bdb7b78c;
            border: 1px solid #fff;
            transition: 0.1s all;
        }

        .about_y:hover {
            background: #03A9F4;
            color: #ffffff;
        }

        .icon {
            width: 1.5em;
            height: 1.5em;
            vertical-align: -0.3em;
            fill: currentColor;
            overflow: hidden;
        }

        li.cike {
            border-bottom: 1px solid #ddd;
        }

        li {
            list-style-type: none;
        }

        .cike:hover {
            cursor: pointer;
            cursor: url(../Style/cur/hover.cur), pointer;
        }

        button:disabled {
            background: #888;
            opacity: 0.6;
        }

        .avatar {
            width: 2.5em;
            height: 2.5em;
            border-radius: 50%;
            box-shadow: 0 2px 8px #a9a9a98c;
            border: 2px solid #fff;
            margin-right: 0.8rem;
        }

        .footer-warp {
            background: #ffffff;
            margin-top: 0;
            border-top: 1px solid #efefef;
            padding: 2rem 0;
        }

        .footer-warp .footer {
            padding-bottom: 0;
        }

        .footer-warp .footer p {
            line-height: 1.2rem;
            margin: 0.5rem auto 0;
        }

        .tab_ic {
            border-bottom: 2px solid transparent;
            padding-bottom: 0.3rem;
        }

        .factive {
            color: #00BCD4;
            font-weight: 700;
            border-bottom: 2px solid #00BCD4;
            padding-bottom: 0.3rem;
            transition: all 0.1s;

        }

        .github-badge {
            display: inline-block;
            border-radius: 4px;
            text-shadow: none;
            font-size: 12px;
            color: #fff;
            line-height: 15px;
            background-color: #5d5d5d;
            margin-bottom: 5px;
            white-space: nowrap;

        }

        .footer .github-badge .badge-subject img {
            width: 12px;
            vertical-align: bottom;
            margin: 0 .3rem;
        }


        .github-badge:hover {
            color: #fafafa;
        }

        .github-badge .badge-subject {
            display: inline-block;
            background-color: #4d4d4d;
            padding: 4px 4px 4px 6px;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .github-badge .badge-value {
            display: inline-block;
            padding: 4px 6px 4px 4px;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        .github-badge .bg-pink {
            /* background-image: linear-gradient(to right, #a4b7ff 0%, #ff7eb3 100%); */
            background-image: linear-gradient(to right, #747474 0%, #ff7eb3 100%);
        }

        .github-badge .bg-DIY {
            /* background-image: linear-gradient(to right, #00decf 0%, #e46cff 100%); */
            background-image: linear-gradient(to right, #747474 0%, #ff7575 100%)
        }

        .github-badge .bg-DIY1 {
            background-color: #7f7f7f;
        }

        .github-badge .bg-blue {
            /* background-image: linear-gradient(120deg, #02f0ff 0%, #66a6ff 100%); */
            background-image: linear-gradient(120deg, #747474 0%, #66a6ff 100%);
        }

        .LG_Tab_Item a {
            display: block;
        }
    </style>
</div>
<div class="div_marb_7rem">
    <div class="footer-warp">
        <div class="footer">

            <?php if ($Moelicp): ?>
                <p><a class="github-badge" href="https://icp.gov.moe/?keyword=<?php echo $Moelicp ?>" target="_blank">
                        <span class="badge-subject"><img src="/Style/img/icp/moeICP.png"></span>
                        <span class="badge-value bg-pink">
                            萌ICP备<?php echo $Moelicp . "号" ?>
                        </span>
                    </a></p>
            <?php endif; ?>
            <?php if ($icp): ?>
                <p><a class="github-badge" href="https://beian.miit.gov.cn/#/Integrated/index" target="_blank">
                        <span class="badge-subject"><img src="/Style/img/icp/ICP.svg"></span>
                        <span class="badge-value bg-blue">
                            <?php echo $icp ?>
                        </span>
                    </a></p>
            <?php endif; ?>
            <?php if ($policeICP): ?>
                <p><a class="github-badge"
                        href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=<?php echo extract_numbers($policeICP) ?>"
                        target="_blank">
                        <span class="badge-subject"><img src="/Style/img/icp/policeICP.svg"></span>
                        <span class="badge-value bg-DIY">
                            <?php echo $policeICP ?>
                        </span>
                    </a></p>
            <?php endif; ?>
            <?php if ($copy): ?>
                <p>
                    <a href="javascript:void(0);" class="github-badge">
                        <span class="badge-subject">Copyright</span>
                        <span class="badge-value bg-DIY1">
                            ©
                            <?php echo date("Y") . " " . $copy ?> All Rights Reserved.
                        </span>
                    </a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($TabTemplate == 1): ?>
    <menu class="menu sm-x-block">
        <a href="little.php" class="menu__item" style="--bgColorItem: #fc7e5e;">
            <svg t="1714223948059" class="FooterBtnicon" viewBox="0 0 1024 1024" version="1.1"
                xmlns="http://www.w3.org/2000/svg" p-id="13202" width="100" height="100">
                <path
                    d="M734.634667 72.448C717.824 55.978667 685.226667 42.666667 661.674667 42.666667H204.8C162.389333 42.666667 128 79.189333 128 124.330667v775.338666C128 944.768 162.474667 981.333333 204.8 981.333333h614.4c42.410667 0 76.8-36.693333 76.8-81.578666V273.493333c0-23.68-13.781333-56.405333-30.378667-72.661333l-130.986666-128.426667zM341.333333 298.666667h341.333334a42.666667 42.666667 0 0 1 0 85.333333H341.333333a42.666667 42.666667 0 1 1 0-85.333333z m0 170.666666h170.666667a42.666667 42.666667 0 0 1 0 85.333334H341.333333a42.666667 42.666667 0 0 1 0-85.333334z"
                    p-id="13203" fill="#ffffff"></path>
            </svg>
        </a>

        <a href="leaving.php" class="menu__item" style="--bgColorItem: #fb6f6f;">
            <svg t="1714223811616" class="FooterBtnicon" viewBox="0 0 1024 1024" version="1.1"
                xmlns="http://www.w3.org/2000/svg" p-id="5315" width="100" height="100">
                <path
                    d="M832.032253 127.978498l-639.892491 0c-52.980346 0-95.983874 43.003528-95.983874 95.983874l0 511.913993c0 52.980346 43.003528 95.983874 95.983874 95.983874l210.717285 0 86.695112 86.695112c6.192508 6.192508 14.449185 9.460776 22.705863 9.460776s16.341341-3.096254 22.705863-9.460776l86.695112-86.695112 210.717285 0c52.980346 0 95.983874-43.003528 95.983874-95.983874L928.360155 223.962372C928.016126 170.982026 884.840585 127.978498 832.032253 127.978498zM335.943558 511.913993c-26.490173 0-47.991937-21.501764-47.991937-47.991937s21.501764-47.991937 47.991937-47.991937 47.991937 21.501764 47.991937 47.991937S362.433731 511.913993 335.943558 511.913993zM527.911305 511.913993c-26.490173 0-47.991937-21.501764-47.991937-47.991937s21.501764-47.991937 47.991937-47.991937 47.991937 21.501764 47.991937 47.991937S554.401478 511.913993 527.911305 511.913993zM720.051067 511.913993c-26.490173 0-47.991937-21.501764-47.991937-47.991937s21.501764-47.991937 47.991937-47.991937c26.490173 0 47.991937 21.501764 47.991937 47.991937S746.369226 511.913993 720.051067 511.913993z"
                    fill="#ffffff" p-id="5316"></path>
            </svg>
        </a>

        <a href="index.php" class="menu__item active home" style="--bgColorItem: #68bcff;">
            <svg t="1714223917645" class="FooterBtnicon" viewBox="0 0 1024 1024" version="1.1"
                xmlns="http://www.w3.org/2000/svg" p-id="12139" width="100" height="100">
                <path
                    d="M554.965333 101.248l406.954667 373.034667a34.133333 34.133333 0 0 1-23.04 59.306666l-40.277333-0.042666v298.666666a85.333333 85.333333 0 0 1-85.333334 85.333334h-597.333333a85.333333 85.333333 0 0 1-85.333333-85.333334v-298.666666h-40.234667a34.133333 34.133333 0 0 1-23.04-59.264l406.912-373.034667a59.733333 59.733333 0 0 1 80.725333 0zM312.448 591.36a42.666667 42.666667 0 0 0 0 60.330667 279.978667 279.978667 0 0 0 395.946667 0 42.666667 42.666667 0 0 0-60.330667-60.330667 194.645333 194.645333 0 0 1-275.242667 0 42.666667 42.666667 0 0 0-60.373333 0z"
                    fill="#ffffff" p-id="12140"></path>
            </svg>
        </a>

        <a href="loveImg.php" class="menu__item" style="--bgColorItem: #dd9479;">
            <svg t="1714223998512" class="FooterBtnicon" viewBox="0 0 1024 1024" version="1.1"
                xmlns="http://www.w3.org/2000/svg" p-id="18682" width="100" height="100">
                <path
                    d="M883.4 163.2H136.7c-40.5 0-72.8 24.9-72.8 55.6v583.9c0 31.9 33.3 57.9 74 57.9h751.2c35.5 3.4 67.2-22.4 71-57.9V218.8c0-30.7-34.5-55.6-77.1-55.6h0.4zM358.9 293.4c40.7-0.1 73.9 32.8 74 73.5 0.1 40.7-32.8 73.9-73.5 74s-73.9-32.8-74-73.5v-0.2c0-40.7 32.9-73.6 73.5-73.8z m457 483.8H209.5c-23.5 0-28.2-13.6-12.4-29.8l130.1-136.9c16.7-16.5 42.7-19 62.2-5.9l95.8 67.6c19.2 13.3 45.3 9.8 60.4-8l221.9-268.9c14.6-17.6 29.1-13.6 32.4 8.5l50.7 334c2.9 18.7-10 36.2-28.7 39.1-1.9 0.3-4 0.4-6 0.3z"
                    fill="#ffffff" p-id="18683"></path>
            </svg>
        </a>

        <a href="list.php" class="menu__item" style="--bgColorItem:#edb837;">
            <svg t="1714224129372" class="FooterBtnicon" viewBox="0 0 1024 1024" version="1.1"
                xmlns="http://www.w3.org/2000/svg" p-id="24033" width="100" height="100">
                <path
                    d="M897.427256 249.003144 126.57172 249.003144c-34.673707 0-62.73174-28.088732-62.73174-62.73174s28.058033-62.73174 62.73174-62.73174l770.855536 0c34.673707 0 62.73174 28.088732 62.73174 62.73174S932.101987 249.003144 897.427256 249.003144z"
                    fill="#ffffff" p-id="24034"></path>
                <path
                    d="M897.427256 574.73174 126.57172 574.73174c-34.673707 0-62.73174-28.088732-62.73174-62.73174s28.058033-62.73174 62.73174-62.73174l770.855536 0c34.673707 0 62.73174 28.088732 62.73174 62.73174S932.101987 574.73174 897.427256 574.73174z"
                    fill="#ffffff" p-id="24035"></path>
                <path
                    d="M897.427256 900.460336 126.57172 900.460336c-34.673707 0-62.73174-28.088732-62.73174-62.73174 0-34.643008 28.058033-62.73174 62.73174-62.73174l770.855536 0c34.673707 0 62.73174 28.088732 62.73174 62.73174C960.16002 872.371604 932.101987 900.460336 897.427256 900.460336z"
                    fill="#ffffff" p-id="24036"></path>
            </svg>
        </a>

        <div class="menu__border"></div>

    </menu>

    <div class="svg-container">
        <svg viewBox="0 0 202.9 45.5">
            <clipPath id="menu" clipPathUnits="objectBoundingBox" transform="scale(0.0049285362247413 0.021978021978022)">
                <path d="M6.7,45.5c5.7,0.1,14.1-0.4,23.3-4c5.7-2.3,9.9-5,18.1-10.5c10.7-7.1,11.8-9.2,20.6-14.3c5-2.9,9.2-5.2,15.2-7
          c7.1-2.1,13.3-2.3,17.6-2.1c4.2-0.2,10.5,0.1,17.6,2.1c6.1,1.8,10.2,4.1,15.2,7c8.8,5,9.9,7.1,20.6,14.3c8.3,5.5,12.4,8.2,18.1,10.5
          c9.2,3.6,17.6,4.2,23.3,4H6.7z" />
            </clipPath>
        </svg>
    </div>

    <script src="../Style/js/Tab.js"></script>

<?php endif ?>


<?php if ($TabTemplate == 2): ?>

    <div class="toggleBar">
        <div class="tab_icon">
            <a class="tab_ic" href="little.php">
                <span class="iconfont icon_sm mar_0">&#xe615;</span>
                <b data-i18n="nav_articles">文章</b>
            </a>

            <a class="tab_ic" href="leaving.php">
                <span class="iconfont icon_sm mar_0">&#xe6a8;</span>
                <b data-i18n="nav_messages">留言</b>
            </a>

            <a class="tab_ic por" href="index.php">
                <span class="iconfont icon_sm mar_0 home">&#xe650;</span>
            </a>

            <a class="tab_ic" href="loveImg.php">
                <span class="iconfont icon_sm mar_0">&#xe60b;</span>
                <b data-i18n="nav_album">相册</b>
            </a>

            <a class="tab_ic" href="list.php">
                <span class="iconfont icon_sm mar_0">&#xe65e;</span>
                <b data-i18n="nav_list">清单</b>
            </a>
        </div>
    </div>

<?php endif ?>


<?php if ($TabTemplate == 3): ?>

    <div id="LGNewUI-Tab-Page-Wrap">
        <div class="LGNewUI_Tab_Pag_Content">
            <div class="LG_Tab_Item">
                <a href="little.php">
                    <svg t="1728295215748" class="icon" viewBox="0 0 1024 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="4894" width="100" height="100">
                        <path
                            d="M513.92 53.312c78.336 0 139.776 0 188.544 5.312 49.728 5.376 89.984 16.576 124.416 41.6 20.8 15.104 39.104 33.408 54.272 54.208 24.96 34.432 36.16 74.752 41.6 124.48 5.248 48.704 5.248 110.08 5.248 188.48v89.216c0 78.336 0 139.776-5.248 188.48-5.44 49.728-16.64 90.048-41.6 124.416a245.248 245.248 0 0 1-54.272 54.272c-34.432 25.024-74.688 36.224-124.416 41.6-48.768 5.312-110.144 5.312-188.544 5.312h-3.84c-78.4 0-139.776 0-188.544-5.312-49.728-5.376-89.984-16.576-124.416-41.6a245.312 245.312 0 0 1-54.272-54.272c-24.96-34.368-36.16-74.688-41.6-124.416-5.248-48.704-5.248-110.08-5.248-188.48V467.392c0-78.336 0-139.776 5.248-188.48 5.44-49.728 16.64-90.048 41.6-124.48 15.168-20.8 33.472-39.104 54.272-54.272 34.432-24.96 74.688-36.16 124.416-41.6C370.304 53.376 431.68 53.376 510.08 53.376h3.84zM256 405.312c0 23.616 19.072 42.688 42.688 42.688H640a42.688 42.688 0 1 0 0-85.312H298.688A42.688 42.688 0 0 0 256 405.312z m0 213.376c0 23.552 19.072 42.624 42.688 42.624h128a42.688 42.688 0 0 0 0-85.312h-128A42.688 42.688 0 0 0 256 618.688z"
                            p-id="4895"></path>
                    </svg>
                </a>
            </div>
            <div class="LG_Tab_Item">
                <a href="leaving.php">
                    <svg t="1728467953110" class="icon" viewBox="0 0 1066 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="33920" width="200" height="200">
                        <path
                            d="M148.821333 128.042667a85.333333 85.333333 0 0 0-85.333333 85.333333v512a85.333333 85.333333 0 0 0 85.333333 85.333333h223.445334a85.333333 85.333333 0 0 1 56.832 21.674667l46.890666 41.898667a85.333333 85.333333 0 0 0 113.706667 0l46.848-41.898667a85.333333 85.333333 0 0 1 56.832-21.674667h223.445333a85.333333 85.333333 0 0 0 85.333334-85.333333v-512a85.333333 85.333333 0 0 0-85.333334-85.333333h-768z m128 426.624a85.333333 85.333333 0 1 1 0-170.666667 85.333333 85.333333 0 0 1 0 170.666667z m341.333334-85.333334a85.333333 85.333333 0 1 1-170.666667 0 85.333333 85.333333 0 0 1 170.666667 0z m170.666666 85.333334a85.333333 85.333333 0 1 1 0-170.666667 85.333333 85.333333 0 0 1 0 170.666667z"
                            p-id="33921"></path>
                    </svg>
                </a>
            </div>
            <div class="LG_Tab_Item">
                <a href="index.php" class="TabActive">
                    <svg t="1728467578949" class="icon" viewBox="0 0 1024 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="12485" width="200" height="200">
                        <path
                            d="M1013.28 447.41L561.9 26.95c-28.1-26.18-71.7-26.18-99.8 0L10.71 447.41c-14.29 13.3-14.29 34.87 0 48.18s37.45 13.3 51.74 0l10.64-9.91v421.48c0 60.49 49.12 109.53 109.71 109.53h146.29c40.4 0 73.14-32.69 73.14-73.02V741.41c0-19.72 16.09-36.51 36.57-36.51h146.29c20.11 0 36.57 16.43 36.57 36.51v202.26c0 40.33 32.75 73.02 73.14 73.02h146.29c60.59 0 109.71-49.04 109.71-109.53V485.6l10.73 9.99c14.29 13.3 37.46 13.3 51.74 0 14.3-13.31 14.3-34.88 0.01-48.18z"
                            p-id="12486"></path>
                    </svg>
                </a>
            </div>
            <div class="LG_Tab_Item">
                <a href="loveImg.php">
                    <svg t="1728294221478" class="icon" viewBox="0 0 1024 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="2579" width="100" height="100">
                        <path
                            d="M940.032 102.4H83.968C37.888 102.4 0 140.288 0 186.88v650.752c0 46.592 37.888 83.968 83.968 83.968h855.552c46.592 0 83.968-37.888 83.968-83.968V186.88c0.512-46.592-37.376-84.48-83.456-84.48z m-322.56 145.408c46.592 0 84.48 37.888 84.48 84.48s-37.888 84.48-84.48 84.48-84.48-37.888-84.48-84.48 37.888-84.48 84.48-84.48zM83.968 809.472s69.12-402.432 229.376-402.432c157.184 0 197.12 281.088 307.712 281.088 69.632 0 62.464-126.976 173.056-126.976 79.36 0 130.048 247.808 130.048 247.808l-840.192 0.512z"
                            p-id="2580"></path>
                    </svg>
                </a>
            </div>
            <div class="LG_Tab_Item">
                <a href="list.php">
                    <svg t="1728294249422" class="icon" viewBox="0 0 1024 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="3065" width="100" height="100">
                        <path
                            d="M349.265367 51.686157c19.446277 0 35.822089 6.652674 49.639181 19.958021 13.817091 13.305347 20.469765 29.681159 20.469765 49.127436v277.877062c0 19.446277-6.652674 35.822089-20.469765 49.63918-13.817091 13.817091-30.192904 20.469765-49.639181 20.469765H71.90005c-19.446277 0-35.310345-6.652674-49.127436-20.469765C9.467266 434.470765 2.814593 418.094953 2.814593 398.648676V120.25987c0-19.446277 6.652674-35.310345 19.958021-49.127436S52.453773 51.174413 71.90005 51.174413l277.365317 0.511744z m0 555.242379c19.446277 0 35.822089 6.652674 49.639181 19.958021 13.817091 13.305347 20.469765 29.681159 20.469765 49.127436v278.90055c0 19.446277-6.652674 35.310345-20.469765 49.127436-13.817091 13.305347-30.192904 19.958021-49.639181 19.958021H71.90005c-19.446277 0-35.310345-6.652674-49.127436-19.958021-13.305347-13.305347-19.958021-29.681159-19.958021-49.127436v-278.90055c0-19.446277 6.652674-35.310345 19.958021-49.127436 13.305347-13.305347 29.681159-19.958021 49.127436-19.958021h277.365317z m556.777612 0c19.446277 0 35.310345 6.652674 49.127436 19.958021 13.305347 13.305347 19.958021 29.681159 19.958021 49.127436v278.90055c0 19.446277-6.652674 35.310345-19.958021 49.127436-13.305347 13.305347-29.681159 19.958021-49.127436 19.958021h-277.877062c-19.446277 0-35.822089-6.652674-49.63918-19.958021-13.817091-13.305347-20.469765-29.681159-20.469765-49.127436v-278.90055c0-19.446277 6.652674-35.310345 20.469765-49.127436 13.817091-13.305347 30.192904-19.958021 49.63918-19.958021H906.042979zM1000.715642 209.815092c13.817091 13.817091 20.469765 29.681159 20.469765 48.103948s-6.652674 34.286857-20.469765 47.08046l-190.368815 190.368816c-13.817091 13.817091-29.681159 20.469765-47.592204 20.469765-17.911044 0-33.775112-6.652674-47.592204-20.469765l-190.368816-190.368816c-12.793603-12.793603-19.446277-28.657671-19.446277-47.08046 0-18.422789 6.652674-34.286857 19.446277-48.103948l190.368816-190.368815c13.817091-12.793603 29.681159-19.446277 47.592204-19.446277 17.911044 0 33.775112 6.652674 47.592204 19.446277L1000.715642 209.815092z"
                            p-id="3066"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

<?php endif ?>

<script src="/Style/js/i18n.js?v=<?php echo $versionCode ?>"></script>


<?php
echo htmlspecialchars_decode($diy['footerCon'], ENT_QUOTES);
mysqli_close($conn);
?>
