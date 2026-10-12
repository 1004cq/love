const app = getApp();
let APIURL = app.globalData.GlobalLink;

Page({
  data: {
    CheckPwApi: APIURL + "api/lookPw.php",
    xcimg: [null],
    ShowPwCard: null,
    currentCode: null,
    inputValue: null,
    AppLoveData: '',
    isLoading: true
  },

  previewimg: function (e) {
    let that = this;
    wx.previewImage({
      current: e.currentTarget.dataset.src,
      urls: [e.currentTarget.dataset.src],
    })
  },

  hideModal(e) {
    this.setData({
      ShowPwCard: null,
    })
  },

  inputChange: function (e) {
    this.setData({
      inputValue: e.detail.value
    });
  },

  CheckPw: function () {
    let Id = this.data.currentCode;
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
        encrypType: "loveImg"
      },
      success: function (res) {
        console.log(res.data);
        // console.log(res.data[0]);
        let ResData = res.data[0]
        let AID = ResData.ResId
        if (ResData.state) {
          // 如果通过密码验证 则将当前密码和相册ID Token临时存到缓存
          that.setData({
            ShowPwCard: null,
            inputValue: ''
          })
          ResData.password = Pw;
          wx.setStorageSync('Photo_' + AID, ResData);
          wx.showToast({
            title: '成功',
            icon: 'success',
            duration: 2000
          });
          wx.navigateTo({
            url: '/pages/PhtotList/index?code=' + AID + '&state=true',
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

  goPhotoList: function (e) {
    let code = e.currentTarget.dataset.code
    let PhotoCoverUrl = e.currentTarget.dataset.cover
    let ptitle = e.currentTarget.dataset.ptitle
    let pname = e.currentTarget.dataset.pname
    let avatar = e.currentTarget.dataset.pavatar

    // console.log(code);
    let encryptionSwitch = e.currentTarget.dataset.encryptionswitch

    // console.log(encryptionSwitch);
    if (encryptionSwitch) {
      let storedData = wx.getStorageSync('Photo_' + code);
      if (storedData.password && storedData.Token) {
        wx.navigateTo({
          url: '/pages/PhtotList/index?code=' + code + '&state=true',
        })
        return
      }
      this.setData({
        ShowPwCard: true,
        currentCode: code
      })
      return false
    }

    wx.navigateTo({
      url: '/pages/PhtotList/index?code=' + code + '&cover=' + PhotoCoverUrl + '&ptitle=' + ptitle + '&pname=' + pname + '&pavatar=' + avatar,
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
  onShow: function () {
    this.tabBar();
    app.getLikeInfo((data) => {
      this.setData({
        AppLoveData: data,
      });
    });

    app.getImgInfo((data) => {
      this.setData({
        xcimg: data,
      });
      this.hideLoading()
    });
  },
  tabBar() {
    if (typeof this.getTabBar === 'function' && this.getTabBar()) {
      this.getTabBar().setData({
        selected: 3
      })
    }
  },

   // 分享好友
   onShareAppMessage() {
      return {
         title: this.data.AppLoveData.share_title,
         imageUrl: this.data.AppLoveData.share_cover,
         path: "/pages/loveImg/index"
      }
   },

   // 分享朋友圈
   onShareTimeline() {
      return {
         title: this.data.AppLoveData.share_title,
         imageUrl: this.data.AppLoveData.share_cover
      };
   }

})