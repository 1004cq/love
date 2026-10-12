//获取应用实例
var WxParse = require('../wxParse/wxParse.js');
const app = getApp();
let APIURL = app.globalData.GlobalLink;

Page({
   data: {
      CheckPwApi: APIURL + "api/lookPw.php",
      article: [null],
      list: [null],
      ShowPwCard: null,
      currentId: null,
      inputValue: null,
      AppLoveData: '',
      isLoading: true,
   },

   hideModal(e) {
      this.setData({
         ShowPwCard: null,
      })
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

   getPage: function (e) {
      let id = e.currentTarget.dataset.id;
      let encryptionSwitch = e.currentTarget.dataset.encryptionswitch
      //  console.log(encryptionSwitch)
      if (encryptionSwitch) {
         let storedData = wx.getStorageSync('Article_' + id);
         if (storedData.password && storedData.Token) {
            wx.navigateTo({
               url: '/pages/xqy/index?id=' + id + '&state=true',
            })
            return
         }
         this.setData({
            ShowPwCard: true,
            currentId: id
         })
         return false
      }
      wx.navigateTo({
         url: '/pages/xqy/index?id=' + id,
      })
   },

   inputChange: function (e) {
      this.setData({
         inputValue: e.detail.value
      });
   },

   CheckPw: function () {
      let Id = this.data.currentId;
      let Pw = this.data.inputValue;

      if (Pw == null) {
         wx.showToast({
            title: '错误 请先填写密码',
            icon: 'none',
            duration: 2000
         });
         return false
      }
      wx.showLoading({
         title: '验证中',
      })
      var that = this
      wx.request({
         url: that.data.CheckPwApi,
         data: {
            id: Id,
            encrypCode: Pw,
            encrypType: "article"
         },
         success: function (res) {
            // console.log(res);
            //   console.log(res.data[0]);
            let ResData = res.data[0]
            let AID = ResData.ResId
            if (ResData.state) {
               // 如果通过密码验证 则将密码和ID Token临时存到缓存
               that.setData({
                  ShowPwCard: null,
                  inputValue: ''
               })
               ResData.password = Pw;
               wx.setStorageSync('Article_' + AID, ResData);
               wx.showToast({
                  title: '成功',
                  icon: 'success',
                  duration: 2000
               });
               wx.navigateTo({
                  url: '/pages/xqy/index?id=' + AID + '&state=true',
               })
            } else {
               wx.showToast({
                  title: '密码错误 请重试！',
                  icon: 'none',
                  duration: 2000
               });
            }
            wx.hideLoading()
         },
         fail: function (err) {
            console.log(err)
            wx.hideLoading()
            wx.showLoading({
               title: '渲染失败',
            })
         }
      })
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
      wx.navigateTo({
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

   onShow: function () {
      this.tabBar();

      app.getLikeInfo((data) => {
         this.setData({
            AppLoveData: data,
         });
      });

      app.getWzInfo((data) => {
         this.setData({
            wenz: data,
            list: data,
         });
         this.hideLoading()
      });
   },

   // 分享好友
   onShareAppMessage() {
      return {
         title: this.data.AppLoveData.share_title,
         imageUrl: this.data.AppLoveData.share_cover,
         path: "/pages/little/index"
      }
   },

   // 分享朋友圈
   onShareTimeline() {
      return {
         title: this.data.AppLoveData.share_title,
         imageUrl: this.data.AppLoveData.share_cover
      };
   }

});
