"use client";

import { motion, useReducedMotion } from "framer-motion";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { duration, easeOutSoft } from "@/lib/motion";

type Partner = {
  displayName: string;
  avatarUrl?: string | null;
};

export function LgHero({
  partnerA,
  partnerB,
  distanceKm,
}: {
  partnerA: Partner;
  partnerB: Partner;
  distanceKm?: string | null;
}) {
  const reduce = useReducedMotion();

  return (
    <section className="relative overflow-hidden bg-[linear-gradient(180deg,#d9d9de_0%,#ececf0_55%,#f3f3f5_100%)] pb-10 pt-3">
      <div className="relative z-[1] mx-auto flex max-w-lg flex-col items-center px-4">
        {distanceKm ? (
          <div className="mb-5 inline-flex items-center gap-1.5 rounded-full bg-white/45 px-3 py-1 text-xs text-foreground/70 backdrop-blur-md">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden>
              <path
                d="M4 12h12l-3-3M16 12l-3 3"
                stroke="currentColor"
                strokeWidth="1.7"
                strokeLinecap="round"
                strokeLinejoin="round"
              />
            </svg>
            相距 {distanceKm}
          </div>
        ) : null}

        <div className="flex items-end gap-5 sm:gap-8">
          <PartnerBlock partner={partnerA} />
          <motion.div
            aria-hidden
            className="relative mb-10 flex h-12 w-12 items-center justify-center"
            animate={reduce ? undefined : { scale: [1, 1.08, 1] }}
            transition={{ duration: 2.2, repeat: Infinity, ease: "easeInOut" }}
          >
            <span className="absolute inset-[-10px] rounded-full bg-[radial-gradient(circle,rgba(125,211,252,0.55)_0%,transparent_70%)]" />
            <svg viewBox="0 0 24 24" className="relative h-7 w-7 text-[#ff4d6d]" fill="currentColor">
              <path d="M12 21s-6.7-4.35-9.33-8.1C.8 9.9 2.1 6.2 5.2 5.3c1.7-.5 3.5.1 4.6 1.4L12 9l2.2-2.3c1.1-1.3 2.9-1.9 4.6-1.4 3.1.9 4.4 4.6 2.53 7.6C18.7 16.65 12 21 12 21z" />
            </svg>
          </motion.div>
          <PartnerBlock partner={partnerB} />
        </div>
      </div>

      <div className="pointer-events-none absolute inset-x-0 bottom-0 leading-[0]">
        <svg viewBox="0 0 1440 90" className="h-[70px] w-full" preserveAspectRatio="none">
          <path
            fill="#f5f5f5"
            d="M0,48 C240,90 480,10 720,48 C960,86 1200,20 1440,52 L1440,90 L0,90 Z"
          />
        </svg>
      </div>
    </section>
  );
}

function PartnerBlock({ partner }: { partner: Partner }) {
  const reduce = useReducedMotion();
  return (
    <motion.div
      whileHover={reduce ? undefined : { y: -2 }}
      transition={{ duration: duration.base, ease: easeOutSoft }}
      className="flex flex-col items-center gap-3"
    >
      <div className="rounded-full bg-gradient-to-b from-white to-[#f0e6d8] p-[3px] shadow-soft ring-1 ring-white/80">
        <Avatar className="h-[88px] w-[88px] border-[3px] border-white sm:h-24 sm:w-24">
          {partner.avatarUrl ? (
            <AvatarImage src={partner.avatarUrl} alt={partner.displayName} />
          ) : null}
          <AvatarFallback className="bg-[#f3e7ea] text-xl text-[#b4536a]">
            {partner.displayName.slice(0, 1)}
          </AvatarFallback>
        </Avatar>
      </div>
      <span className="rounded-full bg-[#3f3f46] px-4 py-1.5 font-script text-base text-white shadow-xs">
        {partner.displayName}
      </span>
    </motion.div>
  );
}
