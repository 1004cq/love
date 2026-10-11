"use client";

import { motion, useReducedMotion } from "framer-motion";
import { ReactNode } from "react";
import { duration, easeOutSoft } from "@/lib/motion";

export function EmptyState({
  icon,
  title,
  description,
}: {
  icon: ReactNode;
  title: string;
  description: string;
}) {
  const reduce = useReducedMotion();

  return (
    <motion.div
      initial={reduce ? false : { opacity: 0, y: 8 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: duration.enter, ease: easeOutSoft }}
      className="flex flex-col items-center justify-center px-6 py-20 text-center"
    >
      <div
        className={`mb-5 text-muted-foreground ${reduce ? "" : "animate-float"}`}
        aria-hidden
      >
        {icon}
      </div>
      <h3 className="font-display text-xl tracking-tight text-foreground">
        {title}
      </h3>
      <p className="mt-2 max-w-sm text-sm leading-relaxed text-muted-foreground">
        {description}
      </p>
    </motion.div>
  );
}
