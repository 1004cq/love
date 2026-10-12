<template>
  <view class="page" v-if="site">
    <view class="hero card">
      <view class="couple">
        <view class="avatar" :style="{ backgroundImage: `url(${site.if_aiv == 1 ? site.diyb_url : site.boyimg})` }" />
        <text class="heart">♡</text>
        <view class="avatar" :style="{ backgroundImage: `url(${site.if_aiv == 1 ? site.diyg_url : site.girlimg})` }" />
      </view>
      <view class="hero-title">{{ site.title || 'LGNewUI' }}</view>
      <view class="hero-sub">{{ site.boy }} & {{ site.girl }}</view>
      <view class="days">第 <text>{{ site.startTime }}</text> 天</view>
      <view class="muted">{{ site.loveDayTitle || '这是我们一起走过的' }}</view>
    </view>

    <view class="banner">
      <text class="banner-text">{{ site.writing }}</text>
    </view>

    <view class="section-title">纪念日</view>
    <view v-if="!loveDays.length" class="card empty">暂无纪念日数据</view>
    <view
      v-for="(item, index) in loveDays"
      :key="index"
      class="card day-card"
      @tap="openDay(item)"
    >
      <view class="day-title">{{ item.day_title }}</view>
      <view class="muted" v-if="item.day_type">过了 {{ item.LovePast }} 天 · {{ item.Loveday }}</view>
      <view class="muted" v-else>还有 {{ item.LoveFuture }} 天 · {{ item.FutureLoveday }}</view>
    </view>

    <view class="section-title">最新留言</view>
    <view v-if="!leaving.length" class="card empty">暂无留言</view>
    <view v-for="(item, index) in leavingPreview" :key="index" class="card">
      <view class="row">
        <view class="avatar" :style="{ backgroundImage: `url(${item.qq})` }" />
        <view>
          <view>{{ item.name }}</view>
          <view class="muted">{{ item.time }}</view>
        </view>
      </view>
      <view class="body">{{ item.text }}</view>
    </view>

    <view class="section-title">点点滴滴</view>
    <view v-if="!articles.length" class="card empty">暂无日记</view>
    <view
      v-for="(item, index) in articlesPreview"
      :key="index"
      class="card"
      @tap="openArticle(item)"
    >
      <view class="article-title">{{ item.articletitle }}</view>
      <view class="muted">{{ item.articlename }} · {{ item.articletime }}</view>
    </view>
  </view>
  <view v-else class="page empty">加载中…</view>
</template>

<script setup>
import { computed, ref } from 'vue'
import { onShow, onShareAppMessage, onShareTimeline } from '@dcloudio/uni-app'
import { loadArticles, loadLeaving, loadLoveDays, loadSite, useLoveStore } from '../../stores/love.js'

const store = useLoveStore()
const site = computed(() => store.site)
const loveDays = ref([])
const leaving = ref([])
const articles = ref([])

const leavingPreview = computed(() => {
  const n = Number(site.value?.index_leav_num || 5)
  return leaving.value.slice(0, n)
})

const articlesPreview = computed(() => {
  const n = Number(site.value?.index_essay_num || 5)
  return articles.value.filter((item) => !item.encryptionSwitch).slice(0, n)
})

onShow(async () => {
  try {
    await loadSite()
    loveDays.value = await loadLoveDays()
    leaving.value = await loadLeaving('index')
    articles.value = await loadArticles()
  } catch (err) {
    console.error(err)
    uni.showToast({ title: '首页加载失败', icon: 'none' })
  }
})

function openDay(item) {
  const tip = item.day_type
    ? `过了 ${item.LovePast} 天\n${item.Loveday || ''}`
    : `还有 ${item.LoveFuture} 天\n${item.FutureLoveday || ''}`
  uni.showModal({
    title: item.day_title || '纪念日',
    content: tip,
    showCancel: false
  })
}

function openArticle(item) {
  uni.navigateTo({
    url: `/pages/article/index?id=${item.id}&time=${encodeURIComponent(item.articletime || '')}&title=${encodeURIComponent(item.articletitle || '')}&name=${encodeURIComponent(item.articlename || '')}`
  })
}

onShareAppMessage(() => ({
  title: site.value?.share_title || site.value?.title || 'LGNewUI',
  imageUrl: site.value?.share_cover || '',
  path: '/pages/index/index'
}))

onShareTimeline(() => ({
  title: site.value?.share_title || site.value?.title || 'LGNewUI',
  imageUrl: site.value?.share_cover || ''
}))
</script>

<style scoped>
.hero {
  text-align: center;
  background:
    radial-gradient(circle at top, rgba(255, 214, 198, 0.55), transparent 55%),
    linear-gradient(180deg, #fff, #ffe9e1);
}

.couple {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20rpx;
  margin-bottom: 18rpx;
}

.couple .avatar {
  width: 120rpx;
  height: 120rpx;
  border: 4rpx solid #fff;
}

.heart {
  color: var(--lg-accent);
  font-size: 40rpx;
}

.hero-title {
  font-size: 40rpx;
  font-weight: 700;
}

.hero-sub {
  margin-top: 8rpx;
  color: var(--lg-muted);
}

.days {
  margin: 18rpx 0 8rpx;
  font-size: 34rpx;
}

.days text {
  color: var(--lg-accent);
  font-weight: 700;
  margin: 0 8rpx;
}

.day-title,
.article-title {
  font-size: 30rpx;
  font-weight: 600;
  margin-bottom: 8rpx;
}

.body {
  margin-top: 16rpx;
  line-height: 1.6;
  font-size: 28rpx;
}
</style>
