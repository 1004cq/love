import { PageTransition } from "@/components/PageTransition";
import { SiteHero } from "@/components/SiteHero";
import { SoftCard } from "@/components/SoftCard";
import { TogetherList } from "@/components/TogetherList";
import { WritingBar } from "@/components/WritingBar";
import { getSession } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { getSiteConfig } from "@/lib/site";

export const dynamic = "force-dynamic";

export const metadata = {
  title: "恋爱事件",
  description: "总有些惊奇的际遇",
};

export default async function ListPage() {
  const session = await getSession();
  const canManage = Boolean(session);
  const site = await getSiteConfig();

  const items = await prisma.togetherItem.findMany({
    orderBy: [{ isCompleted: "asc" }, { createdAt: "desc" }],
    include: {
      createdBy: { select: { id: true, displayName: true } },
    },
  });

  const todo = items.filter((item) => !item.isCompleted).length;
  const done = items.length - todo;

  return (
    <div>
      <SiteHero site={site} />
      <PageTransition>
        <div className="mx-auto max-w-3xl space-y-6 px-4 pb-10 sm:px-6">
          <WritingBar>{site.deci4}</WritingBar>

          <div className="grid grid-cols-3 gap-3">
            <SoftCard padding="sm" className="text-center" hoverLift={false}>
              <p className="font-num text-2xl font-semibold">{items.length}</p>
              <p className="mt-1 text-xs text-muted-foreground">全部</p>
            </SoftCard>
            <SoftCard padding="sm" className="text-center" hoverLift={false}>
              <p className="font-num text-2xl font-semibold text-blush">{done}</p>
              <p className="mt-1 text-xs text-muted-foreground">已完成</p>
            </SoftCard>
            <SoftCard padding="sm" className="text-center" hoverLift={false}>
              <p className="font-num text-2xl font-semibold">{todo}</p>
              <p className="mt-1 text-xs text-muted-foreground">未完成</p>
            </SoftCard>
          </div>

          <TogetherList
            canManage={canManage}
            initialItems={items.map((item) => ({
              ...item,
              createdAt: item.createdAt.toISOString(),
              updatedAt: item.updatedAt.toISOString(),
              completedAt: item.completedAt?.toISOString() ?? null,
            }))}
          />
        </div>
      </PageTransition>
    </div>
  );
}
