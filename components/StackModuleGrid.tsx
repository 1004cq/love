"use client";

import { StackModuleCard } from "@/components/StackModuleCard";
import { techModules } from "@/lib/tech-modules";

export function StackModuleGrid() {
  return (
    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      {techModules.map((module, index) => (
        <StackModuleCard key={module.slug} module={module} index={index} />
      ))}
    </div>
  );
}
