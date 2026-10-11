"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useEffect, useRef, useState } from "react";
import {
  BookOpenText,
  Camera,
  Clock,
  Home,
  ListChecks,
  MessageCircleMore,
  Notebook,
} from "lucide-react";
import { cn } from "@/lib/utils";

const items = [
  { href: "/diary", label: "点滴", icon: Notebook },
  { href: "/message", label: "留言", icon: MessageCircleMore },
  { href: "/anniversary", label: "轨迹", icon: Clock },
  { href: "/", label: "首页", icon: Home },
  { href: "/album", label: "相册", icon: Camera },
  { href: "/together", label: "清单", icon: ListChecks },
  { href: "/about", label: "关于", icon: BookOpenText },
];

function activeIndex(pathname: string) {
  if (pathname === "/") return 3;
  const idx = items.findIndex(
    (item) => item.href !== "/" && pathname.startsWith(item.href)
  );
  return idx >= 0 ? idx : 3;
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
    <div className="lgnewui-mobile-nav-root pointer-events-none fixed inset-x-0 bottom-0 z-[60] md:hidden">
      <div
        ref={scrollerRef}
        className="lg-glass pointer-events-auto absolute bottom-5 left-1/2 flex h-14 max-w-[calc(100%-2.5rem)] -translate-x-1/2 items-center overflow-x-auto rounded-[32px] px-1.5"
      >
        <span
          aria-hidden
          className="pointer-events-none absolute top-1/2 z-[1] h-11 w-11 -translate-y-1/2 rounded-full bg-[#1d1d1f] shadow-[0_4px_12px_rgba(0,0,0,0.2)] transition-[left] duration-500"
          style={{
            left: indicatorLeft,
            transitionTimingFunction: "cubic-bezier(0.34, 1.56, 0.64, 1)",
          }}
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
                "relative z-[2] mr-1 flex h-11 w-11 shrink-0 items-center justify-center rounded-full transition-colors duration-300 last:mr-0",
                active ? "text-white" : "text-[#99999e] hover:text-[#555]"
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
