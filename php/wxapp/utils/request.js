const env = require('../config/env.js')

function request(options, retries) {
  const remaining = typeof retries === 'number' ? retries : 1
  const requestOptions = Object.assign({
    timeout: env.REQUEST_TIMEOUT,
    header: { 'Accept': 'application/json' }
  }, options)

  const originalFail = requestOptions.fail
  requestOptions.fail = function (error) {
    if (remaining > 0) {
      setTimeout(function () { request(options, remaining - 1) }, 350)
      return
    }
    if (typeof originalFail === 'function') originalFail(error)
  }
  wx.request(requestOptions)
}

function api(path, options) {
  const normalized = String(path || '').replace(/^\/+/, '')
  return request(Object.assign({}, options || {}, { url: env.BASE_URL + normalized }))
}

module.exports = { request, api }
