import Link from "next/link";
import { SoftCard } from "@/components/SoftCard";

type StatCardProps = {
  href: string;
  label: string;
  english: string;
  value: number;
  unit: string;
  delay?: number;
};

export function StatCard({
  href,
  label,
  english,
  value,
  unit,
  delay = 0,
}: StatCardProps) {
  return (
    <Link href={href} className="block h-full">
      <SoftCard delay={delay} className="h-full" padding="md">
        <p className="text-[11px] font-medium uppercase tracking-[0.16em] text-blush">
          {english}
        </p>
        <h3 className="mt-2 font-display text-lg text-foreground">{label}</h3>
        <p className="mt-4 font-display text-3xl tracking-tight text-foreground">
          {value}
          <span className="ml-1 text-sm font-sans text-muted-foreground">
            {unit}
          </span>
        </p>
      </SoftCard>
    </Link>
  );
}
