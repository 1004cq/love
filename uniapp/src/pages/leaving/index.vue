<template>
  <view class="page">
    <view class="banner" @tap="openComposer">
      <text class="banner-text">{{ site?.deci2 || '书写下你的留言祝福～' }}</text>
    </view>

    <view v-if="!leaving.length" class="card empty">暂无留言</view>
    <view v-for="(item, index) in leaving" :key="index" class="card">
      <view class="row">
        <view class="avatar" :style="{ backgroundImage: `url(${item.qq})` }" />
        <view>
          <view class="name">{{ item.name }}</view>
          <view class="muted">{{ item.time }}</view>
        </view>
      </view>
      <rich-text class="body" :nodes="item.text" />
      <view class="meta muted">
        <text>{{ item.Br_info || '-' }}</text>
        <text>·</text>
        <text>{{ item.Os_info || '-' }}</text>
        <text>·</text>
        <text>{{ item.city || '外太空' }}</text>
      </view>
    </view>

    <view
      v-if="Number(site?.MsgSwitch) === 1"
      class="fab"
      @tap="openComposer"
    >+</view>

    <view v-if="showPop" class="mask" @tap="showPop = false">
      <view class="dialog" @tap.stop>
        <view class="dialog-title">写下祝福</view>
        <input class="field" v-model="form.qq" :disabled="anonymous" placeholder="QQ号" />
        <input class="field" v-model="form.nickname" placeholder="昵称" />
        <textarea class="field area" v-model="form.message" placeholder="留言内容" />
        <view class="anon" @tap="toggleAnon">
          <text class="check">{{ anonymous ? '✓' : '' }}</text>
          <text>匿名留言</text>
        </view>
        <button class="primary-btn" @tap="submitMessage">提交留言</button>
      </view>
    </view>
  </view>
</template>

<script setup>
import { computed, reactive, ref } from 'vue'
import { onShow, onShareAppMessage, onShareTimeline } from '@dcloudio/uni-app'
import { api } from '../../utils/request.js'
import { loadLeaving, loadSite, useLoveStore } from '../../stores/love.js'

const store = useLoveStore()
const site = computed(() => store.site)
const leaving = ref([])
const showPop = ref(false)
const anonymous = ref(false)
const form = reactive({
  qq: '',
  nickname: '',
  message: ''
})

onShow(async () => {
  await loadSite()
  leaving.value = await loadLeaving()
})

function openComposer() {
  if (Number(site.value?.MsgSwitch) !== 1) {
    uni.showToast({ title: '留言功能未开启', icon: 'none' })
    return
  }
  showPop.value = true
}

function toggleAnon() {
  anonymous.value = !anonymous.value
  if (anonymous.value) {
    form.qq = 'anon'
    form.nickname = '匿名'
  } else {
    form.qq = ''
    form.nickname = ''
  }
}

async function submitMessage() {
  if (!form.qq.trim() && !anonymous.value) {
    uni.showToast({ title: '请输入QQ号', icon: 'none' })
    return
  }
  if (!form.nickname.trim()) {
    uni.showToast({ title: '请输入昵称', icon: 'none' })
    return
  }
  if (!form.message.trim()) {
    uni.showToast({ title: '请输入留言内容', icon: 'none' })
    return
  }
  const banned = String(site.value?.lanjiezf || '').split('').filter(Boolean)
  if (banned.some((word) => form.message.includes(word))) {
    uni.showToast({ title: '留言内容包含违禁词', icon: 'none' })
    return
  }

  uni.showLoading({ title: '提交中' })
  try {
    const res = await api('admin/leavingPost.php', {
      method: 'POST',
      data: {
        name: form.nickname.trim(),
        qq: form.qq.trim() || 'anon',
        text: form.message.trim()
      },
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      }
    })
    if (res.data?.Status) {
      uni.showToast({ title: '留言成功！', icon: 'success' })
      showPop.value = false
      form.qq = ''
      form.nickname = ''
      form.message = ''
      anonymous.value = false
      leaving.value = await loadLeaving()
    } else {
      uni.showToast({ title: res.data?.message || '留言失败，请重试！', icon: 'none' })
    }
  } catch {
    uni.showToast({ title: '网络错误，请重试！', icon: 'none' })
  } finally {
    uni.hideLoading()
  }
}

onShareAppMessage(() => ({
  title: site.value?.share_title || '留言',
  imageUrl: site.value?.share_cover || '',
  path: '/pages/leaving/index'
}))

onShareTimeline(() => ({
  title: site.value?.share_title || '留言',
  imageUrl: site.value?.share_cover || ''
}))
</script>

<style scoped>
.name {
  font-weight: 600;
}

.body {
  margin-top: 18rpx;
  line-height: 1.65;
  font-size: 28rpx;
}

.meta {
  display: flex;
  gap: 10rpx;
  margin-top: 18rpx;
  flex-wrap: wrap;
}

.fab {
  position: fixed;
  right: 36rpx;
  bottom: 160rpx;
  width: 96rpx;
  height: 96rpx;
  border-radius: 50%;
  background: var(--lg-accent);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 56rpx;
  box-shadow: 0 12rpx 28rpx rgba(196, 92, 92, 0.35);
  z-index: 10;
}

.mask {
  position: fixed;
  inset: 0;
  background: rgba(30, 20, 18, 0.45);
  display: flex;
  align-items: flex-end;
  z-index: 20;
}

.dialog {
  width: 100%;
  background: #fffaf7;
  border-radius: 28rpx 28rpx 0 0;
  padding: 36rpx 32rpx 48rpx;
}

.dialog-title {
  font-size: 32rpx;
  font-weight: 700;
  margin-bottom: 24rpx;
}

.field {
  width: 100%;
  background: #fff;
  border: 1px solid #ead8d1;
  border-radius: 16rpx;
  padding: 18rpx 20rpx;
  margin-bottom: 16rpx;
  box-sizing: border-box;
}

.area {
  min-height: 180rpx;
}

.anon {
  display: flex;
  align-items: center;
  gap: 12rpx;
  margin: 8rpx 0 24rpx;
  color: var(--lg-muted);
}

.check {
  width: 34rpx;
  height: 34rpx;
  border: 1px solid #cbb5ad;
  border-radius: 8rpx;
  text-align: center;
  line-height: 34rpx;
  color: var(--lg-accent);
}
</style>
