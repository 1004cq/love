import Link from "next/link";
import { redirect } from "next/navigation";
import { AdminPanel } from "@/components/AdminPanel";
import { AdminShell } from "@/components/admin/AdminShell";
import { DashboardStats } from "@/components/admin/DashboardStats";
import { PageTransition } from "@/components/PageTransition";
import { SoftCard } from "@/components/SoftCard";
import { Button } from "@/components/ui/button";
import { getSession } from "@/lib/auth";
import { formatDateTime } from "@/lib/format";
import { prisma } from "@/lib/prisma";
import { getSiteConfig } from "@/lib/site";

export const dynamic = "force-dynamic";

export const metadata = {
  title: "后台首页 · LG_NewUi",
  description: "LGNewUI 后台仪表盘",
};

export default async function AdminDashboardPage() {
  const session = await getSession();
  if (!session) {
    redirect("/admin?next=/admin/dashboard");
  }

  const site = await getSiteConfig();

  const [
    photoCount,
    diaryCount,
    anniversaryCount,
    messageCount,
    togetherCount,
    togetherDone,
    latestDiaries,
    latestMessages,
    latestTogether,
  ] = await Promise.all([
    prisma.albumPhoto.count(),
    prisma.diaryEntry.count(),
    prisma.anniversary.count(),
    prisma.message.count(),
    prisma.togetherItem.count(),
    prisma.togetherItem.count({ where: { isCompleted: true } }),
    prisma.diaryEntry.findMany({
      orderBy: { createdAt: "desc" },
      take: 5,
      include: { author: { select: { displayName: true } } },
    }),
    prisma.message.findMany({
      orderBy: { createdAt: "desc" },
      take: 5,
      include: { author: { select: { displayName: true } } },
    }),
    prisma.togetherItem.findMany({
      orderBy: { updatedAt: "desc" },
      take: 5,
    }),
  ]);

  const togetherPct =
    togetherCount === 0 ? 0 : Math.round((togetherDone / togetherCount) * 100);

  /** PHP admin/index.php 四卡片 */
  const stats = [
    {
      key: "messages",
      label: "留言数量",
      value: messageCount,
      hint: site.card2,
      href: "/leaving",
    },
    {
      key: "diaries",
      label: "点点滴滴",
      value: diaryCount,
      hint: site.card1,
      href: "/little",
    },
    {
      key: "together",
      label: "恋爱清单",
      value: togetherCount,
      hint: `完成 ${togetherDone} · ${togetherPct}%`,
      href: "/list",
    },
    {
      key: "photos",
      label: "相册数量",
      value: photoCount,
      hint: site.card3,
      href: "/love-img",
    },
  ];

  const activity = [
    ...latestDiaries.map((item) => ({
      id: `diary-${item.id}`,
      kind: "点点滴滴",
      title: item.title,
      meta: `${item.author.displayName} · ${formatDateTime(item.createdAt)}`,
      href: "/little",
      at: item.createdAt.getTime(),
    })),
    ...latestMessages.map((item) => ({
      id: `msg-${item.id}`,
      kind: "祝福留言",
      title: item.content.slice(0, 48) + (item.content.length > 48 ? "…" : ""),
      meta: `${item.author.displayName} · ${formatDateTime(item.createdAt)}`,
      href: "/leaving",
      at: item.createdAt.getTime(),
    })),
    ...latestTogether.map((item) => ({
      id: `todo-${item.id}`,
      kind: item.isCompleted ? "恋爱事件·完成" : "恋爱事件",
      title: item.title,
      meta: formatDateTime(item.updatedAt),
      href: "/list",
      at: item.updatedAt.getTime(),
    })),
  ]
    .sort((a, b) => b.at - a.at)
    .slice(0, 8);

  return (
    <PageTransition>
      <AdminShell displayName={session.displayName}>
        <div className="space-y-8">
          <section className="space-y-4">
            <div>
              <p className="text-xs tracking-[0.14em] text-blush">OVERVIEW</p>
              <h2 className="mt-1 font-display text-2xl tracking-tight">数据概览</h2>
              <p className="mt-2 text-sm text-muted-foreground">
                对齐 PHP LGNewUI 后台首页四卡片：留言 / 点滴 / 清单 / 相册。
              </p>
            </div>
            <DashboardStats stats={stats} />
          </section>

          <section className="grid gap-4 lg:grid-cols-[1.35fr_1fr]">
            <SoftCard padding="lg" className="space-y-4" hoverLift={false}>
              <div className="flex items-end justify-between gap-3">
                <div>
                  <p className="text-xs tracking-[0.14em] text-blush">ACTIVITY</p>
                  <h2 className="mt-1 font-display text-xl tracking-tight">近期动态</h2>
                </div>
                <Button asChild variant="secondary" size="sm" className="rounded-full">
                  <Link href="/">回到前台</Link>
                </Button>
              </div>
              {activity.length === 0 ? (
                <p className="text-sm text-muted-foreground">还没有内容。</p>
              ) : (
                <ul className="divide-y divide-border/60">
                  {activity.map((item) => (
                    <li key={item.id}>
                      <Link
                        href={item.href}
                        className="flex items-start justify-between gap-3 py-3.5 transition-colors hover:text-blush"
                      >
                        <div className="min-w-0">
                          <p className="text-[11px] tracking-[0.12em] text-muted-foreground">
                            {item.kind}
                          </p>
                          <p className="mt-1 truncate text-sm font-medium">{item.title}</p>
                        </div>
                        <p className="shrink-0 text-xs text-muted-foreground">{item.meta}</p>
                      </Link>
                    </li>
                  ))}
                </ul>
              )}
            </SoftCard>

            <AdminPanel
              initialStartAt={site.startTime.toISOString()}
              stats={{
                photos: photoCount,
                diaries: diaryCount,
                anniversaries: anniversaryCount,
                messages: messageCount,
              }}
            />
          </section>
        </div>
      </AdminShell>
    </PageTransition>
  );
}
