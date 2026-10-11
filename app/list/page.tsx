import { LgHero } from "@/components/LgHero";
import { TogetherList } from "@/components/TogetherList";
import { PageTransition } from "@/components/PageTransition";
import { SoftCard } from "@/components/SoftCard";
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
      <LgHero
        partnerA={{ displayName: site.boy, avatarUrl: site.boyAvatar || undefined }}
        partnerB={{ displayName: site.girl, avatarUrl: site.girlAvatar || undefined }}
        distanceKm="546.9 km"
      />
      <PageTransition>
        <div className="mx-auto max-w-3xl px-4 pb-10 sm:px-6">
          <div className="mb-6 text-center">
            <p className="text-xs tracking-[0.14em] text-blush">{site.card4}</p>
            <h1 className="mt-2 font-display text-2xl tracking-tight sm:text-3xl">
              {site.deci4}
            </h1>
          </div>

          <div className="mb-6 grid grid-cols-3 gap-3">
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
