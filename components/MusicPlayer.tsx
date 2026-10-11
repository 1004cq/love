"use client";

import Image from "next/image";
import { useEffect, useRef, useState } from "react";
import { AnimatePresence, motion, useReducedMotion } from "framer-motion";
import { cn } from "@/lib/utils";
import { duration, easeOutSoft } from "@/lib/motion";

export type MusicTrack = {
  id: string;
  title: string;
  artist: string | null;
  url: string;
  coverUrl: string | null;
};

export function MusicPlayer({ tracks }: { tracks: MusicTrack[] }) {
  const reduce = useReducedMotion();
  const audioRef = useRef<HTMLAudioElement | null>(null);
  const [index, setIndex] = useState(0);
  const [playing, setPlaying] = useState(false);
  const [expanded, setExpanded] = useState(false);
  const [progress, setProgress] = useState(0);
  const [durationSec, setDurationSec] = useState(0);

  const track = tracks[index] ?? null;

  useEffect(() => {
    const audio = audioRef.current;
    if (!audio || !track) return;
    audio.src = track.url;
    audio.load();
    if (playing) {
      void audio.play().catch(() => setPlaying(false));
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [track?.id]);

  useEffect(() => {
    const audio = audioRef.current;
    if (!audio) return;
    const onTime = () => {
      setProgress(audio.currentTime);
      setDurationSec(audio.duration || 0);
    };
    const onEnded = () => {
      setIndex((prev) => (prev + 1) % Math.max(tracks.length, 1));
      setPlaying(true);
    };
    const onPlay = () => setPlaying(true);
    const onPause = () => setPlaying(false);
    audio.addEventListener("timeupdate", onTime);
    audio.addEventListener("loadedmetadata", onTime);
    audio.addEventListener("ended", onEnded);
    audio.addEventListener("play", onPlay);
    audio.addEventListener("pause", onPause);
    return () => {
      audio.removeEventListener("timeupdate", onTime);
      audio.removeEventListener("loadedmetadata", onTime);
      audio.removeEventListener("ended", onEnded);
      audio.removeEventListener("play", onPlay);
      audio.removeEventListener("pause", onPause);
    };
  }, [tracks.length]);

  if (!track || tracks.length === 0) return null;

  async function togglePlay() {
    const audio = audioRef.current;
    if (!audio) return;
    if (playing) {
      audio.pause();
      setPlaying(false);
      return;
    }
    try {
      await audio.play();
      setPlaying(true);
    } catch {
      setPlaying(false);
    }
  }

  function next() {
    setIndex((prev) => (prev + 1) % tracks.length);
    setPlaying(true);
  }

  function prev() {
    setIndex((prev) => (prev - 1 + tracks.length) % tracks.length);
    setPlaying(true);
  }

  const pct = durationSec ? Math.min(100, (progress / durationSec) * 100) : 0;

  return (
    <>
      <audio ref={audioRef} preload="metadata" />
      <div className="pointer-events-none fixed inset-x-0 bottom-[4.75rem] z-50 flex justify-center px-3 md:bottom-6 md:justify-start md:px-6">
        <div className="pointer-events-auto w-full max-w-sm md:max-w-xs">
          <AnimatePresence>
            {expanded && (
              <motion.div
                initial={reduce ? false : { opacity: 0, y: 12, height: 0 }}
                animate={{ opacity: 1, y: 0, height: "auto" }}
                exit={reduce ? undefined : { opacity: 0, y: 8, height: 0 }}
                transition={{ duration: duration.base, ease: easeOutSoft }}
                className="mb-2 overflow-hidden rounded-2xl border border-white/40 bg-[rgba(92,62,48,0.72)] shadow-lift backdrop-blur-xl"
              >
                <div className="max-h-56 overflow-y-auto px-2 py-2">
                  {tracks.map((item, i) => (
                    <button
                      key={item.id}
                      type="button"
                      onClick={() => {
                        setIndex(i);
                        setPlaying(true);
                      }}
                      className={cn(
                        "flex w-full items-center gap-3 rounded-xl px-2.5 py-2 text-left transition-colors duration-soft",
                        i === index
                          ? "bg-white/20 text-white"
                          : "text-white/75 hover:bg-white/10 hover:text-white"
                      )}
                    >
                      <Cover
                        src={item.coverUrl}
                        title={item.title}
                        className="h-10 w-10"
                      />
                      <span className="min-w-0 flex-1">
                        <span className="block truncate text-sm font-medium">
                          {item.title}
                        </span>
                        <span className="block truncate text-xs opacity-70">
                          {item.artist || "未知艺人"}
                        </span>
                      </span>
                    </button>
                  ))}
                </div>
              </motion.div>
            )}
          </AnimatePresence>

          <motion.div
            initial={reduce ? false : { opacity: 0, y: 14 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: duration.enter, ease: easeOutSoft }}
            className="overflow-hidden rounded-2xl border border-white/35 bg-[rgba(92,62,48,0.68)] text-white shadow-lift backdrop-blur-xl"
          >
            <div className="h-0.5 w-full bg-white/15">
              <div
                className="h-full bg-white/70 transition-[width] duration-soft ease-soft"
                style={{ width: `${pct}%` }}
              />
            </div>
            <div className="flex items-center gap-3 px-3 py-2.5">
              <button
                type="button"
                onClick={() => setExpanded((v) => !v)}
                className="shrink-0"
                aria-label={expanded ? "收起播放列表" : "展开播放列表"}
              >
                <Cover
                  src={track.coverUrl}
                  title={track.title}
                  className="h-11 w-11 ring-1 ring-white/30"
                />
              </button>

              <button
                type="button"
                onClick={() => setExpanded((v) => !v)}
                className="min-w-0 flex-1 text-left"
              >
                <p className="truncate text-sm font-medium">{track.title}</p>
                <p className="truncate text-xs text-white/70">
                  {track.artist || "我们的歌单"}
                  {durationSec > 0 && (
                    <span className="ml-2 tabular-nums opacity-80">
                      {fmt(progress)} / {fmt(durationSec)}
                    </span>
                  )}
                </p>
              </button>

              <div className="flex items-center gap-0.5">
                <IconBtn label="上一首" onClick={prev}>
                  <path
                    d="M15 7L9 12l6 5M8 7v10"
                    stroke="currentColor"
                    strokeWidth="1.6"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  />
                </IconBtn>
                <button
                  type="button"
                  onClick={togglePlay}
                  className="mx-0.5 flex h-9 w-9 items-center justify-center rounded-full bg-white text-[#5c3e30] shadow-xs transition-transform duration-soft hover:scale-[1.04] active:scale-[0.98]"
                  aria-label={playing ? "暂停" : "播放"}
                >
                  {playing ? (
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                      <rect x="6" y="5" width="4" height="14" rx="1" />
                      <rect x="14" y="5" width="4" height="14" rx="1" />
                    </svg>
                  ) : (
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M8 5.5v13l11-6.5-11-6.5z" />
                    </svg>
                  )}
                </button>
                <IconBtn label="下一首" onClick={next}>
                  <path
                    d="M9 7l6 5-6 5M16 7v10"
                    stroke="currentColor"
                    strokeWidth="1.6"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  />
                </IconBtn>
              </div>
            </div>
          </motion.div>
        </div>
      </div>
    </>
  );
}

function Cover({
  src,
  title,
  className,
}: {
  src?: string | null;
  title: string;
  className?: string;
}) {
  return (
    <span
      className={cn(
        "relative inline-flex shrink-0 items-center justify-center overflow-hidden rounded-xl bg-white/20 text-xs font-medium",
        className
      )}
    >
      {src ? (
        <Image src={src} alt={title} fill className="object-cover" sizes="48px" />
      ) : (
        title.slice(0, 1)
      )}
    </span>
  );
}

function IconBtn({
  label,
  onClick,
  children,
}: {
  label: string;
  onClick: () => void;
  children: React.ReactNode;
}) {
  return (
    <button
      type="button"
      onClick={onClick}
      aria-label={label}
      className="flex h-8 w-8 items-center justify-center rounded-full text-white/85 transition-colors hover:bg-white/15 hover:text-white"
    >
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
        {children}
      </svg>
    </button>
  );
}

function fmt(sec: number) {
  if (!Number.isFinite(sec)) return "0:00";
  const m = Math.floor(sec / 60);
  const s = Math.floor(sec % 60);
  return `${m}:${s.toString().padStart(2, "0")}`;
}
