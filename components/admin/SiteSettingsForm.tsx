"use client";

import Image from "next/image";
import { FormEvent, useState } from "react";
import { useRouter } from "next/navigation";
import { SoftCard } from "@/components/SoftCard";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { LG_BOY_AVATAR, LG_GIRL_AVATAR } from "@/lib/lg-assets";

export type SiteFormValues = {
  boy: string;
  girl: string;
  boyAvatar: string;
  girlAvatar: string;
  title: string;
  logo: string;
  writing: string;
  loveDayTitle: string;
  startAt: string;
  card1: string;
  card2: string;
  card3: string;
  card4: string;
  deci1: string;
  deci2: string;
  deci3: string;
  deci4: string;
  copyright: string;
  siteDescription: string;
};

export function SiteSettingsForm({ initial }: { initial: SiteFormValues }) {
  const router = useRouter();
  const [form, setForm] = useState(initial);
  const [loading, setLoading] = useState(false);
  const [uploading, setUploading] = useState<"boy" | "girl" | null>(null);
  const [message, setMessage] = useState("");
  const [error, setError] = useState("");

  function setField<K extends keyof SiteFormValues>(key: K, value: SiteFormValues[K]) {
    setForm((prev) => ({ ...prev, [key]: value }));
  }

  async function onUpload(side: "boy" | "girl", file: File | null) {
    if (!file) return;
    setUploading(side);
    setError("");
    setMessage("");
    try {
      const body = new FormData();
      body.append("side", side);
      body.append("file", file);
      const res = await fetch("/api/settings", { method: "POST", body });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || "上传失败");
      if (side === "boy") setField("boyAvatar", data.url);
      else setField("girlAvatar", data.url);
      setMessage(side === "boy" ? "男主头像已更新" : "女主头像已更新");
      router.refresh();
    } catch (err) {
      setError(err instanceof Error ? err.message : "上传失败");
    } finally {
      setUploading(null);
    }
  }

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
          relationshipStartAt: new Date(`${form.startAt}T00:00:00`).toISOString(),
          boy: form.boy,
          girl: form.girl,
          boyAvatar: form.boyAvatar,
          girlAvatar: form.girlAvatar,
          title: form.title,
          logo: form.logo,
          writing: form.writing,
          loveDayTitle: form.loveDayTitle,
          card1: form.card1,
          card2: form.card2,
          card3: form.card3,
          card4: form.card4,
          deci1: form.deci1,
          deci2: form.deci2,
          deci3: form.deci3,
          deci4: form.deci4,
          copyright: form.copyright,
          siteDescription: form.siteDescription || null,
        }),
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || "保存失败");
      setMessage("站点设置已保存，前台会立即生效");
      router.refresh();
    } catch (err) {
      setError(err instanceof Error ? err.message : "保存失败");
    } finally {
      setLoading(false);
    }
  }

  return (
    <form onSubmit={onSave} className="space-y-6">
      <SoftCard padding="lg" hoverLift={false} className="space-y-5">
        <div>
          <p className="text-xs tracking-[0.14em] text-blush">情侣相关</p>
          <h2 className="mt-1 font-display text-xl tracking-tight">头像与昵称</h2>
          <p className="mt-1.5 text-sm text-muted-foreground">
            对齐 PHP 后台「情侣相关」：可上传图片，或填写图片 URL / QQ 号。
          </p>
        </div>

        <div className="grid gap-6 sm:grid-cols-2">
          <AvatarEditor
            label="男主"
            name={form.boy}
            avatar={form.boyAvatar || LG_BOY_AVATAR}
            avatarInput={form.boyAvatar}
            uploading={uploading === "boy"}
            onNameChange={(v) => setField("boy", v)}
            onAvatarInputChange={(v) => setField("boyAvatar", v)}
            onFile={(file) => onUpload("boy", file)}
          />
          <AvatarEditor
            label="女主"
            name={form.girl}
            avatar={form.girlAvatar || LG_GIRL_AVATAR}
            avatarInput={form.girlAvatar}
            uploading={uploading === "girl"}
            onNameChange={(v) => setField("girl", v)}
            onAvatarInputChange={(v) => setField("girlAvatar", v)}
            onFile={(file) => onUpload("girl", file)}
          />
        </div>
      </SoftCard>

      <SoftCard padding="lg" hoverLift={false} className="space-y-4">
        <div>
          <p className="text-xs tracking-[0.14em] text-blush">基本设置</p>
          <h2 className="mt-1 font-display text-xl tracking-tight">站点文案</h2>
        </div>
        <div className="grid gap-4 sm:grid-cols-2">
          <Field label="站点标题" value={form.title} onChange={(v) => setField("title", v)} />
          <Field label="站点 LOGO" value={form.logo} onChange={(v) => setField("logo", v)} />
        </div>
        <Field
          label="站点文案 / 口号"
          value={form.writing}
          onChange={(v) => setField("writing", v)}
        />
        <Field
          label="Love Day 标题"
          value={form.loveDayTitle}
          onChange={(v) => setField("loveDayTitle", v)}
        />
        <div className="space-y-2">
          <Label htmlFor="startAt">Love Day 起始日期</Label>
          <Input
            id="startAt"
            type="date"
            value={form.startAt}
            onChange={(e) => setField("startAt", e.target.value)}
            required
          />
        </div>
        <div className="space-y-2">
          <Label htmlFor="siteDescription">站点描述</Label>
          <Textarea
            id="siteDescription"
            value={form.siteDescription}
            onChange={(e) => setField("siteDescription", e.target.value)}
            rows={3}
          />
        </div>
        <Field
          label="Copyright"
          value={form.copyright}
          onChange={(v) => setField("copyright", v)}
        />
      </SoftCard>

      <SoftCard padding="lg" hoverLift={false} className="space-y-4">
        <div>
          <p className="text-xs tracking-[0.14em] text-blush">卡片设置</p>
          <h2 className="mt-1 font-display text-xl tracking-tight">四入口卡文案</h2>
        </div>
        <div className="grid gap-4 sm:grid-cols-2">
          <Field label="卡片1 标题" value={form.card1} onChange={(v) => setField("card1", v)} />
          <Field label="卡片1 描述" value={form.deci1} onChange={(v) => setField("deci1", v)} />
          <Field label="卡片2 标题" value={form.card2} onChange={(v) => setField("card2", v)} />
          <Field label="卡片2 描述" value={form.deci2} onChange={(v) => setField("deci2", v)} />
          <Field label="卡片3 标题" value={form.card3} onChange={(v) => setField("card3", v)} />
          <Field label="卡片3 描述" value={form.deci3} onChange={(v) => setField("deci3", v)} />
          <Field label="卡片4 标题" value={form.card4} onChange={(v) => setField("card4", v)} />
          <Field label="卡片4 描述" value={form.deci4} onChange={(v) => setField("deci4", v)} />
        </div>
      </SoftCard>

      <div className="flex flex-wrap items-center gap-3">
        <Button type="submit" disabled={loading} className="rounded-full px-6">
          {loading ? "保存中…" : "保存全部设置"}
        </Button>
        {message ? <p className="text-sm text-sage">{message}</p> : null}
        {error ? <p className="text-sm text-destructive">{error}</p> : null}
      </div>
    </form>
  );
}

