# LGNewUI 微信小程序（uni-app · Vue 3）

用 **uni-app + Vue 3 + Vite** 重写的小程序端，继续对接现有 PHP 接口（`api/*.json` 等）。

## 页面

| Tab | 路径 | 说明 |
|-----|------|------|
| 日记 | `pages/little/index` | 点点滴滴 / 文章列表 |
| 留言 | `pages/leaving/index` | 祝福留言 |
| 首页 | `pages/index/index` | 情侣信息、纪念日、摘要 |
| 相册 | `pages/loveImg/index` | 恋爱相册 |
| 列表 | `pages/list/index` | 恋爱清单 |

子页：文章详情 `pages/article`、相册详情 `pages/photo`、通用详情 `pages/detail`。

## 配置

编辑 `src/config/env.js`：

```js
const BASE_URL = 'https://你的域名/'
```

并在微信公众平台配置 request 合法域名。

## 开发 / 构建

```bash
cd uniapp
npm install
npm run dev:mp-weixin    # 输出 dist/dev/mp-weixin ，用微信开发者工具打开
npm run build:mp-weixin  # 产出 dist/build/mp-weixin
```

原生小程序工程仍保留在仓库根目录 `wxapp/`（旧版参考）。
