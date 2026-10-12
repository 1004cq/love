// components/NavBar/NavBar.js

const app = getApp();

Component({
  properties: {
    title: {
      type: String,
      value: "LGNewUi"
    },
    // 接收父组件传递
  },

  data: {
    GBAppTitle: '',
    navBarHeight: 0, // 用来存储导航栏高度
  },

  lifetimes: {
    attached() {
      // 组件初始化时获取导航栏高度
      let navBarHeight = app.globalData.navBarHeight;

      if (!navBarHeight) {
        // 如果全局没有缓存，获取系统信息
        try {
          const systemInfo = wx.getSystemInfoSync();
          navBarHeight = systemInfo.statusBarHeight + 44 + 19; // 状态栏 + 默认导航栏 + 间隙
          app.globalData.navBarHeight = navBarHeight;
          wx.setStorageSync('navBarHeight', navBarHeight);
        } catch (error) {
          console.error('获取系统信息失败', error);
        }
      }

      // 更新组件的导航栏高度
      this.setData({
        navBarHeight
      });
    }
  },
  methods: {
  }
});