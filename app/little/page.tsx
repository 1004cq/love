import { DiaryTimeline } from "@/components/DiaryTimeline";
import { PageTransition } from "@/components/PageTransition";
import { SiteHero } from "@/components/SiteHero";
import { WritingBar } from "@/components/WritingBar";
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
      <SiteHero site={site} />
      <PageTransition>
        <div className="mx-auto max-w-3xl space-y-6 px-4 pb-10 sm:px-6">
          <WritingBar>{site.deci1}</WritingBar>
          <p className="text-center text-sm text-muted-foreground">
            共 {entries.length} 篇。
            {canManage ? "登录后可新增、编辑与删除。" : "公开浏览，登录后可管理。"}
          </p>
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
