import TouchEvent from "./utils/touchEvent";

Component({
  properties: {
    data: Array,
    back: {
      type: String,
      value: '#fff'
    }
  },

  options: {
    multipleSlots: true
  },

  data: {
    isLoading: false,
    swiperData: [],
    swiperCurIndex: 0,
    slideClass: "",
    lockSwipe: false,
    LoveListPhoto: []
  },

  lifetimes: {
    created() {
      const notification = this.selectComponent('#notification');

      this.data.swiperData = this.data.swiperData.concat(this.data.data);

      new TouchEvent(this, "touchCard", {
        swipe: evt => {
          //在touch结束触发，evt.direction代表滑动的方向 ['Up','Right','Down','Left']
          if (evt.direction === "Up" || evt.direction === "Left") {
            this.next(evt);
          };
          if (evt.direction === "Down" || evt.direction === "Right") {
            this.prev(evt);
          }
        },
        tap: (evt) => {
          console.log("Tap event triggered", evt);
          console.log("数组为", this.data.swiperData);
          console.log("1111新数组为", this.data.LoveListPhoto);


          const image = evt.currentTarget.dataset.image.url;

          console.log("当前ID:", image.url);

          // 调用微信内置的图片查看器
          wx.previewImage({
            current: image, // 当前显示的图片链接
            urls: [image],
            success() {
              console.log("预览成功");
            },
            fail(err) {
              console.error("预览失败", err);
              notification.show('您的操作速度太快啦，请稍等片刻！', {
                icon: 'icon-cuowu',
                backgroundColor: '#ff827b',
                TopPx: '90px',
                duration: 1500
              });
            }
          });
        }
      });
    },

    attached() {
      this.setData({
        swiperData: this.data.data,
        LoveListPhoto: this.data.data,
      })
    }
  },

  methods: {

    next(e) {
      if (this.data.lockSwipe) return;
      this.data.lockSwipe = true;
      if (-this.data.swiperCurIndex >= this.data.swiperData.length - 1)
        return (this.data.lockSwipe = false);

      if (-this.data.swiperCurIndex >= this.data.swiperData.length - 3) {
        this.loadMore();
      }

      const index = e.currentTarget.dataset["index"];
      this.setData({
        ["swiperData[" + index + "].slideClass"]: " ani-slide-up"
      }, () => {
        this.setData({
          swiperCurIndex: --this.data.swiperCurIndex
        });
      })

      setTimeout(() => {
        this.data.lockSwipe = false;
        this.setData({
          ["swiperData[" + index + "].slideClass"]: ""
        });
      }, 590);
    },

    prev(e) {
      const index = e.currentTarget.dataset["index"] - 1;
      if (this.data.lockSwipe || index < 0) return;
      this.data.lockSwipe = true;
      this.setData({
        ["swiperData[" + index + "].slideClass"]: " ani-slide-down",
        swiperCurIndex: ++this.data.swiperCurIndex
      });

      setTimeout(() => {
        this.data.lockSwipe = false;
        this.setData({
          ["swiperData[" + index + "].slideClass"]: ""
        });
      }, 590);
    },

    loadMore() {
      this.isLoading = true;
      this.triggerEvent("loadmore", {
        addToList: this.addToList.bind(this)
      });
    },

    addToList(list) {
      this.isLoading = false;
      this.setData({
        swiperData: this.data.swiperData.concat(list)
      });
    }
  }
});