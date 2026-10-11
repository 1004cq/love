"use client";

import Image from "next/image";
import { useEffect, useState } from "react";
import { motion, useReducedMotion } from "framer-motion";
import { LG_CAROUSEL, LG_LOVE_ICON } from "@/lib/lg-assets";
import { duration, easeOutSoft } from "@/lib/motion";

type Partner = {
  displayName: string;
  avatarUrl?: string | null;
};

export function LgHero({
  partnerA,
  partnerB,
  slides = LG_CAROUSEL,
}: {
  partnerA: Partner;
  partnerB: Partner;
  slides?: string[];
}) {
  const reduce = useReducedMotion();
  const images = slides.length ? slides : LG_CAROUSEL;
  const [index, setIndex] = useState(0);

  useEffect(() => {
    if (images.length <= 1 || reduce) return;
    const id = window.setInterval(() => {
      setIndex((prev) => (prev + 1) % images.length);
    }, 4500);
    return () => window.clearInterval(id);
  }, [images.length, reduce]);

  return (
    <section className="relative overflow-hidden">
      <div className="relative h-[280px] w-full sm:h-[360px] md:h-[420px]">
        {images.map((src, i) => (
          <div
            key={src}
            className={`absolute inset-0 transition-opacity duration-700 ${
              i === index ? "opacity-100" : "opacity-0"
            }`}
          >
            <Image
              src={src}
              alt=""
              fill
              priority={i === 0}
              className="object-cover"
              sizes="100vw"
            />
            <div className="absolute inset-0 bg-black/35" />
          </div>
        ))}

        <div className="relative z-[1] flex h-full flex-col items-center justify-center px-4 pb-10 pt-6">
          <div className="flex items-end gap-4 sm:gap-10">
            <PartnerBlock partner={partnerA} />
            <motion.div
              aria-hidden
              className="mb-10 flex h-14 w-14 items-center justify-center sm:mb-12"
              animate={reduce ? undefined : { scale: [1, 1.1, 1] }}
              transition={{ duration: 1.8, repeat: Infinity, ease: "easeInOut" }}
            >
              <Image
                src={LG_LOVE_ICON}
                alt=""
                width={52}
                height={52}
                className="drop-shadow-lg"
              />
            </motion.div>
            <PartnerBlock partner={partnerB} />
          </div>
        </div>

        <div className="pointer-events-none absolute inset-x-0 bottom-0 z-[2] leading-[0]">
          <svg
            className="h-[56px] w-full sm:h-[72px]"
            viewBox="0 24 150 28"
            preserveAspectRatio="none"
          >
            <defs>
              <path
                id="gentle-wave"
                d="M-160 44c30 0 58-18 88-18s58 18 88 18 58-18 88-18 58 18 88 18v44h-352z"
              />
            </defs>
            <g>
              <use href="#gentle-wave" x="48" y="0" fill="rgba(247,247,251,0.7)" />
              <use href="#gentle-wave" x="48" y="3" fill="rgba(247,247,251,0.5)" />
              <use href="#gentle-wave" x="48" y="5" fill="rgba(247,247,251,0.3)" />
              <use href="#gentle-wave" x="48" y="7" fill="#f7f7fb" />
            </g>
          </svg>
        </div>
      </div>

      {images.length > 1 ? (
        <div className="absolute bottom-14 left-1/2 z-[3] flex -translate-x-1/2 gap-2 sm:bottom-16">
          {images.map((_, i) => (
            <button
              key={i}
              type="button"
              aria-label={`轮播 ${i + 1}`}
              onClick={() => setIndex(i)}
              className={`h-2 w-2 rounded-full transition-all ${
                i === index ? "w-5 bg-white" : "bg-white/50"
              }`}
            />
          ))}
        </div>
      ) : null}
    </section>
  );
}

function PartnerBlock({ partner }: { partner: Partner }) {
  const reduce = useReducedMotion();
  return (
    <motion.div
      whileHover={reduce ? undefined : { y: -2 }}
      transition={{ duration: duration.base, ease: easeOutSoft }}
      className="flex flex-col items-center gap-2.5"
    >
      <div className="rounded-full bg-white/25 p-[3px] shadow-lg ring-2 ring-white/70 backdrop-blur-sm">
        <div className="relative h-[78px] w-[78px] overflow-hidden rounded-full border-[3px] border-white sm:h-24 sm:w-24">
          {partner.avatarUrl ? (
            <Image
              src={partner.avatarUrl}
              alt={partner.displayName}
              fill
              className="object-cover"
              sizes="96px"
            />
          ) : (
            <div className="flex h-full w-full items-center justify-center bg-[#f3e7ea] text-2xl text-[#b4536a]">
              {partner.displayName.slice(0, 1)}
            </div>
          )}
        </div>
      </div>
      <span className="rounded-full bg-black/75 px-4 py-1 font-script text-base text-white shadow-md backdrop-blur-sm sm:text-lg">
        {partner.displayName}
      </span>
    </motion.div>
  );
}
