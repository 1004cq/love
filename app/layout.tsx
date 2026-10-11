import type { Metadata } from "next";
import { Inter, Niconne, Noto_Sans_SC, Noto_Serif_SC } from "next/font/google";
import { BottomNav } from "@/components/BottomNav";
import { Footer } from "@/components/Footer";
import { Header } from "@/components/Header";
import { MotionProvider } from "@/components/MotionProvider";
import { MusicPlayerHost } from "@/components/MusicPlayerHost";
import "@/styles/globals.css";

const notoSerif = Noto_Serif_SC({
  subsets: ["latin"],
  weight: ["400", "600", "700"],
  variable: "--font-noto-serif",
  display: "swap",
});

const notoSans = Noto_Sans_SC({
  subsets: ["latin"],
  weight: ["300", "400", "500", "600", "700"],
  variable: "--font-noto-sans",
  display: "swap",
});

const niconne = Niconne({
  subsets: ["latin"],
  weight: ["400"],
  variable: "--font-niconne",
  display: "swap",
});

const inter = Inter({
  subsets: ["latin"],
  variable: "--font-inter",
  display: "swap",
});

export const metadata: Metadata = {
  title: "Ours · 我们的小世界",
  description:
    "朝暮与年岁并往，与你行至天光。共享相册、点滴、清单、纪念日与留言。",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="zh-CN">
      <body
        className={`${notoSerif.variable} ${notoSans.variable} ${niconne.variable} ${inter.variable} font-sans antialiased`}
      >
        <MotionProvider>
          <div className="flex min-h-screen flex-col">
            <Header />
            <main className="w-full flex-1 pb-28 md:pb-16">{children}</main>
            <Footer />
            <MusicPlayerHost />
            <BottomNav />
          </div>
        </MotionProvider>
      </body>
    </html>
  );
}
