import Link from "next/link";
import { PageTransition } from "@/components/PageTransition";
import { SoftCard } from "@/components/SoftCard";
import { Button } from "@/components/ui/button";
import { techModules } from "@/lib/tech-modules";

export const metadata = {
  title: "关于 · 模块栈",
  description: "模块技术说明框架：Next.js 14+ App Router 前后端一体方案",
};

export default function AboutPage() {
  return (
    <PageTransition>
      <div className="mx-auto max-w-2xl px-5 py-12 sm:px-8">
        <p className="text-xs font-medium tracking-[0.16em] text-teal-ink">ABOUT</p>
        <h1 className="mt-2 font-display text-4xl tracking-tight">关于模块栈</h1>
        <p className="mt-3 text-base leading-relaxed text-muted-foreground">
          这是一份可落地的模块技术说明框架：用同一套仓库讲清选型，并演示前后端一体开发与部署。
        </p>

        <SoftCard padding="lg" className="mt-8 space-y-4">
          <h2 className="font-display text-xl tracking-tight">技术模块</h2>
          <ul className="space-y-3 text-sm leading-relaxed">
            {techModules.map((m) => (
              <li key={m.slug} className="flex flex-wrap items-baseline gap-x-2 gap-y-1">
                <span className="text-muted-foreground">{m.category}</span>
                <Link
                  href={`/stack/${m.slug}`}
                  className="font-medium text-foreground underline-offset-4 hover:underline"
                >
                  {m.name}
                </Link>
                <span className="text-teal-ink">{m.tagline}</span>
              </li>
            ))}
          </ul>
        </SoftCard>

        <SoftCard padding="lg" className="mt-4 space-y-3 text-sm leading-relaxed text-muted-foreground">
          <p>
            框架层用 Next.js 14+ App Router 承接页面与 API；语言层 TypeScript 保证类型安全；
            样式与组件用 Tailwind + shadcn/ui 快速定视觉；动效用 Framer Motion；
            数据用 Prisma + SQLite 保持简单够用；部署走 Vercel 一键上线。
          </p>
          <p>
            管理示例入口仍保留在 <span className="text-foreground">/admin</span>
            ，用于演示鉴权与数据写入。
          </p>
        </SoftCard>

        <div className="mt-8 flex flex-wrap gap-3">
          <Button asChild className="rounded-xl">
            <Link href="/start">快速开始</Link>
          </Button>
          <Button asChild variant="secondary" className="rounded-xl">
            <Link href="/stack">模块说明</Link>
          </Button>
        </div>
      </div>
    </PageTransition>
  );
}
