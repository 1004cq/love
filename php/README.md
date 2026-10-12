# PHP LGNewUI（love.zip 原版）

本目录是 [https://me.cq.je/love.zip](https://me.cq.je/love.zip) 的 **PHP 原版**源码，不是 Next.js 重写。

| 路径 | 说明 |
|------|------|
| `love.zip` | 从 me.cq.je 下载的原始压缩包 |
| `website/` | 网页端（`index.php`、后台 `admin/` 等） |
| `wxapp/` | 微信小程序端 |
| `DEPLOYMENT.md` | 官方部署说明 |

## 安装

1. 将 `website/` 部署到 PHP 8.2–8.5 + MySQL 站点根目录  
2. 在 `website/` 执行：`composer install`（`vendor/` 未入库，需自行安装）  
3. 浏览器打开 `/install/` 完成安装  
4. 详见 `DEPLOYMENT.md`

## 与仓库根目录 Next.js 的关系

- 仓库根目录：`Next.js` 重写版（技术栈迁移）  
- 本目录 `php/`：**原版 PHP LGNewUI**
