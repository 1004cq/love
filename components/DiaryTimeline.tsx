"use client";

import { FormEvent, useMemo, useState } from "react";
import { DiaryCard, type DiaryCardData } from "@/components/DiaryCard";
import { EmptyState } from "@/components/EmptyState";
import { SoftCard } from "@/components/SoftCard";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { MOOD_OPTIONS, WEATHER_OPTIONS } from "@/lib/diary-meta";
import { cn } from "@/lib/utils";

export type DiaryEntry = DiaryCardData;

export function DiaryTimeline({
  initialEntries,
  canManage = false,
  relationshipStartAt,
}: {
  initialEntries: DiaryEntry[];
  canManage?: boolean;
  relationshipStartAt: string;
}) {
  const [entries, setEntries] = useState(initialEntries);
  const [openId, setOpenId] = useState<string | null>(
    initialEntries[0]?.id ?? null
  );
  const [title, setTitle] = useState("");
  const [content, setContent] = useState("");
  const [weather, setWeather] = useState("sunny");
  const [mood, setMood] = useState("happy");
  const [image, setImage] = useState<File | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [editingId, setEditingId] = useState<string | null>(null);
  const [editTitle, setEditTitle] = useState("");
  const [editContent, setEditContent] = useState("");
  const [editWeather, setEditWeather] = useState("sunny");
  const [editMood, setEditMood] = useState("happy");

  const startMs = useMemo(
    () => new Date(relationshipStartAt).getTime(),
    [relationshipStartAt]
  );

  function dayNumber(createdAt: string) {
    const diff = new Date(createdAt).getTime() - startMs;
    return Math.max(1, Math.floor(diff / 86400000) + 1);
  }

  async function onCreate(e: FormEvent) {
    e.preventDefault();
    setLoading(true);
    setError("");
    try {
      const form = new FormData();
      form.append("title", title.trim());
      form.append("content", content.trim());
      form.append("weather", weather);
      form.append("mood", mood);
      if (image) form.append("image", image);
      const res = await fetch("/api/diary", { method: "POST", body: form });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || "创建失败");
      setEntries((prev) => [data.entry, ...prev]);
      setOpenId(data.entry.id);
      setTitle("");
      setContent("");
      setWeather("sunny");
      setMood("happy");
      setImage(null);
      (e.target as HTMLFormElement).reset();
    } catch (err) {
      setError(err instanceof Error ? err.message : "创建失败");
    } finally {
      setLoading(false);
    }
  }

  function startEdit(entry: DiaryEntry) {
    setEditingId(entry.id);
    setEditTitle(entry.title);
    setEditContent(entry.content);
    setEditWeather(entry.weather || "sunny");
    setEditMood(entry.mood || "happy");
    setOpenId(entry.id);
  }

  async function onSaveEdit(id: string) {
    setLoading(true);
    setError("");
    try {
      const res = await fetch("/api/diary", {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id,
          title: editTitle.trim(),
          content: editContent.trim(),
          weather: editWeather,
          mood: editMood,
        }),
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || "更新失败");
      setEntries((prev) =>
        prev.map((item) => (item.id === id ? data.entry : item))
      );
      setEditingId(null);
    } catch (err) {
      setError(err instanceof Error ? err.message : "更新失败");
    } finally {
      setLoading(false);
    }
  }

  async function onDelete(id: string) {
    const res = await fetch(`/api/diary?id=${id}`, { method: "DELETE" });
    const data = await res.json();
    if (!res.ok) {
      setError(data.error || "删除失败");
      return;
    }
    setEntries((prev) => prev.filter((item) => item.id !== id));
    if (openId === id) setOpenId(null);
    if (editingId === id) setEditingId(null);
  }

  return (
    <div className="space-y-8">
      {canManage && (
        <SoftCard>
          <form onSubmit={onCreate} className="space-y-4">
            <Input
              placeholder="标题"
              value={title}
              onChange={(e) => setTitle(e.target.value)}
              required
            />
            <Textarea
              placeholder="写一点今天想记住的事…"
              value={content}
              onChange={(e) => setContent(e.target.value)}
              required
            />
            <div className="flex flex-wrap gap-4">
              <MetaSelect
                label="天气"
                value={weather}
                onChange={setWeather}
                options={WEATHER_OPTIONS}
              />
              <MetaSelect
                label="心情"
                value={mood}
                onChange={setMood}
                options={MOOD_OPTIONS}
              />
            </div>
            <div className="flex flex-col gap-3 sm:flex-row sm:items-center">
              <Input
                type="file"
                accept="image/*"
                className="h-auto py-2.5"
                onChange={(e) => setImage(e.target.files?.[0] ?? null)}
              />
              <Button type="submit" disabled={loading} className="shrink-0">
                {loading ? "保存中…" : "写下这一天"}
              </Button>
            </div>
            {error && <p className="text-sm text-destructive">{error}</p>}
          </form>
        </SoftCard>
      )}

      {entries.length === 0 ? (
        <EmptyState
          icon={
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
              <path
                d="M5 4h10l4 4v12H5V4z"
                stroke="currentColor"
                strokeWidth="1.5"
              />
              <path d="M15 4v4h4" stroke="currentColor" strokeWidth="1.5" />
              <path
                d="M8 12h8M8 16h5"
                stroke="currentColor"
                strokeWidth="1.5"
                strokeLinecap="round"
              />
            </svg>
          }
          title="时间轴还是空的"
          description="从今天起，把日常轻轻记下来。"
        />
      ) : (
        <ul className="space-y-4">
          {entries.map((entry) => (
            <li key={entry.id}>
              {editingId === entry.id ? (
                <SoftCard className="space-y-3">
                  <Input
                    value={editTitle}
                    onChange={(e) => setEditTitle(e.target.value)}
                  />
                  <Textarea
                    value={editContent}
                    onChange={(e) => setEditContent(e.target.value)}
                  />
                  <div className="flex flex-wrap gap-4">
                    <MetaSelect
                      label="天气"
                      value={editWeather}
                      onChange={setEditWeather}
                      options={WEATHER_OPTIONS}
                    />
                    <MetaSelect
                      label="心情"
                      value={editMood}
                      onChange={setEditMood}
                      options={MOOD_OPTIONS}
                    />
                  </div>
                  <div className="flex flex-wrap gap-2">
                    <Button
                      type="button"
                      size="sm"
                      disabled={loading}
                      onClick={() => onSaveEdit(entry.id)}
                    >
                      保存
                    </Button>
                    <Button
                      type="button"
                      size="sm"
                      variant="secondary"
                      onClick={() => setEditingId(null)}
                    >
                      取消
                    </Button>
                  </div>
                </SoftCard>
              ) : (
                <DiaryCard
                  entry={entry}
                  dayNumber={dayNumber(entry.createdAt)}
                  expanded={openId === entry.id}
                  onToggle={() =>
                    setOpenId(openId === entry.id ? null : entry.id)
                  }
                  actions={
                    canManage ? (
                      <div className="flex flex-wrap gap-3">
                        <button
                          type="button"
                          onClick={() => startEdit(entry)}
                          className="text-xs text-muted-foreground transition-colors duration-soft ease-soft hover:text-foreground"
                        >
                          编辑
                        </button>
                        <button
                          type="button"
                          onClick={() => onDelete(entry.id)}
                          className="text-xs text-muted-foreground transition-colors duration-soft ease-soft hover:text-destructive"
                        >
                          删除
                        </button>
                      </div>
                    ) : undefined
                  }
                />
              )}
            </li>
          ))}
        </ul>
      )}

      {!canManage && error && (
        <p className="text-sm text-destructive">{error}</p>
      )}
    </div>
  );
}

function MetaSelect({
  label,
  value,
  onChange,
  options,
}: {
  label: string;
  value: string;
  onChange: (value: string) => void;
  options: readonly { value: string; label: string; icon: string }[];
}) {
  return (
    <div className="space-y-1.5">
      <p className="text-xs text-muted-foreground">{label}</p>
      <div className="flex flex-wrap gap-1.5">
        {options.map((option) => (
          <button
            key={option.value}
            type="button"
            onClick={() => onChange(option.value)}
            className={cn(
              "rounded-lg px-2.5 py-1.5 text-xs transition-all duration-soft",
              value === option.value
                ? "bg-blush text-white shadow-xs"
                : "bg-secondary text-muted-foreground hover:text-foreground"
            )}
          >
            <span className="mr-1" aria-hidden>
              {option.icon}
            </span>
            {option.label}
          </button>
        ))}
      </div>
    </div>
  );
}
