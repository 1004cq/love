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

export const dynamic = "force-dynamic";

export const metadata = {
  title: "仪表盘 · Ours",
  description: "情侣空间后台仪表盘：数据概览与近期动态",
};

export default async function AdminDashboardPage() {
  const session = await getSession();
  if (!session) {
    redirect("/admin?next=/admin/dashboard");
  }

  const setting = await prisma.setting.findUnique({ where: { id: 1 } });
  const startAt =
    setting?.relationshipStartAt.toISOString() ||
    process.env.RELATIONSHIP_START ||
    "2023-05-20T00:00:00.000Z";

  const [
    photoCount,
    diaryCount,
    anniversaryCount,
    messageCount,
    togetherCount,
    togetherDone,
    musicCount,
    latestDiaries,
    latestMessages,
    latestPhotos,
    latestTogether,
  ] = await Promise.all([
    prisma.albumPhoto.count(),
    prisma.diaryEntry.count(),
    prisma.anniversary.count(),
    prisma.message.count(),
    prisma.togetherItem.count(),
    prisma.togetherItem.count({ where: { isCompleted: true } }),
    prisma.musicTrack.count(),
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
    prisma.albumPhoto.findMany({
      orderBy: { createdAt: "desc" },
      take: 4,
      include: { uploadedBy: { select: { displayName: true } } },
    }),
    prisma.togetherItem.findMany({
      orderBy: { updatedAt: "desc" },
      take: 5,
    }),
  ]);

  const togetherPct =
    togetherCount === 0 ? 0 : Math.round((togetherDone / togetherCount) * 100);

  const stats = [
    {
      key: "diaries",
      label: "点滴",
      value: diaryCount,
      hint: "DiaryEntry",
      href: "/diary",
    },
    {
      key: "photos",
      label: "相册",
      value: photoCount,
      hint: "AlbumPhoto",
      href: "/album",
    },
    {
      key: "messages",
      label: "留言",
      value: messageCount,
      hint: "Message",
      href: "/message",
    },
    {
      key: "together",
      label: "清单",
      value: togetherCount,
      hint: `完成 ${togetherDone} · ${togetherPct}%`,
      href: "/together",
    },
    {
      key: "anniversaries",
      label: "轨迹",
      value: anniversaryCount,
      hint: "Anniversary",
      href: "/anniversary",
    },
    {
      key: "music",
      label: "曲目",
      value: musicCount,
      hint: "MusicTrack",
    },
  ];

  const activity = [
    ...latestDiaries.map((item) => ({
      id: `diary-${item.id}`,
      kind: "点滴",
      title: item.title,
      meta: `${item.author.displayName} · ${formatDateTime(item.createdAt)}`,
      href: "/diary",
      at: item.createdAt.getTime(),
    })),
    ...latestMessages.map((item) => ({
      id: `msg-${item.id}`,
      kind: "留言",
      title: item.content.slice(0, 48) + (item.content.length > 48 ? "…" : ""),
      meta: `${item.author.displayName} · ${formatDateTime(item.createdAt)}`,
      href: "/message",
      at: item.createdAt.getTime(),
    })),
    ...latestPhotos.map((item) => ({
      id: `photo-${item.id}`,
      kind: "相册",
      title: item.caption || "未命名照片",
      meta: `${item.uploadedBy.displayName} · ${formatDateTime(item.createdAt)}`,
      href: "/album",
      at: item.createdAt.getTime(),
    })),
    ...latestTogether.map((item) => ({
      id: `todo-${item.id}`,
      kind: item.isCompleted ? "清单·完成" : "清单",
      title: item.title,
      meta: formatDateTime(item.updatedAt),
      href: "/together",
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
                我们小世界的实时统计，点击卡片可跳到对应页面。
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
              initialStartAt={startAt}
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
