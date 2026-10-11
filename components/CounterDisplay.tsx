"use client";

import { AnimatePresence, motion, useReducedMotion } from "framer-motion";
import { useEffect, useMemo, useState } from "react";
import { pad2 } from "@/lib/format";
import { cn } from "@/lib/utils";
import { duration, easeOutSoft } from "@/lib/motion";

type Parts = {
  days: number;
  hours: number;
  minutes: number;
  seconds: number;
};

function calcParts(startAt: string): Parts {
  const start = new Date(startAt).getTime();
  const now = Date.now();
  const diff = Math.max(0, now - start);
  const days = Math.floor(diff / (1000 * 60 * 60 * 24));
  const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
  const minutes = Math.floor((diff / (1000 * 60)) % 60);
  const seconds = Math.floor((diff / 1000) % 60);
  return { days, hours, minutes, seconds };
}

function DigitBlock({
  value,
  label,
  pulse,
}: {
  value: string;
  label: string;
  pulse?: boolean;
}) {
  const reduce = useReducedMotion();

  return (
    <div className="flex min-w-[4.75rem] flex-col items-center gap-2.5 sm:min-w-[5.75rem]">
      <motion.div
        animate={
          pulse && !reduce ? { scale: [1, 1.02, 1] } : { scale: 1 }
        }
        transition={
          pulse && !reduce
            ? { duration: 1, repeat: Infinity, ease: "easeInOut" }
            : undefined
        }
        className={cn(
          "relative flex h-[4.25rem] w-full items-center justify-center overflow-hidden",
          "rounded-2xl border border-border/70 bg-card shadow-soft sm:h-20"
        )}
      >
        <AnimatePresence mode="popLayout" initial={false}>
          <motion.span
            key={value}
            initial={reduce ? false : { opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            exit={reduce ? undefined : { opacity: 0, y: -10 }}
            transition={{ duration: duration.base, ease: easeOutSoft }}
            className="absolute font-display text-3xl tracking-tight text-foreground sm:text-4xl"
          >
            {value}
          </motion.span>
        </AnimatePresence>
      </motion.div>
      <span className="text-[11px] uppercase tracking-[0.2em] text-muted-foreground">
        {label}
      </span>
    </div>
  );
}

/**
 * CounterDisplay — love-days realtime counter
 * digit change: fade swap; days block: gentle 1→1.02→1 pulse
 */
export function CounterDisplay({
  startAt,
  className,
}: {
  startAt: string;
  className?: string;
}) {
  const [parts, setParts] = useState<Parts>(() => calcParts(startAt));

  useEffect(() => {
    setParts(calcParts(startAt));
    const timer = window.setInterval(() => setParts(calcParts(startAt)), 1000);
    return () => window.clearInterval(timer);
  }, [startAt]);

  const display = useMemo(
    () => ({
      days: String(parts.days),
      hours: pad2(parts.hours),
      minutes: pad2(parts.minutes),
      seconds: pad2(parts.seconds),
    }),
    [parts]
  );

  return (
    <div
      className={cn(
        "flex flex-wrap items-end justify-center gap-3 sm:gap-4",
        className
      )}
    >
      <DigitBlock value={display.days} label="天" pulse />
      <DigitBlock value={display.hours} label="时" />
      <DigitBlock value={display.minutes} label="分" />
      <DigitBlock value={display.seconds} label="秒" />
    </div>
  );
}
