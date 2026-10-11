import { unlink } from "fs/promises";
import path from "path";
import { NextRequest } from "next/server";
import { getSession } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { jsonError, jsonOk, unauthorized } from "@/lib/api";
import { saveUploadedImage } from "@/lib/upload";

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

  try {
    const form = await request.formData();
    const file = form.get("file");
    const caption = String(form.get("caption") || "").trim() || null;

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

  if (photo.url.startsWith("/uploads/")) {
    try {
      await unlink(path.join(process.cwd(), "public", photo.url));
    } catch {
      // ignore missing file
    }
  }

  return jsonOk({ ok: true });
}
