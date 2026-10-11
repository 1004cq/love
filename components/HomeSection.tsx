import Link from "next/link";
import { ReactNode } from "react";
import { SoftCard } from "@/components/SoftCard";
import { cn } from "@/lib/utils";

type HomeSectionProps = {
  eyebrow?: string;
  title: string;
  description?: string;
  href?: string;
  linkLabel?: string;
  children: ReactNode;
  delay?: number;
  className?: string;
};

export function HomeSection({
  eyebrow,
  title,
  description,
  href,
  linkLabel = "查看全部",
  children,
  delay = 0,
  className,
}: HomeSectionProps) {
  return (
    <SoftCard delay={delay} padding="none" className={cn("overflow-hidden", className)}>
      <div className="flex flex-col gap-3 border-b border-border/60 px-5 py-5 sm:flex-row sm:items-end sm:justify-between sm:px-6">
        <div>
          {eyebrow && (
            <p className="text-[11px] font-medium uppercase tracking-[0.18em] text-blush">
              {eyebrow}
            </p>
          )}
          <h2 className="mt-1 font-display text-xl tracking-tight text-foreground sm:text-2xl">
            {title}
          </h2>
          {description && (
            <p className="mt-1.5 max-w-xl text-sm leading-relaxed text-muted-foreground">
              {description}
            </p>
          )}
        </div>
        {href && (
          <Link
            href={href}
            className="shrink-0 text-sm text-muted-foreground transition-colors duration-soft ease-soft hover:text-blush"
          >
            {linkLabel} →
          </Link>
        )}
      </div>
      <div className="px-5 py-5 sm:px-6 sm:py-6">{children}</div>
    </SoftCard>
  );
}
