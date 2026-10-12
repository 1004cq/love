var WxParse = require('../wxParse/wxParse.js');
const app = getApp();
let APIURL = app.globalData.GlobalLink;

Page({
  data: {
    loading: false,
    color: '#000',
    background: '#fff',
    show: true,
    animated: false,
    loveDayapi: APIURL + "api/loveTime.json",
    like: null,
    LoveDaydata: [null],
    leaving: [null],
    wenz: [null],
    BirthDay: null,
    navBarHeight: 0,
    showPopup: false,
    DayCompleteData: {},
    animationClass: '',
    isLoading: true,
    animationClassMask: ''
  },

  handleItemTap: function (e) {
    const {
      title,
      content,
      time,
      cover
    } = e.detail;
    // console.log("传递值：", title, content, time, cover);

    wx.navigateTo({
      url: `/pages/detail/detail?title=${encodeURIComponent(title)}&content=${encodeURIComponent(content)}&time=${encodeURIComponent(time)}&detailcover=${encodeURIComponent(cover)}`
    });
  },

  getLoveDay: function () {
    const that = this
    wx.request({
      url: that.data.loveDayapi,
      success: function (res) {
        that.setData({
          LoveDaydata: res.data
        })
      },
      fail: function (err) {
        console.log(err)
      }
    })
  },

  getPage: function (e) {
    let id = e.currentTarget.dataset.id;
    let time = e.currentTarget.dataset.time
    let title = e.currentTarget.dataset.title
    let name = e.currentTarget.dataset.name
    //  console.log(e.currentTarget.dataset.id)
    wx.navigateTo({
      url: '/pages/xqy/index?id=' + id + '&time=' + time + '&title=' + title + '&name=' + name,
    })
  },

  onLoad: function (options) {
    let navBarHeight = getApp().globalData.navBarHeight;
    if (!navBarHeight) {
      wx.getWindowInfo({
        success: (res) => {
          navBarHeight = res.statusBarHeight + 44 + 10;
          getApp().globalData.navBarHeight = navBarHeight; // 更新全局数据

          // 更新页面的顶部导航栏高度
          this.setData({
            navBarHeight: navBarHeight
          });
        }
      });
    } else {
      // 如果已缓存，直接使用
      this.setData({
        navBarHeight: navBarHeight
      });
    }

  },

  tabBar() {
    const isTabShow = Number(this.data.like.audit_mode);;
    if (typeof this.getTabBar === 'function' && this.getTabBar()) {
      this.getTabBar().setData({
        selected: 2,
        isTabShow: isTabShow,
      })
    }
  },

  showLoading() {
    this.setData({
      isLoading: true
    });
  },

  hideLoading() {
    this.setData({
      isLoading: false
    });
  },

  onShow: function () {
    // 检查数据是否已加载
    if (app.globalData.AppLoveData) {
      this.setData({
        like: app.globalData.AppLoveData,
      });
      this.hideLoading();
      this.tabBar();

    } else {
      // 否则请求数据
      this.setData({
        isLoading: true
      });
      app.getLikeInfo((data) => {
        // 请求成功后，更新页面数据
        this.setData({
          like: data,
        });
        this.hideLoading();
        this.tabBar();
      });
    }

    // 留言信息
    app.getLyInfo('index', (data) => {
      this.setData({
        leaving: data
      });
    });

    // 点点滴滴信息
    app.getWzInfo((data) => {
      this.setData({
        // 过滤加密数据
        wenz: data.filter(item => !item.encryptionSwitch)
      });
    });

    this.getLoveDay();
  },

  onReady: function () {
    this.getLoveDay();
  },

  showPopupLayer(e) {
    let DayData = {
      title: e.currentTarget.dataset.daytext,
      Type: e.currentTarget.dataset.daytype,
      LovePast: e.currentTarget.dataset.lovepast,
      Loveday: e.currentTarget.dataset.loveday,
      LoveFuture: e.currentTarget.dataset.lovefuture,
      FutureLoveday: e.currentTarget.dataset.futureloveday,
    }
    this.setData({
      showPopup: true,
      DayCompleteData: DayData,
      animationClass: 'slideDown',
      animationClassMask: 'fadeIn'
    });
  },

  closePopup() {
    this.setData({
      animationClass: 'slideUp',
      animationClassMask: 'fadeOut'
    });
    setTimeout(() => {
      this.setData({
        showPopup: false,
        animationClass: '',
      });
    }, 500);
  },

  // 分享好友
  onShareAppMessage() {
    return {
      title: this.data.like.share_title,
      imageUrl: this.data.like.share_cover,
      path: "/pages/index/index"
    }
  },

  // 分享朋友圈
  onShareTimeline() {
    return {
      title: this.data.like.share_title,
      imageUrl: this.data.like.share_cover
    };
  }


});