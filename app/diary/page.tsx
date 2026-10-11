import { DiaryTimeline } from "@/components/DiaryTimeline";
import { LgHero } from "@/components/LgHero";
import { PageTransition } from "@/components/PageTransition";
import { getSession } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

export const dynamic = "force-dynamic";

export default async function DiaryPage() {
  const session = await getSession();
  const canManage = Boolean(session);

  const [setting, partners, entries] = await Promise.all([
    prisma.setting.findUnique({ where: { id: 1 } }),
    prisma.user.findMany({
      orderBy: { createdAt: "asc" },
      take: 2,
      select: { displayName: true, avatarUrl: true },
    }),
    prisma.diaryEntry.findMany({
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
    }),
  ]);

  const relationshipStartAt =
    setting?.relationshipStartAt.toISOString() ||
    process.env.RELATIONSHIP_START ||
    "2023-05-20T00:00:00.000Z";

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
              ＊ Memory Notes ＊
            </p>
            <h1 className="mt-2 font-display text-2xl tracking-tight sm:text-3xl">
              写下日常、心情与想念
            </h1>
            <p className="mt-3 text-sm text-muted-foreground">
              共 {entries.length} 篇。
              {canManage ? "登录后可新增、编辑与删除。" : "公开浏览，登录后可管理。"}
            </p>
          </div>
          <DiaryTimeline
            canManage={canManage}
            relationshipStartAt={relationshipStartAt}
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
