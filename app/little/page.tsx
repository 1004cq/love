import { DiaryTimeline } from "@/components/DiaryTimeline";
import { LgHero } from "@/components/LgHero";
import { PageTransition } from "@/components/PageTransition";
import { getSession } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { getSiteConfig } from "@/lib/site";

export const dynamic = "force-dynamic";

export const metadata = {
  title: "点点滴滴",
  description: "记录生活点点滴滴",
};

export default async function LittlePage() {
  const session = await getSession();
  const canManage = Boolean(session);
  const site = await getSiteConfig();

  const entries = await prisma.diaryEntry.findMany({
    orderBy: { createdAt: "desc" },
    include: {
      author: {
        select: {
          id: true,
          displayName: true,
          username: true,
          avatarUrl: true,
        },
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
            <p className="text-xs tracking-[0.14em] text-blush">{site.card1}</p>
            <h1 className="mt-2 font-display text-2xl tracking-tight sm:text-3xl">
              {site.deci1}
            </h1>
            <p className="mt-3 text-sm text-muted-foreground">
              共 {entries.length} 篇。
              {canManage ? "登录后可新增、编辑与删除。" : "公开浏览，登录后可管理。"}
            </p>
          </div>
          <DiaryTimeline
            canManage={canManage}
            relationshipStartAt={site.startTime.toISOString()}
            initialEntries={entries.map((entry) => ({
              ...entry,
              createdAt: entry.createdAt.toISOString(),
            }))}
          />
        </div>
      </PageTransition>
    </div>
  );
}
