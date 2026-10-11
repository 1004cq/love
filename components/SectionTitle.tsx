import { cn } from "@/lib/utils";

/** PHP `.ltps` section title chip */
export function SectionTitle({
  children,
  className,
}: {
  children: React.ReactNode;
  className?: string;
}) {
  return (
    <div
      className={cn(
        "inline-flex items-center rounded-lg bg-gradient-to-br from-[#0081ff] to-[#1cbbb4] px-4 py-3 font-display text-base font-bold text-white shadow-soft sm:text-lg",
        className
      )}
    >
      <span className="mr-2.5 inline-block h-5 w-1 rounded-full bg-white" />
      {children}
    </div>
  );
}
