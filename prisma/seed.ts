import { PrismaClient } from "@prisma/client";
import bcrypt from "bcryptjs";

const prisma = new PrismaClient();

async function main() {
  const start =
    process.env.RELATIONSHIP_START || "2023-07-19T00:00:00.000Z";

  // PHP login defaults: love123 / user123 — keep alice/bob aliases for env override
  const partners = [
    {
      username: process.env.PARTNER_A_USERNAME || "love123",
      password: process.env.PARTNER_A_PASSWORD || "love123",
      displayName: process.env.PARTNER_A_NAME || "My Name",
    },
    {
      username: process.env.PARTNER_B_USERNAME || "user123",
      password: process.env.PARTNER_B_PASSWORD || "love123",
      displayName: process.env.PARTNER_B_NAME || "My Name",
    },
  ];

  for (const partner of partners) {
    const passwordHash = await bcrypt.hash(partner.password, 10);
    await prisma.user.upsert({
      where: { username: partner.username },
      update: { passwordHash, displayName: partner.displayName },
      create: {
        username: partner.username,
        passwordHash,
        displayName: partner.displayName,
      },
    });
  }

  await prisma.setting.upsert({
    where: { id: 1 },
    update: { relationshipStartAt: new Date(start) },
    create: { id: 1, relationshipStartAt: new Date(start) },
  });

  await prisma.siteConfig.upsert({
    where: { id: 1 },
    update: {
      boy: partners[0].displayName,
      girl: partners[1].displayName,
      title: "LG_NewUi",
      logo: "LG_Demo",
      writing: "小手一牵 岁岁年年～",
      startTime: new Date(start),
      loveDayTitle: "这是我们一起走过的",
      card1: "点点滴滴",
      card2: "祝福留言",
      card3: "恋爱相册",
      card4: "恋爱事件",
      deci1: "记录生活点点滴滴",
      deci2: "书写下你的留言祝福～",
      deci3: "记录下美好瞬间～",
      deci4: "总有些惊奇的际遇",
      copyright: "LG_NewUi Web",
      siteDescription:
        "LGNewUI是一个专注于情侣和爱情的网站，提供了多种功能来帮助用户记录和分享他们的恋爱故事。",
    },
    create: {
      id: 1,
      boy: partners[0].displayName,
      girl: partners[1].displayName,
      title: "LG_NewUi",
      logo: "LG_Demo",
      writing: "小手一牵 岁岁年年～",
      startTime: new Date(start),
      loveDayTitle: "这是我们一起走过的",
      card1: "点点滴滴",
      card2: "祝福留言",
      card3: "恋爱相册",
      card4: "恋爱事件",
      deci1: "记录生活点点滴滴",
      deci2: "书写下你的留言祝福～",
      deci3: "记录下美好瞬间～",
      deci4: "总有些惊奇的际遇",
      copyright: "LG_NewUi Web",
      siteDescription:
        "LGNewUI是一个专注于情侣和爱情的网站，提供了多种功能来帮助用户记录和分享他们的恋爱故事。",
    },
  });

  const boy = await prisma.user.findUniqueOrThrow({
    where: { username: partners[0].username },
  });
  const girl = await prisma.user.findUniqueOrThrow({
    where: { username: partners[1].username },
  });

  if ((await prisma.diaryEntry.count()) === 0) {
    await prisma.diaryEntry.createMany({
      data: [
        {
          title: "第一次一起看日落",
          content: "天边慢慢染成橘粉色，我们什么也没说，只是靠得很近。",
          weather: "sunny",
          mood: "love",
          authorId: boy.id,
          createdAt: new Date("2023-08-01T18:30:00.000Z"),
        },
        {
          title: "下雨天的咖啡馆",
          content: "窗外雨声很轻，你点了热拿铁，我点了红茶，那杯茶现在还记得味道。",
          weather: "rainy",
          mood: "calm",
          authorId: girl.id,
          createdAt: new Date("2023-09-12T14:00:00.000Z"),
        },
        {
          title: "周末的菜市场",
          content: "你负责挑水果，我负责拎袋子。回家路上阳光很好，像把日子过得很慢。",
          weather: "cloudy",
          mood: "happy",
          authorId: boy.id,
          createdAt: new Date("2024-03-09T10:20:00.000Z"),
        },
      ],
    });
  }

  if ((await prisma.anniversary.count()) === 0) {
    await prisma.anniversary.createMany({
      data: [
        {
          title: "在一起纪念日",
          date: new Date(start),
          isImportant: true,
          note: "Love Day 起点",
        },
        {
          title: "下一次旅行",
          date: new Date("2027-05-20T00:00:00.000Z"),
          isImportant: false,
          note: "倒计时",
        },
      ],
    });
  }

  if ((await prisma.message.count()) === 0) {
    await prisma.message.createMany({
      data: [
        {
          content: "今天也要开心一点呀。",
          authorId: boy.id,
          createdAt: new Date(Date.now() - 1000 * 60 * 60 * 5),
        },
        {
          content: "收到。晚上一起吃饭吧。",
          authorId: girl.id,
          createdAt: new Date(Date.now() - 1000 * 60 * 60 * 4),
        },
      ],
    });
  }

  if ((await prisma.togetherItem.count()) === 0) {
    await prisma.togetherItem.createMany({
      data: [
        {
          title: "一起去看海",
          note: "日落时分最好",
          isCompleted: false,
          createdById: boy.id,
        },
        {
          title: "学做一顿晚饭",
          note: "番茄炒蛋也算",
          isCompleted: true,
          createdById: girl.id,
          completedAt: new Date(),
        },
        {
          title: "雨天看电影马拉松",
          note: "轮流选片",
          isCompleted: false,
          createdById: boy.id,
        },
        {
          title: "去一次没去过的城市",
          isCompleted: false,
          createdById: girl.id,
        },
        {
          title: "一起种一盆绿植",
          isCompleted: true,
          createdById: boy.id,
          completedAt: new Date(),
        },
      ],
    });
  }

  if ((await prisma.musicTrack.count()) === 0) {
    await prisma.musicTrack.createMany({
      data: [
        {
          title: "SoundHelix Song 1",
          artist: "T. Schürger",
          url: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3",
          coverUrl: "https://picsum.photos/seed/ours-m1/200/200",
          sortOrder: 1,
        },
        {
          title: "SoundHelix Song 2",
          artist: "T. Schürger",
          url: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3",
          coverUrl: "https://picsum.photos/seed/ours-m2/200/200",
          sortOrder: 2,
        },
        {
          title: "SoundHelix Song 3",
          artist: "T. Schürger",
          url: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3",
          coverUrl: "https://picsum.photos/seed/ours-m3/200/200",
          sortOrder: 3,
        },
      ],
    });
  }

  console.log("LGNewUI seed completed.");
  console.log("Admin:", partners.map((p) => p.username).join(" / "));
  console.log("Login path: /admin");
}

main()
  .catch((e) => {
    console.error(e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
