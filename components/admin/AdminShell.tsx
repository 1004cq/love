"use client";

import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import {
  BookOpenText,
  Camera,
  Home,
  LayoutDashboard,
  ListChecks,
  LogOut,
  MessageCircleMore,
  Notebook,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";

const links = [
  { href: "/admin/dashboard", label: "后台首页", icon: LayoutDashboard },
  { href: "/", label: "前台首页", icon: Home },
  { href: "/little", label: "点点滴滴", icon: Notebook },
  { href: "/leaving", label: "祝福留言", icon: MessageCircleMore },
  { href: "/love-img", label: "恋爱相册", icon: Camera },
  { href: "/list", label: "恋爱事件", icon: ListChecks },
  { href: "/about", label: "关于我们", icon: BookOpenText },
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
          <p className="font-script text-2xl text-blush">LG_NewUi</p>
          <h1 className="mt-1 font-display text-3xl tracking-tight">后台首页</h1>
          <p className="mt-2 text-sm text-muted-foreground">
            你好，{displayName}。小手一牵 岁岁年年～
          </p>
        </div>
        <Button
          variant="secondary"
          size="sm"
          onClick={logout}
          className="self-start rounded-full"
        >
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
              : link.href === "/"
                ? pathname === "/"
                : pathname.startsWith(link.href);
          return (
            <Link
              key={link.href}
              href={link.href}
              className={cn(
                "inline-flex items-center gap-2 rounded-full px-3.5 py-2 text-sm transition-colors",
                active
                  ? "bg-foreground text-background"
                  : "bg-white/70 text-muted-foreground hover:bg-white hover:text-foreground"
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
