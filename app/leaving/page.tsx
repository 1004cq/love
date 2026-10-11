import { LgHero } from "@/components/LgHero";
import { MessageBoard } from "@/components/MessageBoard";
import { PageTransition } from "@/components/PageTransition";
import { getSession } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { getSiteConfig } from "@/lib/site";

export const dynamic = "force-dynamic";

export const metadata = {
  title: "祝福留言",
  description: "书写下你的留言祝福",
};

export default async function LeavingPage() {
  const session = await getSession();
  const canManage = Boolean(session);
  const site = await getSiteConfig();

  const messages = await prisma.message.findMany({
    orderBy: { createdAt: "asc" },
    include: {
      author: {
        select: { id: true, displayName: true, username: true },
      },
    },
  });

  return (
    <div>
      <LgHero
        partnerA={{ displayName: site.boy, avatarUrl: site.boyAvatar || undefined }}
        partnerB={{ displayName: site.girl, avatarUrl: site.girlAvatar || undefined }}
        distanceKm="546.9 km"
      />
      <PageTransition>
        <div className="mx-auto max-w-3xl px-4 pb-10 sm:px-6">
          <div className="mb-8 text-center">
            <p className="text-xs tracking-[0.14em] text-blush">{site.card2}</p>
            <h1 className="mt-2 font-display text-2xl tracking-tight sm:text-3xl">
              {site.deci2}
            </h1>
            <p className="mt-3 text-sm text-muted-foreground">
              祝福留言共 {messages.length} 条。
              {canManage ? "登录后可发布与管理。" : "公开浏览，登录后可发布。"}
            </p>
          </div>
          <MessageBoard
            canManage={canManage}
            currentUserId={session?.id ?? null}
            initialMessages={messages.map((m) => ({
              ...m,
              createdAt: m.createdAt.toISOString(),
            }))}
          />
        </div>
      </PageTransition>
    </div>
  );
}
