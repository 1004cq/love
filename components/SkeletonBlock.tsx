import { Skeleton } from "@/components/ui/skeleton";

export function SkeletonBlock({ className = "" }: { className?: string }) {
  return <Skeleton className={className} />;
}

export function PageSkeleton() {
  return (
    <div className="space-y-5">
      <Skeleton className="h-8 w-40" />
      <Skeleton className="h-4 w-64" />
      <div className="grid grid-cols-2 gap-4 sm:grid-cols-3">
        <Skeleton className="aspect-square" />
        <Skeleton className="aspect-square" />
        <Skeleton className="aspect-square" />
      </div>
    </div>
  );
}
