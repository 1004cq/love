"use client";

import Image from "next/image";
import { SoftCard } from "@/components/SoftCard";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { diaryDateParts, moodMeta, weatherMeta } from "@/lib/diary-meta";

export type DiaryCardData = {
  id: string;
  title: string;
  content: string;
  imageUrl: string | null;
  weather?: string | null;
  mood?: string | null;
  createdAt: string;
  author: {
    id: string;
    displayName: string;
    avatarUrl?: string | null;
  };
};

export function DiaryCard({
  entry,
  dayNumber,
  actions,
  expanded,
  onToggle,
}: {
  entry: DiaryCardData;
  dayNumber: number;
  actions?: React.ReactNode;
  expanded?: boolean;
  onToggle?: () => void;
}) {
  const parts = diaryDateParts(entry.createdAt);
  const weather = weatherMeta(entry.weather);
  const mood = moodMeta(entry.mood);

  return (
    <SoftCard padding="none" animateOnView className="overflow-hidden rounded-2xl">
      <button
        type="button"
        onClick={onToggle}
        className="w-full px-4 py-5 text-left sm:px-5"
      >
        <div className="flex items-start justify-between gap-3">
          <div className="flex items-end gap-2">
            <span className="font-num text-5xl font-semibold leading-none tracking-tight text-foreground">
              {parts.day}
            </span>
            <span className="mb-1 text-sm leading-tight text-muted-foreground">
              {parts.monthZh}
              <br />
              {parts.year}
            </span>
          </div>
          <span className="text-xs tracking-[0.14em] text-muted-foreground">
            DAY {dayNumber}
          </span>
        </div>

        <div className="mt-4 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted-foreground">
          <span>{parts.time}</span>
          <span className="inline-flex items-center gap-1">
            <span aria-hidden>{weather.icon}</span>
            {weather.label}
          </span>
          <span className="inline-flex items-center gap-1">
            <span aria-hidden>{mood.icon}</span>
            {mood.label}
          </span>
        </div>

        <h3 className="mt-3 font-display text-lg tracking-tight text-foreground">
          {entry.title}
        </h3>
        <p
          className={`mt-2 text-sm leading-relaxed text-muted-foreground ${
            expanded ? "whitespace-pre-wrap" : "line-clamp-2"
          }`}
        >
          {entry.content}
        </p>

        <div className="mt-4 flex items-center justify-between gap-3">
          <div className="flex items-center gap-2">
            <Avatar className="h-7 w-7">
              {entry.author.avatarUrl ? (
                <AvatarImage
                  src={entry.author.avatarUrl}
                  alt={entry.author.displayName}
                />
              ) : null}
              <AvatarFallback className="text-[10px]">
                {entry.author.displayName.slice(0, 1)}
              </AvatarFallback>
            </Avatar>
            <span className="text-xs text-blush">{entry.author.displayName}</span>
          </div>
          <span className="text-xs text-muted-foreground">
            {expanded ? "收起" : "展开"}
          </span>
        </div>
      </button>

      {expanded && entry.imageUrl && (
        <div className="relative mx-4 mb-4 aspect-[16/10] overflow-hidden rounded-xl sm:mx-5">
          <Image
            src={entry.imageUrl}
            alt={entry.title}
            fill
            className="object-cover"
            sizes="(max-width: 768px) 100vw, 640px"
          />
        </div>
      )}

      {expanded && actions ? (
        <div className="border-t border-border/60 px-4 py-3 sm:px-5">{actions}</div>
      ) : null}
    </SoftCard>
  );
}
