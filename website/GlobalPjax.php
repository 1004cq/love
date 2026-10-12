<script>
    $(document).pjax('a[target!=_blank]', '#pjax-container', {
        fragment: '#pjax-container',
        timeout: 15000
    });
    $(document).on('pjax:start', function () {
        $('body').removeClass('loaded');
        $('html').css('scroll-behavior', 'auto');
    });
    $(document).on('pjax:send', function () {
        NProgress.start();
    });
    $(document).on('pjax:complete', function () {
        $('html').css('scroll-behavior', 'smooth');
        $('body').addClass('loaded');


        hljs.initHighlightingOnLoad();
        window.ViewImage && ViewImage.init('.loveimg img, .img_list img, #md-view img,.leav_card .aiv_qq img, img.photo_style');
        NProgress.done();
        $(".content_a ul").hide();
        $(".img_list .cike").bind("click", function () {
            var $currentUl = $(this).next("ul");
            var isVisible = $currentUl.is(":visible");
            $(".img_list .cike").not($(this)).next("ul").slideUp();
            if (!isVisible) {
                $currentUl.slideDown();
            }
        });
        AOS.init({
            offset: 50,
            duration: 600,
            easing: 'ease-in-sine',
            delay: 30,
            // 是否重复
            once: true,
        });
        $("quote").addClass("shadow-blur");

        GetEm();

        new LazyLoad({
            threshold: 0,
            elements_selector: ".photo_style, .aiv_touxiang, .MessageCard img, .Item_OwO img, .leav_card img, .sm-x-block img, .CarouselImage, .ConventionPhoto img, .avatarArea .avatarFrame"
        });

        getMusic();

        IsCurrentURL();

        setActiveTab();

        const carouselContainers = document.getElementsByClassName("ConventionPhoto");
        Array.from(carouselContainers).forEach(container => {
            new Carousel(container, {
                Dots: false
            }, {
                Thumbs
            });
        });

        let gridLength = document.querySelectorAll('.Photo');
        if (gridLength.length >= 1) {
            var grid = document.querySelector('.Photo');
            var masonry = new Masonry(grid, {
                itemSelector: '.PhotoCard',
                columnWidth: '.PhotoCard',
                fitWidth: true
            });
        }

        const iframes = document.getElementsByTagName("iframe");

        for (let i = 0; i < iframes.length; i++) {
            let iframe = iframes[i];
            iframe.style.height = iframe.scrollWidth * 0.76 + "px";
        }



        $('.left_info,.Message_Wrap .MessageCard .MsgFooter .InfoItem').hover(function () {
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
        }, function () {
            let tooltipElement = $(this).find('.tooltip');
            tooltipElement.fadeOut(80, function () {
                tooltipElement.remove();
            });
        });

        adjustCarouselHeight();
        adjustPhotoCoverHeight()
        adjustImgMinHeight();

        $('.photo_content img').on('error', function () {
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


    $(document).on('pjax:end', function () {
        $('body').addClass('loaded');
        IsCurrentURL();
            var currentSiteUrl = window.location.href;
    
    });
</script>