import { AlbumGrid } from "@/components/AlbumGrid";
import { LgHero } from "@/components/LgHero";
import { PageTransition } from "@/components/PageTransition";
import { getSession } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { getSiteConfig } from "@/lib/site";

export const dynamic = "force-dynamic";

export const metadata = {
  title: "恋爱相册",
  description: "记录下美好瞬间",
};

export default async function LoveImgPage() {
  const session = await getSession();
  const canManage = Boolean(session);
  const site = await getSiteConfig();

  const photos = await prisma.albumPhoto.findMany({
    orderBy: { createdAt: "desc" },
    include: {
      uploadedBy: {
        select: { id: true, displayName: true, username: true },
      },
    },
  });

  return (
    <div>
      <LgHero
        partnerA={{ displayName: site.boy, avatarUrl: site.boyAvatar || undefined }}
        partnerB={{ displayName: site.girl, avatarUrl: site.girlAvatar || undefined }}
        distanceKm="546.9 km"
      />
      <PageTransition>
        <div className="mx-auto max-w-5xl px-4 pb-10 sm:px-6">
          <div className="mb-8 text-center">
            <p className="text-xs tracking-[0.14em] text-blush">{site.card3}</p>
            <h1 className="mt-2 font-display text-2xl tracking-tight sm:text-3xl">
              {site.deci3}
            </h1>
            <p className="mt-3 text-sm text-muted-foreground">
              已收录 {photos.length} 张照片。
              {canManage ? "登录后可上传与删除。" : "公开浏览，登录后可管理。"}
            </p>
          </div>
          <AlbumGrid
            canManage={canManage}
            initialPhotos={photos.map((p) => ({
              ...p,
              createdAt: p.createdAt.toISOString(),
            }))}
          />
        </div>
      </PageTransition>
    </div>
  );
}
