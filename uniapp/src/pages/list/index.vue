<template>
  <view class="page">
    <view class="banner">
      <text class="banner-text">{{ site?.deci4 || '总有些惊奇的际遇' }}</text>
    </view>

    <view class="tabs">
      <view
        v-for="tab in tabs"
        :key="tab.id"
        class="tab"
        :class="{ active: activeTab === tab.id }"
        @tap="switchTab(tab.id)"
      >{{ tab.label }}</view>
    </view>

    <view class="search">
      <input v-model="searchValue" class="search-input" placeholder="请输入关键词..." />
      <view class="search-btn" @tap="submitQuery">搜</view>
    </view>

    <view v-if="!lovelist.length" class="card empty">暂无恋爱清单</view>
    <view v-for="(item, index) in lovelist" :key="index" class="card row item" @tap="openItem(item)">
      <text class="status" :class="Number(item.icon) === 0 ? 'fail' : 'ok'">
        {{ Number(item.icon) === 0 ? '未完成' : '已完成' }}
      </text>
      <text class="title">{{ item.title || item.eventname }}</text>
    </view>
  </view>
</template>

<script setup>
import { computed, ref } from 'vue'
import { onShow, onShareAppMessage, onShareTimeline } from '@dcloudio/uni-app'
import { api } from '../../utils/request.js'
import { loadLoveList, loadSite, useLoveStore } from '../../stores/love.js'

const store = useLoveStore()
const site = computed(() => store.site)
const lovelist = ref([])
const activeTab = ref('All')
const searchValue = ref('')
const tabs = [
  { id: 'Success', label: '已完成' },
  { id: 'All', label: '全部' },
  { id: 'Fail', label: '未完成' }
]

onShow(async () => {
  await loadSite()
  lovelist.value = await loadLoveList()
})

async function getListType(query) {
  uni.showLoading({ title: '加载中' })
  try {
    const res = await api('search_list.php', {
      method: 'POST',
      data: {
        search_info: query,
        search_Type: 1
      },
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      }
    })
    if (Array.isArray(res.data) && res.data.length) {
      lovelist.value = res.data.slice().reverse()
    } else {
      lovelist.value = []
      uni.showToast({ title: '未查询到相关数据...', icon: 'none' })
    }
  } catch {
    uni.showToast({ title: '查询失败', icon: 'none' })
  } finally {
    uni.hideLoading()
  }
}

function switchTab(id) {
  activeTab.value = id
  getListType(id)
}

function submitQuery() {
  if (!searchValue.value) {
    uni.showToast({ title: '请先输入关键词！', icon: 'none' })
    return
  }
  getListType(searchValue.value)
}

function openItem(item) {
  const urls = item.listurl || []
  const first = urls[0]?.url
  if (!first || first === '0') return
  const images = urls.map((u) => u.url).filter((u) => u && u !== '0')
  if (images.length) {
    uni.previewImage({ current: images[0], urls: images })
  }
}

onShareAppMessage(() => ({
  title: site.value?.share_title || '恋爱清单',
  imageUrl: site.value?.share_cover || '',
  path: '/pages/list/index'
}))

onShareTimeline(() => ({
  title: site.value?.share_title || '恋爱清单',
  imageUrl: site.value?.share_cover || ''
}))
</script>

<style scoped>
.tabs {
  display: flex;
  gap: 12rpx;
  margin-bottom: 20rpx;
}

.tab {
  flex: 1;
  text-align: center;
  padding: 16rpx 0;
  border-radius: 999rpx;
  background: rgba(255, 255, 255, 0.8);
  color: var(--lg-muted);
  font-size: 26rpx;
}

.tab.active {
  background: var(--lg-accent);
  color: #fff;
}

.search {
  display: flex;
  gap: 12rpx;
  margin-bottom: 22rpx;
}

.search-input {
  flex: 1;
  background: #fff;
  border-radius: 999rpx;
  padding: 16rpx 28rpx;
  border: 1px solid #ead8d1;
}

.search-btn {
  width: 96rpx;
  border-radius: 999rpx;
  background: var(--lg-accent);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
}

.item {
  gap: 18rpx;
}

.status {
  font-size: 22rpx;
  padding: 6rpx 14rpx;
  border-radius: 999rpx;
}

.status.ok {
  background: rgba(93, 155, 106, 0.15);
  color: var(--lg-ok);
}

.status.fail {
  background: rgba(184, 107, 107, 0.15);
  color: var(--lg-fail);
}

.title {
  flex: 1;
  font-size: 28rpx;
}
</style>
