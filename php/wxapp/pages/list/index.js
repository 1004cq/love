const app = getApp();
let APIURL = app.globalData.GlobalLink;

Page({
   data: {
      lovelist: [null],
      AppLoveData: null,
      ListTypeApi: APIURL + 'search_list.php',
      tcimg: "",
      currentUrl: [],
      showPop: false,
      animationData: {},
      currentPage: 0,
      isLoading: true,
      searchValue: '',
      activeTab: 'All',
   },

   onInputSearch(e) {
      this.setData({
         searchValue: e.detail.value
      });
   },

   SubmitQuery() {
      const notification = this.selectComponent('#notification');
      let searchValue = this.data.searchValue;
      if (searchValue) {
         this.getListType(searchValue)
         this.showLoading();
      } else {
         notification.show('请先输入关键词！', {
            icon: 'icon-cuowu',
            backgroundColor: '#FF6666',
            duration: 1500
         });
      }
   },

   switchTab(e) {
      const tabId = e.currentTarget.dataset.id;
      this.setData({
         activeTab: tabId,
      });
      // console.log(`当前选中的 Tab: ${tabId}`);
      this.showLoading();
      this.getListType(tabId)
   },

   getListType: function (tabId) {
      const that = this
      const notification = this.selectComponent('#notification');

      wx.request({
         url: that.data.ListTypeApi,
         method: 'POST',
         data: {
            search_info: tabId,
            search_Type: 1,
         },
         header: {
            'Content-Type': 'application/x-www-form-urlencoded'
         },
         success: function (res) {
            console.log(res);
            if (res.data && Array.isArray(res.data) && res.data.length >= 1) {
               that.setData({
                  lovelist: res.data.reverse()
               })
               notification.show('正在为您展示相应数据...', {
                  icon: 'icon-AppIcon',
                  backgroundColor: '#99999999',
                  duration: 1500
               });
            } else {
               notification.show('未查询到相关数据...', {
                  icon: 'icon-cuowu',
                  backgroundColor: '#FF6666',
                  duration: 1500
               });
            }
            that.hideLoading();
         },
         fail: function (err) {
            console.log(err)
         }
      })
   },

   // 显示遮罩层并添加滑动动画
   showModal(e) {
      const listUrl = e.currentTarget.dataset.url;

      const animation = wx.createAnimation({
         duration: 500, // 动画时长
         timingFunction: 'ease', // 动画曲线
      });

      // 初始设置透明度为0并将弹窗位于屏幕顶部
      animation.opacity(0).translateY(-100).step();

      this.setData({
         currentUrl: listUrl,
         showPop: true, // 显示弹出层
         animationData: animation.export(),
      });

      // 延迟后再执行透明度动画 + 弹窗滑动到可视区域
      setTimeout(() => {
         animation.opacity(1).translateY(0).step(); // 滑动到正常位置并显示
         this.setData({
            animationData: animation.export(),
         });
      }, 50); // 延迟50ms后开始执行滑入动画
   },

   // 隐藏遮罩层并添加滑动动画
   hideModal() {
      const animation = wx.createAnimation({
         duration: 300, // 动画时长
         timingFunction: 'ease', // 动画曲线
      });

      // 延迟后开始执行透明度动画 + 弹窗滑出顶部
      setTimeout(() => {
         animation.opacity(0).translateY(-100).step(); // 滑动出屏幕并透明度为0
         this.setData({
            animationData: animation.export(),
         });

         // 延迟300ms后隐藏弹窗
         setTimeout(() => {
            this.setData({
               showPop: false,
            });
         }, 500); // 延迟300ms后隐藏弹窗

      }, 50); // 延迟50ms后执行渐隐滑动动画
   },


   previewimg: function (e) {
      let that = this;
      wx.previewImage({
         current: e.currentTarget.dataset.src, //当前图片地址
         urls: [e.currentTarget.dataset.src], //所有要预览的图片的地址集合 数组形式
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

      app.getListInfo((data) => {
         this.setData({
            lovelist: data,
         });
         this.hideLoading()
      });

   },

   tabBar() {
      if (typeof this.getTabBar === 'function' && this.getTabBar()) {
         this.getTabBar().setData({
            selected: 4
         })
      }
   },

   // 分享好友
   onShareAppMessage() {
      return {
         title: this.data.AppLoveData.share_title,
         imageUrl: this.data.AppLoveData.share_cover,
         path: "/pages/list/index"
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