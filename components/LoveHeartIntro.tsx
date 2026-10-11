"use client";

import {
  motion,
  useReducedMotion,
  AnimatePresence,
} from "framer-motion";
import { useCallback, useEffect, useRef, useState } from "react";
import { duration, easeOutSoft } from "@/lib/motion";

type Particle = {
  x: number;
  y: number;
  size: number;
  delay: number;
  phase: number;
};

type LoveHeartIntroProps = {
  onFinish: () => void;
  /** Auto dismiss after this many ms (default 4200) */
  autoFinishMs?: number;
};

/** Thick, widely available faces so canvas sampling stays complete on mobile. */
const SAMPLE_FONT =
  '900 1px Impact, "Arial Black", "Helvetica Neue", Arial, "Noto Sans", sans-serif';

function drawHeart(
  ctx: CanvasRenderingContext2D,
  x: number,
  y: number,
  size: number,
  alpha: number
) {
  ctx.save();
  ctx.translate(x, y);
  ctx.scale(size / 18, size / 18);
  ctx.beginPath();
  ctx.moveTo(0, 4);
  ctx.bezierCurveTo(0, 1.5, -3.5, -1.2, -7, 1.2);
  ctx.bezierCurveTo(-11, 4.2, -10.5, 9.5, 0, 15);
  ctx.bezierCurveTo(10.5, 9.5, 11, 4.2, 7, 1.2);
  ctx.bezierCurveTo(3.5, -1.2, 0, 1.5, 0, 4);
  ctx.closePath();

  ctx.shadowColor = `rgba(232, 155, 168, ${0.5 * alpha})`;
  ctx.shadowBlur = 8;
  ctx.fillStyle = `rgba(224, 140, 156, ${alpha})`;
  ctx.fill();

  ctx.shadowBlur = 0;
  ctx.fillStyle = `rgba(245, 190, 200, ${0.32 * alpha})`;
  ctx.beginPath();
  ctx.ellipse(-2.5, 3, 2.2, 1.5, -0.4, 0, Math.PI * 2);
  ctx.fill();
  ctx.restore();
}

function paintLoveMask(
  ctx: CanvasRenderingContext2D,
  width: number,
  height: number
) {
  const fontSize = Math.min(width * 0.26, height * 0.52, 160);
  const letterGap = fontSize * 0.12;
  const letters = ["L", "O", "V", "E"] as const;

  ctx.clearRect(0, 0, width, height);
  ctx.fillStyle = "#000";
  ctx.textAlign = "center";
  ctx.textBaseline = "middle";
  ctx.lineJoin = "round";
  ctx.lineCap = "round";

  // Measure with target size
  ctx.font = SAMPLE_FONT.replace("1px", `${fontSize}px`);

  const widths = letters.map((ch) => ctx.measureText(ch).width);
  const total =
    widths.reduce((sum, w) => sum + w, 0) + letterGap * (letters.length - 1);
  let cursor = (width - total) / 2;
  const cy = height * 0.5;

  for (let i = 0; i < letters.length; i += 1) {
    const ch = letters[i];
    const w = widths[i];
    const cx = cursor + w / 2;

    // Fill + stroke thickens thin bars (L foot, E arms) for denser sampling
    ctx.lineWidth = Math.max(3, fontSize * 0.045);
    ctx.strokeText(ch, cx, cy);
    ctx.fillText(ch, cx, cy);

    // Tiny dilation so stroke gaps don't drop out on sparse pixels
    const swell = Math.max(0.8, fontSize * 0.012);
    for (const [dx, dy] of [
      [swell, 0],
      [-swell, 0],
      [0, swell],
      [0, -swell],
    ] as const) {
      ctx.fillText(ch, cx + dx, cy + dy);
    }

    cursor += w + letterGap;
  }
}

function sampleLoveParticles(
  width: number,
  height: number,
  dpr: number
): Particle[] {
  const off = document.createElement("canvas");
  off.width = Math.max(1, Math.floor(width * dpr));
  off.height = Math.max(1, Math.floor(height * dpr));
  const ctx = off.getContext("2d", { willReadFrequently: true });
  if (!ctx) return [];

  ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  paintLoveMask(ctx, width, height);

  const { data } = ctx.getImageData(0, 0, off.width, off.height);

  // Step in CSS pixels — keep dense enough for L/E horizontals on mobile
  const stepCss = width < 420 ? 3.2 : 3.8;
  const step = Math.max(2, Math.round(stepCss * dpr));
  const particles: Particle[] = [];

  for (let py = 0; py < off.height; py += step) {
    for (let px = 0; px < off.width; px += step) {
      const alpha = data[(py * off.width + px) * 4 + 3];
      if (alpha < 100) continue;

      const jx = (Math.random() - 0.5) * step * 0.22;
      const jy = (Math.random() - 0.5) * step * 0.22;
      particles.push({
        x: (px + jx) / dpr,
        y: (py + jy) / dpr,
        size: (width < 420 ? 4.6 : 5.2) + Math.random() * 1.8,
        delay: Math.random() * 0.45,
        phase: Math.random() * Math.PI * 2,
      });
    }
  }

  return particles;
}

