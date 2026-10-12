<template>
  <view class="page">
    <image v-if="cover" class="cover" :src="cover" mode="aspectFill" />
    <view class="card">
      <view class="title">{{ title }}</view>
      <view class="muted">{{ time }}</view>
      <rich-text class="body" :nodes="content" />
    </view>
  </view>
</template>

<script setup>
import { ref } from 'vue'
import { onLoad } from '@dcloudio/uni-app'

const title = ref('')
const content = ref('')
const time = ref('')
const cover = ref('')

onLoad((options) => {
  title.value = decodeURIComponent(options.title || '')
  content.value = decodeURIComponent(options.content || '')
  time.value = decodeURIComponent(options.time || '')
  cover.value = decodeURIComponent(options.detailcover || options.cover || '')
})
</script>

<style scoped>
.cover {
  width: 100%;
  height: 360rpx;
  border-radius: 24rpx;
  margin-bottom: 20rpx;
  background: #f3e6e0;
}

.title {
  font-size: 36rpx;
  font-weight: 700;
  margin-bottom: 10rpx;
}

.body {
  margin-top: 20rpx;
  line-height: 1.7;
  font-size: 28rpx;
}
</style>
