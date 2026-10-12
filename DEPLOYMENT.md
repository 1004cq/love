# LGNewUI 2026 升级版部署说明

## 运行环境

- PHP 8.2–8.5（目标：PHP 8.5）
- MySQL 5.7 或更高版本
- PHP 扩展：mysqli、pdo_mysql、mbstring、json、curl、gd、exif、fileinfo
- 已启用 HTTPS（小程序必须使用 HTTPS）

## 网页端安装

1. 将 `website` 目录中的全部文件上传到站点根目录。
2. 确认 `admin/Config_DB.php`、`install/`、`Lovefolder/`、`api/` 对 PHP 进程可写。
3. 打开 `https://你的域名/install/`，填写数据库与至少 8 位安全码，按向导完成安装。
4. 安装完成会自动创建 `install/install.lock`。请勿删除该文件。
5. 登录 `/admin/` 后可在“系统状态”检查 PHP、MySQL、上传目录和扩展。

## 小程序端配置

1. 用微信开发者工具打开 `wxapp`。
2. 编辑 `wxapp/config/env.js`：把 `https://example.com/` 改为网页端 HTTPS 域名。
3. 在微信公众平台“开发管理 → 开发设置 → 服务器域名”添加该域名为 request 合法域名。
4. 在 `project.config.json` 中填入自己的小程序 AppID，再上传代码审核/发布。

小程序继续使用既有的 `api/data.json`、留言、文章、相册、恋爱清单等接口；新增 `api/health.php` 和 `api/version.json` 用于连接状态与版本确认。

## 环境变量（可选，推荐生产环境）

如果主机支持 PHP-FPM / Docker / 面板环境变量，可设置以下值。它们优先于安装器写入的配置，避免将数据库密码保存在 Web 根目录：

```text
LG_DB_HOST=127.0.0.1
LG_DB_USER=你的数据库用户
LG_DB_PASSWORD=你的数据库密码
LG_DB_NAME=你的数据库名
LG_SECURITY_CODE=至少8位的后台安全码
```

## 本次升级内容

- 适配 PHP 8.2–8.5，修复旧版登录语句、旧邮件组件 `each()` 与 `utf8_encode()` 兼容问题。
- 安装器改为检查 PHP 8.2–8.5、MySQL 5.7+，并校验数据库名、转义配置写入。
- 登录成功后轮换 Session ID；Session 使用 HttpOnly 与 SameSite=Lax。
- 外部 cURL 恢复 TLS 证书校验；上传按真实 MIME 白名单校验并限制 50MB；媒体删除防止目录穿越。
- 增加安全响应头、禁止目录索引、阻止上传目录执行脚本。
- 网页与后台升级为现代圆角卡片、响应式布局，网页支持深色模式。
- 小程序采用统一可配置域名、10 秒超时/一次重试、首页数据离线缓存与后端健康检测。

## 发布前检查

- 访问 `/api/health.php`，应返回 `status: ok`。
- 后台“系统状态”中的各项应为绿色。
- 小程序域名必须是 HTTPS 且已在微信公众平台配置。
- 请备份原数据库后再迁移生产站；本包为干净安装包，不包含原数据库凭据、访问日志和安装锁。
