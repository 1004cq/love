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
  { href: "/stack", label: "模块" },
  { href: "/start", label: "开始" },
  { href: "/about", label: "关于" },
];

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
    <header className="sticky top-0 z-40 border-b border-border/40 bg-background/75 backdrop-blur-md">
      <div className="mx-auto flex max-w-5xl items-center justify-between gap-3 px-4 py-3 sm:px-6">
        <Link href="/" className="min-w-0">
          <p className="font-display text-xl tracking-tight text-foreground sm:text-2xl">
            模块栈
          </p>
        </Link>

        <div className="flex items-center gap-2">
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
                  className={`rounded-xl px-3.5 py-2 text-sm transition-colors duration-soft ease-soft ${
                    active
                      ? "bg-foreground text-background"
                      : "text-muted-foreground hover:bg-secondary hover:text-foreground"
                  }`}
                >
                  {link.label}
                </Link>
              );
            })}
          </nav>
          {user ? (
            <>
              <Button asChild variant="secondary" size="sm" className="rounded-xl">
                <Link href="/admin/dashboard">后台</Link>
              </Button>
              <Button variant="secondary" size="sm" onClick={logout} className="rounded-xl">
                退出
              </Button>
            </>
          ) : null}
        </div>
      </div>
    </header>
  );
}
