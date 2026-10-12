// pages/detail/detail.js
Page({
  data: {
    title: '',
    content: '',
    time: '',
    cover: ''
  },

  onLoad: function (options) {
    // console.log(options);
    this.setData({
      title: decodeURIComponent(options.title),
      content: decodeURIComponent(options.content),
      time: decodeURIComponent(options.time),
      cover: decodeURIComponent(options.detailcover)
    });
  },

  handlerGobackClick(delta) {
    const pages = getCurrentPages();
    if (pages.length >= 2) {
      wx.navigateBack({
        delta: delta
      });
    } else {
      wx.navigateTo({
        url: '/pages/index/index'
      });
    }
  },

  handlerGohomeClick() {
    wx.switchTab({
      url: '/pages/index/index'
    });
  },
});