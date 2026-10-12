<template>
  <view class="page">
    <view class="banner">
      <text class="banner-text">{{ site?.deci1 || '记录生活点点滴滴' }}</text>
    </view>

    <view v-if="!list.length" class="card empty">暂无日记</view>
    <view v-for="(item, index) in list" :key="index" class="card" @tap="openArticle(item)">
      <view class="row">
        <view class="avatar" :style="{ backgroundImage: `url(${avatarFor(item)})` }" />
        <view class="flex-1">
          <view class="title">{{ item.articletitle }}</view>
          <view class="muted">{{ item.articlename }} · {{ item.articletime }}</view>
        </view>
        <text v-if="item.encryptionSwitch" class="lock">锁</text>
      </view>
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
import { loadArticles, loadSite, useLoveStore } from '../../stores/love.js'

const store = useLoveStore()
const site = computed(() => store.site)
const list = ref([])
const showPw = ref(false)
const currentId = ref(null)
const pw = ref('')

onShow(async () => {
  await loadSite()
  list.value = await loadArticles()
})

function avatarFor(item) {
  if (!site.value) return ''
  const isBoy = item.articlename === site.value.boy
  if (Number(site.value.if_aiv) === 1) {
    return isBoy ? site.value.diyb_url : site.value.diyg_url
  }
  return isBoy ? site.value.boyimg : site.value.girlimg
}

function openArticle(item) {
  if (item.encryptionSwitch) {
    const stored = uni.getStorageSync('Article_' + item.id)
    if (stored?.password && stored?.Token) {
      uni.navigateTo({ url: `/pages/article/index?id=${item.id}&state=true` })
      return
    }
    currentId.value = item.id
    showPw.value = true
    return
  }
  uni.navigateTo({
    url: `/pages/article/index?id=${item.id}&time=${encodeURIComponent(item.articletime || '')}&title=${encodeURIComponent(item.articletitle || '')}&name=${encodeURIComponent(item.articlename || '')}`
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
        id: currentId.value,
        encrypCode: pw.value,
        encrypType: 'article'
      }
    })
    const data = Array.isArray(res.data) ? res.data[0] : res.data
    if (data?.state) {
      data.password = pw.value
      uni.setStorageSync('Article_' + data.ResId, data)
      showPw.value = false
      pw.value = ''
      uni.navigateTo({ url: `/pages/article/index?id=${data.ResId}&state=true` })
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
  title: site.value?.share_title || '日记',
  imageUrl: site.value?.share_cover || '',
  path: '/pages/little/index'
}))

onShareTimeline(() => ({
  title: site.value?.share_title || '日记',
  imageUrl: site.value?.share_cover || ''
}))
</script>

<style scoped>
.title {
  font-size: 30rpx;
  font-weight: 600;
  margin-bottom: 6rpx;
}

.flex-1 {
  flex: 1;
}

.lock {
  color: var(--lg-accent);
  font-size: 24rpx;
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
