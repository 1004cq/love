# 设计约束（LGNewUI from love.zip）

视觉对齐 PHP LGNewUI（`modern-2026.css` + `new.css` + `home.css` + `head.php` / `index.php`）：

## 结构（与 PHP 一致）

1. 顶栏 `LG_Demo` logo
2. 轮播头图 + 双人头像 + 爱心 + 波浪收边（`#homePage`）
3. 四色渐变入口卡：点点滴滴 / 祝福留言 / 恋爱相册 / 恋爱事件（`nav_card`）
4. 粉紫渐变口号条（`nav_url`）：小手一牵 岁岁年年～
5. Love Day：蓝绿标题条（`ltps`）+ 白卡片倒计时「天/时/分/秒」（`ios_page`）
6. 彩色纪念日卡（`time_card`）
7. 最新留言 / 最新动态列表

## 色板

- 强调色：`#ff6b8a` + `#7c6cff`
- 背景：`#f7f7fb`
- 入口卡渐变：黄蓝 / 粉红 / 蓝 / 青紫
- 纪念日卡：`#fc5d79` `#a67cff` `#49d4a2` …

## 约束清单

- 圆角约 16–20px，毛玻璃与轻阴影
- 动效 200–500ms；轮播约 4.5s
- 优先 shadcn/ui + Framer Motion
- 默认头像/轮播图沿用 PHP install 种子图（gejiba）
