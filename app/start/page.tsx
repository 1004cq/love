import Link from "next/link";
import { PageTransition } from "@/components/PageTransition";
import { SoftCard } from "@/components/SoftCard";
import { Button } from "@/components/ui/button";

const steps = [
  {
    title: "安装依赖",
    code: "npm install",
    note: "安装 Next.js、Prisma、Framer Motion 与 UI 相关包。",
  },
  {
    title: "初始化数据库",
    code: "npm run db:setup",
    note: "Prisma push schema 到 SQLite，并写入种子数据。",
  },
  {
    title: "本地开发",
    code: "npm run dev",
    note: "打开 http://localhost:3000 预览模块说明与示例能力。",
  },
  {
    title: "部署上线",
    code: "git push → Vercel",
    note: "连接仓库后一键构建；配置 DATABASE_URL 与 AUTH_SECRET。",
  },
];

export const metadata = {
  title: "快速开始 · 模块栈",
  description: "安装、数据库、本地开发与 Vercel 部署步骤",
};

export default function StartPage() {
  return (
    <PageTransition>
      <div className="mx-auto max-w-2xl px-5 py-12 sm:px-8">
        <p className="text-xs font-medium tracking-[0.16em] text-teal-ink">GET STARTED</p>
        <h1 className="mt-2 font-display text-4xl tracking-tight">快速开始</h1>
        <p className="mt-3 text-base leading-relaxed text-muted-foreground">
          四步把模块技术栈跑起来：安装、建库、开发、部署。
        </p>

        <ol className="mt-10 space-y-4">
          {steps.map((step, i) => (
            <li key={step.title}>
              <SoftCard padding="md" className="space-y-2">
                <p className="text-xs font-medium tracking-[0.12em] text-teal-ink">
                  STEP {String(i + 1).padStart(2, "0")}
                </p>
                <h2 className="font-display text-xl tracking-tight">{step.title}</h2>
                <pre className="overflow-x-auto rounded-xl bg-ink px-4 py-3 font-num text-sm text-background">
                  {step.code}
                </pre>
                <p className="text-sm text-muted-foreground">{step.note}</p>
              </SoftCard>
            </li>
          ))}
        </ol>

        <div className="mt-10 flex flex-wrap gap-3">
          <Button asChild className="rounded-xl">
            <Link href="/stack">浏览模块</Link>
          </Button>
          <Button asChild variant="secondary" className="rounded-xl">
            <Link href="/about">关于框架</Link>
          </Button>
        </div>
      </div>
    </PageTransition>
  );
}
