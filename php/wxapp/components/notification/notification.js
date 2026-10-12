Component({
  properties: {
    message: {
      type: String,
      value: ''
    },
    icon: {
      type: String,
      value: 'icon-jiantouarrow591' // 默认图标
    },
    backgroundColor: {
      type: String,
      value: '#323232' // 默认背景色
    },
    TopPx: {
      type: String,
      value: '110px' // 顶部距离
    },
    duration: {
      type: Number,
      value: 3000 // 默认显示时长（毫秒）
    }
  },
  data: {
    visible: false, // 是否显示通知
    animation: null, // 动画实例
    timeoutId: null // 当前计时器的 ID
  },
  methods: {
    show(message, options = {}) {
      const {
        icon,
        backgroundColor,
        duration,
        TopPx
      } = options;

      // 如果当前有计时器在运行，清除它
      if (this.data.timeoutId) {
        clearTimeout(this.data.timeoutId);
        this.setData({
          timeoutId: null
        });
      }

      this.setData({
        visible: true,
        TopPx: TopPx || this.data.TopPx,
        message: message || this.data.message,
        icon: icon || this.data.icon,
        backgroundColor: backgroundColor || this.data.backgroundColor
      });

      // 显示时的动画
      const animation = wx.createAnimation({
        duration: 300, // 动画持续时间
        timingFunction: 'ease'
      });
      animation.opacity(1).step(); // 从透明变为可见
      this.setData({
        animation: animation.export()
      });

      // 设置一个新的计时器
      const timeoutId = setTimeout(() => {
        this.hide();
      }, duration || this.data.duration);

      // 存储计时器 ID
      this.setData({
        timeoutId
      });
    },

    hide() {
      // 如果当前有计时器，清除它
      if (this.data.timeoutId) {
        clearTimeout(this.data.timeoutId);
        this.setData({
          timeoutId: null
        });
      }

      // 隐藏时的动画
      const animation = wx.createAnimation({
        duration: 300,
        timingFunction: 'ease'
      });
      animation.opacity(0).step(); // 渐隐
      this.setData({
        animation: animation.export()
      });

      // 动画结束后彻底隐藏
      setTimeout(() => {
        this.setData({
          visible: false
        });
      }, 300); // 动画时长
    }
  }
});