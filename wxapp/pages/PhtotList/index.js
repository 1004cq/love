const app = getApp();
let APIURL = app.globalData.GlobalLink;

Page({
   data: {
      photo_list: APIURL + "api/select_photo.php",
      photo_data: null,
      codeid: null,
      Cover: null,
      ShowCard: false,
      TopRefresh: false,
      leftColumnData: [],
      rightColumnData: [],
      isLoading: true,
      pname: '',
      ptitle: '',
      avatar: '',
   },

   previewimg: function (e) {
      wx.previewImage({
         current: e.currentTarget.dataset.src, //当前图片地址
         urls: [e.currentTarget.dataset.src], //所有要预览的图片的地址集合 数组形式
      })
   },

   RefreshStorage: function () {
      let KeyData = 'Photo_' + this.data.codeid
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
      let code = options.code;
      let PhotoCover = options.cover;
      let ptitle = options.ptitle;
      let pname = options.pname;
      let avatar = options.pavatar;
      let state = Boolean(options.state);
      // console.log(PhotoCover);

      this.setData({
         codeid: code,
         Cover: PhotoCover,
         pname,
         ptitle,
         avatar,
      });

      this.showLoading();
      if (state) {
         // 加载本地存储中的数据
         let storedData = wx.getStorageSync('Photo_' + code);
         let PhotoListData = storedData.PhotoList;
         if (PhotoListData) {
            this.processPhotoData(PhotoListData);
            this.setData({
               TopRefresh: true,
               photo_data: PhotoListData,
               ShowCard: true
            })
            this.hideLoading()
         } else {
            this.hideLoading()
            this.setData({
               TopRefresh: true,
               ShowCard: true
            })
         }
      } else {
         setTimeout(() => {
            const that = this
            wx.request({
               url: that.data.photo_list,
               data: {
                  code: code
               },
               success: function (res) {
                  that.processPhotoData(res.data);
                  that.setData({
                     photo_data: res.data,
                     ShowCard: true
                  })
               },
               fail: function (err) {
                  console.log(err)
               },
               complete: function () {
                  that.hideLoading()
               }
            })
         }, 1000)
      }
   },

   processPhotoData: function (data) {
      let leftColumnData = [];
      let rightColumnData = [];

      if (!data) {
         return;
      }

      data.forEach((item, index) => {
         if (index % 2 === 0) {
            leftColumnData.push(item);
         } else {
            rightColumnData.push(item);
         }
      });


      this.setData({
         leftColumnData: leftColumnData.reverse(),
         rightColumnData: rightColumnData.reverse()
      });
   },

   /**
    * 生命周期函数--监听页面初次渲染完成
    */
   onReady: function () {

   },

   /**
    * 生命周期函数--监听页面显示
    */
   onShow: function () {

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

   // 分享好友
   onShareAppMessage() {
      return {
         title: this.data.ptitle,
         imageUrl: this.data.Cover,
         path: '/pages/PhtotList/index?code=' + this.data.codeid + '&cover=' + this.data.Cover + '&ptitle=' + this.data.ptitle + '&pname=' + this.data.pname + '&pavatar=' + this.data.avatar
      }
   },

   // 分享朋友圈
   onShareTimeline() {
      return {
         title: this.data.ptitle,
         imageUrl: this.data.Cover,
      };
   }
})