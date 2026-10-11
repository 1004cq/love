"use client";

import { ReactNode } from "react";
import { motion, useReducedMotion } from "framer-motion";
import { Avatar, AvatarFallback } from "@/components/ui/avatar";
import { cn } from "@/lib/utils";
import { duration, easeOutSoft } from "@/lib/motion";

type MessageBubbleProps = {
  content: string;
  authorName: string;
  meta?: string;
  mine?: boolean;
  actions?: ReactNode;
  className?: string;
};

/**
 * MessageBubble — left/right enter for partner vs self
 */
export function MessageBubble({
  content,
  authorName,
  meta,
  mine = false,
  actions,
  className,
}: MessageBubbleProps) {
  const reduce = useReducedMotion();
  const initials = authorName.slice(0, 1).toUpperCase();

  return (
    <motion.div
      initial={
        reduce
          ? false
          : { opacity: 0, x: mine ? 24 : -24, y: 12 }
      }
      animate={{ opacity: 1, x: 0, y: 0 }}
      exit={
        reduce ? undefined : { opacity: 0, y: 8, transition: { duration: 0.25 } }
      }
      transition={{ duration: duration.enter, ease: easeOutSoft }}
      className={cn(
        "flex items-end gap-2.5",
        mine ? "flex-row-reverse" : "flex-row",
        className
      )}
    >
      <Avatar className="h-8 w-8">
        <AvatarFallback className="text-xs">{initials}</AvatarFallback>
      </Avatar>

      <div
        className={cn(
          "max-w-[82%] rounded-2xl px-4 py-3 shadow-soft transition-all duration-soft ease-soft",
          mine
            ? "rounded-br-md bg-primary text-primary-foreground"
            : "rounded-bl-md border border-border/70 bg-secondary/60 text-foreground"
        )}
      >
        <div
          className={cn(
            "mb-1 flex items-center justify-between gap-3 text-[11px]",
            mine ? "text-primary-foreground/75" : "text-muted-foreground"
          )}
        >
          <span>{authorName}</span>
          {meta && <span>{meta}</span>}
        </div>
        <p className="whitespace-pre-wrap text-sm leading-relaxed">{content}</p>
        {actions && <div className="mt-2">{actions}</div>}
      </div>
    </motion.div>
  );
}
