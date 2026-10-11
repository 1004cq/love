import { AlbumGrid } from "@/components/AlbumGrid";
import { LgHero } from "@/components/LgHero";
import { PageTransition } from "@/components/PageTransition";
import { getSession } from "@/lib/auth";
import { getPartners } from "@/lib/partners";
import { prisma } from "@/lib/prisma";

export const dynamic = "force-dynamic";

export default async function AlbumPage() {
  const session = await getSession();
  const canManage = Boolean(session);

  const [partners, photos] = await Promise.all([
    getPartners(),
    prisma.albumPhoto.findMany({
      orderBy: { createdAt: "desc" },
      include: {
        uploadedBy: {
          select: { id: true, displayName: true, username: true },
        },
      },
    }),
  ]);

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
        distanceKm="546.9 km"
      />
      <PageTransition>
        <div className="mx-auto max-w-5xl px-4 pb-10 sm:px-6">
          <div className="mb-8 text-center">
            <p className="font-script text-lg italic text-muted-foreground">
              ＊ Photo Keepsakes ＊
            </p>
            <h1 className="mt-2 font-display text-2xl tracking-tight sm:text-3xl">
              记录下美好瞬间
            </h1>
            <p className="mt-3 text-sm text-muted-foreground">
              已收录 {photos.length} 张照片。
              {canManage ? "登录后可上传与删除。" : "公开浏览，登录后可管理。"}
            </p>
          </div>
          <AlbumGrid
            canManage={canManage}
            initialPhotos={photos.map((photo) => ({
              ...photo,
              createdAt: photo.createdAt.toISOString(),
            }))}
          />
        </div>
      </PageTransition>
    </div>
  );
}
