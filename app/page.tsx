import Image from "next/image";
import Link from "next/link";
import { AdminPanel } from "@/components/AdminPanel";
import { CoupleCard } from "@/components/CoupleCard";
import { DayFusionCard } from "@/components/DayFusionCard";
import { LgHero } from "@/components/LgHero";
import { SoftCard } from "@/components/SoftCard";
import { Button } from "@/components/ui/button";
import { getSession } from "@/lib/auth";
import { daysTogether, daysUntil, formatDate, formatDateTime } from "@/lib/format";
import { prisma } from "@/lib/prisma";

export const dynamic = "force-dynamic";

export default async function HomePage() {
  const session = await getSession();
  const setting = await prisma.setting.findUnique({ where: { id: 1 } });
  const startAt =
    setting?.relationshipStartAt.toISOString() ||
    process.env.RELATIONSHIP_START ||
    "2023-05-20T00:00:00.000Z";

  const togetherDays = daysTogether(startAt);

  const [
    partners,
    photoCount,
    diaryCount,
    anniversaryCount,
    messageCount,
    togetherCount,
    togetherDone,
    latestDiaries,
    anniversaries,
    recentMessages,
    recentPhotos,
    togetherPreview,
  ] = await Promise.all([
    prisma.user.findMany({
      orderBy: { createdAt: "asc" },
      take: 2,
      select: { id: true, displayName: true, username: true, avatarUrl: true },
    }),
    prisma.albumPhoto.count(),
    prisma.diaryEntry.count(),
    prisma.anniversary.count(),
    prisma.message.count(),
    prisma.togetherItem.count(),
    prisma.togetherItem.count({ where: { isCompleted: true } }),
    prisma.diaryEntry.findMany({
      orderBy: { createdAt: "desc" },
      take: 3,
      include: { author: { select: { displayName: true } } },
    }),
    prisma.anniversary.findMany({ orderBy: { date: "asc" }, take: 6 }),
    prisma.message.findMany({
      orderBy: { createdAt: "desc" },
      take: 6,
      include: { author: { select: { displayName: true } } },
    }),
    prisma.albumPhoto.findMany({
      orderBy: { createdAt: "desc" },
      take: 6,
    }),
    prisma.togetherItem.findMany({
      orderBy: [{ isCompleted: "asc" }, { createdAt: "desc" }],
      take: 4,
    }),
  ]);

  const partnerA = partners[0];
  const partnerB = partners[1];
  const pct =
    togetherCount === 0 ? 0 : Math.round((togetherDone / togetherCount) * 100);

  return (
    <div>
      <LgHero
        partnerA={{
          displayName: partnerA?.displayName || "Alice",
          avatarUrl: partnerA?.avatarUrl,
        }}
        partnerB={{
          displayName: partnerB?.displayName || "Bob",
          avatarUrl: partnerB?.avatarUrl,
        }}
        distanceKm="546.9 km"
      />

      <div className="mx-auto max-w-5xl space-y-8 px-4 pb-8 sm:px-6">
        <div className="pt-2 text-center">
          <p className="font-script text-lg text-muted-foreground">* Our Cozy Place *</p>
          <h1 className="mt-2 font-display text-2xl tracking-tight text-foreground sm:text-3xl">
            收好我们的日常与心动
          </h1>
        </div>

        <DayFusionCard startAt={startAt} />

        <CoupleCard
          partnerA={{
            displayName: partnerA?.displayName || "Alice",
            avatarUrl: partnerA?.avatarUrl,
          }}
          partnerB={{
            displayName: partnerB?.displayName || "Bob",
            avatarUrl: partnerB?.avatarUrl,
          }}
          subtitle="我们的小世界"
        />

        <div className="flex flex-wrap justify-center gap-3">
          <Button asChild className="rounded-full">
            <Link href="/album">进入相册</Link>
          </Button>
          <Button asChild variant="secondary" className="rounded-full">
            <Link href="/together">清单</Link>
          </Button>
        </div>

        <section className="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
          <StatTile href="/together" label="清单" en="Plans Together" value={`${togetherDone} / ${togetherCount}`} unit={`${pct}%`} />
          <StatTile href="/diary" label="点滴" en="Memory Notes" value={String(diaryCount)} unit="Notes" />
          <StatTile href="/album" label="相册" en="Photo Keepsakes" value={String(photoCount)} unit="Photos" />
          <StatTile href="/message" label="留言" en="Kind Messages" value={String(messageCount)} unit="Messages" />
          <StatTile href="/anniversary" label="轨迹" en="Steps of Us" value={String(anniversaryCount)} unit="Days" />
          <StatTile href="/" label="我们的小世界" en="Running" value={String(togetherDays)} unit="已平稳运行" />
        </section>

        <SectionBlock eyebrow="清单 NEW" title="Plans Together" href="/together">
          <ul className="space-y-3">
            {togetherPreview.map((item) => (
              <li key={item.id} className="rounded-2xl border border-border/70 bg-card px-4 py-3.5">
                <p className={item.isCompleted ? "text-muted-foreground line-through" : "font-medium"}>
                  {item.title}
                </p>
                {item.note ? (
                  <p className="mt-1 text-sm text-muted-foreground">{item.note}</p>
                ) : null}
              </li>
            ))}
          </ul>
        </SectionBlock>

        <SectionBlock eyebrow="Love Day" title="纪念日" href="/anniversary">
          <ul className="space-y-3">
            {anniversaries.map((item) => {
              const remain = daysUntil(item.date);
              return (
                <li
                  key={item.id}
                  className="flex items-center justify-between gap-3 rounded-2xl border border-border/70 bg-card px-4 py-3.5"
                >
                  <div>
                    <p className="font-medium">{item.title}</p>
                    <p className="mt-1 text-xs text-muted-foreground">
                      {remain >= 0 ? "目标日" : "起始日"}：{formatDate(item.date)}
                    </p>
                  </div>
                  <p className="font-num text-lg text-blush">
                    {Math.abs(remain)}
                    <span className="ml-0.5 text-xs text-muted-foreground">天</span>
                  </p>
                </li>
              );
            })}
          </ul>
        </SectionBlock>

        <SectionBlock eyebrow="点滴 NEW" title="Memory Notes" href="/diary">
          <ul className="space-y-3">
            {latestDiaries.map((entry) => (
              <li key={entry.id} className="rounded-2xl border border-border/70 bg-card px-4 py-4">
                <div className="flex items-center justify-between gap-2">
                  <h3 className="font-display text-lg">{entry.title}</h3>
                  <span className="text-xs text-muted-foreground">
                    {formatDateTime(entry.createdAt)}
                  </span>
                </div>
                <p className="mt-2 line-clamp-2 text-sm text-muted-foreground">
                  {entry.content}
                </p>
                <p className="mt-2 text-xs text-blush">{entry.author.displayName}</p>
              </li>
            ))}
          </ul>
        </SectionBlock>

        <SectionBlock eyebrow="相册" title="Photo Keepsakes" href="/album">
          {recentPhotos.length === 0 ? (
            <p className="text-sm text-muted-foreground">还没有照片。</p>
          ) : (
            <div className="grid grid-cols-3 gap-2">
              {recentPhotos.map((photo) => (
                <Link
                  key={photo.id}
                  href="/album"
                  className="relative aspect-square overflow-hidden rounded-2xl bg-muted"
                >
                  <Image
                    src={photo.url}
                    alt={photo.caption || "相册"}
                    fill
                    className="object-cover"
                    sizes="33vw"
                  />
                </Link>
              ))}
            </div>
          )}
        </SectionBlock>

        <SectionBlock eyebrow="留言" title="Kind Messages" href="/message">
          <ul className="space-y-3">
            {recentMessages.map((message) => (
              <li key={message.id} className="rounded-2xl border border-border/70 bg-card px-4 py-3.5">
                <div className="flex justify-between text-xs text-muted-foreground">
                  <span className="text-blush">{message.author.displayName}</span>
                  <span>{formatDateTime(message.createdAt)}</span>
                </div>
                <p className="mt-2 text-sm">{message.content}</p>
              </li>
            ))}
          </ul>
        </SectionBlock>

        {session && (
          <AdminPanel
            initialStartAt={startAt}
            stats={{
              photos: photoCount,
              diaries: diaryCount,
              anniversaries: anniversaryCount,
              messages: messageCount,
            }}
          />
        )}
      </div>
    </div>
  );
}

function StatTile({
  href,
  label,
  en,
  value,
  unit,
}: {
  href: string;
  label: string;
  en: string;
  value: string;
  unit: string;
}) {
  return (
    <Link href={href} className="block">
      <SoftCard className="h-full" padding="sm">
        <p className="text-[11px] tracking-[0.12em] text-blush">{label}</p>
        <p className="mt-1 text-xs text-muted-foreground">{en}</p>
        <p className="mt-3 font-num text-2xl font-semibold tracking-tight">{value}</p>
        <p className="mt-1 text-xs text-muted-foreground">{unit}</p>
      </SoftCard>
    </Link>
  );
}

function SectionBlock({
  eyebrow,
  title,
  href,
  children,
}: {
  eyebrow: string;
  title: string;
  href: string;
  children: React.ReactNode;
}) {
  return (
    <section className="space-y-4">
      <div className="flex items-end justify-between gap-3">
        <div>
          <p className="text-xs tracking-[0.14em] text-blush">{eyebrow}</p>
          <h2 className="mt-1 font-display text-2xl tracking-tight">{title}</h2>
        </div>
        <Link href={href} className="text-sm text-muted-foreground hover:text-foreground">
          更多 →
        </Link>
      </div>
      {children}
    </section>
  );
}
