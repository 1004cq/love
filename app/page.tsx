import Link from "next/link";
import { DayFusionCard } from "@/components/DayFusionCard";
import { LgHero } from "@/components/LgHero";
import { ModuleNavCards } from "@/components/ModuleNavCards";
import { SectionTitle } from "@/components/SectionTitle";
import { WritingBar } from "@/components/WritingBar";
import { daysUntil, formatDate, formatDateTime } from "@/lib/format";
import { LG_BOY_AVATAR, LG_GIRL_AVATAR, LG_TIME_CARD_COLORS } from "@/lib/lg-assets";
import { prisma } from "@/lib/prisma";
import { getSiteConfig } from "@/lib/site";

export const dynamic = "force-dynamic";

export default async function HomePage() {
  const site = await getSiteConfig();
  const startAt = site.startTime.toISOString();

  const [latestArticles, anniversaries, recentMessages] = await Promise.all([
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
  ]);

  const cards = [
    { href: "/little", title: site.card1, desc: site.deci1 },
    { href: "/leaving", title: site.card2, desc: site.deci2 },
    { href: "/love-img", title: site.card3, desc: site.deci3 },
    { href: "/list", title: site.card4, desc: site.deci4 },
  ];

  return (
    <div>
      <LgHero
        partnerA={{
          displayName: site.boy,
          avatarUrl: site.boyAvatar || LG_BOY_AVATAR,
        }}
        partnerB={{
          displayName: site.girl,
          avatarUrl: site.girlAvatar || LG_GIRL_AVATAR,
        }}
      />

      <ModuleNavCards cards={cards} />

      <div className="mx-auto max-w-5xl space-y-7 px-4 pb-10 sm:px-6">
        <WritingBar>{site.writing}</WritingBar>

        <section className="space-y-4">
          <SectionTitle>Love Day</SectionTitle>
          <DayFusionCard startAt={startAt} title={site.loveDayTitle} />
        </section>

        {anniversaries.length > 0 ? (
          <section className="grid gap-3 sm:grid-cols-2">
            {anniversaries.map((item, i) => {
              const remain = daysUntil(item.date);
              const past = remain < 0;
              return (
                <div
                  key={item.id}
                  className="flex items-center justify-between gap-3 rounded-xl px-5 py-5 font-display text-white shadow-soft"
                  style={{
                    background: LG_TIME_CARD_COLORS[i % LG_TIME_CARD_COLORS.length],
                  }}
                >
                  <p className="truncate text-lg font-bold tracking-wide">{item.title}</p>
                  <div className="shrink-0 text-right">
                    <p className="text-sm opacity-90">{formatDate(item.date)}</p>
                    <p className="mt-1 text-base font-bold">
                      {past ? "过了" : "还有"}
                      {Math.abs(remain)}天
                    </p>
                  </div>
                </div>
              );
            })}
          </section>
        ) : null}

        {recentMessages.length > 0 ? (
          <section className="space-y-4">
            <SectionTitle>最新留言</SectionTitle>
            <div className="space-y-3 rounded-2xl border border-[rgba(62,52,78,0.1)] bg-white/90 p-4 shadow-soft sm:p-5">
              {recentMessages.map((message) => (
                <div
                  key={message.id}
                  className="flex items-start justify-between gap-3 border-b border-border/50 pb-3 last:border-0 last:pb-0"
                >
                  <div className="min-w-0">
                    <div className="flex flex-wrap items-center gap-2">
                      <b className="text-sm text-foreground">{message.author.displayName}</b>
                      <span className="rounded bg-[#e7ebed] px-2 py-0.5 text-xs text-[#8799a3]">
                        中国
                      </span>
                    </div>
                    <p className="mt-1 text-sm text-muted-foreground">{message.content}</p>
                  </div>
                  <span className="shrink-0 text-xs text-muted-foreground">
                    {formatDateTime(message.createdAt)}
                  </span>
                </div>
              ))}
              <Link
                href="/leaving"
                className="inline-flex text-sm text-[#ff6b8a] hover:underline"
              >
                查看全部留言 →
              </Link>
            </div>
          </section>
        ) : null}

        {latestArticles.length > 0 ? (
          <section className="space-y-4">
            <SectionTitle>最新动态</SectionTitle>
            <div className="space-y-3">
              {latestArticles.map((entry) => (
                <Link
                  key={entry.id}
                  href="/little"
                  className="block rounded-2xl border border-[rgba(62,52,78,0.1)] bg-white/90 p-5 shadow-soft transition-transform hover:-translate-y-0.5"
                >
                  <div className="flex items-center justify-between gap-2">
                    <span className="font-medium text-[#058bf3]">
                      {entry.author.displayName}
                    </span>
                    <span className="text-xs text-muted-foreground">
                      {formatDateTime(entry.createdAt)}
                    </span>
                  </div>
                  <h3 className="mt-2 font-display text-xl font-bold tracking-tight">
                    {entry.title}
                  </h3>
                  <p className="mt-2 line-clamp-2 text-sm text-[#787878]">
                    {entry.content}
                  </p>
                </Link>
              ))}
            </div>
          </section>
        ) : null}
      </div>
    </div>
  );
}
