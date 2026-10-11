import type { Config } from "tailwindcss";
import animate from "tailwindcss-animate";

const config: Config = {
  darkMode: ["class"],
  content: [
    "./pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./components/**/*.{js,ts,jsx,tsx,mdx}",
    "./app/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      colors: {
        border: "hsl(var(--border))",
        input: "hsl(var(--input))",
        ring: "hsl(var(--ring))",
        background: "hsl(var(--background))",
        foreground: "hsl(var(--foreground))",
        primary: {
          DEFAULT: "hsl(var(--primary))",
          foreground: "hsl(var(--primary-foreground))",
        },
        secondary: {
          DEFAULT: "hsl(var(--secondary))",
          foreground: "hsl(var(--secondary-foreground))",
        },
        destructive: {
          DEFAULT: "hsl(var(--destructive))",
          foreground: "hsl(var(--destructive-foreground))",
        },
        muted: {
          DEFAULT: "hsl(var(--muted))",
          foreground: "hsl(var(--muted-foreground))",
        },
        accent: {
          DEFAULT: "hsl(var(--accent))",
          foreground: "hsl(var(--accent-foreground))",
        },
        popover: {
          DEFAULT: "hsl(var(--popover))",
          foreground: "hsl(var(--popover-foreground))",
        },
        card: {
          DEFAULT: "hsl(var(--card))",
          foreground: "hsl(var(--card-foreground))",
        },
        blush: {
          DEFAULT: "hsl(var(--blush))",
          soft: "hsl(var(--blush-soft))",
        },
        sage: {
          DEFAULT: "hsl(var(--sage))",
          soft: "hsl(var(--sage-soft))",
        },
        // Keep short aliases used by existing pages
        ink: {
          DEFAULT: "hsl(var(--foreground))",
          soft: "hsl(var(--muted-foreground))",
          mute: "hsl(210 8% 58%)",
        },
        mist: {
          DEFAULT: "hsl(var(--muted))",
          deep: "hsl(210 14% 90%)",
          soft: "hsl(var(--background))",
        },
      },
      borderRadius: {
        lg: "var(--radius)",
        md: "calc(var(--radius) - 2px)",
        sm: "calc(var(--radius) - 4px)",
        xl: "var(--radius-lg)",
        "2xl": "var(--radius-xl)",
      },
      fontFamily: {
        display: ["var(--font-noto-serif)", "Noto Serif SC", "Georgia", "serif"],
        sans: ["var(--font-noto-sans)", "Noto Sans SC", "Inter", "ui-sans-serif", "sans-serif"],
        script: ["var(--font-niconne)", "Niconne", "cursive"],
        num: ["var(--font-inter)", "Inter", "ui-sans-serif", "sans-serif"],
      },
      boxShadow: {
        xs: "var(--shadow-xs)",
        soft: "var(--shadow-soft)",
        lift: "var(--shadow-lift)",
        glow: "var(--shadow-glow)",
      },
      transitionTimingFunction: {
        soft: "var(--ease-out-soft)",
      },
      transitionDuration: {
        soft: "300ms",
        gentle: "450ms",
        slow: "500ms",
      },
      keyframes: {
        breathe: {
          "0%, 100%": { opacity: "0.55" },
          "50%": { opacity: "1" },
        },
        float: {
          "0%, 100%": { transform: "translateY(0)" },
          "50%": { transform: "translateY(-6px)" },
        },
        "fade-up": {
          from: { opacity: "0", transform: "translateY(8px)" },
          to: { opacity: "1", transform: "translateY(0)" },
        },
      },
      animation: {
        breathe: "breathe 2.4s ease-in-out infinite",
        float: "float 3.2s ease-in-out infinite",
        "fade-up": "fade-up 0.4s var(--ease-out-soft) both",
      },
    },
  },
  plugins: [animate],
};

export default config;
