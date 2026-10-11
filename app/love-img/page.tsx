import { AlbumGrid } from "@/components/AlbumGrid";
import { PageTransition } from "@/components/PageTransition";
import { SiteHero } from "@/components/SiteHero";
import { WritingBar } from "@/components/WritingBar";
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
      <SiteHero site={site} />
      <PageTransition>
        <div className="mx-auto max-w-5xl space-y-6 px-4 pb-10 sm:px-6">
          <WritingBar>{site.deci3}</WritingBar>
          <p className="text-center text-sm text-muted-foreground">
            已收录 {photos.length} 张照片。
            {canManage ? "登录后可上传与删除。" : "公开浏览，登录后可管理。"}
          </p>
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
