<script setup>
import { onLaunch } from '@dcloudio/uni-app'
import { checkBackend, loadSite } from './stores/love.js'

onLaunch(() => {
  checkBackend()
  loadSite().catch((err) => {
    console.error('loadSite failed', err)
  })
})
</script>

<style>
page {
  --lg-bg: #fff4ef;
  --lg-ink: #2f2624;
  --lg-muted: #8a7b76;
  --lg-accent: #c45c5c;
  --lg-accent-soft: #f3d2c8;
  --lg-ok: #5d9b6a;
  --lg-fail: #b86b6b;
  background: var(--lg-bg);
  color: var(--lg-ink);
  font-family: 'PingFang SC', 'Noto Sans SC', 'Helvetica Neue', sans-serif;
  min-height: 100%;
}

.page {
  padding: 24rpx 28rpx 48rpx;
}

.banner {
  display: flex;
  align-items: center;
  gap: 16rpx;
  padding: 22rpx 24rpx;
  margin-bottom: 24rpx;
  border-radius: 20rpx;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.92), rgba(255, 236, 228, 0.95));
  box-shadow: 0 10rpx 30rpx rgba(120, 70, 60, 0.06);
}

.banner-text {
  flex: 1;
  font-size: 26rpx;
  line-height: 1.5;
  color: var(--lg-muted);
}

.card {
  margin-bottom: 22rpx;
  padding: 28rpx;
  border-radius: 24rpx;
  background: rgba(255, 255, 255, 0.92);
  box-shadow: 0 12rpx 36rpx rgba(110, 60, 50, 0.07);
}

.section-title {
  font-size: 30rpx;
  font-weight: 600;
  margin-bottom: 16rpx;
}

.empty {
  padding: 80rpx 20rpx;
  text-align: center;
  color: var(--lg-muted);
  font-size: 26rpx;
}

.avatar {
  width: 84rpx;
  height: 84rpx;
  border-radius: 50%;
  background-size: cover;
  background-position: center;
  background-color: #f0e4df;
  flex-shrink: 0;
}

.row {
  display: flex;
  align-items: center;
  gap: 18rpx;
}

.muted {
  color: var(--lg-muted);
  font-size: 24rpx;
}

.primary-btn {
  background: var(--lg-accent);
  color: #fff;
  border-radius: 999rpx;
  font-size: 28rpx;
}
</style>
