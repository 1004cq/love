# LG_NewUi（PHP 原版 · 完整解压）

来自 [me.cq.je/love.zip](https://me.cq.je/love.zip) 的 **PHP LGNewUI**，已在仓库内完整解压（含 `website/vendor/`）。

| 路径 | 说明 |
|------|------|
| `love.zip` | 压缩包（已脱敏后重打包） |
| `website/` | 网页端 |
| `wxapp/` | 微信小程序端 |
| `DEPLOYMENT.md` | 部署说明 |

## 隐私说明

仓库内已移除/清空敏感示例数据，例如：

- 邮件 SMTP 账号与授权码
- 访客留言 JSON 中的 QQ / 城市等信息
- 控制台联系 QQ / 邮箱
- 数据库配置请复制 `website/admin/Config_DB.example.php` → `Config_DB.php`（该文件已 gitignore）

## 部署

1. 将 `website/` 上传到站点根目录（PHP 8.2–8.5 + MySQL）
2. 配置 `admin/Config_DB.php`（或环境变量，见 DEPLOYMENT.md）
3. 浏览器打开 `/install/` 完成安装
4. 详见 `DEPLOYMENT.md`

口号：小手一牵 岁岁年年～
