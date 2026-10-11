import { AnniversaryList } from "@/components/AnniversaryList";
import { LgHero } from "@/components/LgHero";
import { PageTransition } from "@/components/PageTransition";
import { getSession } from "@/lib/auth";
import { getPartners } from "@/lib/partners";
import { prisma } from "@/lib/prisma";

export const dynamic = "force-dynamic";

export default async function AnniversaryPage() {
  const session = await getSession();
  const canManage = Boolean(session);

  const [partners, anniversaries] = await Promise.all([
    getPartners(),
    prisma.anniversary.findMany({ orderBy: { date: "asc" } }),
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
              ＊ Love Day ＊
            </p>
            <h1 className="mt-2 font-display text-2xl tracking-tight sm:text-3xl">
              轨迹与纪念日
            </h1>
            <p className="mt-3 text-sm text-muted-foreground">
              共 {anniversaries.length} 个日子。
              {canManage ? "登录后可添加、修改与删除。" : "公开浏览，登录后可管理。"}
            </p>
          </div>
          <AnniversaryList
            canManage={canManage}
            initialItems={anniversaries.map((item) => ({
              ...item,
              date: item.date.toISOString(),
            }))}
          />
        </div>
      </PageTransition>
    </div>
  );
}
