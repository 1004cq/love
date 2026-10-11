import * as React from "react";
import { Slot } from "@radix-ui/react-slot";
import { cva, type VariantProps } from "class-variance-authority";
import { cn } from "@/lib/utils";

const buttonVariants = cva(
  [
    "inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-medium",
    "shadow-xs outline-none select-none",
    "transition-all duration-soft ease-soft",
    "hover:-translate-y-0.5 hover:shadow-soft",
    "active:translate-y-0 active:scale-[0.98]",
    "disabled:pointer-events-none disabled:opacity-50",
    "focus-visible:ring-2 focus-visible:ring-ring/40 focus-visible:ring-offset-2 focus-visible:ring-offset-background",
  ].join(" "),
  {
    variants: {
      variant: {
        default:
          "bg-primary text-primary-foreground hover:bg-primary/92",
        secondary:
          "border border-border bg-card text-foreground hover:bg-secondary/80",
        soft:
          "bg-accent text-accent-foreground hover:bg-accent/80 shadow-none",
        outline:
          "border border-border bg-transparent text-foreground hover:bg-secondary/70",
        ghost:
          "shadow-none hover:bg-secondary/80 hover:shadow-none hover:translate-y-0",
        destructive:
          "bg-destructive text-destructive-foreground hover:bg-destructive/92",
        link:
          "shadow-none text-foreground underline-offset-4 hover:underline hover:shadow-none hover:translate-y-0 active:scale-100",
      },
      size: {
        default: "h-10 px-4 py-2",
        sm: "h-9 rounded-xl px-3 text-xs",
        lg: "h-11 rounded-2xl px-6",
        icon: "h-10 w-10",
      },
    },
    defaultVariants: {
      variant: "default",
      size: "default",
    },
  }
);

export interface ButtonProps
  extends React.ButtonHTMLAttributes<HTMLButtonElement>,
    VariantProps<typeof buttonVariants> {
  asChild?: boolean;
}

const Button = React.forwardRef<HTMLButtonElement, ButtonProps>(
  ({ className, variant, size, asChild = false, ...props }, ref) => {
    const Comp = asChild ? Slot : "button";
    return (
      <Comp
        className={cn(buttonVariants({ variant, size, className }))}
        ref={ref}
        {...props}
      />
    );
  }
);
Button.displayName = "Button";

export { Button, buttonVariants };
