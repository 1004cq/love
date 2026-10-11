import { NextRequest } from "next/server";
import { z } from "zod";
import { getSession } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { jsonError, jsonOk, unauthorized } from "@/lib/api";

export async function GET() {
  const setting = await prisma.setting.findUnique({ where: { id: 1 } });
  const photoCount = await prisma.albumPhoto.count();
  const diaryCount = await prisma.diaryEntry.count();
  const anniversaryCount = await prisma.anniversary.count();
  const messageCount = await prisma.message.count();

  return jsonOk({
    relationshipStartAt: setting?.relationshipStartAt ?? null,
    stats: {
      photos: photoCount,
      diaries: diaryCount,
      anniversaries: anniversaryCount,
      messages: messageCount,
    },
  });
}

const patchSchema = z.object({
  relationshipStartAt: z.string().min(1),
});

export async function PATCH(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  try {
    const body = patchSchema.parse(await request.json());
    const date = new Date(body.relationshipStartAt);
    if (Number.isNaN(date.getTime())) {
      return jsonError("日期格式不正确");
    }

    const setting = await prisma.setting.upsert({
      where: { id: 1 },
      update: { relationshipStartAt: date },
      create: { id: 1, relationshipStartAt: date },
    });

    await prisma.siteConfig.upsert({
      where: { id: 1 },
      update: { startTime: date },
      create: {
        id: 1,
        startTime: date,
      },
    });

    return jsonOk({ setting });
  } catch (error) {
    if (error instanceof z.ZodError) {
      return jsonError("请提供开始日期");
    }
    return jsonError("更新失败", 500);
  }
}
