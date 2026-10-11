"use client";

import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import {
  LayoutDashboard,
  Layers3,
  LogOut,
  Rocket,
  Settings2,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";

const links = [
  { href: "/admin/dashboard", label: "仪表盘", icon: LayoutDashboard },
  { href: "/stack", label: "模块说明", icon: Layers3 },
  { href: "/start", label: "快速开始", icon: Rocket },
  { href: "/about", label: "关于", icon: Settings2 },
];

export function AdminShell({
  children,
  displayName,
}: {
  children: React.ReactNode;
  displayName: string;
}) {
  const pathname = usePathname();
  const router = useRouter();

  async function logout() {
    await fetch("/api/auth/logout", { method: "POST" });
    router.push("/admin");
    router.refresh();
  }

  return (
    <div className="mx-auto max-w-6xl px-4 py-8 sm:px-6">
      <div className="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <p className="text-xs font-medium tracking-[0.16em] text-teal-ink">
            ADMIN
          </p>
          <h1 className="mt-2 font-display text-3xl tracking-tight">后台控制台</h1>
          <p className="mt-2 text-sm text-muted-foreground">
            你好，{displayName}。查看数据概览与模块栈状态。
          </p>
        </div>
        <Button variant="secondary" size="sm" onClick={logout} className="rounded-xl self-start">
          <LogOut className="mr-1.5 h-4 w-4" />
          退出登录
        </Button>
      </div>

      <nav className="mb-8 flex flex-wrap gap-2">
        {links.map((link) => {
          const Icon = link.icon;
          const active =
            link.href === "/admin/dashboard"
              ? pathname.startsWith("/admin")
              : pathname.startsWith(link.href);
          return (
            <Link
              key={link.href}
              href={link.href}
              className={cn(
                "inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm transition-colors duration-soft ease-soft",
                active
                  ? "bg-foreground text-background"
                  : "bg-secondary/80 text-muted-foreground hover:bg-secondary hover:text-foreground"
              )}
            >
              <Icon className="h-4 w-4" />
              {link.label}
            </Link>
          );
        })}
      </nav>

      {children}
    </div>
  );
}
