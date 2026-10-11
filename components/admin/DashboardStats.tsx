"use client";

import type { ComponentType } from "react";
import Link from "next/link";
import { motion, useReducedMotion } from "framer-motion";
import {
  BookOpenText,
  Camera,
  Clock,
  ListChecks,
  MessageCircleMore,
  Music2,
  Users,
} from "lucide-react";
import { SoftCard } from "@/components/SoftCard";
import { duration, easeOutSoft } from "@/lib/motion";

export type DashboardStat = {
  key: string;
  label: string;
  value: number;
  hint: string;
  href?: string;
};

const icons: Record<string, ComponentType<{ className?: string }>> = {
  diaries: BookOpenText,
  photos: Camera,
  anniversaries: Clock,
  together: ListChecks,
  messages: MessageCircleMore,
  music: Music2,
  users: Users,
};

export function DashboardStats({ stats }: { stats: DashboardStat[] }) {
  const reduce = useReducedMotion();

  return (
    <div className="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
      {stats.map((stat, index) => {
        const Icon = icons[stat.key] || ListChecks;
        const body = (
          <SoftCard
            padding="md"
            delay={reduce ? 0 : Math.min(index * 0.04, 0.2)}
            className="relative overflow-hidden"
          >
            <div className="flex items-start justify-between gap-3">
              <div>
                <p className="text-xs tracking-[0.12em] text-blush">{stat.label}</p>
                <p className="mt-3 font-num text-3xl font-semibold tracking-tight">
                  {stat.value}
                </p>
                <p className="mt-1.5 text-xs text-muted-foreground">{stat.hint}</p>
              </div>
              <span className="rounded-xl bg-blush-soft p-2.5 text-blush">
                <Icon className="h-4 w-4" />
              </span>
            </div>
          </SoftCard>
        );

        if (!stat.href) return <div key={stat.key}>{body}</div>;
        return (
          <motion.div
            key={stat.key}
            whileHover={reduce ? undefined : { y: -2 }}
            transition={{ duration: duration.base, ease: easeOutSoft }}
          >
            <Link href={stat.href} className="block h-full">
              {body}
            </Link>
          </motion.div>
        );
      })}
    </div>
  );
}
