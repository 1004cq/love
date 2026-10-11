import Link from "next/link";
import { redirect } from "next/navigation";
import { AdminShell } from "@/components/admin/AdminShell";
import { DashboardStats } from "@/components/admin/DashboardStats";
import { PageTransition } from "@/components/PageTransition";
import { SoftCard } from "@/components/SoftCard";
import { Button } from "@/components/ui/button";
import { getSession } from "@/lib/auth";
import { formatDateTime } from "@/lib/format";
import { prisma } from "@/lib/prisma";
import { techModules } from "@/lib/tech-modules";

export const dynamic = "force-dynamic";

export const metadata = {
  title: "仪表盘 · 后台 · 模块栈",
  description: "后台仪表盘：数据概览、近期动态与模块栈状态",
};

export default async function AdminDashboardPage() {
  const session = await getSession();
  if (!session) {
    redirect("/admin?next=/admin/dashboard");
  }

  const [
    photoCount,
    diaryCount,
    anniversaryCount,
    messageCount,
    togetherCount,
    togetherDone,
    musicCount,
    userCount,
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
    prisma.user.count(),
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
      label: "日记",
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
    {
      key: "users",
      label: "管理员",
      value: userCount,
      hint: "User",
    },
  ];

  const activity = [
    ...latestDiaries.map((item) => ({
      id: `diary-${item.id}`,
      kind: "日记",
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
              <p className="text-xs font-medium tracking-[0.14em] text-teal-ink">
                OVERVIEW
              </p>
              <h2 className="mt-1 font-display text-2xl tracking-tight">数据概览</h2>
              <p className="mt-2 text-sm text-muted-foreground">
                基于 Prisma + SQLite 的实时统计，点击卡片可跳转对应前台模块。
              </p>
            </div>
            <DashboardStats stats={stats} />
          </section>

          <section className="grid gap-4 lg:grid-cols-[1.4fr_1fr]">
            <SoftCard padding="lg" className="space-y-4" hoverLift={false}>
              <div className="flex items-end justify-between gap-3">
                <div>
                  <p className="text-xs font-medium tracking-[0.14em] text-teal-ink">
                    ACTIVITY
                  </p>
                  <h2 className="mt-1 font-display text-xl tracking-tight">近期动态</h2>
                </div>
                <Button asChild variant="secondary" size="sm" className="rounded-xl">
                  <Link href="/">查看前台</Link>
                </Button>
              </div>
              {activity.length === 0 ? (
                <p className="text-sm text-muted-foreground">暂无内容，可先执行 seed。</p>
              ) : (
                <ul className="divide-y divide-border/60">
                  {activity.map((item) => (
                    <li key={item.id}>
                      <Link
                        href={item.href}
                        className="flex items-start justify-between gap-3 py-3.5 transition-colors hover:text-teal-ink"
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

            <SoftCard padding="lg" className="space-y-4" hoverLift={false}>
              <div>
                <p className="text-xs font-medium tracking-[0.14em] text-teal-ink">
                  STACK
                </p>
                <h2 className="mt-1 font-display text-xl tracking-tight">技术模块</h2>
                <p className="mt-2 text-sm text-muted-foreground">
                  当前后台由下列七个模块支撑。
                </p>
              </div>
              <ul className="space-y-2.5">
                {techModules.map((m) => (
                  <li key={m.slug}>
                    <Link
                      href={`/stack/${m.slug}`}
                      className="flex items-baseline justify-between gap-2 rounded-xl border border-border/60 bg-card px-3.5 py-3 text-sm transition-colors hover:border-teal-ink/30"
                    >
                      <span>
                        <span className="text-muted-foreground">{m.category}</span>
                        <span className="ml-2 font-medium">{m.name}</span>
                      </span>
                      <span className="shrink-0 text-xs text-teal-ink">{m.tagline}</span>
                    </Link>
                  </li>
                ))}
              </ul>
            </SoftCard>
          </section>

          <section className="overflow-hidden rounded-[1.5rem] border border-border/70 bg-ink px-6 py-8 text-background sm:px-8">
            <p className="text-xs font-medium tracking-[0.16em] text-teal-soft">
              QUICK ACTIONS
            </p>
            <h2 className="mt-2 font-display text-2xl tracking-tight">快捷操作</h2>
            <p className="mt-2 max-w-lg text-sm text-white/70">
              登录态已生效。可回到前台浏览模块说明，或继续完善数据示例。
            </p>
            <div className="mt-6 flex flex-wrap gap-3">
              <Button
                asChild
                className="rounded-xl bg-background text-foreground hover:bg-white"
              >
                <Link href="/stack">模块说明</Link>
              </Button>
              <Button
                asChild
                variant="secondary"
                className="rounded-xl border-white/20 bg-white/10 text-background hover:bg-white/20"
              >
                <Link href="/start">快速开始</Link>
              </Button>
              <Button
                asChild
                variant="secondary"
                className="rounded-xl border-white/20 bg-white/10 text-background hover:bg-white/20"
              >
                <Link href="/">前台首页</Link>
              </Button>
            </div>
          </section>
        </div>
      </AdminShell>
    </PageTransition>
  );
}
