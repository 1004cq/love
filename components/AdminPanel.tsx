"use client";

import Image from "next/image";
import Link from "next/link";
import { FormEvent, useState } from "react";
import { useRouter } from "next/navigation";
import { SoftCard } from "@/components/SoftCard";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { LG_BOY_AVATAR, LG_GIRL_AVATAR } from "@/lib/lg-assets";

export function AdminPanel({
  initialStartAt,
  boy,
  girl,
  boyAvatar,
  girlAvatar,
  stats,
}: {
  initialStartAt: string;
  boy: string;
  girl: string;
  boyAvatar?: string | null;
  girlAvatar?: string | null;
  stats: {
    photos: number;
    diaries: number;
    anniversaries: number;
    messages: number;
  };
}) {
  const router = useRouter();
  const [startAt, setStartAt] = useState(initialStartAt.slice(0, 10));
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState("");
  const [error, setError] = useState("");

  async function onSave(e: FormEvent) {
    e.preventDefault();
    setLoading(true);
    setMessage("");
    setError("");
    try {
      const res = await fetch("/api/settings", {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          relationshipStartAt: new Date(`${startAt}T00:00:00`).toISOString(),
        }),
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || "保存失败");
      setMessage("已更新 Love Day 起始日期");
      router.refresh();
    } catch (err) {
      setError(err instanceof Error ? err.message : "保存失败");
    } finally {
      setLoading(false);
    }
  }

  return (
    <SoftCard>
      <h2 className="font-display text-xl tracking-tight text-foreground">
        基本设置
      </h2>
      <p className="mt-1.5 text-sm leading-relaxed text-muted-foreground">
        快速改日期；头像 / 昵称 / 口号请到站点设置。
      </p>

      <div className="mt-5 flex items-center justify-center gap-4">
        <CoupleMini name={boy} avatar={boyAvatar || LG_BOY_AVATAR} />
        <span className="text-blush">♥</span>
        <CoupleMini name={girl} avatar={girlAvatar || LG_GIRL_AVATAR} />
      </div>

      <div className="mt-5 text-center">
        <Button asChild className="rounded-full">
          <Link href="/admin/settings">修改头像与站点文案</Link>
        </Button>
      </div>

      <div className="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
        {[
          ["相册", stats.photos],
          ["点滴", stats.diaries],
          ["日期", stats.anniversaries],
          ["留言", stats.messages],
        ].map(([label, value]) => (
          <div
            key={String(label)}
            className="rounded-xl bg-secondary/80 px-3 py-4 text-center"
          >
            <p className="font-display text-2xl text-foreground">{value}</p>
            <p className="mt-1 text-xs text-muted-foreground">{label}</p>
          </div>
        ))}
      </div>

      <form onSubmit={onSave} className="mt-6 space-y-3">
        <Label htmlFor="relationship-start">Love Day 起始日期</Label>
        <div className="flex flex-col gap-3 sm:flex-row">
          <Input
            id="relationship-start"
            type="date"
            value={startAt}
            onChange={(e) => setStartAt(e.target.value)}
            required
          />
          <Button type="submit" disabled={loading} className="shrink-0">
            {loading ? "保存中…" : "更新开始日期"}
          </Button>
        </div>
      </form>
      {message && <p className="mt-3 text-sm text-sage">{message}</p>}
      {error && <p className="mt-3 text-sm text-destructive">{error}</p>}
    </SoftCard>
  );
}

function CoupleMini({ name, avatar }: { name: string; avatar: string }) {
  return (
    <div className="flex flex-col items-center gap-2">
      <div className="relative h-14 w-14 overflow-hidden rounded-full border-2 border-white shadow-soft">
        <Image src={avatar} alt={name} fill className="object-cover" sizes="56px" />
      </div>
      <p className="max-w-[5.5rem] truncate text-xs text-muted-foreground">{name}</p>
    </div>
  );
}
