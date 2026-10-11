import { LgHero } from "@/components/LgHero";
import { TogetherList } from "@/components/TogetherList";
import { PageTransition } from "@/components/PageTransition";
import { SoftCard } from "@/components/SoftCard";
import { getSession } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

export const dynamic = "force-dynamic";

export default async function TogetherPage() {
  const session = await getSession();
  const canManage = Boolean(session);

  const [partners, items] = await Promise.all([
    prisma.user.findMany({
      orderBy: { createdAt: "asc" },
      take: 2,
      select: { displayName: true, avatarUrl: true },
    }),
    prisma.togetherItem.findMany({
      orderBy: [{ isCompleted: "asc" }, { createdAt: "desc" }],
      include: {
        createdBy: { select: { id: true, displayName: true } },
      },
    }),
  ]);

  const todo = items.filter((item) => !item.isCompleted).length;
  const done = items.length - todo;

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
          <div className="mb-6 text-center">
            <p className="font-script text-lg italic text-muted-foreground">
              ✦ Plans Together ✦
            </p>
            <h1 className="mt-2 font-display text-2xl tracking-tight sm:text-3xl">
              记下想一起完成的心愿
            </h1>
          </div>

          <SoftCard padding="sm" className="mb-5 overflow-hidden bg-foreground text-background">
            <div className="flex items-center gap-3 px-2 py-1.5">
              <span className="flex h-8 w-8 items-center justify-center rounded-full bg-background/15" aria-hidden>
                ♪
              </span>
              <p className="truncate text-sm tracking-wide">总有些惊奇的际遇</p>
            </div>
          </SoftCard>

          <p className="mb-4 text-center text-xs text-muted-foreground">
            已完成 {done} · 全部 {items.length} · 未完成 {todo}
            {canManage ? "。可添加、勾选与删除。" : "。公开浏览，登录后可管理。"}
          </p>

          <TogetherList
            canManage={canManage}
            initialItems={items.map((item) => ({
              ...item,
              createdAt: item.createdAt.toISOString(),
              completedAt: item.completedAt?.toISOString() ?? null,
            }))}
          />
        </div>
      </PageTransition>
    </div>
  );
}
