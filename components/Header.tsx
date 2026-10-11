"use client";

import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import { Button } from "@/components/ui/button";

type User = {
  id: string;
  username: string;
  displayName: string;
};

export function Header() {
  const pathname = usePathname();
  const router = useRouter();
  const [user, setUser] = useState<User | null>(null);

  useEffect(() => {
    fetch("/api/auth/me")
      .then((res) => (res.ok ? res.json() : null))
      .then((data) => setUser(data?.user ?? null))
      .catch(() => setUser(null));
  }, [pathname]);

  async function logout() {
    await fetch("/api/auth/logout", { method: "POST" });
    setUser(null);
    router.push("/");
    router.refresh();
  }

  return (
    <header className="sticky top-0 z-40 bg-transparent">
      <div className="mx-auto flex max-w-5xl items-center justify-between gap-3 px-4 py-3 sm:px-6">
        <Link href="/" className="min-w-0">
          <p className="font-script text-[1.65rem] leading-none tracking-wide text-foreground">
            Ours
          </p>
        </Link>

        <div className="flex items-center gap-2">
          <span className="hidden items-center gap-1.5 rounded-full bg-white/55 px-3 py-1.5 text-xs text-foreground/70 backdrop-blur-md sm:inline-flex">
            <span aria-hidden>☾</span>
            晴
          </span>
          <Link
            href="/anniversary"
            className="inline-flex items-center gap-1.5 rounded-full bg-white/55 px-3 py-1.5 text-xs text-foreground/70 backdrop-blur-md transition-colors hover:bg-white/80"
          >
            <span aria-hidden>◎</span>
            足迹
          </Link>
          {user ? (
            <>
              <Button asChild variant="secondary" size="sm" className="rounded-full">
                <Link href="/admin/dashboard">后台</Link>
              </Button>
              <Button variant="secondary" size="sm" onClick={logout} className="rounded-full">
                退出
              </Button>
            </>
          ) : null}
        </div>
      </div>

      <nav className="mx-auto hidden max-w-5xl items-center justify-center gap-1 px-4 pb-2 md:flex">
        {[
          { href: "/", label: "首页" },
          { href: "/diary", label: "点滴" },
          { href: "/album", label: "相册" },
          { href: "/together", label: "清单" },
          { href: "/anniversary", label: "轨迹" },
          { href: "/message", label: "留言" },
          { href: "/about", label: "关于" },
        ].map((link) => {
          const active =
            link.href === "/"
              ? pathname === "/"
              : pathname.startsWith(link.href);
          return (
            <Link
              key={link.href}
              href={link.href}
              className={`rounded-full px-3.5 py-2 text-sm transition-colors ${
                active
                  ? "bg-foreground text-background"
                  : "text-muted-foreground hover:bg-white/70 hover:text-foreground"
              }`}
            >
              {link.label}
            </Link>
          );
        })}
      </nav>
    </header>
  );
}
