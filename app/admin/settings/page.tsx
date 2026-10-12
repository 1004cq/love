import { redirect } from "next/navigation";
import { AdminShell } from "@/components/admin/AdminShell";
import { SiteSettingsForm } from "@/components/admin/SiteSettingsForm";
import { PageTransition } from "@/components/PageTransition";
import { getSession } from "@/lib/auth";
import { LG_BOY_AVATAR, LG_GIRL_AVATAR } from "@/lib/lg-assets";
import { getSiteConfig } from "@/lib/site";

export const dynamic = "force-dynamic";

export const metadata = {
  title: "站点设置 · LG_NewUi",
  description: "修改情侣头像、昵称与站点文案",
};

export default async function AdminSettingsPage() {
  const session = await getSession();
  if (!session) {
    redirect("/admin?next=/admin/settings");
  }

  const site = await getSiteConfig();

  return (
    <PageTransition>
      <AdminShell displayName={session.displayName}>
        <SiteSettingsForm
          initial={{
            boy: site.boy,
            girl: site.girl,
            boyAvatar: site.boyAvatar || LG_BOY_AVATAR,
            girlAvatar: site.girlAvatar || LG_GIRL_AVATAR,
            title: site.title,
            logo: site.logo,
            writing: site.writing,
            loveDayTitle: site.loveDayTitle,
            startAt: site.startTime.toISOString().slice(0, 10),
            card1: site.card1,
            card2: site.card2,
            card3: site.card3,
            card4: site.card4,
            deci1: site.deci1,
            deci2: site.deci2,
            deci3: site.deci3,
            deci4: site.deci4,
            copyright: site.copyright,
            siteDescription: site.siteDescription || "",
          }}
        />
      </AdminShell>
    </PageTransition>
  );
}
