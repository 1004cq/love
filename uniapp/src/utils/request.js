import env from '../config/env.js'

export function request(options = {}) {
  const { url, method = 'GET', data, header = {}, timeout = env.REQUEST_TIMEOUT } = options
  return new Promise((resolve, reject) => {
    uni.request({
      url,
      method,
      data,
      timeout,
      header: {
        Accept: 'application/json',
        ...header
      },
      success: (res) => resolve(res),
      fail: (err) => reject(err)
    })
  })
}

export function api(path, options = {}) {
  const normalized = String(path || '').replace(/^\/+/, '')
  return request({
    ...options,
    url: env.BASE_URL + normalized
  })
}
