import { PrismaClient } from "@prisma/client";
import bcrypt from "bcryptjs";

const prisma = new PrismaClient();

async function main() {
  const start =
    process.env.RELATIONSHIP_START || "2023-05-20T00:00:00.000Z";

  const partners = [
    {
      username: process.env.PARTNER_A_USERNAME || "alice",
      password: process.env.PARTNER_A_PASSWORD || "love123",
      displayName: process.env.PARTNER_A_NAME || "Alice",
    },
    {
      username: process.env.PARTNER_B_USERNAME || "bob",
      password: process.env.PARTNER_B_PASSWORD || "love123",
      displayName: process.env.PARTNER_B_NAME || "Bob",
    },
  ];

  for (const partner of partners) {
    const passwordHash = await bcrypt.hash(partner.password, 10);
    await prisma.user.upsert({
      where: { username: partner.username },
      update: {
        passwordHash,
        displayName: partner.displayName,
      },
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

  const alice = await prisma.user.findUniqueOrThrow({
    where: { username: partners[0].username },
  });
  const bob = await prisma.user.findUniqueOrThrow({
    where: { username: partners[1].username },
  });

  const diaryCount = await prisma.diaryEntry.count();
  if (diaryCount === 0) {
    await prisma.diaryEntry.createMany({
      data: [
        {
          title: "第一次一起看日落",
          content: "天边慢慢染成橘粉色，我们什么也没说，只是靠得很近。",
          weather: "sunny",
          mood: "love",
          authorId: alice.id,
          createdAt: new Date("2023-06-01T18:30:00.000Z"),
        },
        {
          title: "下雨天的咖啡馆",
          content: "窗外雨声很轻，你点了热拿铁，我点了红茶，那杯茶现在还记得味道。",
          weather: "rainy",
          mood: "calm",
          authorId: bob.id,
          createdAt: new Date("2023-08-12T14:00:00.000Z"),
        },
        {
          title: "周末的菜市场",
          content: "你负责挑水果，我负责拎袋子。回家路上阳光很好，像把日子过得很慢。",
          weather: "cloudy",
          mood: "happy",
          authorId: alice.id,
          createdAt: new Date("2024-03-09T10:20:00.000Z"),
        },
      ],
    });
  } else {
    await prisma.diaryEntry.updateMany({
      where: { title: "第一次一起看日落" },
      data: { weather: "sunny", mood: "love" },
    });
    await prisma.diaryEntry.updateMany({
      where: { title: "下雨天的咖啡馆" },
      data: { weather: "rainy", mood: "calm" },
    });
    await prisma.diaryEntry.updateMany({
      where: { title: "周末的菜市场" },
      data: { weather: "cloudy", mood: "happy" },
    });
  }

  const anniCount = await prisma.anniversary.count();
  if (anniCount === 0) {
    const nextYear = new Date();
    nextYear.setFullYear(nextYear.getFullYear() + 1);
    nextYear.setMonth(4, 20);
    nextYear.setHours(0, 0, 0, 0);

    await prisma.anniversary.createMany({
      data: [
        {
          title: "在一起纪念日",
          date: new Date(start),
          note: "一切开始的地方",
          isImportant: true,
          isCompleted: true,
        },
        {
          title: "下一次旅行",
          date: nextYear,
          note: "想去海边走走",
          isImportant: true,
          isCompleted: false,
        },
      ],
    });
  }

  const messageCount = await prisma.message.count();
  if (messageCount === 0) {
    await prisma.message.createMany({
      data: [
        {
          content: "今天也要开心一点呀。",
          authorId: alice.id,
          createdAt: new Date(Date.now() - 1000 * 60 * 60 * 5),
        },
        {
          content: "收到。晚上一起吃饭吧。",
          authorId: bob.id,
          createdAt: new Date(Date.now() - 1000 * 60 * 60 * 4),
        },
      ],
    });
  }

  const togetherCount = await prisma.togetherItem.count();
  if (togetherCount === 0) {
    await prisma.togetherItem.createMany({
      data: [
        {
          title: "一起去看海",
          note: "日落时分最好",
          isCompleted: false,
          createdById: alice.id,
        },
        {
          title: "学做一顿晚饭",
          note: "番茄炒蛋也算",
          isCompleted: true,
          createdById: bob.id,
          completedAt: new Date("2024-05-01T12:00:00.000Z"),
        },
        {
          title: "雨天看电影马拉松",
          note: "轮流选片",
          isCompleted: false,
          createdById: alice.id,
        },
        {
          title: "去一次没去过的城市",
          isCompleted: false,
          createdById: bob.id,
        },
        {
          title: "一起种一盆绿植",
          note: "已养活两周",
          isCompleted: true,
          createdById: alice.id,
          completedAt: new Date("2024-08-20T09:00:00.000Z"),
        },
      ],
    });
  }

  const musicCount = await prisma.musicTrack.count();
  if (musicCount === 0) {
    await prisma.musicTrack.createMany({
      data: [
        {
          title: "SoundHelix Song 1",
          artist: "T. Schürger",
          url: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3",
          coverUrl: "https://picsum.photos/seed/ours-m1/200/200",
          sortOrder: 0,
        },
        {
          title: "SoundHelix Song 2",
          artist: "T. Schürger",
          url: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3",
          coverUrl: "https://picsum.photos/seed/ours-m2/200/200",
          sortOrder: 1,
        },
        {
          title: "SoundHelix Song 3",
          artist: "T. Schürger",
          url: "https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3",
          coverUrl: "https://picsum.photos/seed/ours-m3/200/200",
          sortOrder: 2,
        },
      ],
    });
  } else {
    await prisma.musicTrack.updateMany({
      where: { coverUrl: null },
      data: { coverUrl: "https://picsum.photos/seed/ours-music/200/200" },
    });
  }

  console.log("Seed completed.");
  console.log(
    `Admin accounts: ${partners[0].username} / ${partners[1].username}`
  );
  console.log("Login path: /admin");
  console.log("Default password: love123 (or from .env)");
}

main()
  .catch((error) => {
    console.error(error);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
