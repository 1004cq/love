# LG_NewUi · 情侣小站（Next.js）

根据 [me.cq.je/love.zip](https://me.cq.je/love.zip) 的 **PHP LGNewUI** 重写为：

**Next.js 14+ (App Router) · TypeScript · Tailwind CSS · shadcn/ui · Framer Motion · Prisma + SQLite · Vercel**

口号：小手一牵 岁岁年年～

## 对应 PHP 页面

| Next 路由 | PHP 原页 | 模块 |
|-----------|----------|------|
| `/` | `index.php` | 首页 Love Day + 四入口卡 |
| `/little` | `little.php` | 点点滴滴 |
| `/leaving` | `leaving.php` | 祝福留言 |
| `/love-img` | `loveImg.php` | 恋爱相册 |
| `/list` | `list.php` | 恋爱事件 |
| `/about` | `about.php` | 关于我们 |
| `/admin` | `admin/login.php` | 后台登录 |
| `/admin/dashboard` | `admin/index.php` | 后台四卡片仪表盘 |

旧路径 `/diary` `/message` `/album` `/together` 会重定向到上表新路由。

## 快速开始

```bash
npm install
npm run db:setup
npm run dev
```

- 站点：http://localhost:3000
- 后台：http://localhost:3000/admin

### 默认管理员（对齐 PHP login 种子）

| 用户名 | 密码 |
|--------|------|
| love123 | love123 |
| user123 | love123 |

可在 `.env` 修改后执行 `npm run db:seed`。

## 技术栈

- 框架：Next.js 14 App Router（前后端一体）
- 语言：TypeScript（类型安全）
- 样式：Tailwind CSS（快速控制视觉）
- 组件：shadcn/ui（可深度定制）
- 动效：Framer Motion（细腻动画）
- 数据库：Prisma + SQLite（简单够用）
- 部署：Vercel（一键上线）

## 未迁入第一版（PHP 有、本版后置）

云存储全家桶、SMTP 邮件、微信小程序、IP 拉黑、文章/相册密码门完整链路、三套底栏模板 DIY。
