import { PageTransition } from "@/components/PageTransition";
import { SiteHero } from "@/components/SiteHero";
import { SoftCard } from "@/components/SoftCard";
import { WritingBar } from "@/components/WritingBar";
import { getSiteConfig } from "@/lib/site";

export const dynamic = "force-dynamic";

export const metadata = {
  title: "关于我们",
  description: "与情侣小站对话",
};

export default async function AboutPage() {
  const site = await getSiteConfig();

  const lines = [
    "Hi, 欢迎来访~",
    "花有重开日 人无再少年",
    `本站前端模块：${site.card1}、${site.card2}、${site.card3}、${site.card4}`,
    "技术栈：Next.js 14 App Router · TypeScript · Tailwind · shadcn/ui · Framer Motion · Prisma + SQLite · Vercel",
    "视觉对齐：LGNewUI（love.zip PHP 版）轮播头图 / 四入口卡 / Love Day 倒计时",
  ];

  return (
    <div>
      <SiteHero site={site} />
      <PageTransition>
        <div className="mx-auto max-w-2xl space-y-6 px-4 pb-10 sm:px-6">
          <WritingBar>关于我们 · {site.title}</WritingBar>
          <SoftCard padding="lg" className="space-y-4">
            {lines.map((line) => (
              <p
                key={line}
                className="rounded-2xl bg-secondary/70 px-4 py-3 text-sm leading-relaxed text-foreground/85"
              >
                {line}
              </p>
            ))}
            <p className="text-sm text-muted-foreground">
              {site.writing} 管理入口：
              <span className="text-foreground"> /admin </span>
            </p>
          </SoftCard>
        </div>
      </PageTransition>
    </div>
  );
}
