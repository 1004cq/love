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

const nav = [
  { href: "/", label: "首页" },
  { href: "/little", label: "点点滴滴" },
  { href: "/leaving", label: "祝福留言" },
  { href: "/love-img", label: "恋爱相册" },
  { href: "/list", label: "恋爱事件" },
  { href: "/about", label: "关于我们" },
];

export function Header({ logo = "LG_Demo" }: { logo?: string }) {
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
    <header className="sticky top-0 z-40 border-b border-white/60 bg-[#f7f7fb]/85 backdrop-blur-xl">
      <div className="mx-auto flex max-w-5xl items-center justify-between gap-3 px-4 py-3 sm:px-6">
        <Link href="/" className="min-w-0">
          <p className="font-script text-[1.75rem] leading-none tracking-wide text-[#181818]">
            {logo}
          </p>
        </Link>

        <nav className="hidden items-center gap-1 md:flex">
          {nav.map((link) => {
            const active =
              link.href === "/"
                ? pathname === "/"
                : pathname.startsWith(link.href);
            return (
              <Link
                key={link.href}
                href={link.href}
                className={`rounded-full px-3 py-1.5 text-sm transition-colors ${
                  active
                    ? "bg-[#ff6b8a] text-white shadow-sm"
                    : "text-[#7d7888] hover:bg-white/80 hover:text-[#282531]"
                }`}
              >
                {link.label}
              </Link>
            );
          })}
        </nav>

        <div className="flex items-center gap-2">
          {user ? (
            <>
              <Button asChild variant="secondary" size="sm" className="rounded-full">
                <Link href="/admin/dashboard">后台</Link>
              </Button>
              <Button variant="secondary" size="sm" onClick={logout} className="rounded-full">
                退出
              </Button>
            </>
          ) : (
            <Link
              href="/admin"
              className="text-xs text-[#7d7888] transition-colors hover:text-[#ff6b8a]"
            >
              管理
            </Link>
          )}
        </div>
      </div>
    </header>
  );
}
