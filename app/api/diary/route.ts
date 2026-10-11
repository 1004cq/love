import { NextRequest } from "next/server";
import { z } from "zod";
import { getSession } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { jsonError, jsonOk, unauthorized } from "@/lib/api";
import { saveUploadedImage } from "@/lib/upload";

const createSchema = z.object({
  title: z.string().min(1).max(80),
  content: z.string().min(1).max(5000),
  weather: z.enum(["sunny", "cloudy", "rainy", "snowy"]).optional(),
  mood: z.enum(["happy", "calm", "love", "miss", "tired"]).optional(),
});

const patchSchema = z.object({
  id: z.string().min(1),
  title: z.string().min(1).max(80).optional(),
  content: z.string().min(1).max(5000).optional(),
  weather: z.enum(["sunny", "cloudy", "rainy", "snowy"]).optional(),
  mood: z.enum(["happy", "calm", "love", "miss", "tired"]).optional(),
});

export async function GET() {
  const entries = await prisma.diaryEntry.findMany({
    orderBy: { createdAt: "desc" },
    include: {
      author: {
        select: {
          id: true,
          displayName: true,
          username: true,
          avatarUrl: true,
        },
      },
    },
  });

  return jsonOk({ entries });
}

export async function POST(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  try {
    const contentType = request.headers.get("content-type") || "";
    let title = "";
    let content = "";
    let weather = "sunny";
    let mood = "happy";
    let imageUrl: string | null = null;

    if (contentType.includes("multipart/form-data")) {
      const form = await request.formData();
      title = String(form.get("title") || "").trim();
      content = String(form.get("content") || "").trim();
      weather = String(form.get("weather") || "sunny");
      mood = String(form.get("mood") || "happy");
      const file = form.get("image");
      if (file instanceof File && file.size > 0) {
        imageUrl = await saveUploadedImage(file);
      }
    } else {
      const body = createSchema.parse(await request.json());
      title = body.title;
      content = body.content;
      weather = body.weather || "sunny";
      mood = body.mood || "happy";
    }

    const parsed = createSchema.safeParse({ title, content, weather, mood });
    if (!parsed.success) {
      return jsonError("请填写标题和内容");
    }

    const entry = await prisma.diaryEntry.create({
      data: {
        title: parsed.data.title,
        content: parsed.data.content,
        weather: parsed.data.weather || "sunny",
        mood: parsed.data.mood || "happy",
        imageUrl,
        authorId: session.id,
      },
      include: {
        author: {
          select: {
            id: true,
            displayName: true,
            username: true,
            avatarUrl: true,
          },
        },
      },
    });

    return jsonOk({ entry }, { status: 201 });
  } catch (error) {
    const message = error instanceof Error ? error.message : "创建失败";
    return jsonError(message, 400);
  }
}

export async function PATCH(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  try {
    const body = patchSchema.parse(await request.json());
    const entry = await prisma.diaryEntry.update({
      where: { id: body.id },
      data: {
        title: body.title,
        content: body.content,
        weather: body.weather,
        mood: body.mood,
      },
      include: {
        author: {
          select: {
            id: true,
            displayName: true,
            username: true,
            avatarUrl: true,
          },
        },
      },
    });
    return jsonOk({ entry });
  } catch (error) {
    if (error instanceof z.ZodError) {
      return jsonError("参数不正确");
    }
    return jsonError("更新失败", 500);
  }
}

export async function DELETE(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  const id = request.nextUrl.searchParams.get("id");
  if (!id) return jsonError("缺少日记 id");

  const entry = await prisma.diaryEntry.findUnique({ where: { id } });
  if (!entry) return jsonError("日记不存在", 404);

  await prisma.diaryEntry.delete({ where: { id } });
  return jsonOk({ ok: true });
}
