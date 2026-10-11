"use client";

import { AnimatePresence, motion, useReducedMotion } from "framer-motion";
import Image from "next/image";
import { FormEvent, useState } from "react";
import { EmptyState } from "@/components/EmptyState";
import { SoftCard } from "@/components/SoftCard";
import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { fadeUp, softSpring, staggerContainer } from "@/lib/motion";
import { formatDateTime } from "@/lib/format";

export type AlbumPhoto = {
  id: string;
  url: string;
  caption: string | null;
  createdAt: string;
  uploadedBy: {
    id: string;
    displayName: string;
  };
};

export function AlbumGrid({
  initialPhotos,
  canManage = false,
}: {
  initialPhotos: AlbumPhoto[];
  canManage?: boolean;
}) {
  const reduce = useReducedMotion();
  const [photos, setPhotos] = useState(initialPhotos);
  const [caption, setCaption] = useState("");
  const [file, setFile] = useState<File | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [active, setActive] = useState<AlbumPhoto | null>(null);
  const [exitingId, setExitingId] = useState<string | null>(null);

  async function onUpload(e: FormEvent) {
    e.preventDefault();
    if (!file) {
      setError("请先选择图片");
      return;
    }
    setLoading(true);
    setError("");
    try {
      const form = new FormData();
      form.append("file", file);
      if (caption.trim()) form.append("caption", caption.trim());
      const res = await fetch("/api/album", { method: "POST", body: form });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || "上传失败");
      setPhotos((prev) => [data.photo, ...prev]);
      setCaption("");
      setFile(null);
      (e.target as HTMLFormElement).reset();
    } catch (err) {
      setError(err instanceof Error ? err.message : "上传失败");
    } finally {
      setLoading(false);
    }
  }

  async function onDelete(id: string) {
    setExitingId(id);
    try {
      const res = await fetch(`/api/album?id=${id}`, { method: "DELETE" });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || "删除失败");
      window.setTimeout(
        () => {
          setPhotos((prev) => prev.filter((p) => p.id !== id));
          setExitingId(null);
          if (active?.id === id) setActive(null);
        },
        reduce ? 0 : 320
      );
    } catch (err) {
      setExitingId(null);
      setError(err instanceof Error ? err.message : "删除失败");
    }
  }

  return (
    <div className="space-y-10">
      {canManage && (
        <SoftCard>
          <form
            onSubmit={onUpload}
            className="grid gap-3 sm:grid-cols-[1fr_1.2fr_auto]"
          >
            <Input
              type="file"
              accept="image/*"
              className="h-auto py-2.5"
              onChange={(e) => setFile(e.target.files?.[0] ?? null)}
            />
            <Input
              placeholder="写一句说明（可选）"
              value={caption}
              onChange={(e) => setCaption(e.target.value)}
            />
            <Button type="submit" disabled={loading}>
              {loading ? "上传中…" : "上传"}
            </Button>
          </form>
          {error && <p className="mt-3 text-sm text-destructive">{error}</p>}
        </SoftCard>
      )}

      {photos.length === 0 ? (
        <EmptyState
          icon={
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
              <rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" strokeWidth="1.5" />
              <circle cx="9" cy="10" r="1.5" fill="currentColor" />
              <path d="M3 16l5-4 4 3 4-5 5 6" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
          }
          title="还没有照片"
          description="上传第一张属于你们的瞬间吧。"
        />
      ) : (
        <motion.div
          variants={staggerContainer}
          initial="hidden"
          animate="show"
          className="grid grid-cols-2 gap-4 sm:grid-cols-3 sm:gap-5"
        >
          <AnimatePresence mode="popLayout">
            {photos.map((photo) => (
              <motion.article
                key={photo.id}
                layout
                variants={fadeUp}
                exit={
                  reduce
                    ? { opacity: 0 }
                    : { opacity: 0, x: 48, transition: { duration: 0.32 } }
                }
                animate={
                  exitingId === photo.id && !reduce
                    ? { opacity: 0, x: 48 }
                    : { opacity: 1, x: 0 }
                }
                className="group overflow-hidden rounded-2xl border border-border/70 bg-card shadow-soft"
              >
                <button
                  type="button"
                  className="block w-full overflow-hidden"
                  onClick={() => setActive(photo)}
                >
                  <motion.div
                    whileHover={reduce ? undefined : { scale: 1.03 }}
                    transition={{ duration: 0.35, ease: [0.22, 1, 0.36, 1] }}
                    className="relative aspect-square"
                  >
                    <Image
                      src={photo.url}
                      alt={photo.caption || "相册图片"}
                      fill
                      className="object-cover transition-shadow duration-soft group-hover:shadow-lift"
                      sizes="(max-width: 640px) 50vw, 33vw"
                    />
                  </motion.div>
                </button>
                <div className="flex items-start justify-between gap-2 p-3.5">
                  <div className="min-w-0">
                    <p className="truncate text-sm text-foreground">
                      {photo.caption || "未命名瞬间"}
                    </p>
                    <p className="mt-0.5 text-xs text-muted-foreground">
                      {photo.uploadedBy.displayName} ·{" "}
                      {formatDateTime(photo.createdAt)}
                    </p>
                  </div>
                  {canManage && (
                    <button
                      type="button"
                      onClick={() => onDelete(photo.id)}
                      className="shrink-0 text-xs text-muted-foreground transition-colors duration-soft hover:text-destructive"
                    >
                      删除
                    </button>
                  )}
                </div>
              </motion.article>
            ))}
          </AnimatePresence>
        </motion.div>
      )}

      <Dialog open={!!active} onOpenChange={(open) => !open && setActive(null)}>
        <DialogContent className="max-w-3xl overflow-hidden p-0 sm:p-0">
          {active && (
            <>
              <div className="relative aspect-[4/3] w-full bg-foreground/95 sm:aspect-video">
                <motion.div
                  initial={reduce ? false : { scale: 0.96, opacity: 0 }}
                  animate={{ scale: 1, opacity: 1 }}
                  transition={reduce ? { duration: 0 } : softSpring}
                  className="absolute inset-0"
                >
                  <Image
                    src={active.url}
                    alt={active.caption || "预览"}
                    fill
                    className="object-contain"
                    sizes="100vw"
                  />
                </motion.div>
              </div>
              <div className="flex items-center justify-between gap-3 px-5 py-4">
                <DialogHeader className="space-y-1 text-left">
                  <DialogTitle className="text-base">
                    {active.caption || "未命名瞬间"}
                  </DialogTitle>
                  <p className="text-xs text-muted-foreground">
                    {active.uploadedBy.displayName}
                  </p>
                </DialogHeader>
                <Button variant="secondary" onClick={() => setActive(null)}>
                  关闭
                </Button>
              </div>
            </>
          )}
        </DialogContent>
      </Dialog>
    </div>
  );
}
