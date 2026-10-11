import type { Metadata } from "next";
import { Fraunces, Noto_Sans_SC, JetBrains_Mono } from "next/font/google";
import { BottomNav } from "@/components/BottomNav";
import { Footer } from "@/components/Footer";
import { Header } from "@/components/Header";
import { MotionProvider } from "@/components/MotionProvider";
import "@/styles/globals.css";

const display = Fraunces({
  subsets: ["latin"],
  variable: "--font-display",
  display: "swap",
});

const notoSans = Noto_Sans_SC({
  subsets: ["latin"],
  weight: ["300", "400", "500", "600", "700"],
  variable: "--font-noto-sans",
  display: "swap",
});

const mono = JetBrains_Mono({
  subsets: ["latin"],
  variable: "--font-mono",
  display: "swap",
});

export const metadata: Metadata = {
  title: "模块栈 · 技术说明框架",
  description:
    "Next.js 14+ (App Router) 前后端一体 · TypeScript · Tailwind CSS · shadcn/ui · Framer Motion · Prisma + SQLite · Vercel 一键上线",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="zh-CN">
      <body
        className={`${display.variable} ${notoSans.variable} ${mono.variable} font-sans antialiased`}
      >
        <MotionProvider>
          <div className="flex min-h-screen flex-col">
            <Header />
            <main className="w-full flex-1 pb-28 md:pb-10">{children}</main>
            <Footer />
            <BottomNav />
          </div>
        </MotionProvider>
      </body>
    </html>
  );
}
