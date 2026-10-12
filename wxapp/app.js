/**
 * Info: 购买程序后可随意使用，但不得有任何泄露源代码行为。
 * 包括但不限于分发、转售、上传至开源平台、代替他人购买。如有以上行为，将 不再提供程序更新等售后服务。
 * 因程序属于可复制的商品，在代码发出后不予退款，敬请谅解。
 * 再次声明 开发不易 禁止以任何方式倒卖 分发他人 一经查证 必定追究
 * 感谢您的购买 祝使用愉快 ～
 * @Author: Ki.
 * @Project: LikeGirlAPP v3.0（LGNewUi - 冬至限定）
 * @Date: 2024-12-21
 * @Description: 风雨同舟数十载 相濡以沫共白头
 * @Document: https://blog.kikiw.cn/index.php/archives/55/
 * Copyright (c) 2024 by Ki All Rights Reserved. 
 **/
const font = require('/utils/CustomFont.js')
const env = require('/config/env.js')
const network = require('/utils/request.js')

App({
   globalData: {
      GlobalLink: env.BASE_URL,
      ApiVersion: env.API_VERSION,
      BackendStatus: 'unknown',
      StatusBar: null,
      CustomBar: null,
      Custom: null,
      Playersign: false,
      LGSwiperIndex: 0,
      AppLoveData: null,
   },

   onLaunch() {
      this.checkBackend();
   },

   onLoad() {
      wx.setNavigationBarColor({
         frontColor: '#ffffff',
         backgroundColor: 'transparent',
         animation: {
            duration: 0,
            timingFunc: 'easeIn'
         }
      });
   },

   // 获取情侣基本数据&配置
   getLikeInfo: function (callback) {
      const that = this;

      // 如果数据已存在，则不请求
      if (that.globalData.AppLoveData) {
         if (typeof callback === 'function') {
            callback(that.globalData.AppLoveData);
         }
         return; // 数据已经加载，直接返回
      }

      network.request({
         url: that.globalData.GlobalLink + 'api/data.json',
         success: function (res) {
            if (res.statusCode === 200) {
               try {
                  const data = res.data;
                  data.startTime = that.calculateDays(data.startTime);
                  data.boyimg = that.getQQAvatarUrl(data.boyimg);
                  data.girlimg = that.getQQAvatarUrl(data.girlimg);

                  that.globalData.AppLoveData = data; // 将数据保存到 globalData
                  wx.setStorageSync('LG_APP_LOVE_DATA', data);

                  // 设置自定义字体
                  const FontUrl = that.globalData.AppLoveData.custom_font_url;
                  if (typeof FontUrl === 'string' && /^https:\/\//.test(FontUrl)) {
                     font.loadFont(FontUrl);
                  }

                  // 调用回调函数
                  if (typeof callback === 'function') {
                     callback(data);
                  }
               } catch (error) {
                  console.error("Error during data processing or callback:", error);
               }
            } else {
               console.error("请求失败，状态码:", res.statusCode);
            }
         },
         fail: function (err) {
            const cached = wx.getStorageSync('LG_APP_LOVE_DATA');
            if (cached) {
               that.globalData.AppLoveData = cached;
               if (typeof callback === 'function') callback(cached);
            } else {
               console.error(err);
            }
         },
         complete: function (res) {
            // console.log(res);
         }
      });
   },

   //获取留言祝福数据
   getLyInfo: function (page, callback) {
      const that = this;
      network.request({
         url: that.globalData.GlobalLink + 'api/leav.json',
         success: function (res) {
            res.data.forEach(element => {
               if (element.qq != "anon") {
                  element.qq = that.getQQAvatarUrl(element.qq);
               } else {
                  element.qq = element.DiyPic;
               }
               if (page != 'index') {
                  element.city = element.city.replace(/\s*·\s*/g, '·');
               }
               element.time = that.timestampToTime(element.time);
               element.time = that.getDateDiff(element.time);
            });

            if (typeof callback === 'function') {
               callback(res.data);
            }
         },
         fail: function (err) {
            console.log(err);
         }
      });
   },

   // 获取点点滴滴数据
   getWzInfo: function (callback) {

      const that = this;
      network.request({
         url: that.globalData.GlobalLink + 'api/www.json',
         success: function (res) {
            if (typeof callback === 'function') {
               callback(res.data);
            }
         },
         fail: function (err) {
            console.log(err);
         }
      });
   },

   // 获取恋爱事件数据
   getListInfo: function (callback) {
      const that = this
      network.request({
         url: that.globalData.GlobalLink + "api/list.json",
         success: function (res) {
            if (typeof callback === 'function') {
               callback(res.data);
            }
         },
         fail: function (err) {
            console.log(err)
         }
      })
   },

   // 获取恋爱相册数据
   getImgInfo: function (callback) {
      const that = this;
      network.request({
         url: that.globalData.GlobalLink + "api/images.json",
         success: function (res) {
            if (typeof callback === 'function') {
               callback(res.data);
            }
         },
         fail: function (err) {
            console.log(err)
         }
      })
   },

   // 组件自定义数据 2
   getCtplTowInfo: function (callback) {
      const that = this;
      network.request({
         url: that.globalData.GlobalLink + 'api/ctplTow.json',
         success: function (res) {
            if (typeof callback === 'function') {
               callback(res.data);
            }
         },
         fail: function (err) {
            console.log(err);
         }
      });
   },

   // 组件自定义数据 3
   getCtplThreeInfo: function (callback) {
      const that = this;
      network.request({
         url: that.globalData.GlobalLink + 'api/chatData.json',
         success: function (res) {
            if (typeof callback === 'function') {
               callback(res.data);
            }
         },
         fail: function (err) {
            console.log(err);
         }
      });
   },



   checkBackend: function () {
      const that = this;
      network.api('api/health.php', {
         success: function (res) {
            that.globalData.BackendStatus = res.statusCode === 200 && res.data.status === 'ok' ? 'ok' : 'degraded';
         },
         fail: function () { that.globalData.BackendStatus = 'offline'; }
      });
   },

   // Tool: 计算开始时间到今天的天数
   calculateDays: function (startTime) {
      const startDate = new Date(startTime);
      const today = new Date();
      const differenceInMillis = today.getTime() - startDate.getTime();
      const days = Math.floor(differenceInMillis / (24 * 60 * 60 * 1000)); // 转换为天
      return days;
   },

   // Tool: 拼接 QQ 头像 URL
   getQQAvatarUrl: function (qqNumber) {
      return `https://q1.qlogo.cn/g?b=qq&nk=${qqNumber}&s=640`;
   },

   timestampToTime: function (timestamp) {
      var date = new Date(timestamp * 1000);
      var Y = date.getFullYear() + '-';
      var M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
      const D = (date.getDate() < 10 ? '0' + date.getDate() : date.getDate()) + ' ';
      const h = (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':';
      const m = (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()) + ':';
      const s = (date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds());
      return Y + M + D + h + m + s;
   },

   getDateDiff: function (dateTimeStamp) {
      if (typeof dateTimeStamp === "string" && !dateTimeStamp.includes("T")) {
         dateTimeStamp = dateTimeStamp.replace(" ", "T");
      }

      var timestamp = new Date(dateTimeStamp).getTime();
      var minute = 1000 * 60;
      var hour = minute * 60;
      var day = hour * 24;
      var year = day * 365;
      var now = new Date().getTime();
      var diffValue = now - timestamp;

      if (diffValue < 0) {
         return "刚刚";
      }

      var yearC = diffValue / year;
      var monthC = diffValue / (day * 30);
      var weekC = diffValue / (7 * day);
      var dayC = diffValue / day;
      var hourC = diffValue / hour;
      var minC = diffValue / minute;

      if (yearC >= 1) {
         return parseInt(yearC) + "年前";
      } else if (monthC >= 1) {
         return parseInt(monthC) + "月前";
      } else if (weekC >= 1) {
         return parseInt(weekC) + "周前";
      } else if (dayC >= 1) {
         return parseInt(dayC) + "天前";
      } else if (hourC >= 1) {
         return parseInt(hourC) + "小时前";
      } else if (minC >= 1) {
         return parseInt(minC) + "分钟前";
      } else {
         return "刚刚";
      }
   },
})
