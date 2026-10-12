export function calculateDays(startTime) {
  const startDate = new Date(startTime)
  const today = new Date()
  const differenceInMillis = today.getTime() - startDate.getTime()
  return Math.floor(differenceInMillis / (24 * 60 * 60 * 1000))
}

export function getQQAvatarUrl(qqNumber) {
  return `https://q1.qlogo.cn/g?b=qq&nk=${qqNumber}&s=640`
}

export function timestampToTime(timestamp) {
  const date = new Date(Number(timestamp) * 1000)
  const pad = (n) => (n < 10 ? `0${n}` : `${n}`)
  return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`
}

export function getDateDiff(dateTimeStamp) {
  let value = dateTimeStamp
  if (typeof value === 'string' && !value.includes('T')) {
    value = value.replace(' ', 'T')
  }
  const timestamp = new Date(value).getTime()
  const minute = 1000 * 60
  const hour = minute * 60
  const day = hour * 24
  const year = day * 365
  const diffValue = Date.now() - timestamp
  if (diffValue < 0) return '刚刚'
  if (diffValue / year >= 1) return `${parseInt(diffValue / year, 10)}年前`
  if (diffValue / (day * 30) >= 1) return `${parseInt(diffValue / (day * 30), 10)}月前`
  if (diffValue / (7 * day) >= 1) return `${parseInt(diffValue / (7 * day), 10)}周前`
  if (diffValue / day >= 1) return `${parseInt(diffValue / day, 10)}天前`
  if (diffValue / hour >= 1) return `${parseInt(diffValue / hour, 10)}小时前`
  if (diffValue / minute >= 1) return `${parseInt(diffValue / minute, 10)}分钟前`
  return '刚刚'
}
