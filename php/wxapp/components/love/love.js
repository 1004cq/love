const app = getApp();

Component({
  properties: {
  },

  data: {
    love_gif: app.globalData.GlobalLink + "Style/img/like.svg",
    LGSwiperData: '',
    LGSwiperCurIndex: 0,
    LGSwiperAutoplay: true,
    likeInfo: {},
    BackgroundMask: '',
  },

  lifetimes: {
    attached() {
      // 在组件加载时从全局变量读取索引
      const currentIndex = app.globalData.LGSwiperIndex || 0;
      this.setData({
        LGSwiperCurIndex: currentIndex,
      });

      this.getLikeInfo();

      // 延时设置自动播放，避免初次加载时出现抖动
      setTimeout(() => {
        this.setData({
          LGSwiperAutoplay: true,
        });
      }, 100);
    },
  },

  methods: {
    getLikeInfo() {
      app.getLikeInfo((data) => {
        this.setData({
          likeInfo: data,
          BackgroundMask: data.BackgroundMask,
          LGSwiperData:data.carousel
        });
      });
    },

    // 监听轮播切换
    onLGSwiperChange(e) {
      const currentIndex = e.detail.current;

      // 如果是自动播放触发的切换，或者触摸切换
      if (e.detail.source === "autoplay" || e.detail.source === "touch") {
        this.setData({
          LGSwiperCurIndex: currentIndex,
        });

        // 同步到全局变量
        wx.nextTick(() => {
          app.globalData.LGSwiperIndex = currentIndex;
        });
      }
    },

    // 点击自定义指示器切换轮播图
    onLGIndicatorTap(e) {
      const index = e.currentTarget.dataset.index;

      // 停止自动轮播
      this.setData({
        LGSwiperCurIndex: index,
        LGSwiperAutoplay: false,
      });

      // 同步到全局变量
      app.globalData.LGSwiperIndex = index;

      // 恢复自动轮播
      clearTimeout(this.autoPlayTimeout);
      this.autoPlayTimeout = setTimeout(() => {
        this.setData({
          LGSwiperAutoplay: true,
        });
      }, 5000);
    },
  },
});