"use client";

import Link from "next/link";
import { motion, useReducedMotion } from "framer-motion";
import { Camera, HeartHandshake, MessageCircleHeart, NotebookPen } from "lucide-react";
import { duration, easeOutSoft } from "@/lib/motion";

const tones = [
  "from-[#78caff] to-[#ffed7c]",
  "from-[#ff56aa] to-[#ff8181]",
  "from-[#599fff] to-[#78bdff]",
  "from-[#00c6d4] to-[#f0abff]",
] as const;

const icons = [NotebookPen, MessageCircleHeart, Camera, HeartHandshake];

export function ModuleNavCards({
  cards,
}: {
  cards: { href: string; title: string; desc: string }[];
}) {
  const reduce = useReducedMotion();

  return (
    <div className="mx-auto grid max-w-5xl gap-3 px-4 py-5 sm:grid-cols-2 sm:px-6 lg:grid-cols-4">
      {cards.map((card, index) => {
        const Icon = icons[index % icons.length];
        return (
          <motion.div
            key={card.href}
            whileHover={reduce ? undefined : { y: -3 }}
            transition={{ duration: duration.fast, ease: easeOutSoft }}
          >
            <Link
              href={card.href}
              className={`block overflow-hidden rounded-2xl bg-gradient-to-br ${tones[index % tones.length]} p-5 text-white shadow-[0_18px_50px_rgba(68,54,91,0.14)] transition-transform`}
            >
              <div className="flex flex-col items-center text-center">
                <Icon className="mb-3 h-12 w-12 opacity-95" strokeWidth={1.5} />
                <h2 className="font-display text-xl font-bold tracking-wide">
                  {card.title}
                </h2>
                <p className="mt-2 line-clamp-1 w-full font-display text-sm tracking-[0.18em] text-white/90">
                  {card.desc}
                </p>
              </div>
            </Link>
          </motion.div>
        );
      })}
    </div>
  );
}
