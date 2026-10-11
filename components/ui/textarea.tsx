import * as React from "react";
import { cn } from "@/lib/utils";

export interface TextareaProps
  extends React.TextareaHTMLAttributes<HTMLTextAreaElement> {}

const Textarea = React.forwardRef<HTMLTextAreaElement, TextareaProps>(
  ({ className, ...props }, ref) => {
    return (
      <textarea
        className={cn(
          "flex min-h-[96px] w-full rounded-xl border border-input bg-card/80 px-3.5 py-2.5 text-sm text-foreground shadow-xs",
          "placeholder:text-muted-foreground",
          "outline-none transition-all duration-soft ease-soft",
          "hover:border-border",
          "focus-visible:border-blush/50 focus-visible:shadow-glow",
          "disabled:cursor-not-allowed disabled:opacity-50",
          className
        )}
        ref={ref}
        {...props}
      />
    );
  }
);
Textarea.displayName = "Textarea";

export { Textarea };
