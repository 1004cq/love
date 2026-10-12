const app = getApp();

Component({
  /**
   * 组件的属性列表
   */
  properties: {
    LoversData: {
      type: Object,
      value: {},
    }
  },

  /**
   * 组件的初始数据
   */
  data: {
    currentTime: "",
    CtplThreeData: {},
    CtplTowData: {},
    chatUserName: ''
  },

  lifetimes: {
    attached() {
      this.getCtplThreeInfo();
      this.getCtplTowData();

      const now = new Date();
      const formattedTime = this.formatTime(now);
      this.setData({
        currentTime: formattedTime
      });
    }
  },

  /**
   * 组件的方法列表
   */
  methods: {
    handleItemTap: function (e) {
      const title = e.currentTarget.dataset.title;
      const content = e.currentTarget.dataset.content;
      const time = e.currentTarget.dataset.time;
      const cover = e.currentTarget.dataset.cover;

      this.triggerEvent('itemTap', {
        title,
        content,
        cover,
        time
      });
    },

    getCtplThreeInfo() {
      app.getCtplThreeInfo((data) => {
        // console.log(data);
        this.setData({
          CtplThreeData: data.messages,
          chatUserName: data.chatUserName,
        });
      });
    },

    getCtplTowData() {
      app.getCtplTowInfo((data) => {
        const convertedData = data.map((item) => {
          if (item.price > 10000) {
            item.price = (item.price / 10000).toFixed(1) + 'w';
          }
          return item;
        });
        this.setData({
          CtplTowData: convertedData,
        });
      });
    },

    formatTime(date) {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, "0");
      const day = String(date.getDate()).padStart(2, "0");
      const hours = String(date.getHours()).padStart(2, "0");
      const minutes = String(date.getMinutes()).padStart(2, "0");
      return `${year}-${month}-${day} ${hours}:${minutes}`;
    },

    calculateDays: function (startTime) {
      const startDate = new Date(startTime);
      const today = new Date();
      const differenceInMillis = today.getTime() - startDate.getTime();
      const days = Math.floor(differenceInMillis / (24 * 60 * 60 * 1000)); // 转换为天
      return days;
    },
  }
})