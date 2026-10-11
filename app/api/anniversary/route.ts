import { NextRequest } from "next/server";
import { z } from "zod";
import { getSession } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { jsonError, jsonOk, unauthorized } from "@/lib/api";

const createSchema = z.object({
  title: z.string().min(1).max(80),
  date: z.string().min(1),
  note: z.string().max(500).optional().nullable(),
  isImportant: z.boolean().optional(),
});

const patchSchema = z.object({
  id: z.string().min(1),
  isCompleted: z.boolean().optional(),
  isImportant: z.boolean().optional(),
  title: z.string().min(1).max(80).optional(),
  note: z.string().max(500).optional().nullable(),
  date: z.string().optional(),
});

export async function GET() {
  const anniversaries = await prisma.anniversary.findMany({
    orderBy: { date: "asc" },
  });

  return jsonOk({ anniversaries });
}

export async function POST(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  try {
    const body = createSchema.parse(await request.json());
    const date = new Date(body.date);
    if (Number.isNaN(date.getTime())) {
      return jsonError("日期格式不正确");
    }

    const anniversary = await prisma.anniversary.create({
      data: {
        title: body.title,
        date,
        note: body.note || null,
        isImportant: body.isImportant ?? false,
      },
    });

    return jsonOk({ anniversary }, { status: 201 });
  } catch (error) {
    if (error instanceof z.ZodError) {
      return jsonError("请填写完整信息");
    }
    return jsonError("创建失败", 500);
  }
}

export async function PATCH(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  try {
    const body = patchSchema.parse(await request.json());
    const data: {
      isCompleted?: boolean;
      isImportant?: boolean;
      title?: string;
      note?: string | null;
      date?: Date;
    } = {
      isCompleted: body.isCompleted,
      isImportant: body.isImportant,
      title: body.title,
      note: body.note,
    };

    if (body.date) {
      const date = new Date(body.date);
      if (Number.isNaN(date.getTime())) {
        return jsonError("日期格式不正确");
      }
      data.date = date;
    }

    const anniversary = await prisma.anniversary.update({
      where: { id: body.id },
      data,
    });
    return jsonOk({ anniversary });
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
  if (!id) return jsonError("缺少纪念日 id");

  await prisma.anniversary.delete({ where: { id } });
  return jsonOk({ ok: true });
}
