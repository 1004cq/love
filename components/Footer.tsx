import Link from "next/link";

export function Footer() {
  return (
    <footer className="mt-auto border-t border-border/50 bg-card/40 pb-28 md:pb-10">
      <div className="mx-auto flex max-w-5xl flex-col gap-5 px-5 py-10 text-center sm:px-8">
        <p className="font-display text-2xl tracking-tight text-foreground">模块栈</p>
        <p className="text-sm text-muted-foreground">
          Next.js 14+ · TypeScript · Tailwind · shadcn/ui · Motion · Prisma · Vercel
        </p>
        <div className="flex flex-wrap justify-center gap-4 text-sm text-muted-foreground">
          <Link href="/stack" className="hover:text-foreground">
            模块说明
          </Link>
          <Link href="/start" className="hover:text-foreground">
            快速开始
          </Link>
          <Link href="/about" className="hover:text-foreground">
            关于
          </Link>
          <Link href="/admin" className="hover:text-foreground">
            管理示例
          </Link>
        </div>
      </div>
    </footer>
  );
}
