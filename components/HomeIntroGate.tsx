"use client";

import { ReactNode, useCallback, useEffect, useState } from "react";
import { LoveHeartIntro } from "@/components/LoveHeartIntro";

const STORAGE_KEY = "ours-intro-seen";

/**
 * Shows LoveHeartIntro once per browser session on the home page.
 * Usage:
 *   <HomeIntroGate>
 *     ...page content...
 *   </HomeIntroGate>
 */
export function HomeIntroGate({ children }: { children: ReactNode }) {
  const [showIntro, setShowIntro] = useState(false);

  useEffect(() => {
    try {
      const seen = window.sessionStorage.getItem(STORAGE_KEY);
      setShowIntro(!seen);
    } catch {
      setShowIntro(true);
    }
  }, []);

  const onFinish = useCallback(() => {
    try {
      window.sessionStorage.setItem(STORAGE_KEY, "1");
    } catch {
      // ignore
    }
    setShowIntro(false);
  }, []);

  return (
    <>
      {showIntro && <LoveHeartIntro onFinish={onFinish} />}
      {children}
    </>
  );
}
