// custom-tab-bar/index.js
Component({
   /**
    * 组件的属性列表
    */
   properties: {

   },

   /**
    * 组件的初始数据
    */
   data: {
      //当前高亮项
      selected: 0,
      isTabShow: 0,

      //tabBar页面数据
      tabList: [
         {
            "pagePath": "pages/little/index",
            "text": "文章"
         },
         {
            "pagePath": "pages/leaving/index",
            "text": "留言"
         },
         {
            "pagePath": "pages/index/index"
         },
         {
            "pagePath": "pages/loveImg/index",
            "text": "相册"
         },
         {
            "pagePath": "pages/list/index",
            "text": "列表"
         }
      ]
   },

   /**
    * 组件的方法列表
    */
   methods: {
      switchTab(e) {
         let key = Number(e.currentTarget.dataset.index);
         let tabList = this.data.tabList;
         let selected = this.data.selected;

         if (selected !== key) {
            this.setData({
               selected: key
            });
            wx.switchTab({
               url: `/${tabList[key].pagePath}`,
            })
         }
      },
   }
})
