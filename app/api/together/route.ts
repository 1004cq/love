import { NextRequest } from "next/server";
import { z } from "zod";
import { getSession } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { jsonError, jsonOk, unauthorized } from "@/lib/api";

const createSchema = z.object({
  title: z.string().min(1).max(120),
  note: z.string().max(500).optional().nullable(),
});

const patchSchema = z.object({
  id: z.string().min(1),
  title: z.string().min(1).max(120).optional(),
  note: z.string().max(500).optional().nullable(),
  isCompleted: z.boolean().optional(),
});

export async function GET() {
  const items = await prisma.togetherItem.findMany({
    orderBy: [{ isCompleted: "asc" }, { createdAt: "desc" }],
    include: {
      createdBy: { select: { id: true, displayName: true } },
    },
  });
  return jsonOk({ items });
}

export async function POST(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  try {
    const body = createSchema.parse(await request.json());
    const item = await prisma.togetherItem.create({
      data: {
        title: body.title.trim(),
        note: body.note?.trim() || null,
        createdById: session.id,
      },
      include: {
        createdBy: { select: { id: true, displayName: true } },
      },
    });
    return jsonOk({ item }, { status: 201 });
  } catch (error) {
    if (error instanceof z.ZodError) return jsonError("请填写标题");
    return jsonError("创建失败", 500);
  }
}

export async function PATCH(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  try {
    const body = patchSchema.parse(await request.json());
    const data: {
      title?: string;
      note?: string | null;
      isCompleted?: boolean;
      completedAt?: Date | null;
    } = {
      title: body.title,
      note: body.note,
    };

    if (typeof body.isCompleted === "boolean") {
      data.isCompleted = body.isCompleted;
      data.completedAt = body.isCompleted ? new Date() : null;
    }

    const item = await prisma.togetherItem.update({
      where: { id: body.id },
      data,
      include: {
        createdBy: { select: { id: true, displayName: true } },
      },
    });
    return jsonOk({ item });
  } catch (error) {
    if (error instanceof z.ZodError) return jsonError("参数不正确");
    return jsonError("更新失败", 500);
  }
}

export async function DELETE(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  const id = request.nextUrl.searchParams.get("id");
  if (!id) return jsonError("缺少 id");

  await prisma.togetherItem.delete({ where: { id } });
  return jsonOk({ ok: true });
}
