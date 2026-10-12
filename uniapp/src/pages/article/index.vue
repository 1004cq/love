<template>
  <view class="page">
    <view class="card">
      <view class="title">{{ title }}</view>
      <view class="muted">{{ name }} · {{ time }}</view>
    </view>
    <view class="card content">
      <rich-text v-if="html" :nodes="html" />
      <view v-else class="empty">暂无内容或需要密码解锁</view>
    </view>
  </view>
</template>

<script setup>
import { ref } from 'vue'
import { onLoad } from '@dcloudio/uni-app'
import { loadArticles } from '../../stores/love.js'

const title = ref('文章详情')
const name = ref('')
const time = ref('')
const html = ref('')

onLoad(async (options) => {
  const id = options.id
  title.value = decodeURIComponent(options.title || '文章详情')
  name.value = decodeURIComponent(options.name || '')
  time.value = decodeURIComponent(options.time || '')

  if (options.state === 'true') {
    const stored = uni.getStorageSync('Article_' + id)
    if (stored?.articletext || stored?.content) {
      html.value = stored.articletext || stored.content
      title.value = stored.articletitle || title.value
      name.value = stored.articlename || name.value
      time.value = stored.articletime || time.value
      return
    }
  }

  const list = await loadArticles()
  const item = (list || []).find((row) => String(row.id) === String(id))
  if (item) {
    title.value = item.articletitle || title.value
    name.value = item.articlename || name.value
    time.value = item.articletime || time.value
    html.value = item.articletext || item.content || ''
  }
})
</script>

<style scoped>
.title {
  font-size: 36rpx;
  font-weight: 700;
  margin-bottom: 12rpx;
}

.content {
  line-height: 1.75;
  font-size: 28rpx;
}
</style>
