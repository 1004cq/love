import { NextRequest } from "next/server";
import { z } from "zod";
import { getSession } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { jsonError, jsonOk, unauthorized } from "@/lib/api";

const schema = z.object({
  content: z.string().min(1).max(1000),
});

export async function GET() {
  const session = await getSession();
  const messages = await prisma.message.findMany({
    orderBy: { createdAt: "asc" },
    include: {
      author: {
        select: { id: true, displayName: true, username: true },
      },
    },
  });

  return jsonOk({ messages, me: session });
}

export async function POST(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  try {
    const body = schema.parse(await request.json());
    const message = await prisma.message.create({
      data: {
        content: body.content.trim(),
        authorId: session.id,
      },
      include: {
        author: {
          select: { id: true, displayName: true, username: true },
        },
      },
    });

    return jsonOk({ message }, { status: 201 });
  } catch (error) {
    if (error instanceof z.ZodError) {
      return jsonError("留言不能为空");
    }
    return jsonError("发送失败", 500);
  }
}

export async function DELETE(request: NextRequest) {
  const session = await getSession();
  if (!session) return unauthorized();

  const id = request.nextUrl.searchParams.get("id");
  if (!id) return jsonError("缺少留言 id");

  const message = await prisma.message.findUnique({ where: { id } });
  if (!message) return jsonError("留言不存在", 404);

  // Both admins share the same manage permission.
  await prisma.message.delete({ where: { id } });
  return jsonOk({ ok: true });
}
