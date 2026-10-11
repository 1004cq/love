import { MessageBoard } from "@/components/MessageBoard";
import { PageTransition } from "@/components/PageTransition";
import { SiteHero } from "@/components/SiteHero";
import { WritingBar } from "@/components/WritingBar";
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
      <SiteHero site={site} />
      <PageTransition>
        <div className="mx-auto max-w-3xl space-y-6 px-4 pb-10 sm:px-6">
          <WritingBar>{site.deci2}</WritingBar>
          <p className="text-center text-sm text-muted-foreground">
            祝福留言共 {messages.length} 条。
            {canManage ? "登录后可发布与管理。" : "公开浏览，登录后可发布。"}
          </p>
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
