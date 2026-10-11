import { PageTransition } from "@/components/PageTransition";
import { StackModuleGrid } from "@/components/StackModuleGrid";

export const metadata = {
  title: "模块说明 · 模块栈",
  description: "Next.js、TypeScript、Tailwind、shadcn/ui、Framer Motion、Prisma、Vercel 技术模块说明",
};

export default function StackPage() {
  return (
    <PageTransition>
      <div className="mx-auto max-w-5xl px-5 py-12 sm:px-8">
        <div className="mb-10 max-w-2xl">
          <p className="text-xs font-medium tracking-[0.16em] text-teal-ink">STACK</p>
          <h1 className="mt-2 font-display text-4xl tracking-tight">模块技术说明</h1>
          <p className="mt-3 text-base leading-relaxed text-muted-foreground">
            框架、语言、样式、组件、动效、数据库与部署——七个模块组成完整前后端一体方案。
          </p>
        </div>
        <StackModuleGrid />
      </div>
    </PageTransition>
  );
}
