"use client";

import Link from "next/link";
import { motion, useReducedMotion } from "framer-motion";
import type { TechModule } from "@/lib/tech-modules";
import { duration, easeOutSoft, hoverLift, tapPress } from "@/lib/motion";
import { cn } from "@/lib/utils";

type Props = {
  module: TechModule;
  index?: number;
  className?: string;
};

export function StackModuleCard({ module, index = 0, className }: Props) {
  const reduce = useReducedMotion();

  return (
    <motion.div
      initial={reduce ? false : { opacity: 0, y: 8 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true, margin: "-40px" }}
      transition={{
        duration: duration.enter,
        ease: easeOutSoft,
        delay: reduce ? 0 : Math.min(index * 0.04, 0.2),
      }}
      whileHover={reduce ? undefined : hoverLift}
      whileTap={reduce ? undefined : tapPress}
      className={cn("h-full", className)}
    >
      <Link
        href={`/stack/${module.slug}`}
        className="group flex h-full flex-col rounded-2xl border border-border/70 bg-card/90 px-5 py-5 shadow-xs transition-[border-color,box-shadow] duration-soft ease-soft hover:border-teal-ink/30 hover:shadow-soft"
      >
        <div className="flex items-baseline justify-between gap-3">
          <p className="text-[11px] font-medium tracking-[0.14em] text-teal-ink">
            {module.category}
          </p>
          <span className="font-num text-xs text-muted-foreground tabular-nums">
            {String(index + 1).padStart(2, "0")}
          </span>
        </div>
        <h3 className="mt-3 font-display text-xl tracking-tight text-foreground">
          {module.name}
        </h3>
        <p className="mt-2 text-sm font-medium text-foreground/80">{module.tagline}</p>
        <p className="mt-3 flex-1 text-sm leading-relaxed text-muted-foreground">
          {module.summary}
        </p>
        <p className="mt-4 text-sm text-teal-ink opacity-80 transition-opacity group-hover:opacity-100">
          模块说明 →
        </p>
      </Link>
    </motion.div>
  );
}
