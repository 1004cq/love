"use client";

import { useEffect, useState } from "react";
import { pad2 } from "@/lib/format";

function calc(startAt: string) {
  const diff = Math.max(0, Date.now() - new Date(startAt).getTime());
  return {
    days: Math.floor(diff / 86400000),
    hours: Math.floor((diff / 3600000) % 24),
    minutes: Math.floor((diff / 60000) % 60),
    seconds: Math.floor((diff / 1000) % 60),
  };
}

export function DayFusionCard({
  startAt,
  title = "这是我们一起走过的",
}: {
  startAt: string;
  title?: string;
}) {
  // Start at zeros on server/first paint to avoid SSR/client second mismatches.
  const [parts, setParts] = useState({
    days: 0,
    hours: 0,
    minutes: 0,
    seconds: 0,
  });

  useEffect(() => {
    setParts(calc(startAt));
    const id = window.setInterval(() => setParts(calc(startAt)), 1000);
    return () => window.clearInterval(id);
  }, [startAt]);

  const startLabel = new Date(startAt).toISOString().slice(0, 16).replace("T", " ");

  return (
    <div className="relative overflow-hidden rounded-[1.6rem] border border-white/80 bg-white p-5 shadow-soft sm:p-6">
      <div className="pointer-events-none absolute -right-6 top-8 font-num text-[7rem] font-semibold leading-none text-black/[0.04]">
        {parts.days}
      </div>
      <div className="mb-5 flex gap-1.5">
        <span className="h-2.5 w-2.5 rounded-full bg-[#ff5f57]" />
        <span className="h-2.5 w-2.5 rounded-full bg-[#febc2e]" />
        <span className="h-2.5 w-2.5 rounded-full bg-[#28c840]" />
      </div>

      <div className="relative grid gap-6 sm:grid-cols-[1.2fr_0.8fr] sm:items-end">
        <div>
          <h2 className="font-display text-[1.55rem] leading-snug tracking-tight text-foreground sm:text-[1.85rem]">
            {title}
          </h2>
          <div className="mt-5 inline-flex items-center gap-2 rounded-full bg-secondary px-3 py-1.5">
            <span className="flex h-6 w-6 items-center justify-center rounded-full bg-blush text-[10px] text-white">
              ♥
            </span>
            <div className="leading-tight">
              <p className="text-[10px] uppercase tracking-[0.14em] text-muted-foreground">
                Together Since
              </p>
              <p className="font-num text-xs text-foreground">{startLabel}</p>
            </div>
          </div>
        </div>

        <div className="text-right">
          <p className="font-num text-5xl font-semibold tracking-tight text-foreground sm:text-6xl">
            {parts.days}
          </p>
          <p className="mt-1 text-xs uppercase tracking-[0.22em] text-muted-foreground">
            Days
          </p>
          <div className="mt-4 flex justify-end gap-3 font-num text-sm">
            <TimeCell value={pad2(parts.hours)} label="Hours" />
            <TimeCell value={pad2(parts.minutes)} label="Minutes" />
            <TimeCell value={pad2(parts.seconds)} label="Seconds" />
          </div>
        </div>
      </div>
    </div>
  );
}

function TimeCell({ value, label }: { value: string; label: string }) {
  return (
    <div className="min-w-[3.2rem] rounded-xl bg-secondary/80 px-2 py-2 text-center">
      <div className="text-base font-semibold text-foreground">{value}</div>
      <div className="text-[10px] uppercase tracking-wide text-muted-foreground">
        {label}
      </div>
    </div>
  );
}
