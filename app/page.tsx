import Image from "next/image";
import Link from "next/link";
import { DayFusionCard } from "@/components/DayFusionCard";
import { LgHero } from "@/components/LgHero";
import { SoftCard } from "@/components/SoftCard";
import { Button } from "@/components/ui/button";
import { daysUntil, formatDate, formatDateTime } from "@/lib/format";
import { prisma } from "@/lib/prisma";
import { getSiteConfig } from "@/lib/site";

export const dynamic = "force-dynamic";

export default async function HomePage() {
  const site = await getSiteConfig();
  const startAt = site.startTime.toISOString();

  const [
    latestArticles,
    anniversaries,
    recentMessages,
    recentPhotos,
    listPreview,
  ] = await Promise.all([
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

  const cards = [
    { href: "/little", title: site.card1, desc: site.deci1, tone: "from-[#ff8fab]/25 to-[#ffd6e0]/40" },
    { href: "/leaving", title: site.card2, desc: site.deci2, tone: "from-[#7c6cff]/20 to-[#c4b5fd]/35" },
    { href: "/love-img", title: site.card3, desc: site.deci3, tone: "from-[#41c5ff]/20 to-[#a5f3fc]/35" },
    { href: "/list", title: site.card4, desc: site.deci4, tone: "from-[#fbbf24]/20 to-[#fde68a]/35" },
  ];

  return (
    <div>
      <LgHero
        partnerA={{
          displayName: site.boy,
          avatarUrl: site.boyAvatar || undefined,
        }}
        partnerB={{
          displayName: site.girl,
          avatarUrl: site.girlAvatar || undefined,
        }}
        distanceKm="546.9 km"
      />

      <div className="mx-auto max-w-5xl space-y-8 px-4 pb-8 sm:px-6">
        <div className="pt-2 text-center">
          <p className="nav-writing mx-auto">{site.writing}</p>
          <h1 className="mt-4 font-display text-2xl tracking-tight text-foreground sm:text-3xl">
            {site.title}
          </h1>
        </div>

        <section className="space-y-3">
          <div className="flex items-center gap-2">
            <span className="h-px w-6 bg-blush" />
            <p className="text-sm font-medium tracking-wide text-blush">Love Day</p>
          </div>
          <DayFusionCard startAt={startAt} title={site.loveDayTitle} />
        </section>

        <section className="grid gap-3 sm:grid-cols-2">
          {cards.map((card) => (
            <Link key={card.href} href={card.href} className="group block">
              <SoftCard
                className={`bg-gradient-to-br ${card.tone} border-white/80`}
                padding="lg"
              >
                <h2 className="font-display text-xl tracking-tight">{card.title}</h2>
                <p className="mt-2 text-sm text-muted-foreground">{card.desc}</p>
                <p className="mt-4 text-sm text-blush opacity-80 transition-opacity group-hover:opacity-100">
                  进入 →
                </p>
              </SoftCard>
            </Link>
          ))}
        </section>

        <SectionBlock eyebrow="恋爱日期" title="Love Days" href="/list">
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

        <SectionBlock eyebrow="恋爱事件 NEW" title={site.card4} href="/list">
          <ul className="space-y-3">
            {listPreview.map((item) => (
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

        <SectionBlock eyebrow="点点滴滴 NEW" title={site.card1} href="/little">
          <ul className="space-y-3">
            {latestArticles.map((entry) => (
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

        <SectionBlock eyebrow="恋爱相册" title={site.card3} href="/love-img">
          {recentPhotos.length === 0 ? (
            <p className="text-sm text-muted-foreground">还没有照片。</p>
          ) : (
            <div className="grid grid-cols-3 gap-2">
              {recentPhotos.map((photo) => (
                <Link
                  key={photo.id}
                  href="/love-img"
                  className="relative aspect-square overflow-hidden rounded-2xl bg-muted"
                >
                  <Image
                    src={photo.url}
                    alt={photo.caption || "恋爱相册"}
                    fill
                    className="object-cover"
                    sizes="33vw"
                  />
                </Link>
              ))}
            </div>
          )}
        </SectionBlock>

        <SectionBlock eyebrow="祝福留言" title={site.card2} href="/leaving">
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

        <div className="flex flex-wrap justify-center gap-3 pb-2">
          <Button asChild className="rounded-full">
            <Link href="/love-img">进入相册</Link>
          </Button>
          <Button asChild variant="secondary" className="rounded-full">
            <Link href="/list">恋爱事件</Link>
          </Button>
        </div>
      </div>
    </div>
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
