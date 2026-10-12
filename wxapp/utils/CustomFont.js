let loadFont = function (FontUrl) {
   wx.loadFontFace({
      family: 'SYSerifSC',
      global: true,
      source: 'url(' + FontUrl + ')',
      success: function (e) {
         console.log('字体调用成功')
      },
      fail: function (e) {
         console.log('字体调用失败')
      },
   })

}
module.exports = {
   loadFont: loadFont
};