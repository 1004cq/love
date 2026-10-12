import { unlink } from "fs/promises";
import { NextRequest } from "next/server";
import { getSession } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { jsonError, jsonOk, unauthorized } from "@/lib/api";
import { clientKey, rateLimit } from "@/lib/rate-limit";
import { resolveUploadFsPath, saveUploadedImage } from "@/lib/upload";

export async function GET() {
  const photos = await prisma.albumPhoto.findMany({
    orderBy: { createdAt: "desc" },
    include: {
      uploadedBy: {
        select: { id: true, displayName: true, username: true },
      },
    },
  });

  return jsonOk({ photos });
}

export async function POST(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  const limited = rateLimit(clientKey(request, "upload"), 30, 60 * 60 * 1000);
  if (!limited.ok) {
    return jsonError(`上传过于频繁，请 ${limited.retryAfterSec} 秒后再试`, 429);
  }

  try {
    const form = await request.formData();
    const file = form.get("file");
    const captionRaw = String(form.get("caption") || "").trim();
    const caption = captionRaw ? captionRaw.slice(0, 200) : null;

    if (!(file instanceof File)) {
      return jsonError("请选择图片");
    }

    const url = await saveUploadedImage(file);
    const photo = await prisma.albumPhoto.create({
      data: {
        url,
        caption,
        uploadedById: session.id,
      },
      include: {
        uploadedBy: {
          select: { id: true, displayName: true, username: true },
        },
      },
    });

    return jsonOk({ photo }, { status: 201 });
  } catch (error) {
    const message = error instanceof Error ? error.message : "上传失败";
    return jsonError(message, 400);
  }
}

export async function DELETE(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  const id = request.nextUrl.searchParams.get("id");
  if (!id) return jsonError("缺少图片 id");

  const photo = await prisma.albumPhoto.findUnique({ where: { id } });
  if (!photo) return jsonError("图片不存在", 404);

  await prisma.albumPhoto.delete({ where: { id } });

  const fsPath = resolveUploadFsPath(photo.url);
  if (fsPath) {
    try {
      await unlink(fsPath);
    } catch {
      // ignore missing file
    }
  }

  return jsonOk({ ok: true });
}
