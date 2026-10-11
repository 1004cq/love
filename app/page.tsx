import Link from "next/link";
import { StackHero } from "@/components/StackHero";
import { StackModuleGrid } from "@/components/StackModuleGrid";
import { Button } from "@/components/ui/button";
import { techModules } from "@/lib/tech-modules";

export default function HomePage() {
  return (
    <div>
      <StackHero />

      <div className="mx-auto max-w-5xl space-y-20 px-5 py-16 sm:px-8">
        <section className="space-y-6">
          <div className="max-w-xl">
            <p className="text-xs font-medium tracking-[0.16em] text-teal-ink">MODULES</p>
            <h2 className="mt-2 font-display text-3xl tracking-tight">七个模块，各管一件事</h2>
            <p className="mt-3 text-sm leading-relaxed text-muted-foreground sm:text-base">
              从框架到上线，每一层只写清职责与选型理由，方便对照落地。
            </p>
          </div>
          <StackModuleGrid />
        </section>

        <section className="space-y-6">
          <div className="max-w-xl">
            <p className="text-xs font-medium tracking-[0.16em] text-teal-ink">PIPELINE</p>
            <h2 className="mt-2 font-display text-3xl tracking-tight">一条链路走完</h2>
            <p className="mt-3 text-sm leading-relaxed text-muted-foreground sm:text-base">
              本地用 Prisma + SQLite 跑通，推送到 Git 后由 Vercel 一键发布。
            </p>
          </div>
          <ol className="grid gap-3 sm:grid-cols-7">
            {techModules.map((m, i) => (
              <li
                key={m.slug}
                className="rounded-2xl border border-border/70 bg-card/80 px-3 py-4 text-center shadow-xs"
              >
                <p className="font-num text-xs text-muted-foreground">
                  {String(i + 1).padStart(2, "0")}
                </p>
                <p className="mt-2 text-sm font-medium leading-snug">{m.category}</p>
                <p className="mt-1 text-xs text-muted-foreground">{m.tagline}</p>
              </li>
            ))}
          </ol>
        </section>

        <section className="overflow-hidden rounded-[1.75rem] border border-border/70 bg-ink px-6 py-10 text-background shadow-soft sm:px-10">
          <p className="text-xs font-medium tracking-[0.16em] text-teal-soft">DEPLOY</p>
          <h2 className="mt-3 font-display text-3xl tracking-tight">Vercel 一键上线</h2>
          <p className="mt-3 max-w-lg text-sm leading-relaxed text-white/70 sm:text-base">
            连接仓库、配置环境变量，Preview 与 Production 随推送更新。框架层已按 Next.js App
            Router 对齐。
          </p>
          <div className="mt-7 flex flex-wrap gap-3">
            <Button
              asChild
              size="lg"
              className="rounded-xl bg-background text-foreground hover:bg-white"
            >
              <Link href="/start">开始搭建</Link>
            </Button>
            <Button
              asChild
              variant="secondary"
              size="lg"
              className="rounded-xl border-white/20 bg-white/10 text-background hover:bg-white/20"
            >
              <Link href="/stack/vercel">部署说明</Link>
            </Button>
          </div>
        </section>
      </div>
    </div>
  );
}
