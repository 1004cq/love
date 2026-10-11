"use client";

import Link from "next/link";
import { motion, useReducedMotion } from "framer-motion";
import { Button } from "@/components/ui/button";
import { duration, easeOutSoft } from "@/lib/motion";

export function StackHero() {
  const reduce = useReducedMotion();

  return (
    <section className="relative isolate min-h-[min(92vh,820px)] overflow-hidden">
      <div
        aria-hidden
        className="absolute inset-0 -z-10"
        style={{
          background:
            "radial-gradient(120% 80% at 10% 0%, hsl(168 35% 88% / 0.9), transparent 55%)," +
            "radial-gradient(90% 70% at 90% 20%, hsl(210 40% 90% / 0.85), transparent 50%)," +
            "linear-gradient(165deg, hsl(210 20% 97%) 0%, hsl(168 18% 94%) 45%, hsl(210 16% 92%) 100%)",
        }}
      />
      <div
        aria-hidden
        className="pointer-events-none absolute inset-0 -z-10 opacity-[0.35]"
        style={{
          backgroundImage:
            "linear-gradient(hsl(210 20% 20% / 0.05) 1px, transparent 1px)," +
            "linear-gradient(90deg, hsl(210 20% 20% / 0.05) 1px, transparent 1px)",
          backgroundSize: "48px 48px",
          maskImage: "linear-gradient(to bottom, black 40%, transparent 95%)",
        }}
      />

      <div className="mx-auto flex min-h-[min(92vh,820px)] max-w-5xl flex-col justify-center px-5 pb-16 pt-10 sm:px-8">
        <motion.div
          initial={reduce ? false : { opacity: 0, y: 12 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: duration.slow, ease: easeOutSoft }}
          className="max-w-2xl"
        >
          <p className="font-display text-5xl tracking-tight text-foreground sm:text-6xl md:text-7xl">
            模块栈
          </p>
          <h1 className="mt-6 max-w-xl font-sans text-xl font-medium leading-snug tracking-tight text-foreground/90 sm:text-2xl">
            把选型说清楚：从框架到部署的一整条可落地链路
          </h1>
          <p className="mt-4 max-w-lg text-base leading-relaxed text-muted-foreground sm:text-lg">
            Next.js · TypeScript · Tailwind · shadcn/ui · Motion · Prisma · Vercel
          </p>
          <div className="mt-8 flex flex-wrap gap-3">
            <Button asChild size="lg" className="rounded-xl px-6">
              <Link href="/stack">查看模块</Link>
            </Button>
            <Button asChild variant="secondary" size="lg" className="rounded-xl px-6">
              <Link href="/start">快速开始</Link>
            </Button>
          </div>
        </motion.div>

        <motion.div
          aria-hidden
          initial={reduce ? false : { opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{
            duration: duration.gentlest,
            ease: easeOutSoft,
            delay: reduce ? 0 : 0.12,
          }}
          className="pointer-events-none absolute bottom-0 right-0 top-24 hidden w-[42%] lg:block"
        >
          <div className="absolute inset-y-8 right-0 w-full rounded-l-[2rem] border border-white/70 bg-white/40 shadow-lift backdrop-blur-md">
            <div className="space-y-3 p-8 font-num text-[13px] leading-relaxed text-foreground/70">
              <p className="text-xs tracking-[0.16em] text-teal-ink">STACK MAP</p>
              {[
                "framework → Next.js App Router",
                "language  → TypeScript",
                "style     → Tailwind CSS",
                "ui        → shadcn/ui",
                "motion    → Framer Motion",
                "data      → Prisma + SQLite",
                "ship      → Vercel",
              ].map((line) => (
                <p key={line} className="border-b border-border/60 pb-2 last:border-0">
                  {line}
                </p>
              ))}
            </div>
          </div>
        </motion.div>
      </div>
    </section>
  );
}
