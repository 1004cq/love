"use client";

import { ReactNode } from "react";
import { motion, useReducedMotion } from "framer-motion";
import { SoftCard } from "@/components/SoftCard";
import { cn } from "@/lib/utils";
import { duration, easeOutSoft } from "@/lib/motion";

type TimelineItemProps = {
  title: string;
  meta?: string;
  children?: ReactNode;
  open?: boolean;
  onToggle?: () => void;
  actions?: ReactNode;
  className?: string;
};

/**
 * TimelineItem — left-rail node + soft expandable card
 * enter from left + fade; hover shifts slightly right with border highlight
 */
export function TimelineItem({
  title,
  meta,
  children,
  open = false,
  onToggle,
  actions,
  className,
}: TimelineItemProps) {
  const reduce = useReducedMotion();

  return (
    <motion.article
      initial={reduce ? false : { opacity: 0, x: -16 }}
      whileInView={{ opacity: 1, x: 0 }}
      viewport={{ once: true, margin: "-40px" }}
      transition={{ duration: reduce ? 0 : duration.slow, ease: easeOutSoft }}
      className={cn("relative pl-8 sm:pl-10", className)}
    >
      <span className="absolute left-0 top-6 flex h-6 w-6 items-center justify-center rounded-full border border-blush/30 bg-card shadow-xs sm:left-1">
        <span className="h-2 w-2 rounded-full bg-blush" />
      </span>

      <SoftCard
        animateOnView={false}
        hoverLift={false}
        padding="none"
        className="overflow-hidden border-border/50 transition-colors duration-soft ease-soft hover:border-blush/35"
      >
        <button
          type="button"
          onClick={onToggle}
          className={cn(
            "flex w-full items-start justify-between gap-3 px-5 py-5 text-left sm:px-6",
            "transition-transform duration-soft ease-soft hover:translate-x-1"
          )}
        >
          <div>
            <h3 className="font-display text-lg tracking-tight text-foreground">
              {title}
            </h3>
            {meta && (
              <p className="mt-1 text-xs text-muted-foreground">{meta}</p>
            )}
          </div>
          <span className="shrink-0 text-xs text-muted-foreground">
            {open ? "收起" : "展开"}
          </span>
        </button>

        <motion.div
          initial={false}
          animate={
            open
              ? { height: "auto", opacity: 1 }
              : { height: 0, opacity: 0 }
          }
          transition={{
            duration: reduce ? 0 : duration.enter,
            ease: easeOutSoft,
          }}
          className="overflow-hidden"
        >
          <div className="space-y-3 border-t border-border/50 px-5 py-5 sm:px-6">
            {children}
            {actions}
          </div>
        </motion.div>
      </SoftCard>
    </motion.article>
  );
}
