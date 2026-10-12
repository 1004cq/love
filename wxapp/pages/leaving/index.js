const app = getApp();
let APIURL = app.globalData.GlobalLink;
Page({
   data: {
      GetQQInfoapi: APIURL + "admin/infoService.php",
      PostMsgApi: APIURL + "admin/leavingPost.php",
      leaving: [null],
      showPop: false,
      animationData: {},
      bgOpTime: false,
      disableTouch: null,
      isSelected: false,
      isLoading: true,
      MsgQQ: '',
      nickname: '',
      message: '',
      MsgCity: '',
      MsgUserPic: 'https://q1.qlogo.cn/g?b=qq&nk=1234567&s=100',
      AppLoveData: ''
   },

   onInputQQ(e) {
      this.setData({
         MsgQQ: e.detail.value
      });
   },

   // 提交留言
   submitMessage: function () {
      const {
         MsgQQ,
         nickname,
         message
      } = this.data;

      // 获取标识
      const currentSelected = this.data.isSelected;

      // 获取违禁词列表
      const prohibitedWords = this.data.AppLoveData.lanjiezf.split('');

      // 判断值是否为空
      if (!MsgQQ.trim() && !currentSelected) {
         wx.vibrateLong();
         wx.showToast({
            title: '请输入QQ号',
            icon: 'none',
            duration: 2000,
         });
         return;
      }
      if (!nickname.trim()) {
         wx.vibrateLong();
         wx.showToast({
            title: '请输入昵称',
            icon: 'none',
            duration: 2000,
         });
         return;
      }
      if (!message.trim()) {
         wx.vibrateLong();
         wx.showToast({
            title: '请输入留言内容',
            icon: 'none',
            duration: 2000,
         });
         return;
      }

      const hasProhibitedWord = prohibitedWords.some(word => message.includes(word));
      if (hasProhibitedWord) {
         wx.vibrateLong();
         wx.showToast({
            title: '留言内容包含违禁词',
            icon: 'none',
            duration: 2000,
         });
         return;
      }

      this.showLoading();

      const that = this;

      // 发起POST请求
      wx.request({
         url: this.data.PostMsgApi, // 替换为实际接口地址
         method: 'POST',
         data: {
            name: nickname.trim(),
            qq: MsgQQ.trim(),
            text: message.trim(),
         },
         header: {
            'Content-Type': 'application/x-www-form-urlencoded', // 根据后端要求设置
         },
         success: function (res) {
            console.log(res);
            if (res.data.Status) {
               wx.showToast({
                  title: '留言成功！',
                  icon: 'success',
                  duration: 2000,
               });

               setTimeout(() => {
                  that.hideModal();
               }, 1000);

               // 清空输入框
               that.setData({
                  MsgQQ: '',
                  nickname: '',
                  message: '',
                  MsgCity: '',
                  MsgUserPic: 'https://q1.qlogo.cn/g?b=qq&nk=1234567&s=100',
               });
            } else {
               wx.showToast({
                  title: res.data.message || '留言失败，请重试！',
                  icon: 'none',
                  duration: 2000,
               });
            }
         }.bind(this),
         fail: function (err) {
            console.log(err);
            wx.showToast({
               title: '网络错误，请重试！',
               icon: 'none',
               duration: 2000,
            });
         },
         complete: function () {
            that.hideLoading()
         },
      });
   },

   // 处理昵称输入框内容的变化
   onInputNickname(e) {
      this.setData({
         nickname: e.detail.value
      });
   },

   // 处理留言输入框内容的变化
   onInputMessage(e) {
      this.setData({
         message: e.detail.value
      });
   },

   toggleSwitch: function () {
      const currentSelected = this.data.isSelected;
      const RandomUrlFromStr = this.data.AppLoveData.anonymousArr;
      this.setData({
         isSelected: !currentSelected
      });
      if (this.data.isSelected) {
         let resRandomUrl = this.getRandomUrlFromStr(RandomUrlFromStr)
         this.setData({
            MsgUserPic: resRandomUrl,
            MsgQQ: 'anon',
            nickname: '匿名',
         });
         // console.log(resRandomUrl);
      } else {
         this.setData({
            MsgUserPic: 'https://q1.qlogo.cn/g?b=qq&nk=1234567&s=100',
            MsgQQ: '',
            nickname: '',
         });
      }
   },

   showNotification(e) {
      const messageCity = e.currentTarget.dataset.city;
      const notification = this.selectComponent('#notification');
      notification.show(messageCity, {
         icon: 'icon-guishudi',
         backgroundColor: '#99999999',
         duration: 2200
      });
   },

   // 显示遮罩层
   showModal() {
      var animation = wx.createAnimation({
         duration: 500,
         timingFunction: 'ease'
      })
      animation.translateY(500).step()
      this.setData({
         animationData: animation.export(),
         showPop: true,
         disableTouch: true,
         bgOpTime: false
      })
      setTimeout(() => {
         animation.translateY(0).step()
         this.setData({
            animationData: animation.export(),
         })
      }, 50)
   },

   // 隐藏遮罩层
   hideModal() {
      var animation = wx.createAnimation({
         duration: 1000,
         timingFunction: 'linear'
      })
      animation.translateY(1000).step()
      this.setData({
         animationData: animation.export(),
         disableTouch: false,
         bgOpTime: true
      })
      setTimeout(() => {
         animation.translateY(0).step()
         this.setData({
            animationData: animation.export(),
            showPop: false
         })
      }, 1000)
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

   getRandomQuote: function () {
      this.showLoading();
      const notification = this.selectComponent('#notification');
      this.fetchData('', {}).then(data => {
         // 处理成功的数据
         this.setData({
            message: data.randomContent,
         });
         this.hideLoading()
         notification.show(data.message, {
            icon: 'icon-AppIcon',
            backgroundColor: '#99999999',
            duration: 1500
         });
      }).catch(err => {
         // 处理错误信息
         notification.show(err, {
            icon: 'icon-cuowu',
            backgroundColor: '#FF6666',
            duration: 1500
         });
         this.hideLoading()
      });
   },

   // 获取QQ信息
   getQQData: function () {
      let Msgqq = this.data.MsgQQ;
      const notification = this.selectComponent('#notification');

      if (Msgqq == '') {
         notification.show('请先输入QQ号码！', {
            icon: 'icon-cuowu',
            backgroundColor: '#FF6666',
            duration: 1500
         });
         return;
      }
      this.showLoading()
      this.fetchData('qq', {
         qq: Msgqq
      }).then(data => {
         // 处理成功的数据
         this.setData({
            nickname: data.data.nick,
            MsgCity: data.city.replace(/\s*·\s*/g, '·'),
            MsgUserPic: data.data.avatar
         });
         notification.show(data.message, {
            icon: 'icon-AppIcon',
            backgroundColor: '#99999999',
            duration: 1500
         });
         this.hideLoading()
      }).catch(err => {
         notification.show(err, {
            icon: 'icon-cuowu',
            backgroundColor: '#FF6666',
            duration: 1500
         });
         this.hideLoading()
      });
   },

   // 通用的请求方法
   fetchData: function (action, data) {
      const that = this;
      return new Promise((resolve, reject) => {
         wx.request({
            url: that.data.GetQQInfoapi,
            method: 'POST',
            data: {
               action: action,
               ...data
            },
            header: {
               'Content-Type': 'application/x-www-form-urlencoded'
            },
            success: function (res) {
               console.log(res);
               if (res.data.Status) {
                  resolve(res.data);
               } else {
                  reject(res.data.message);
               }
            },
            fail: function (err) {
               reject(err);
            }
         });
      });
   },

   // 获取匿名头像
   getRandomUrlFromStr(inputStr) {
      if (!inputStr || typeof inputStr !== 'string') {
         console.error("数据无效或不是字符串");
         return '';
      }
      const urlArray = inputStr.split('\n').map(url => url.trim());
      const validUrls = urlArray.filter(url => url !== '');
      if (validUrls.length === 0) {
         console.error("无效的 URL");
         return '';
      }
      const randomIndex = Math.floor(Math.random() * validUrls.length);
      return validUrls[randomIndex];
   },

   onShow: function () {
      this.tabBar();

      app.getLikeInfo((data) => {
         this.setData({
            AppLoveData: data,
         });
      });

      // 留言信息
      app.getLyInfo('', (data) => {
         this.setData({
            leaving: data,
         });
         this.hideLoading()
      });
   },

   tabBar() {
      if (typeof this.getTabBar === 'function' && this.getTabBar()) {
         this.getTabBar().setData({
            selected: 1
         })
      }
   },

   // 分享好友
   onShareAppMessage() {
      return {
         title: this.data.AppLoveData.share_title,
         imageUrl: this.data.AppLoveData.share_cover,
         path: "/pages/leaving/index"
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