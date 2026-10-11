import Link from "next/link";

export function Footer({
  writing = "小手一牵 岁岁年年～",
  copyright = "LG_NewUi Web",
  logo = "LG_Demo",
}: {
  writing?: string;
  copyright?: string;
  logo?: string;
}) {
  return (
    <footer className="mt-auto border-t border-border/50 bg-white/50 pb-28 md:pb-10">
      <div className="mx-auto flex max-w-5xl flex-col gap-5 px-5 py-10 text-center sm:px-8">
        <p className="font-script text-2xl text-foreground">{logo}</p>
        <p className="text-sm text-muted-foreground">{writing}</p>
        <div className="flex flex-wrap justify-center gap-4 text-sm text-muted-foreground">
          <Link href="/little" className="hover:text-foreground">
            点点滴滴
          </Link>
          <Link href="/leaving" className="hover:text-foreground">
            祝福留言
          </Link>
          <Link href="/love-img" className="hover:text-foreground">
            恋爱相册
          </Link>
          <Link href="/list" className="hover:text-foreground">
            恋爱事件
          </Link>
          <Link href="/about" className="hover:text-foreground">
            关于我们
          </Link>
        </div>
        <p className="text-xs text-muted-foreground/80">{copyright}</p>
      </div>
    </footer>
  );
}
