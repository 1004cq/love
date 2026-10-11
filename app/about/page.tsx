import { LgHero } from "@/components/LgHero";
import { PageTransition } from "@/components/PageTransition";
import { SoftCard } from "@/components/SoftCard";
import { prisma } from "@/lib/prisma";

export const dynamic = "force-dynamic";

export default async function AboutPage() {
  const partners = await prisma.user.findMany({
    orderBy: { createdAt: "asc" },
    take: 2,
    select: { displayName: true, avatarUrl: true },
  });

  return (
    <div>
      <LgHero
        partnerA={{
          displayName: partners[0]?.displayName || "Alice",
          avatarUrl: partners[0]?.avatarUrl,
        }}
        partnerB={{
          displayName: partners[1]?.displayName || "Bob",
          avatarUrl: partners[1]?.avatarUrl,
        }}
      />
      <PageTransition>
        <div className="mx-auto max-w-2xl px-4 pb-10 sm:px-6">
          <div className="mb-6 text-center">
            <p className="font-script text-lg text-muted-foreground">* About Us *</p>
            <h1 className="mt-2 font-display text-3xl tracking-tight">关于</h1>
          </div>
          <SoftCard padding="lg" className="space-y-4 text-sm leading-relaxed text-muted-foreground">
            <p>
              这是我们的小世界。相册、点滴、清单、轨迹与留言，都收在这里。
            </p>
            <p>
              朝暮与年岁并往，与你行至天光。公开可浏览；管理入口隐藏在
              <span className="text-foreground"> /admin </span>。
            </p>
          </SoftCard>
        </div>
      </PageTransition>
    </div>
  );
}
