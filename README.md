# Ours · 情侣空间（LGNewUi 风格）

基于 Next.js 14（App Router）+ TypeScript + Tailwind CSS + shadcn/ui + Framer Motion + Prisma + SQLite 的情侣小站。

界面与模块结构参考：[love-really.kikiw.cn（LGNewUi）](https://love-really.kikiw.cn/)，语言保持中文。

## 功能

- 首页：恋爱天数计时、情侣头像（中间爱心）、内容概览、底部导航
- 点滴日记：大日期卡片（日期 / 月份年份 / DAY / 天气 / 心情 / 发布者）
- 相册：公开浏览；登录后上传 / 删除
- 清单（一起做的事）：已完成 / 全部 / 未完成筛选 + 搜索 + 勾选
- 轨迹（纪念日）：列表 + 倒计时
- 留言板：公开浏览；登录后发布 / 管理
- 浮动音乐播放器：暖色半透明条（封面 / 歌名 / 歌手 / 播放）+ 可展开列表
- 隐藏管理入口：`/admin`（双固定管理员，权限相同）
- 后台仪表盘：`/admin/dashboard`（登录后数据概览与近期动态）

## 权限

| 角色 | 浏览 | 管理 |
|------|------|------|
| 游客 | 全部内容 | 无（不显示管理按钮） |
| 管理员 | 全部 | 增删改（两人相同） |

## 技术栈

- Next.js 14 App Router + TypeScript
- Tailwind CSS + shadcn/ui
- Framer Motion（轻动效，尊重 `prefers-reduced-motion`）
- Prisma + SQLite
- jose + bcryptjs Cookie Session
- HTML5 Audio
- 部署友好（Vercel）

## 快速开始

```bash
npm install
npm run db:setup
npm run dev
```

- 站点：[http://localhost:3000](http://localhost:3000)
- 管理入口（隐藏）：[http://localhost:3000/admin](http://localhost:3000/admin)

### 默认管理员

| 用户名 | 密码 | 显示名 |
|--------|------|--------|
| alice | love123 | Alice |
| bob | love123 | Bob |

可在 `.env` 修改（见 `.env.example`），改后执行 `npm run db:seed`。

## 文件结构

```text
app/
├── layout.tsx
├── page.tsx                 # 首页
├── admin/page.tsx           # 隐藏登录
├── diary/page.tsx           # 点滴
├── album/page.tsx           # 相册
├── together/page.tsx        # 清单
├── anniversary/page.tsx     # 轨迹
├── message/page.tsx         # 留言
├── about/page.tsx
└── api/
components/
├── Header.tsx / Footer.tsx / BottomNav.tsx
├── Counter.tsx / CounterDisplay.tsx / DayFusionCard.tsx
├── CoupleCard.tsx / LgHero.tsx
├── DiaryCard.tsx / DiaryTimeline.tsx
├── TogetherList.tsx / AlbumGrid.tsx
├── AnniversaryList.tsx / MessageBoard.tsx
├── MusicPlayer.tsx / MusicPlayerHost.tsx
└── ui/
lib/
├── prisma.ts / auth.ts / session.ts
├── diary-meta.ts / motion.ts
prisma/
├── schema.prisma
└── seed.ts
styles/
└── globals.css
```

## 数据模型（Prisma）

- `User`：双管理员（含 `avatarUrl`）
- `Setting`：恋爱开始日
- `DiaryEntry`：标题 / 内容 / 图 / weather / mood
- `AlbumPhoto` / `Anniversary` / `Message`
- `TogetherItem`：清单待办
- `MusicTrack`：歌名 / 歌手 / url / coverUrl

## 脚本

```bash
npm run dev
npm run build
npm run start
npm run db:push
npm run db:seed
npm run db:setup
```

## 注意

- 上传图片：`public/uploads/`
- SQLite：`prisma/dev.db`（gitignore）
- 生产环境请更换 `AUTH_SECRET` 与默认密码
