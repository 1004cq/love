import Link from "next/link";

export function Footer() {
  return (
    <footer className="mt-auto border-t border-border/50 bg-white/50 pb-28 md:pb-10">
      <div className="mx-auto flex max-w-5xl flex-col gap-5 px-5 py-10 text-center sm:px-8">
        <p className="font-script text-2xl text-foreground">Ours</p>
        <p className="text-sm text-muted-foreground">
          朝暮与年岁并往，与你行至天光。
        </p>
        <div className="flex flex-wrap justify-center gap-4 text-sm text-muted-foreground">
          <Link href="/diary" className="hover:text-foreground">点滴</Link>
          <Link href="/album" className="hover:text-foreground">相册</Link>
          <Link href="/together" className="hover:text-foreground">清单</Link>
          <Link href="/anniversary" className="hover:text-foreground">轨迹</Link>
          <Link href="/message" className="hover:text-foreground">留言</Link>
          <Link href="/about" className="hover:text-foreground">关于</Link>
        </div>
      </div>
    </footer>
  );
}
