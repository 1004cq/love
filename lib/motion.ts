import type { Transition, Variants } from "framer-motion";

/** Motion tokens — 200–500ms, ease-out only (no bouncy springs) */
export const duration = {
  fast: 0.2,
  base: 0.35,
  enter: 0.4,
  slow: 0.45,
  gentlest: 0.5,
} as const;

export const easeOutSoft = [0.22, 1, 0.36, 1] as const;

export const pageTransition: Transition = {
  duration: duration.enter,
  ease: easeOutSoft,
};

export const pageVariants: Variants = {
  initial: { opacity: 0, y: 8 },
  animate: { opacity: 1, y: 0 },
  exit: { opacity: 0, y: -4 },
};

export const softEnter: Variants = {
  hidden: { opacity: 0, y: 8 },
  show: {
    opacity: 1,
    y: 0,
    transition: { duration: duration.enter, ease: easeOutSoft },
  },
};

export const fadeUp: Variants = {
  hidden: { opacity: 0, y: 8 },
  show: {
    opacity: 1,
    y: 0,
    transition: { duration: duration.enter, ease: easeOutSoft },
  },
};

export const staggerContainer: Variants = {
  hidden: {},
  show: {
    transition: {
      staggerChildren: 0.05,
      delayChildren: 0.03,
    },
  },
};

export const slideFromLeft: Variants = {
  hidden: { opacity: 0, x: -12 },
  show: {
    opacity: 1,
    x: 0,
    transition: { duration: duration.slow, ease: easeOutSoft },
  },
};

export const slideFromBottom: Variants = {
  hidden: { opacity: 0, y: 12 },
  show: {
    opacity: 1,
    y: 0,
    transition: { duration: duration.enter, ease: easeOutSoft },
  },
};

/** Kept for compatibility — ease-out fade, not spring bounce */
export const softSpring: Transition = {
  duration: duration.enter,
  ease: easeOutSoft,
};

export const reducedMotionTransition: Transition = {
  duration: 0.01,
};

export const hoverLift = {
  y: -2,
  transition: { duration: duration.base, ease: easeOutSoft },
};

export const tapPress = {
  scale: 0.98,
  transition: { duration: duration.fast, ease: easeOutSoft },
};
