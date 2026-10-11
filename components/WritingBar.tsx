import { cn } from "@/lib/utils";

/** PHP `.nav_url` slogan / page intro bar */
export function WritingBar({
  children,
  className,
}: {
  children: React.ReactNode;
  className?: string;
}) {
  return (
    <div
      className={cn(
        "flex w-full items-center justify-center rounded-[0.7rem] bg-gradient-to-br from-[#ff6b8a] to-[#7c6cff] px-4 py-5 text-center font-display text-base tracking-wide text-white shadow-[0_18px_50px_rgba(68,54,91,0.14)] sm:text-lg",
        className
      )}
    >
      <span className="mr-2 opacity-90" aria-hidden>
        ✦
      </span>
      {children}
    </div>
  );
}
