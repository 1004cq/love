<template>
  <view class="page">
    <view class="banner">
      <text class="banner-text">{{ site?.deci3 || '记录下美好瞬间～' }}</text>
    </view>

    <view v-if="!albums.length" class="card empty">暂无相册</view>
    <view v-for="(item, index) in albums" :key="index" class="card" @tap="openAlbum(item)">
      <view class="row">
        <view class="avatar" :style="{ backgroundImage: `url(${item.Avatar})` }" />
        <view class="flex-1">
          <view class="title">{{ item.title || item.name }}</view>
          <view class="muted">{{ item.name }}</view>
        </view>
        <text v-if="item.encryptionSwitch" class="lock">锁</text>
      </view>
      <image
        class="cover"
        :src="item.imgurl"
        mode="aspectFill"
        @tap.stop="preview(item.imgurl)"
      />
    </view>

    <view v-if="showPw" class="mask" @tap="showPw = false">
      <view class="dialog" @tap.stop>
        <view class="dialog-title">请输入密码解锁</view>
        <input class="pw-input" password v-model="pw" placeholder="请输入密码" />
        <button class="primary-btn" @tap="checkPw">认证密码</button>
      </view>
    </view>
  </view>
</template>

<script setup>
import { computed, ref } from 'vue'
import { onShow, onShareAppMessage, onShareTimeline } from '@dcloudio/uni-app'
import { api } from '../../utils/request.js'
import { loadAlbums, loadSite, useLoveStore } from '../../stores/love.js'

const store = useLoveStore()
const site = computed(() => store.site)
const albums = ref([])
const showPw = ref(false)
const currentCode = ref(null)
const pw = ref('')

onShow(async () => {
  await loadSite()
  albums.value = await loadAlbums()
})

function preview(src) {
  if (!src) return
  uni.previewImage({ current: src, urls: [src] })
}

function openAlbum(item) {
  const code = item.id || item.code
  if (item.encryptionSwitch) {
    const stored = uni.getStorageSync('Photo_' + code)
    if (stored?.password && stored?.Token) {
      uni.navigateTo({ url: `/pages/photo/index?code=${code}&state=true` })
      return
    }
    currentCode.value = code
    showPw.value = true
    return
  }
  uni.navigateTo({
    url: `/pages/photo/index?code=${code}&cover=${encodeURIComponent(item.imgurl || '')}&ptitle=${encodeURIComponent(item.title || '')}&pname=${encodeURIComponent(item.name || '')}&pavatar=${encodeURIComponent(item.Avatar || '')}`
  })
}

async function checkPw() {
  if (!pw.value) {
    uni.showToast({ title: '请先填写密码', icon: 'none' })
    return
  }
  uni.showLoading({ title: '验证中' })
  try {
    const res = await api('api/lookPw.php', {
      data: {
        id: currentCode.value,
        encrypCode: pw.value,
        encrypType: 'loveImg'
      }
    })
    const data = Array.isArray(res.data) ? res.data[0] : res.data
    if (data?.state) {
      data.password = pw.value
      uni.setStorageSync('Photo_' + data.ResId, data)
      showPw.value = false
      pw.value = ''
      uni.navigateTo({ url: `/pages/photo/index?code=${data.ResId}&state=true` })
    } else {
      uni.showToast({ title: '密码错误 请重试！', icon: 'none' })
    }
  } catch {
    uni.showToast({ title: '验证失败', icon: 'none' })
  } finally {
    uni.hideLoading()
  }
}

onShareAppMessage(() => ({
  title: site.value?.share_title || '相册',
  imageUrl: site.value?.share_cover || '',
  path: '/pages/loveImg/index'
}))

onShareTimeline(() => ({
  title: site.value?.share_title || '相册',
  imageUrl: site.value?.share_cover || ''
}))
</script>

<style scoped>
.title {
  font-size: 30rpx;
  font-weight: 600;
}

.flex-1 {
  flex: 1;
}

.lock {
  color: var(--lg-accent);
  font-size: 24rpx;
}

.cover {
  width: 100%;
  height: 320rpx;
  margin-top: 18rpx;
  border-radius: 18rpx;
  background: #f3e6e0;
}

.mask {
  position: fixed;
  inset: 0;
  background: rgba(30, 20, 18, 0.45);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 20;
}

.dialog {
  width: 80%;
  background: #fff;
  border-radius: 24rpx;
  padding: 36rpx;
}

.dialog-title {
  text-align: center;
  margin-bottom: 24rpx;
  font-weight: 600;
}

.pw-input {
  border: 1px solid #ead8d1;
  border-radius: 12rpx;
  padding: 18rpx;
  margin-bottom: 24rpx;
}
</style>
