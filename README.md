# 模块栈 · 技术说明框架

把选型拆成七个模块说清楚，并给出可运行的 Next.js 前后端一体示例。

## 模块技术说明

| 模块 | 选型 | 一句话 |
|------|------|--------|
| 框架 | **Next.js 14+ (App Router)** | 前后端一体 |
| 语言 | **TypeScript** | 类型安全 |
| 样式 | **Tailwind CSS** | 快速控制视觉 |
| 组件库 | **shadcn/ui** | 可深度定制 |
| 动效 | **Framer Motion** | 细腻动画 |
| 数据库 | **Prisma + SQLite** | 简单够用 |
| 部署 | **Vercel** | 一键上线 |

## 快速开始

```bash
npm install
npm run db:setup
npm run dev
```

- 站点：[http://localhost:3000](http://localhost:3000)
- 模块说明：`/stack`
- 快速开始：`/start`
- 管理示例（隐藏）：`/admin`

### 默认管理员（示例鉴权）

| 用户名 | 密码 | 显示名 |
|--------|------|--------|
| alice | love123 | Alice |
| bob | love123 | Bob |

可在 `.env` 修改（见 `.env.example`），改后执行 `npm run db:seed`。

## 文件结构

```text
app/
├── page.tsx                 # 首页（模块概览）
├── stack/                   # 模块技术说明
├── start/                   # 快速开始
├── about/                   # 关于框架
├── admin/                   # 鉴权与数据示例
└── api/
components/
├── StackHero.tsx
├── StackModuleCard.tsx / StackModuleGrid.tsx
├── Header.tsx / Footer.tsx / BottomNav.tsx
└── ui/
lib/
├── tech-modules.ts          # 模块说明数据
├── prisma.ts / auth.ts / session.ts
└── motion.ts
prisma/
├── schema.prisma
└── seed.ts
```

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

- SQLite：`prisma/dev.db`（gitignore）
- 生产环境请更换 `AUTH_SECRET` 与默认密码
- 部署：连接 Vercel，配置环境变量后推送即可上线
