"use client";

import { motion, useReducedMotion } from "framer-motion";
import { ReactNode } from "react";
import { pageTransition, pageVariants } from "@/lib/motion";

export function PageTransition({ children }: { children: ReactNode }) {
  const reduce = useReducedMotion();

  return (
    <motion.div
      variants={pageVariants}
      initial={reduce ? false : "initial"}
      animate="animate"
      transition={reduce ? { duration: 0 } : pageTransition}
    >
      {children}
    </motion.div>
  );
}