function Field({
  label,
  value,
  onChange,
}: {
  label: string;
  value: string;
  onChange: (v: string) => void;
}) {
  return (
    <div className="space-y-2">
      <Label>{label}</Label>
      <Input value={value} onChange={(e) => onChange(e.target.value)} required />
    </div>
  );
}

function AvatarEditor({
  label,
  name,
  avatar,
  avatarInput,
  uploading,
  onNameChange,
  onAvatarInputChange,
  onFile,
}: {
  label: string;
  name: string;
  avatar: string;
  avatarInput: string;
  uploading: boolean;
  onNameChange: (v: string) => void;
  onAvatarInputChange: (v: string) => void;
  onFile: (file: File | null) => void;
}) {
  return (
    <div className="rounded-2xl border border-border/70 bg-secondary/40 p-4">
      <div className="flex items-center gap-4">
        <div className="relative h-20 w-20 overflow-hidden rounded-full border-4 border-white shadow-soft">
          <Image src={avatar} alt={label} fill className="object-cover" sizes="80px" />
        </div>
        <div className="min-w-0 flex-1 space-y-2">
          <Label>{label}昵称</Label>
          <Input value={name} onChange={(e) => onNameChange(e.target.value)} required />
        </div>
      </div>
      <div className="mt-4 space-y-2">
        <Label>头像 URL 或 QQ 号</Label>
        <Input
          value={avatarInput}
          onChange={(e) => onAvatarInputChange(e.target.value)}
          placeholder="https://… 或 QQ 号"
        />
      </div>
      <div className="mt-3">
        <Label className="mb-2 block">上传本地图片</Label>
        <Input
          type="file"
          accept="image/*"
          disabled={uploading}
          onChange={(e) => onFile(e.target.files?.[0] ?? null)}
        />
        {uploading ? (
          <p className="mt-2 text-xs text-muted-foreground">上传中…</p>
        ) : (
          <p className="mt-2 text-xs text-muted-foreground">
            选择文件后立即上传并生效；也可填 URL 后点底部「保存全部设置」。
          </p>
        )}
      </div>
    </div>
  );
}
