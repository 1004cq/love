import { NextRequest } from "next/server";
import { z } from "zod";
import { getSession } from "@/lib/auth";
import { resolveAvatarInput } from "@/lib/avatar";
import { jsonError, jsonOk, unauthorized } from "@/lib/api";
import { prisma } from "@/lib/prisma";
import { getSiteConfig } from "@/lib/site";
import { saveUploadedImage } from "@/lib/upload";

export async function GET() {
  const site = await getSiteConfig();
  const photoCount = await prisma.albumPhoto.count();
  const diaryCount = await prisma.diaryEntry.count();
  const anniversaryCount = await prisma.anniversary.count();
  const messageCount = await prisma.message.count();

  return jsonOk({
    site,
    relationshipStartAt: site.startTime,
    stats: {
      photos: photoCount,
      diaries: diaryCount,
      anniversaries: anniversaryCount,
      messages: messageCount,
    },
  });
}

const patchSchema = z.object({
  relationshipStartAt: z.string().min(1).optional(),
  boy: z.string().min(1).max(40).optional(),
  girl: z.string().min(1).max(40).optional(),
  boyAvatar: z.string().max(500).optional().nullable(),
  girlAvatar: z.string().max(500).optional().nullable(),
  title: z.string().min(1).max(80).optional(),
  logo: z.string().min(1).max(40).optional(),
  writing: z.string().min(1).max(120).optional(),
  loveDayTitle: z.string().min(1).max(80).optional(),
  card1: z.string().min(1).max(40).optional(),
  card2: z.string().min(1).max(40).optional(),
  card3: z.string().min(1).max(40).optional(),
  card4: z.string().min(1).max(40).optional(),
  deci1: z.string().min(1).max(80).optional(),
  deci2: z.string().min(1).max(80).optional(),
  deci3: z.string().min(1).max(80).optional(),
  deci4: z.string().min(1).max(80).optional(),
  copyright: z.string().min(1).max(80).optional(),
  siteDescription: z.string().max(500).optional().nullable(),
});

export async function PATCH(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  try {
    const body = patchSchema.parse(await request.json());
    const current = await getSiteConfig();

    let startTime = current.startTime;
    if (body.relationshipStartAt) {
      const date = new Date(body.relationshipStartAt);
      if (Number.isNaN(date.getTime())) {
        return jsonError("日期格式不正确");
      }
      startTime = date;
      await prisma.setting.upsert({
        where: { id: 1 },
        update: { relationshipStartAt: date },
        create: { id: 1, relationshipStartAt: date },
      });
    }

    const boyAvatar =
      body.boyAvatar === undefined
        ? undefined
        : body.boyAvatar
          ? resolveAvatarInput(body.boyAvatar)
          : null;
    const girlAvatar =
      body.girlAvatar === undefined
        ? undefined
        : body.girlAvatar
          ? resolveAvatarInput(body.girlAvatar)
          : null;

    const site = await prisma.siteConfig.upsert({
      where: { id: 1 },
      update: {
        startTime,
        ...(body.boy !== undefined ? { boy: body.boy.trim() } : {}),
        ...(body.girl !== undefined ? { girl: body.girl.trim() } : {}),
        ...(boyAvatar !== undefined ? { boyAvatar } : {}),
        ...(girlAvatar !== undefined ? { girlAvatar } : {}),
        ...(body.title !== undefined ? { title: body.title.trim() } : {}),
        ...(body.logo !== undefined ? { logo: body.logo.trim() } : {}),
        ...(body.writing !== undefined ? { writing: body.writing.trim() } : {}),
        ...(body.loveDayTitle !== undefined
          ? { loveDayTitle: body.loveDayTitle.trim() }
          : {}),
        ...(body.card1 !== undefined ? { card1: body.card1.trim() } : {}),
        ...(body.card2 !== undefined ? { card2: body.card2.trim() } : {}),
        ...(body.card3 !== undefined ? { card3: body.card3.trim() } : {}),
        ...(body.card4 !== undefined ? { card4: body.card4.trim() } : {}),
        ...(body.deci1 !== undefined ? { deci1: body.deci1.trim() } : {}),
        ...(body.deci2 !== undefined ? { deci2: body.deci2.trim() } : {}),
        ...(body.deci3 !== undefined ? { deci3: body.deci3.trim() } : {}),
        ...(body.deci4 !== undefined ? { deci4: body.deci4.trim() } : {}),
        ...(body.copyright !== undefined
          ? { copyright: body.copyright.trim() }
          : {}),
        ...(body.siteDescription !== undefined
          ? { siteDescription: body.siteDescription }
          : {}),
      },
      create: {
        id: 1,
        startTime,
        boy: body.boy?.trim() || current.boy,
        girl: body.girl?.trim() || current.girl,
        boyAvatar: boyAvatar ?? current.boyAvatar,
        girlAvatar: girlAvatar ?? current.girlAvatar,
        title: body.title?.trim() || current.title,
        logo: body.logo?.trim() || current.logo,
        writing: body.writing?.trim() || current.writing,
      },
    });

    // Keep dual admin display names / avatars in sync with couple fields.
    if (body.boy || boyAvatar) {
      await prisma.user.updateMany({
        where: { username: process.env.PARTNER_A_USERNAME || "love123" },
        data: {
          ...(body.boy ? { displayName: body.boy.trim() } : {}),
          ...(boyAvatar ? { avatarUrl: boyAvatar } : {}),
        },
      });
    }
    if (body.girl || girlAvatar) {
      await prisma.user.updateMany({
        where: { username: process.env.PARTNER_B_USERNAME || "user123" },
        data: {
          ...(body.girl ? { displayName: body.girl.trim() } : {}),
          ...(girlAvatar ? { avatarUrl: girlAvatar } : {}),
        },
      });
    }

    return jsonOk({ site });
  } catch (error) {
    if (error instanceof z.ZodError) {
      return jsonError("请检查表单字段");
    }
    return jsonError("更新失败", 500);
  }
}

/** Upload boy/girl avatar image — PHP 自定义头像上传简化版 */
export async function POST(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  try {
    const form = await request.formData();
    const side = String(form.get("side") || "");
    const file = form.get("file");
    if (side !== "boy" && side !== "girl") {
      return jsonError("请指定 boy 或 girl");
    }
    if (!(file instanceof File)) {
      return jsonError("请选择图片文件");
    }

    const url = await saveUploadedImage(file);
    const data =
      side === "boy" ? { boyAvatar: url } : { girlAvatar: url };

    const current = await getSiteConfig();
    const site = await prisma.siteConfig.upsert({
      where: { id: 1 },
      update: data,
      create: {
        id: 1,
        startTime: current.startTime,
        ...data,
      },
    });

    const username =
      side === "boy"
        ? process.env.PARTNER_A_USERNAME || "love123"
        : process.env.PARTNER_B_USERNAME || "user123";
    await prisma.user.updateMany({
      where: { username },
      data: { avatarUrl: url },
    });

    return jsonOk({ site, url });
  } catch (error) {
    return jsonError(
      error instanceof Error ? error.message : "上传失败",
      500
    );
  }
}
