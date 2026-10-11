"use client";

import { useEffect, useState } from "react";

function calc(startAt: string) {
  const diff = Math.max(0, Date.now() - new Date(startAt).getTime());
  return {
    days: Math.floor(diff / 86400000),
    hours: Math.floor((diff / 3600000) % 24),
    minutes: Math.floor((diff / 60000) % 60),
    seconds: Math.floor((diff / 1000) % 60),
  };
}

/** PHP index.php Love Day `.ios_page .time` — 天/时/分/秒 */
export function DayFusionCard({
  startAt,
  title = "这是我们一起走过的",
}: {
  startAt: string;
  title?: string;
}) {
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

  return (
    <div className="relative overflow-hidden rounded-2xl border border-[rgba(62,52,78,0.1)] bg-white px-4 pb-6 pt-10 shadow-[0_0_15px_#ebedf0f7] sm:px-8">
      <span
        aria-hidden
        className="absolute left-6 top-5 h-3 w-3 rounded-full bg-[#fd6458] shadow-[20px_0_0_#ffbf2b,40px_0_0_#24cc3d]"
      />
      <div className="pt-4 text-center font-display tracking-wide text-[#777]">
        <p className="lg-rainbow-title text-xl font-bold sm:text-2xl">{title}</p>
        <p className="mt-4 text-base sm:text-lg">
          <b className="mx-1 font-num text-4xl font-bold text-[#524b4b] drop-shadow-sm sm:text-5xl">
            {parts.days}
          </b>
          天
          <b className="mx-1 font-num text-4xl font-bold text-[#524b4b] drop-shadow-sm sm:text-5xl">
            {parts.hours}
          </b>
          时
          <b className="mx-1 font-num text-4xl font-bold text-[#524b4b] drop-shadow-sm sm:text-5xl">
            {parts.minutes}
          </b>
          分
          <b className="mx-1 font-num text-4xl font-bold text-[#524b4b] drop-shadow-sm sm:text-5xl">
            {parts.seconds}
          </b>
          秒
        </p>
      </div>
    </div>
  );
}
