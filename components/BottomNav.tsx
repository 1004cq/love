"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useEffect, useRef, useState } from "react";
import { BookOpenText, Home, Layers3, Rocket } from "lucide-react";
import { cn } from "@/lib/utils";

const items = [
  { href: "/", label: "首页", icon: Home },
  { href: "/stack", label: "模块", icon: Layers3 },
  { href: "/start", label: "开始", icon: Rocket },
  { href: "/about", label: "关于", icon: BookOpenText },
];

function activeIndex(pathname: string) {
  if (pathname === "/") return 0;
  const idx = items.findIndex(
    (item) => item.href !== "/" && pathname.startsWith(item.href)
  );
  return idx >= 0 ? idx : 0;
}

export function BottomNav() {
  const pathname = usePathname();
  const current = activeIndex(pathname);
  const scrollerRef = useRef<HTMLDivElement>(null);
  const [indicatorLeft, setIndicatorLeft] = useState(6);

  useEffect(() => {
    const root = scrollerRef.current;
    if (!root) return;
    const el = root.querySelectorAll<HTMLElement>("[data-nav-item]")[current];
    if (!el) return;
    setIndicatorLeft(el.offsetLeft);
    el.scrollIntoView({ inline: "center", block: "nearest", behavior: "smooth" });
  }, [current]);

  return (
    <div className="pointer-events-none fixed inset-x-0 bottom-0 z-[60] md:hidden">
      <div
        ref={scrollerRef}
        className="stack-glass pointer-events-auto absolute bottom-5 left-1/2 flex h-14 -translate-x-1/2 items-center rounded-[28px] px-1.5"
      >
        <span
          aria-hidden
          className="pointer-events-none absolute top-1/2 z-[1] h-11 w-11 -translate-y-1/2 rounded-full bg-ink shadow-soft transition-[left] duration-500 ease-soft"
          style={{ left: indicatorLeft }}
        />
        {items.map((item, index) => {
          const Icon = item.icon;
          const active = index === current;
          return (
            <Link
              key={item.href}
              href={item.href}
              data-nav-item
              aria-label={item.label}
              aria-current={active ? "page" : undefined}
              className={cn(
                "relative z-[2] mr-1 flex h-11 w-11 shrink-0 items-center justify-center rounded-full transition-colors duration-soft last:mr-0",
                active ? "text-background" : "text-muted-foreground hover:text-foreground"
              )}
            >
              <Icon className="h-[22px] w-[22px]" strokeWidth={active ? 2.2 : 1.8} />
            </Link>
          );
        })}
      </div>
    </div>
  );
}
