"use client";

import { motion, useReducedMotion } from "framer-motion";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { SoftCard } from "@/components/SoftCard";
import { duration, easeOutSoft } from "@/lib/motion";

type Partner = {
  displayName: string;
  avatarUrl?: string | null;
};

export function CoupleCard({
  partnerA,
  partnerB,
  subtitle = "Together",
}: {
  partnerA: Partner;
  partnerB: Partner;
  subtitle?: string;
}) {
  const reduce = useReducedMotion();

  return (
    <SoftCard padding="lg" className="overflow-hidden">
      <div className="flex flex-col items-center">
        <p className="text-[11px] font-medium uppercase tracking-[0.18em] text-blush">
          {subtitle}
        </p>
        <div className="mt-6 flex items-center gap-4 sm:gap-6">
          <PartnerAvatar partner={partnerA} reduce={!!reduce} />
          <motion.div
            aria-hidden
            className="relative flex h-10 w-10 items-center justify-center"
            animate={
              reduce
                ? undefined
                : { scale: [1, 1.06, 1] }
            }
            transition={{
              duration: 2.4,
              repeat: Infinity,
              ease: "easeInOut",
            }}
          >
            <span className="absolute inset-0 rounded-full bg-blush-soft" />
            <svg
              viewBox="0 0 24 24"
              className="relative h-5 w-5 text-blush"
              fill="currentColor"
            >
              <path d="M12 21s-6.7-4.35-9.33-8.1C.8 9.9 2.1 6.2 5.2 5.3c1.7-.5 3.5.1 4.6 1.4L12 9l2.2-2.3c1.1-1.3 2.9-1.9 4.6-1.4 3.1.9 4.4 4.6 2.53 7.6C18.7 16.65 12 21 12 21z" />
            </svg>
          </motion.div>
          <PartnerAvatar partner={partnerB} reduce={!!reduce} />
        </div>
        <p className="mt-5 font-display text-lg text-foreground">
          {partnerA.displayName}
          <span className="mx-2 text-blush">·</span>
          {partnerB.displayName}
        </p>
      </div>
    </SoftCard>
  );
}

function PartnerAvatar({
  partner,
  reduce,
}: {
  partner: Partner;
  reduce: boolean;
}) {
  return (
    <motion.div
      whileHover={reduce ? undefined : { y: -2 }}
      transition={{ duration: duration.base, ease: easeOutSoft }}
      className="flex flex-col items-center gap-2"
    >
      <Avatar className="h-16 w-16 border-2 border-blush-soft shadow-soft sm:h-20 sm:w-20">
        {partner.avatarUrl ? (
          <AvatarImage src={partner.avatarUrl} alt={partner.displayName} />
        ) : null}
        <AvatarFallback className="bg-accent text-base text-accent-foreground">
          {partner.displayName.slice(0, 1)}
        </AvatarFallback>
      </Avatar>
      <span className="text-sm text-muted-foreground">{partner.displayName}</span>
    </motion.div>
  );
}
