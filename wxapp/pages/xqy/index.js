var WxParse = require('../wxParse/wxParse.js');
const app = getApp();
let APIURL = app.globalData.GlobalLink;
let GLBsign = app.globalData.Playersign;

Page({
   data: {
      article: [null],
      list: [null],
      xqy: null,
      xqyname: "渲染中",
      xqytime: "渲染中",
      xqytitle: "渲染中",
      TopRefresh: false,
      xqyId: null,
      Playersign: GLBsign,
      innerAudioContext: null,
      MusicCover: '../../images/MusicD.png',
      isLoading: true
   },

   RefreshStorage: function () {
      let KeyData = 'Article_' + this.data.xqyId
      wx.removeStorageSync(KeyData);
      wx.showToast({
         title: '刷新成功',
         icon: 'success',
         duration: 2000,
         success: function () {
            setTimeout(function () {
               wx.navigateBack({
                  delta: 1
               });
            }, 1500);
         }
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

   tabBar() {
      if (typeof this.getTabBar === 'function' && this.getTabBar()) {
         this.getTabBar().setData({
            selected: 0
         })
      }
   },

   AOUPlay: function (e) {
      let MusicUrl = e.currentTarget.dataset.src;
      let MusicCover = e.currentTarget.dataset.cover;
      let innerAudioContext = this.data.innerAudioContext;

      let that = this;

      if (MusicUrl == '') {
         wx.showToast({
            title: '音乐Url为空',
            icon: 'none',
            duration: 2000
         });
         that.setData({
            Playersign: false
         });
         if (innerAudioContext) {
            innerAudioContext.pause();
         }
         return false;
      }

      if (!innerAudioContext) {
         innerAudioContext = wx.createInnerAudioContext();
         this.setData({
            innerAudioContext: innerAudioContext
         });
      }

      wx.setInnerAudioOption({
         obeyMuteSwitch: false,
      });

      // 如果点击的是不同的音乐
      if (MusicUrl !== innerAudioContext.src) {
         innerAudioContext.pause(); // 停止当前音乐
         innerAudioContext.src = MusicUrl; // 切换到新音乐
         innerAudioContext.seek(0); // 从头开始播放
         innerAudioContext.play();
         this.setData({
            Playersign: true,
            MusicCover: MusicCover
         });
         wx.showToast({
            title: '正在播放音乐',
            icon: 'none',
            duration: 1500
         });
      } else {
         // 如果点击的是当前音乐，则根据状态切换播放或暂停
         if (!this.data.Playersign) {
            innerAudioContext.play();
            this.setData({
               Playersign: true,
               MusicCover: MusicCover
            });
            wx.showToast({
               title: '正在播放音乐',
               icon: 'none',
               duration: 1500
            });
         } else {
            innerAudioContext.pause();
            this.setData({
               Playersign: false,
               MusicCover: MusicCover
            });
            wx.showToast({
               title: '音乐播放暂停',
               icon: 'none',
               duration: 1500
            });
         }
      }

      innerAudioContext.onError((err) => {
         console.log(err.errCode);
         if (err.errCode == 10004 || err.errCode == 10003) {
            that.setData({
               Playersign: false
            });
            innerAudioContext.pause();
            wx.showToast({
               title: '音乐Url已失效',
               icon: 'none',
               duration: 1500
            });
         }
      });

      innerAudioContext.onPlay(() => {
         console.log('开始播放');
      });

      innerAudioContext.onStop((res) => {
         console.log('停止播放');
      });
   },

   MusicSP: function (e) {
      let Playersign = this.data.Playersign;
      let innerAudioContext = this.data.innerAudioContext;
      if (Playersign) {
         innerAudioContext.pause();
         wx.showToast({
            title: '音乐播放暂停',
            icon: 'none',
            duration: 1500
         });
         this.setData({
            Playersign: false
         })
      }

   },

   onUnload: function () {
      let innerAudioContext = this.data.innerAudioContext;
      let Playersign = this.data.Playersign;
      if (Playersign) {
         innerAudioContext.pause();
         innerAudioContext.stop()
         innerAudioContext.autoplay = false;
      }
   },

   onHide: function () {
      this.onUnload();
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

   onLoad: function (options) {
      app.getLikeInfo((data) => {
         this.setData({
            AppLoveData: data,
         });
      });

      app.getWzInfo((data) => {
         this.setData({
            list: data,
         });
         this.hideLoading()
      });

      setTimeout(() => {
         let state = Boolean(options.state);
         // console.log(state);
         var dqdx = this
         if (state) {
            // console.log(options.id);
            // 加载本地存储中的数据
            let storedData = wx.getStorageSync('Article_' + options.id);
            dqdx.setData({
               TopRefresh: true,
               xqy: storedData.articletext,
               xqyname: storedData.articlename,
               xqytime: storedData.articletime,
               xqytitle: storedData.articletitle,
               xqyId: options.id
            })
            // console.log('从本地存储中获取的数据：', storedData.articletext);
         } else {
            dqdx.data.list.forEach(item => {
               if (options.id == item.id) {
                  // console.log(item.id)
                  dqdx.setData({
                     xqy: item.articletext,
                     xqyname: item.articlename,
                     xqytime: item.articletime,
                     xqytitle: item.articletitle,
                  })
               }
            })
         }

         var article = dqdx.data.xqy;
         // console.log("数据打印"+article)
         article = article.replace(/\n/g, '<text class="custom-br"></text>');
         WxParse.wxParse('article', 'html', article, dqdx, 0);

      }, 1000)
   },

   onShow: function () {
      this.tabBar();
   },

   // 分享好友
   onShareAppMessage() {
      return {
         title: this.data.xqytitle,
         imageUrl: this.data.AppLoveData.share_cover,
         path: "/pages/xqy/index?id=" + this.data.xqyId
      }
   },

   // 分享朋友圈
   onShareTimeline() {
      return {
         title: this.data.xqytitle,
         imageUrl: this.data.AppLoveData.share_cover
      };
   }

});