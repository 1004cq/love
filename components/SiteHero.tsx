import { LgHero } from "@/components/LgHero";
import { ModuleNavCards } from "@/components/ModuleNavCards";
import { LG_BOY_AVATAR, LG_GIRL_AVATAR } from "@/lib/lg-assets";
import type { SiteConfigView } from "@/lib/site";

export function SiteHero({ site }: { site: SiteConfigView }) {
  const cards = [
    { href: "/little", title: site.card1, desc: site.deci1 },
    { href: "/leaving", title: site.card2, desc: site.deci2 },
    { href: "/love-img", title: site.card3, desc: site.deci3 },
    { href: "/list", title: site.card4, desc: site.deci4 },
  ];

  return (
    <>
      <LgHero
        partnerA={{
          displayName: site.boy,
          avatarUrl: site.boyAvatar || LG_BOY_AVATAR,
        }}
        partnerB={{
          displayName: site.girl,
          avatarUrl: site.girlAvatar || LG_GIRL_AVATAR,
        }}
      />
      <ModuleNavCards cards={cards} />
    </>
  );
}
