import { LgHero } from "@/components/LgHero";
import { PageTransition } from "@/components/PageTransition";
import { SoftCard } from "@/components/SoftCard";
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
    "源项目参考：LGNewUI（love.zip PHP 版）",
  ];

  return (
    <div>
      <LgHero
        partnerA={{ displayName: site.boy, avatarUrl: site.boyAvatar || undefined }}
        partnerB={{ displayName: site.girl, avatarUrl: site.girlAvatar || undefined }}
      />
      <PageTransition>
        <div className="mx-auto max-w-2xl px-4 pb-10 sm:px-6">
          <div className="mb-6 text-center">
            <p className="text-xs tracking-[0.14em] text-blush">ABOUT</p>
            <h1 className="mt-2 font-display text-3xl tracking-tight">关于我们</h1>
            <p className="mt-2 text-sm text-muted-foreground">
              与 {site.title} 小站对话中…
            </p>
          </div>
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
