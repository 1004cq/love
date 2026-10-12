# LG_NewUi

PHP 情侣小站（LGNewUI），含网页端与微信小程序端。

| 路径 | 说明 |
|------|------|
| `website/` | 网页端（含 `vendor/`） |
| `uniapp/` | 微信小程序端（uni-app · Vue 3，推荐） |
| `wxapp/` | 旧版原生小程序（参考） |
| `love.zip` | 脱敏后的打包副本 |
| `DEPLOYMENT.md` | 部署说明 |

## 隐私说明

仓库内不含真实隐私数据：

- 邮件 SMTP 示例为 `smtp.example.com`，账号与授权码留空
- `website/api/leav.json` 为空数组（无 QQ / 城市 / 昵称）
- 控制台联系方式已清空
- 数据库配置请复制 `website/admin/Config_DB.example.php` → `Config_DB.php`（已 gitignore；不在 `love.zip` 中）

## 网页端语言

右下角可切换中文 / English（保存在浏览器本地，仅翻译界面文案，不改动用户内容）。

## 性能

网页端已做压缩/缓存、按页裁剪静态资源、接口短缓存与健康检查增强；详见 `DEPLOYMENT.md`。

## 部署

1. 将 `website/` 上传到站点根目录（PHP 8.2–8.5 + MySQL）
2. 配置 `admin/Config_DB.php`（或环境变量，见 `DEPLOYMENT.md`）
3. 浏览器打开 `/install/` 完成安装
4. 详见 `DEPLOYMENT.md`

口号：小手一牵 岁岁年年～
