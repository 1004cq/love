/**
 * 部署前只需要修改这里，并在微信公众平台添加对应 request 合法域名。
 * 必须使用已备案且支持 HTTPS 的域名，末尾斜杠可省略。
 */
const BASE_URL = 'https://example.com/'

module.exports = {
  BASE_URL: BASE_URL.replace(/\/+$/, '') + '/',
  API_VERSION: '3.1',
  REQUEST_TIMEOUT: 10000
}
