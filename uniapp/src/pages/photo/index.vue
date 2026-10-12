<template>
  <view class="page">
    <view class="card header" v-if="ptitle || pname">
      <view class="row">
        <view class="avatar" :style="{ backgroundImage: `url(${avatar})` }" />
        <view>
          <view class="title">{{ ptitle }}</view>
          <view class="muted">{{ pname }}</view>
        </view>
      </view>
    </view>

    <view v-if="!photos.length" class="card empty">暂无照片</view>
    <view class="grid">
      <image
        v-for="(src, index) in photos"
        :key="index"
        class="photo"
        :src="src"
        mode="aspectFill"
        @tap="preview(src)"
      />
    </view>
  </view>
</template>

<script setup>
import { ref } from 'vue'
import { onLoad } from '@dcloudio/uni-app'
import { api } from '../../utils/request.js'

const photos = ref([])
const ptitle = ref('')
const pname = ref('')
const avatar = ref('')
const cover = ref('')

function normalizeList(data) {
  if (!data) return []
  if (Array.isArray(data)) {
    return data
      .map((item) => (typeof item === 'string' ? item : item.url || item.imgurl || item.src))
      .filter(Boolean)
  }
  return []
}

onLoad(async (options) => {
  const code = options.code
  ptitle.value = decodeURIComponent(options.ptitle || '')
  pname.value = decodeURIComponent(options.pname || '')
  avatar.value = decodeURIComponent(options.pavatar || '')
  cover.value = decodeURIComponent(options.cover || '')

  if (options.state === 'true') {
    const stored = uni.getStorageSync('Photo_' + code)
    const list = normalizeList(stored?.PhotoList || stored?.list)
    photos.value = list.length ? list : cover.value ? [cover.value] : []
    return
  }

  try {
    const res = await api('api/select_photo.php', {
      data: { id: code, code }
    })
    const list = normalizeList(res.data?.PhotoList || res.data)
    photos.value = list.length ? list : cover.value ? [cover.value] : []
  } catch {
    photos.value = cover.value ? [cover.value] : []
  }
})

function preview(src) {
  uni.previewImage({ current: src, urls: photos.value })
}
</script>

<style scoped>
.header {
  margin-bottom: 8rpx;
}

.title {
  font-size: 32rpx;
  font-weight: 600;
}

.grid {
  display: flex;
  flex-wrap: wrap;
  gap: 12rpx;
}

.photo {
  width: calc(50% - 6rpx);
  height: 280rpx;
  border-radius: 16rpx;
  background: #f3e6e0;
}
</style>
