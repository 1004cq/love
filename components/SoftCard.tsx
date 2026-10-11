"use client";

import { ReactNode } from "react";
import { motion, useReducedMotion } from "framer-motion";
import { Card } from "@/components/ui/card";
import { cn } from "@/lib/utils";
import { duration, easeOutSoft } from "@/lib/motion";

type SoftCardProps = {
  children: ReactNode;
  className?: string;
  hoverLift?: boolean;
  delay?: number;
  padding?: "none" | "sm" | "md" | "lg";
  /** Disable enter animation when nested inside another motion parent */
  animateOnView?: boolean;
};

const paddingMap = {
  none: "",
  sm: "p-4 sm:p-5",
  md: "p-5 sm:p-6",
  lg: "p-6 sm:p-7",
};

/**
 * SoftCard — breathable card with enter fade+rise
 * enter: opacity 0→1, y 8→0, 0.4s
 * hover: translateY(-2px) + deeper shadow
 */
export function SoftCard({
  className,
  children,
  hoverLift = true,
  delay = 0,
  padding = "md",
  animateOnView = true,
}: SoftCardProps) {
  const reduce = useReducedMotion();

  return (
    <motion.div
      initial={animateOnView && !reduce ? { opacity: 0, y: 8 } : false}
      whileInView={animateOnView ? { opacity: 1, y: 0 } : undefined}
      viewport={animateOnView ? { once: true, margin: "-24px" } : undefined}
      whileHover={
        hoverLift && !reduce
          ? { y: -2, transition: { duration: duration.base, ease: easeOutSoft } }
          : undefined
      }
      transition={{
        duration: reduce ? 0 : duration.enter,
        ease: easeOutSoft,
        delay: reduce ? 0 : delay,
      }}
      className="h-full"
    >
      <Card
        className={cn(
          "h-full",
          hoverLift && "hover:shadow-lift",
          paddingMap[padding],
          className
        )}
      >
        {children}
      </Card>
    </motion.div>
  );
}
