/**
 * 部署前只改这里，并在微信公众平台添加 request 合法域名。
 * 必须使用 HTTPS，末尾斜杠可省略。
 */
const BASE_URL = 'https://example.com/'

export default {
  BASE_URL: BASE_URL.replace(/\/+$/, '') + '/',
  API_VERSION: '3.1',
  REQUEST_TIMEOUT: 10000
}