async function waitForFonts() {
  try {
    if (document.fonts?.ready) {
      await document.fonts.ready;
      // Warm the faces we actually sample with
      await document.fonts.load('900 64px "Arial Black"');
      await document.fonts.load("900 64px Arial");
    }
  } catch {
    // continue with system fallback
  }
}

/**
 * Full-screen light intro: heart lattice spelling LOVE.
 * Call onFinish when skipped or when the sequence completes.
 */
export function LoveHeartIntro({
  onFinish,
  autoFinishMs = 4200,
}: LoveHeartIntroProps) {
  const reduce = useReducedMotion();
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const particlesRef = useRef<Particle[]>([]);
  const rafRef = useRef<number>(0);
  const startRef = useRef<number>(0);
  const [exiting, setExiting] = useState(false);

  const finish = useCallback(() => {
    if (exiting) return;
    setExiting(true);
    window.setTimeout(
      () => onFinish(),
      reduce ? 0 : Math.round(duration.enter * 1000)
    );
  }, [exiting, onFinish, reduce]);

  useEffect(() => {
    if (reduce) {
      const t = window.setTimeout(finish, 600);
      return () => window.clearTimeout(t);
    }
    const t = window.setTimeout(finish, autoFinishMs);
    return () => window.clearTimeout(t);
  }, [autoFinishMs, finish, reduce]);

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;

    const ctx = canvas.getContext("2d");
    if (!ctx) return;

    let cancelled = false;

    const resize = () => {
      const dpr = Math.min(window.devicePixelRatio || 1, 2);
      const w = canvas.clientWidth;
      const h = canvas.clientHeight;
      if (w < 2 || h < 2) return;

      canvas.width = Math.floor(w * dpr);
      canvas.height = Math.floor(h * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      particlesRef.current = sampleLoveParticles(w, h, dpr);
    };

    const startLoop = () => {
      if (cancelled) return;
      resize();
      startRef.current = performance.now();

      const tick = (now: number) => {
        if (cancelled) return;
        const w = canvas.clientWidth;
        const h = canvas.clientHeight;
        const elapsed = (now - startRef.current) / 1000;
        ctx.clearRect(0, 0, w, h);

        for (const p of particlesRef.current) {
          const appear = reduce
            ? 1
            : Math.min(1, Math.max(0, (elapsed - p.delay) / 0.5));
          if (appear <= 0) continue;

          const eased = 1 - Math.pow(1 - appear, 3);
          const pulse = 1 + Math.sin(elapsed * 2.1 + p.phase) * 0.05;
          const driftY = Math.sin(elapsed * 1.3 + p.phase) * 0.6;
          drawHeart(
            ctx,
            p.x,
            p.y + driftY,
            p.size * pulse * (0.88 + eased * 0.18),
            0.2 + eased * 0.8
          );
        }

        rafRef.current = requestAnimationFrame(tick);
      };

      rafRef.current = requestAnimationFrame(tick);
    };

    waitForFonts().then(startLoop);
    window.addEventListener("resize", resize);

    return () => {
      cancelled = true;
      cancelAnimationFrame(rafRef.current);
      window.removeEventListener("resize", resize);
    };
  }, [reduce]);

  return (
    <AnimatePresence>
      {!exiting && (
        <motion.div
          className="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-[#FBFBFA]"
          initial={reduce ? false : { opacity: 0, scale: 1.02 }}
          animate={{ opacity: 1, scale: 1 }}
          exit={{ opacity: 0, scale: 0.985 }}
          transition={{ duration: duration.enter, ease: easeOutSoft }}
          role="dialog"
          aria-label="开场动画"
        >
          <button
            type="button"
            onClick={finish}
            className="absolute right-4 top-4 rounded-xl px-3 py-2 text-[11px] font-medium tracking-[0.14em] text-zinc-400 transition-all duration-soft ease-soft hover:-translate-y-0.5 hover:text-zinc-600 active:scale-[0.98] sm:right-6 sm:top-6"
          >
            SKIP INTRO
          </button>

          <div className="flex w-full max-w-4xl flex-col items-center px-5 sm:px-8">
            <motion.p
              className="mb-5 font-display text-sm italic tracking-[0.08em] sm:mb-7 sm:text-base"
              style={{ color: "rgba(196, 140, 152, 0.85)" }}
              initial={reduce ? false : { opacity: 0, y: 8 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{
                duration: duration.slow,
                ease: easeOutSoft,
                delay: reduce ? 0 : 0.12,
              }}
            >
              let there be
            </motion.p>

            {/* Wider / taller so L foot and E bars are not clipped */}
            <div className="relative h-[32vh] w-full max-h-[300px] min-h-[160px] sm:h-[36vh] sm:max-h-[360px] sm:min-h-[200px]">
              <canvas
                ref={canvasRef}
                className="h-full w-full"
                aria-hidden
              />
            </div>
          </div>
        </motion.div>
      )}
    </AnimatePresence>
  );
}
