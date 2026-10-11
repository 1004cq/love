import { prisma } from "@/lib/prisma";

export type SiteConfigView = {
  boy: string;
  girl: string;
  title: string;
  logo: string;
  writing: string;
  boyAvatar?: string | null;
  girlAvatar?: string | null;
  startTime: Date;
  loveDayTitle: string;
  card1: string;
  card2: string;
  card3: string;
  card4: string;
  deci1: string;
  deci2: string;
  deci3: string;
  deci4: string;
  icp?: string | null;
  copyright: string;
  siteDescription?: string | null;
};

const defaults: Omit<SiteConfigView, "startTime"> & { startTime: string } = {
  boy: "My Name",
  girl: "My Name",
  title: "LG_NewUi",
  logo: "LG_Demo",
  writing: "小手一牵 岁岁年年～",
  boyAvatar: null,
  girlAvatar: null,
  startTime: "2023-07-19T00:00:00.000Z",
  loveDayTitle: "这是我们一起走过的",
  card1: "点点滴滴",
  card2: "祝福留言",
  card3: "恋爱相册",
  card4: "恋爱事件",
  deci1: "记录生活点点滴滴",
  deci2: "书写下你的留言祝福～",
  deci3: "记录下美好瞬间～",
  deci4: "总有些惊奇的际遇",
  icp: null,
  copyright: "LG_NewUi Web",
  siteDescription:
    "LGNewUI是一个专注于情侣和爱情的网站，提供了多种功能来帮助用户记录和分享他们的恋爱故事。",
};

export async function getSiteConfig(): Promise<SiteConfigView> {
  const [config, setting] = await Promise.all([
    prisma.siteConfig.findUnique({ where: { id: 1 } }),
    prisma.setting.findUnique({ where: { id: 1 } }),
  ]);

  const startTime =
    config?.startTime ||
    setting?.relationshipStartAt ||
    new Date(defaults.startTime);

  if (!config) {
    return { ...defaults, startTime };
  }

  return {
    boy: config.boy,
    girl: config.girl,
    title: config.title,
    logo: config.logo,
    writing: config.writing,
    boyAvatar: config.boyAvatar,
    girlAvatar: config.girlAvatar,
    startTime,
    loveDayTitle: config.loveDayTitle,
    card1: config.card1,
    card2: config.card2,
    card3: config.card3,
    card4: config.card4,
    deci1: config.deci1,
    deci2: config.deci2,
    deci3: config.deci3,
    deci4: config.deci4,
    icp: config.icp,
    copyright: config.copyright,
    siteDescription: config.siteDescription,
  };
}
