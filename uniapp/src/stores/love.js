import { reactive } from 'vue'
import { api } from '../utils/request.js'
import { calculateDays, getQQAvatarUrl, timestampToTime, getDateDiff } from '../utils/format.js'

const state = reactive({
  site: null,
  backendStatus: 'unknown',
  loading: false
})

async function fetchJson(path) {
  const res = await api(path)
  if (res.statusCode !== 200) {
    throw new Error(`请求失败 ${res.statusCode}`)
  }
  return res.data
}

export async function loadSite(force = false) {
  if (state.site && !force) return state.site
  const cached = uni.getStorageSync('LG_APP_LOVE_DATA')
  if (cached && !force) {
    state.site = cached
    return cached
  }
  state.loading = true
  try {
    const data = await fetchJson('api/data.json')
    data.startTime = calculateDays(data.startTime)
    data.boyimg = getQQAvatarUrl(data.boyimg)
    data.girlimg = getQQAvatarUrl(data.girlimg)
    state.site = data
    uni.setStorageSync('LG_APP_LOVE_DATA', data)
    return data
  } catch (err) {
    if (cached) {
      state.site = cached
      return cached
    }
    throw err
  } finally {
    state.loading = false
  }
}

export async function loadLeaving(page = '') {
  const list = (await fetchJson('api/leav.json')) || []
  return list.map((element) => {
    const item = { ...element }
    if (item.qq !== 'anon') {
      item.qq = getQQAvatarUrl(item.qq)
    } else {
      item.qq = item.DiyPic
    }
    if (page !== 'index' && typeof item.city === 'string') {
      item.city = item.city.replace(/\s*·\s*/g, '·')
    }
    item.time = getDateDiff(timestampToTime(item.time))
    return item
  })
}

export async function loadArticles() {
  return (await fetchJson('api/www.json')) || []
}

export async function loadAlbums() {
  return (await fetchJson('api/images.json')) || []
}

export async function loadLoveList() {
  return (await fetchJson('api/list.json')) || []
}

export async function loadLoveDays() {
  return (await fetchJson('api/loveTime.json')) || []
}

export async function checkBackend() {
  try {
    const res = await api('api/health.php')
    state.backendStatus =
      res.statusCode === 200 && res.data && res.data.status === 'ok' ? 'ok' : 'degraded'
  } catch {
    state.backendStatus = 'offline'
  }
  return state.backendStatus
}

export function useLoveStore() {
  return state
}
