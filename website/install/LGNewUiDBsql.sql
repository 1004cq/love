-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2024-12-21 22:49:53
-- 服务器版本： 5.7.43-log
-- PHP 版本： 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `LGNewUiDBsql 2024 冬至限定-20241221 By Ki`
--

-- --------------------------------------------------------

--
-- 表的结构 `about`
--

CREATE TABLE `about` (
  `id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL COMMENT '标题',
  `aboutimg` varchar(100) NOT NULL COMMENT '背景图片',
  `info1` varchar(50) NOT NULL COMMENT '对话1',
  `info2` varchar(50) NOT NULL COMMENT '对话2',
  `info3` varchar(50) NOT NULL COMMENT '对话3',
  `btn1` varchar(30) NOT NULL COMMENT '按钮确定',
  `btn2` varchar(30) NOT NULL COMMENT '按钮取消',
  `infox1` varchar(30) NOT NULL COMMENT 'x2',
  `infox2` varchar(30) NOT NULL COMMENT 'x2',
  `infox3` varchar(30) NOT NULL COMMENT 'x2',
  `infox4` varchar(30) NOT NULL COMMENT 'x2',
  `infox5` varchar(30) NOT NULL COMMENT 'x2',
  `infox6` varchar(30) NOT NULL COMMENT 'x2',
  `btnx2` varchar(30) NOT NULL COMMENT 'btnx2',
  `infof1` varchar(30) NOT NULL COMMENT 'f3',
  `infof2` varchar(30) NOT NULL COMMENT 'f3',
  `infof3` varchar(30) NOT NULL COMMENT 'f3',
  `infof4` varchar(30) NOT NULL COMMENT 'f3',
  `btnf3` varchar(30) NOT NULL COMMENT 'btnf3',
  `infod1` varchar(30) NOT NULL COMMENT 'd4',
  `infod2` varchar(30) NOT NULL COMMENT 'd4',
  `infod3` varchar(30) NOT NULL COMMENT 'd4',
  `infod4` varchar(30) NOT NULL COMMENT 'd4',
  `infod5` varchar(30) NOT NULL COMMENT 'd4'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `about`
--

INSERT INTO `about` (`id`, `title`, `aboutimg`, `info1`, `info2`, `info3`, `btn1`, `btn2`, `infox1`, `infox2`, `infox3`, `infox4`, `infox5`, `infox6`, `btnx2`, `infof1`, `infof2`, `infof3`, `infof4`, `btnf3`, `infod1`, `infod2`, `infod3`, `infod4`, `infod5`) VALUES
(1, 'Ki_About', 'https://img.gejiba.com/images/7c5f1a655788c0d6ff0cae50232dde65.jpg', 'Hi, 欢迎来访~', '花有重开日 人无再少年', '本项目是之前闲着写着玩的 但是...', '听我介绍', '结束介绍', '情侣小站Like Girl是本人的原创项目 当前版本为付费版', '在2022年暑假假期最后的几天里写完了第一个版本', '目前已开源到码云 版本更新到5.0 项目已暂停维护', '写后端的时候也是头都大了 也可以说是边学边写 所以写的很吃力', '在开发过程中遇到了许多问题 也是只能自己探索解决 ...', '喜欢探索知识，热爱学习新知识，热爱开源文化', '为什么叫 Ki？', '不知你是否看过《比悲伤更悲伤的故事》电影', '嗨，我是k，如果有下辈子的话，', '“我想当戒指，眼镜，床和笔记本，这样的话，我就可以...”', '当然跟这个没有关系哈哈', '本站前端所有页面', '点点滴滴页 #little.php', '祝福留言页 #leaving.php', '恋爱相册页 #loveImg.php', '恋爱事件页 #list.php', '首页 #index.php');

-- --------------------------------------------------------

--
-- 表的结构 `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `articletext` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `articletime` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `articletitle` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `articlename` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ip_city` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ip归属地',
  `encryptionSwitch` tinyint(4) NOT NULL COMMENT '文章加密开关-20240501',
  `PasswordSee` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '文章加密密码-20240501'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `Behavior`
--

CREATE TABLE `Behavior` (
  `id` int(11) NOT NULL,
  `L_name` varchar(50) NOT NULL COMMENT '用户昵称',
  `L_user` varchar(100) NOT NULL COMMENT '用户名',
  `L_ip` varchar(50) NOT NULL COMMENT '用户IP',
  `L_city` varchar(30) NOT NULL COMMENT '用户归属地',
  `L_date` varchar(50) NOT NULL COMMENT '用户登陆时间',
  `L_os` varchar(30) NOT NULL COMMENT '用户设备',
  `L_br` varchar(30) NOT NULL COMMENT '用户浏览器'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `CloudStorageConfig`
--

CREATE TABLE `CloudStorageConfig` (
  `id` int(11) NOT NULL,
  `Provider` varchar(100) NOT NULL,
  `AccessKey` varchar(255) NOT NULL,
  `SecretKey` text NOT NULL,
  `BucketName` varchar(255) NOT NULL,
  `Endpoint` varchar(255) NOT NULL,
  `customCDN` varchar(255) NOT NULL,
  `Region` varchar(100) DEFAULT NULL,
  `Current` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `CloudStorageConfig`
--

INSERT INTO `CloudStorageConfig` (`id`, `Provider`, `AccessKey`, `SecretKey`, `BucketName`, `Endpoint`, `customCDN`, `Region`, `Current`) VALUES
(1, 'AWS-S3', '', '', '', '', '', '', NULL),
(2, '腾讯云-COS', '', '', '', '', '', '', 1),
(3, '阿里云-OSS', '', '', '', '', '', NULL, NULL),
(4, '七牛云-Kodo', '', '', '', '', '', '', NULL),
(5, '又拍云-USS', '', '', '', '', '', 'us-east-1', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `CoupleAvatarFrames`
--

CREATE TABLE `CoupleAvatarFrames` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `scale` float DEFAULT '1',
  `top_offset` int(11) DEFAULT '0',
  `left_offset` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `CoupleAvatarFrames`
--

INSERT INTO `CoupleAvatarFrames` (`id`, `title`, `url`, `scale`, `top_offset`, `left_offset`, `created_at`) VALUES
(1, '男主头像框', 'https://s1.locimg.com/2024/09/23/2694fbf0dcc97.gif', 1.5, 0, 0, '0000-00-00 00:00:00'),
(2, '女主头像框', 'https://s1.locimg.com/2024/09/23/5b0754ca4dd35.gif', 1.5, 0, 0, '2024-10-03 08:48:22'),
(3, '预设头像框 — 1', 'https://s1.locimg.com/2024/09/23/1225767ffb03b.png', 1.6, 0, 0, '2024-10-03 08:48:22'),
(4, '预设头像框 — 2', 'https://s1.locimg.com/2024/10/04/b5379d054e3c2.png', 1.43, 0, 0, '2024-10-03 08:48:22'),
(5, '预设头像框 — 3', 'https://s1.locimg.com/2024/10/04/976e7750eecae.png', 1.5, 0, 0, '2024-10-03 08:48:22'),
(6, '预设头像框 — 4', 'https://s1.locimg.com/2024/10/04/1b0bbcb27427a.png', 1.2, 0, 0, '2024-10-03 08:48:22'),
(7, '预设头像框 — 5', 'https://s1.locimg.com/2024/10/04/a57333d221509.png', 1.4, 0, 0, '2024-10-03 08:48:22');

-- --------------------------------------------------------

--
-- 表的结构 `diySet`
--

CREATE TABLE `diySet` (
  `id` int(11) NOT NULL,
  `headCon` text NOT NULL,
  `footerCon` text NOT NULL,
  `cssCon` text NOT NULL,
  `Pjaxkg` varchar(1) NOT NULL COMMENT 'pjax开关',
  `Blurkg` varchar(1) NOT NULL COMMENT '高斯模糊开关',
  `diy_load` varchar(200) NOT NULL COMMENT '自定义loading图（相册）',
  `aiv_name_show` int(1) NOT NULL COMMENT '头像昵称模块显示（相册）',
  `index_essay_num` varchar(10) NOT NULL COMMENT '首页文章截取长度',
  `index_leav_num` varchar(10) NOT NULL COMMENT '首页留言截取长度',
  `diy_page_pw` varchar(100) NOT NULL COMMENT '自定义密码设置',
  `open_pw` int(1) NOT NULL COMMENT '密码访问控制',
  `url_pw` varchar(300) NOT NULL,
  `carousel` text NOT NULL,
  `Fullscreen` tinyint(1) DEFAULT '1',
  `is_aboutActive` tinyint(4) NOT NULL COMMENT '关于页面开关-20240501',
  `StationMaintenanceSwitch` tinyint(4) NOT NULL COMMENT '站点维护开关-20240501',
  `SmTitie` varchar(100) NOT NULL COMMENT '维护标题-20240501',
  `SmText` varchar(200) NOT NULL COMMENT '维护副文本-20240501',
  `SmBgUrl` varchar(300) NOT NULL COMMENT '维护背景图-20240501',
  `BackgroundMask` int(11) NOT NULL DEFAULT '2',
  `pcCarouselHeight` varchar(100) NOT NULL DEFAULT '80vh',
  `mobileCarouselHeight` varchar(100) NOT NULL DEFAULT '400px',
  `pcPhotoCoverHeight` varchar(100) NOT NULL DEFAULT '80vh',
  `mobilePhotoCoverHeight` varchar(100) NOT NULL DEFAULT '300px',
  `pcImgMaxHeight` varchar(100) NOT NULL DEFAULT '500px',
  `mobileImgMaxHeight` varchar(100) NOT NULL DEFAULT '330px',
  `RandomSort` int(11) NOT NULL DEFAULT '1',
  `TabTemplate` int(11) NOT NULL DEFAULT '3'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `diySet`
--

INSERT INTO `diySet` (`id`, `headCon`, `footerCon`, `cssCon`, `Pjaxkg`, `Blurkg`, `diy_load`, `aiv_name_show`, `index_essay_num`, `index_leav_num`, `diy_page_pw`, `open_pw`, `url_pw`, `carousel`, `Fullscreen`, `is_aboutActive`, `StationMaintenanceSwitch`, `SmTitie`, `SmText`, `SmBgUrl`, `BackgroundMask`, `pcCarouselHeight`, `mobileCarouselHeight`, `pcPhotoCoverHeight`, `mobilePhotoCoverHeight`, `pcImgMaxHeight`, `mobileImgMaxHeight`, `RandomSort`, `TabTemplate`) VALUES
(1, '&lt;!-- 引入字体 --&gt;\n&lt;link rel=&quot;stylesheet&quot; href=&quot;https://fonts.googleapis.com/css2?family=Ma+Shan+Zheng&amp;amp;family=Pacifico&amp;amp;display=swap&quot;&gt;', '&lt;!-- &lt;script src=&quot;https://img-love.kikiw.cn/jsxg/fengye/cursor6.js&quot; type=&quot;text/javascript&quot;&gt;&lt;/script&gt; --&gt;', '/*顶部logo文字字体*/\n/*\n.alogo{\n    font-family: &#039;Pacifico&#039;,Arial,&#039;Ma Shan Zheng&#039;,Helvetica;\n    font-weight: 400;\n}\n*/\n@media screen and (max-width: 768px) {\ndiv#homePage {\n    height: 260px!important;\n}\n}\n\n.little_texts b{\n    background-color: transparent;\n    font-size: inherit;\n}\n\n.mar_0 {\n    margin: 0!important;\n}', '1', '2', 'https://img.gejiba.com/images/fe42086d4fb326caa11c6c323e8f18d5.gif', 1, '3', '15', 'love', 0, 'https://img.gejiba.com/images/a354dd8e47b08166f00d769da32a79aa.jpg', 'https://img.gejiba.com/images/47021e4863f21d29426bae3e1c037796.jpg\nhttps://img.gejiba.com/images/c7bb0fc9ab8aaf00588062f502382864.jpg\nhttps://img.gejiba.com/images/8cc6b86af3a169db11cfd9dc59d2079d.jpg\nhttps://img.gejiba.com/images/ded5a377126ff86db78bc17008f20e6f.jpg\nhttps://img.gejiba.com/images/a529aa2a8960dd5d181dc01e4ea8fc1c.jpg', 1, 0, 0, '站点正在维护中 请稍后访问！', '花开花落终有时，相逢相聚本无意。《LGNewUI》--Ki', 'https://img.gejiba.com/images/46df62a621b0f0a256ea4a5ac47c3087.jpg', 2, '80vh', '400px', '80vh', '300px', '500px', '330px', 1, 3);

-- --------------------------------------------------------

--
-- 表的结构 `ImageSet`
--

CREATE TABLE `ImageSet` (
  `id` int(11) NOT NULL,
  `TextWateSwitch` varchar(10) NOT NULL,
  `ImgText` varchar(50) NOT NULL,
  `colorPicker` varchar(50) NOT NULL,
  `ColorOpacity` varchar(10) NOT NULL,
  `PhotoQuality` varchar(10) NOT NULL,
  `UploadDirectory` varchar(300) NOT NULL DEFAULT 'Lovefolder/',
  `localOrCloud` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `ImageSet`
--

INSERT INTO `ImageSet` (`id`, `TextWateSwitch`, `ImgText`, `colorPicker`, `ColorOpacity`, `PhotoQuality`, `UploadDirectory`, `localOrCloud`) VALUES
(1, 'true', 'Love-Li', '255,255,255', '80', '30', 'Lovefolder/', 0);

-- --------------------------------------------------------

--
-- 表的结构 `IPerror`
--

CREATE TABLE `IPerror` (
  `id` int(11) NOT NULL,
  `ipAdd` varchar(100) NOT NULL COMMENT 'ip归属地',
  `Time` varchar(200) NOT NULL COMMENT '时间',
  `State` text NOT NULL COMMENT '拉黑ip',
  `text` varchar(100) NOT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `leaving`
--

CREATE TABLE `leaving` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名字',
  `QQ` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'QQ号码',
  `text` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '留言内容',
  `time` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '时间',
  `ip` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ip记录',
  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '归属地',
  `switch` int(11) NOT NULL DEFAULT '0' COMMENT '留言审核',
  `Os_info` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Windows 10' COMMENT '操作系统',
  `Br_info` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Chrome' COMMENT '浏览器'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `leavSet`
--

CREATE TABLE `leavSet` (
  `id` int(11) NOT NULL,
  `jiequ` varchar(10) NOT NULL COMMENT '截取长度',
  `anonymousArr` text NOT NULL COMMENT '随机头像URL-20240501',
  `lanjiezf` varchar(500) NOT NULL COMMENT '违禁词',
  `leav_day_num` int(11) NOT NULL COMMENT '一天限制多少条留言	',
  `MsgSwitch` int(11) NOT NULL DEFAULT '1',
  `MsgVerify` int(11) NOT NULL DEFAULT '1',
  `CutOffSwitch` int(11) NOT NULL DEFAULT '1',
  `EmojiSwitch` int(11) NOT NULL DEFAULT '1',
  `randomWord` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `leavSet`
--

INSERT INTO `leavSet` (`id`, `jiequ`, `anonymousArr`, `lanjiezf`, `leav_day_num`, `MsgSwitch`, `MsgVerify`, `CutOffSwitch`, `EmojiSwitch`, `randomWord`) VALUES
(1, '30', 'https://img.gejiba.com/images/a3464b68829c65c86726f6d9b1da1984.jpg\nhttps://img.gejiba.com/images/d5de7616a13b063f165dce236c39b42b.jpg\nhttps://img.gejiba.com/images/f49926e0c49348b6d3203e8a22084954.jpg\nhttps://img.gejiba.com/images/017a15b9bb054ce5cf7f11cc2d0c38ca.jpg\nhttps://img.gejiba.com/images/4d898720377b0fa656e34c4a047a4bd2.png\nhttps://img.gejiba.com/images/e33fe0d2aa8854766cf26245f099bbf4.png\nhttps://img.gejiba.com/images/0c24f25f6fc543a60a82f2fd74ac19de.png\nhttps://img.gejiba.com/images/25f128c999a132d2fc34683a45294827.png\nhttps://img.gejiba.com/images/2e4c04dd573604d8967835cf6a5b8f29.png\nhttps://img.gejiba.com/images/33124c61d6db0e1cdf688d644d018d99.png\nhttps://img.gejiba.com/images/e18e0954745be0a7bc1c826a46e00f86.png', '操傻屌', 5, 1, 1, 1, 1, '情定今生 爱如繁星闪耀 永恒璀璨\n鸳鸯比翼飞 恩爱两不疑 情长岁月久\n携手同行 看遍世间美景 爱意永不渝\n琴瑟和鸣 奏响爱情乐章 相伴到天荒\n花开并蒂 情连一心 共赴美好前程\n愿君心似我心 定不负相思意 情久长\n比翼连枝 恩爱缠绵 岁月温柔相伴\n情牵一世 缘定三生 携手走过春秋\n两心相悦 爱意绵绵 共筑温馨港湾\n执手相伴 风雨同舟 情比金坚如初\n良缘由凤缔 佳偶自天成 恩爱永相随\n爱意深似海 情绵无尽期 白首永相伴\n心心相印 情情相连 一生一世一双人\n共赏烟霞 同度风雨 情路漫漫亦甜蜜\n佳偶天成心相印 情丝万缕永相连\n携手共赴红尘路 恩爱相伴到永远\n情投意合两情悦 相濡以沫伴一生\n愿得一人心 白首不相离 爱情永保鲜\n花前月下情无尽 携手同行到白头\n一世情长 两心相悦 三生有幸遇君\n比目连枝情似火 鸳鸯戏水爱如潮\n情丝缠绕 爱意交融 共谱恋曲悠扬\n红尘相伴情愈浓 岁月相守爱更深\n与君共执手 相伴岁月长 情暖人间路\n爱意悠悠情切切 携手同行乐融融\n同心同德情不断 相亲相爱到永远\n情定此生永不悔 爱伴一世常相随\n携手走过风雨路 共赏晴空万里云\n一眼万年情已定 一生相伴爱无尽\n情在时光里沉淀 爱在岁月中永恒\n比翼双飞情似蜜 连理同枝爱如花\n共赴爱河情无尽 同享人生乐无边\n情牵彼此心相守 爱满人间岁无忧\n执子之手情愈暖 与子偕老爱更浓\n爱意如诗韵悠长 情丝似画美未央\n两情相悦共欢颜 携手同行谱新篇\n情似春风拂面暖 爱如冬阳照心田\n红尘作伴情无尽 岁月为歌爱永恒\n愿君与卿情长久 朝朝暮暮共欢颜\n比翼齐飞情浪漫 同心共筑爱家园\n情在岁月中沉淀 爱于时光里升华\n携手相伴情路远 爱意绵延岁月长\n一眼定情心相印 终生相伴爱永恒\n情丝万缕系君心 爱意千般伴卿身\n共赴爱河情似海 同修姻缘爱如山\n情暖春秋冬夏 爱伴日月星辰\n执手相看情脉脉 倾心相守爱悠悠\n两情相悦春常在 一世恩爱梦也甜\n情定今生缘不尽 爱满人间福永恒\n携手同行情路畅 相濡以沫爱悠扬\n你们的爱似星辰璀璨 每一刻都盈满温暖与幸福 愿99哦\n岁月长河悠悠 携手相伴的你们定能共创美好回忆 祝福99\n爱情如春花绚烂盛放 两颗心永远相依相偎 愿你们长长久久\n祝福你们的心像潺潺溪水 涓涓流淌而永不干涸 情意悠悠绵长 99呀\n于彼此陪伴之时 你们定能发掘生活的每一帧美好瞬间 愿爱情99\n你们的爱情如同浩渺海洋 宽广无垠 彼此倾心守护 愿长长久久\n心 于每个清晨相互唤醒 每个夜晚相互依偎 愿你们爱情久久\n祝福在爱的旅途上并肩携手的你们 共历风雨沧桑 爱情99哦\n未来洒满阳光 盈满希望 爱意如初 岁月安然静好 愿你们99\n你们的爱情仿若美酒醇厚 随着时光流转 愈发香浓 永不失色 祝福99\n心像花朵欣然绽放 每日都弥漫着甜蜜的芬芳 愿你们爱情长长久久\n在岁月的轮回交替间 要永远珍视彼此相伴的时光 祝福你们99\n祝福你们的爱如涓涓细流 岁月悠悠 亦能经得住时光的考验 愿爱情久久\n生活宛如音乐和谐美妙 两颗心彼此印合 祝福你们长长久久\n你们的爱情像阳光般和煦温暖 驱散生活中的重重阴霾 愿99\n未来如同绚丽画卷 由你们共同绘就美好 祝福爱情长长久久\n祝福你们的心灵似广袤天空般辽阔 彼此成就不凡 愿你们99\n在每一个日出日落间 体悟爱的纯粹真谛 愿你们爱情久久\n爱情宛如晨露般澄澈晶莹 纯净而无瑕 祝福你们长长久久\n双手永远紧紧相握 在生活的各个角落 愿你们爱情99\n祝福你们的心如同繁星闪耀 永远相伴不离 愿长长久久\n你们的爱情像大海一样宽广无垠 包容所有风浪波折 愿99\n相伴的每一刻 都成为幸福的深深印记 祝福你们爱情长长久久\n未来充满欢声笑语 爱意浓郁满盈 愿你们99\n祝福你们的心仿若灯塔明亮闪耀 引领彼此走向幸福彼岸 愿爱情久久\n你们的爱如阳光遍洒 使生活处处充满温暖 祝福长长久久\n心灵似繁花盛绽的花海般绚烂 永远芬芳满溢 愿你们99\n在爱的旅途中 坚守初心 一路相伴同行 祝福你们爱情长长久久\n祝福你们的爱情如同彩虹般绚丽多彩 斑斓如画 愿99\n生活如同星空璀璨 彼此相互依存 愿你们爱情久久');

-- --------------------------------------------------------

--
-- 表的结构 `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `user` varchar(100) NOT NULL COMMENT '登录用户名',
  `pw` char(200) NOT NULL COMMENT '登录密码'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `login`
--

INSERT INTO `login` (`id`, `user`, `pw`) VALUES
(1, 'love123', '$2y$10$vUBIaXHlxafFgThIYG9ajObldUvu3rkx5Dy9ES/d3BftykHMkuPse'),
(2, 'user123', '$2y$10$vUBIaXHlxafFgThIYG9ajObldUvu3rkx5Dy9ES/d3BftykHMkuPse');

-- --------------------------------------------------------

--
-- 表的结构 `loveday`
--

CREATE TABLE `loveday` (
  `id` int(11) NOT NULL,
  `day_title` varchar(50) NOT NULL,
  `day` varchar(100) NOT NULL,
  `day_type` int(11) NOT NULL,
  `day_Weights` int(11) NOT NULL COMMENT '恋爱日期排序-20240501',
  `isDateHidden` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `loveImg`
--

CREATE TABLE `loveImg` (
  `id` int(11) NOT NULL,
  `imgDatd` varchar(100) CHARACTER SET utf8mb4 NOT NULL COMMENT '日期',
  `imgText` varchar(200) CHARACTER SET utf8mb4 NOT NULL COMMENT '描述',
  `imgUrl` varchar(200) CHARACTER SET utf8mb4 NOT NULL COMMENT '外链',
  `upload_name` varchar(50) CHARACTER SET utf8mb4 NOT NULL COMMENT '上传者',
  `img_code` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `encryptionSwitch` int(11) NOT NULL,
  `PasswordSee` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `lovelist`
--

CREATE TABLE `lovelist` (
  `id` int(11) NOT NULL,
  `icon` int(1) NOT NULL COMMENT '是否完成',
  `eventname` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '事件内容',
  `imgurl` text COLLATE utf8_unicode_ci NOT NULL COMMENT '图片地址'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `MailSet`
--

CREATE TABLE `MailSet` (
  `id` int(11) NOT NULL,
  `host` varchar(100) NOT NULL COMMENT '发信服务器地址',
  `port` varchar(30) NOT NULL COMMENT '端口',
  `mail_ssl` varchar(10) NOT NULL COMMENT '加密协议',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `from_name` varchar(100) NOT NULL COMMENT '发件人昵称',
  `mail_user` varchar(100) NOT NULL COMMENT '账号',
  `mail_pw` varchar(100) NOT NULL COMMENT '密码',
  `admin_mail` varchar(100) NOT NULL COMMENT '接收者1',
  `admin_name` varchar(50) NOT NULL COMMENT '接收者昵称1',
  `user_mail` varchar(100) NOT NULL COMMENT '接收者2',
  `user_name` varchar(50) NOT NULL COMMENT '接收者2昵称',
  `SendingSwitch` varchar(10) NOT NULL COMMENT '邮件开关',
  `LoginMailSwitch` tinyint(4) NOT NULL COMMENT '登录邮件提醒开关-20240501',
  `LoginSendlMinute` int(11) NOT NULL COMMENT '登录邮件发送频率-20240501'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `MailSet`
--

INSERT INTO `MailSet` (`id`, `host`, `port`, `mail_ssl`, `title`, `from_name`, `mail_user`, `mail_pw`, `admin_mail`, `admin_name`, `user_mail`, `user_name`, `SendingSwitch`, `LoginMailSwitch`, `LoginSendlMinute`) VALUES
(1, 'smtp.example.com', '465', 'true', 'Love', '情侣小站', '', '', '', '男主昵称', '', '女主昵称', 'false', 0, 10);

-- --------------------------------------------------------

--
-- 表的结构 `Photolist`
--

CREATE TABLE `Photolist` (
  `id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `photo_code` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `photo_text` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo_url` varchar(400) CHARACTER SET utf8 NOT NULL,
  `photo_date` varchar(30) CHARACTER SET utf8 NOT NULL,
  `photo_byname` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `photo_type` int(11) NOT NULL DEFAULT '0',
  `VideoCover` varchar(300) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `text`
--

CREATE TABLE `text` (
  `id` int(11) NOT NULL,
  `boy` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '男name',
  `girl` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '女name',
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '网站标题',
  `logo` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '网站logo',
  `writing` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '网站文案',
  `boyimg` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '男QQ',
  `girlimg` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '女QQ',
  `diyb_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '自定义男主头像URL',
  `diyg_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '自定义女主头像URL',
  `startTime` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '开始时间',
  `icp` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '网站备案号',
  `Copyright` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '网站版权',
  `card1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `card2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `card3` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `card4` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `deci1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `deci2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `deci3` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `deci4` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bgimg` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '首页背景图片地址',
  `Animation` int(1) NOT NULL COMMENT '动画开关',
  `if_aiv` int(1) NOT NULL COMMENT '判断头像自定义',
  `Moelicp` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `policeICP` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ml_frame` int(11) NOT NULL,
  `fl_frame` int(11) NOT NULL,
  `seo_switch` int(11) NOT NULL DEFAULT '1',
  `site_description` varchar(300) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'LGNewUI是一个专注于情侣和爱情的网站，提供了多种功能来帮助用户记录和分享他们的恋爱故事。该网站具有丰富的互动性和个性化设置选项，旨在为情侣用户提供一个温馨、浪漫的程序。',
  `global_keywords` varchar(300) COLLATE utf8_unicode_ci NOT NULL DEFAULT '情侣网站, 恋爱记录, 祝福留言, 情侣相册, 恋爱清单, 爱情纪念, 情侣头像框, 祝福语句, 情侣互动, 爱情相册, 情侣事件记录, 情侣留言, 爱情故事, 情感交流, 用户互动, 祝福卡片, 音乐分享, 甜蜜瞬间, 情侣活动, 爱情动态, LGNewUI',
  `loveDayTitle` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '这是我们一起走过的',
  `loveIconLink` varchar(300) COLLATE utf8_unicode_ci NOT NULL DEFAULT '../Style/img/like.svg'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `text`
--

INSERT INTO `text` (`id`, `boy`, `girl`, `title`, `logo`, `writing`, `boyimg`, `girlimg`, `diyb_url`, `diyg_url`, `startTime`, `icp`, `Copyright`, `card1`, `card2`, `card3`, `card4`, `deci1`, `deci2`, `deci3`, `deci4`, `bgimg`, `Animation`, `if_aiv`, `Moelicp`, `policeICP`, `ml_frame`, `fl_frame`, `seo_switch`, `site_description`, `global_keywords`, `loveDayTitle`, `loveIconLink`) VALUES
(1, 'My Name', 'My Name', 'LG_NewUi', 'LG_Demo', '小手一牵 岁岁年年～', '10001', '10002', 'https://img.gejiba.com/images/57bc92dc7b60fd50f1649bad2c6429e7.jpg', 'https://img.gejiba.com/images/9575cb9cb22a30fe70795792431f2a96.jpg', '2023-07-19T00:00', '', 'LG_NewUi Web', '点点滴滴', '祝福留言', '恋爱相册', '恋爱事件', '记录生活点点滴滴', '书写下你的留言祝福～', '记录下美好瞬间～', '总有些惊奇的际遇', 'https://img.gejiba.com/images/b0d44ed67e25235f552aacbe32d81b5c.jpg', 1, 1, '', '', 0, 0, 1, 'LGNewUI是一个专注于情侣和爱情的网站，提供了多种功能来帮助用户记录和分享他们的恋爱故事。该网站具有丰富的互动性和个性化设置选项，旨在为情侣用户提供一个温馨、浪漫的程序。', '情侣网站, 恋爱记录, 祝福留言, 情侣相册, 恋爱清单, 爱情纪念, 情侣头像框, 祝福语句, 情侣互动, 爱情相册, 情侣事件记录, 情侣留言, 爱情故事, 情感交流, 用户互动, 祝福卡片, 音乐分享, 甜蜜瞬间, 情侣活动, 爱情动态, LGNewUI', '这是我们一起走过的', '../Style/img/like.svg');

-- --------------------------------------------------------

--
-- 表的结构 `warning`
--

CREATE TABLE `warning` (
  `id` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL COMMENT 'ip地址',
  `gsd` varchar(50) NOT NULL COMMENT '归属地',
  `time` varchar(80) NOT NULL COMMENT '时间',
  `file` varchar(100) NOT NULL COMMENT '路径'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `wx_app_config`
--

CREATE TABLE `wx_app_config` (
  `id` int(11) NOT NULL,
  `audit_mode` tinyint(4) DEFAULT '0',
  `template_id` int(11) DEFAULT NULL,
  `custom_font_url` varchar(255) DEFAULT NULL,
  `swiperHeight` int(11) NOT NULL,
  `sample_title` varchar(255) NOT NULL,
  `test_cover` varchar(255) NOT NULL,
  `sample_description` varchar(255) NOT NULL,
  `share_title` varchar(255) NOT NULL,
  `share_cover` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `wx_app_config`
--

INSERT INTO `wx_app_config` (`id`, `audit_mode`, `template_id`, `custom_font_url`, `swiperHeight`, `sample_title`, `test_cover`, `sample_description`, `share_title`, `share_cover`) VALUES
(1, 1, 3, NULL, 700, '正在读这篇文章的你是正在经历着青春岁月？', '/Style/img/AdminLogo.webp', '在时光的长河中，青春如同一颗璀璨而又转瞬即逝的流星，划过我们生命的夜空。它是一个短暂的美梦，当我们从梦中醒来，却发现它早已消失得无影无踪。那么，正在读这篇文章的你，是正在经历着如梦似幻的青春岁月，还是那一段段青春往事早已沉淀为心中难以言说的遗憾呢？', 'LGNewUi', '/Style/img/AdminLogo.webp');

--
-- 转储表的索引
--

--
-- 表的索引 `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `Behavior`
--
ALTER TABLE `Behavior`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `CloudStorageConfig`
--
ALTER TABLE `CloudStorageConfig`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `CoupleAvatarFrames`
--
ALTER TABLE `CoupleAvatarFrames`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `diySet`
--
ALTER TABLE `diySet`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `ImageSet`
--
ALTER TABLE `ImageSet`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `IPerror`
--
ALTER TABLE `IPerror`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `leaving`
--
ALTER TABLE `leaving`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `leavSet`
--
ALTER TABLE `leavSet`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `loveday`
--
ALTER TABLE `loveday`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `loveImg`
--
ALTER TABLE `loveImg`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `lovelist`
--
ALTER TABLE `lovelist`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `MailSet`
--
ALTER TABLE `MailSet`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `Photolist`
--
ALTER TABLE `Photolist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `PhotoID` (`photo_id`);

--
-- 表的索引 `text`
--
ALTER TABLE `text`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `warning`
--
ALTER TABLE `warning`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `wx_app_config`
--
ALTER TABLE `wx_app_config`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `about`
--
ALTER TABLE `about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `Behavior`
--
ALTER TABLE `Behavior`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `CloudStorageConfig`
--
ALTER TABLE `CloudStorageConfig`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `CoupleAvatarFrames`
--
ALTER TABLE `CoupleAvatarFrames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `diySet`
--
ALTER TABLE `diySet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `ImageSet`
--
ALTER TABLE `ImageSet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `IPerror`
--
ALTER TABLE `IPerror`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `leaving`
--
ALTER TABLE `leaving`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `leavSet`
--
ALTER TABLE `leavSet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `loveday`
--
ALTER TABLE `loveday`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `loveImg`
--
ALTER TABLE `loveImg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `lovelist`
--
ALTER TABLE `lovelist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `MailSet`
--
ALTER TABLE `MailSet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `Photolist`
--
ALTER TABLE `Photolist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `text`
--
ALTER TABLE `text`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `warning`
--
ALTER TABLE `warning`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `wx_app_config`
--
ALTER TABLE `wx_app_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 限制导出的表
--

--
-- 限制表 `Photolist`
--
ALTER TABLE `Photolist`
  ADD CONSTRAINT `PhotoID` FOREIGN KEY (`photo_id`) REFERENCES `loveImg` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
