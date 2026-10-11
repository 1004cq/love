import { LgHero } from "@/components/LgHero";
import { MessageBoard } from "@/components/MessageBoard";
import { PageTransition } from "@/components/PageTransition";
import { getSession } from "@/lib/auth";
import { getPartners } from "@/lib/partners";
import { prisma } from "@/lib/prisma";

export const dynamic = "force-dynamic";

export default async function MessagePage() {
  const session = await getSession();
  const canManage = Boolean(session);

  const [partners, messages] = await Promise.all([
    getPartners(),
    prisma.message.findMany({
      orderBy: { createdAt: "asc" },
      include: {
        author: {
          select: { id: true, displayName: true, username: true },
        },
      },
    }),
  ]);

  return (
    <div>
      <LgHero
        partnerA={{
          displayName: partners[0]?.displayName || "Alice",
          avatarUrl: partners[0]?.avatarUrl,
        }}
        partnerB={{
          displayName: partners[1]?.displayName || "Bob",
          avatarUrl: partners[1]?.avatarUrl,
        }}
        distanceKm="546.9 km"
      />
      <PageTransition>
        <div className="mx-auto max-w-3xl px-4 pb-10 sm:px-6">
          <div className="mb-8 text-center">
            <p className="font-script text-lg italic text-muted-foreground">
              ＊ Kind Messages ＊
            </p>
            <h1 className="mt-2 font-display text-2xl tracking-tight sm:text-3xl">
              书写下你的留言祝福
            </h1>
            <p className="mt-3 text-sm text-muted-foreground">
              共 {messages.length} 条留言。
              {canManage ? "登录后可发布与管理。" : "公开浏览，登录后可发布。"}
            </p>
          </div>
          <MessageBoard
            canManage={canManage}
            currentUserId={session?.id ?? null}
            initialMessages={messages.map((message) => ({
              ...message,
              createdAt: message.createdAt.toISOString(),
            }))}
          />
        </div>
      </PageTransition>
    </div>
  );
}
