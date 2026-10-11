"use client";

import { FormEvent, useMemo, useState } from "react";
import { EmptyState } from "@/components/EmptyState";
import { SoftCard } from "@/components/SoftCard";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { cn } from "@/lib/utils";

export type TogetherItem = {
  id: string;
  title: string;
  note: string | null;
  isCompleted: boolean;
  createdAt: string;
  completedAt?: string | null;
  createdBy?: { id: string; displayName: string } | null;
};

type Filter = "all" | "todo" | "done";

export function TogetherList({
  initialItems,
  canManage = false,
}: {
  initialItems: TogetherItem[];
  canManage?: boolean;
}) {
  const [items, setItems] = useState(initialItems);
  const [filter, setFilter] = useState<Filter>("all");
  const [query, setQuery] = useState("");
  const [title, setTitle] = useState("");
  const [note, setNote] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const filtered = useMemo(() => {
    const q = query.trim().toLowerCase();
    return items.filter((item) => {
      if (filter === "todo" && item.isCompleted) return false;
      if (filter === "done" && !item.isCompleted) return false;
      if (!q) return true;
      return (
        item.title.toLowerCase().includes(q) ||
        (item.note || "").toLowerCase().includes(q)
      );
    });
  }, [items, filter, query]);

  const counts = useMemo(
    () => ({
      all: items.length,
      todo: items.filter((i) => !i.isCompleted).length,
      done: items.filter((i) => i.isCompleted).length,
    }),
    [items]
  );

  async function onCreate(e: FormEvent) {
    e.preventDefault();
    setLoading(true);
    setError("");
    try {
      const res = await fetch("/api/together", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ title, note }),
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || "添加失败");
      setItems((prev) => [data.item, ...prev]);
      setTitle("");
      setNote("");
    } catch (err) {
      setError(err instanceof Error ? err.message : "添加失败");
    } finally {
      setLoading(false);
    }
  }

  async function toggle(item: TogetherItem) {
    if (!canManage) return;
    const res = await fetch("/api/together", {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: item.id, isCompleted: !item.isCompleted }),
    });
    const data = await res.json();
    if (!res.ok) {
      setError(data.error || "更新失败");
      return;
    }
    setItems((prev) =>
      prev.map((row) => (row.id === item.id ? data.item : row))
    );
  }

  async function onDelete(id: string) {
    const res = await fetch(`/api/together?id=${id}`, { method: "DELETE" });
    const data = await res.json();
    if (!res.ok) {
      setError(data.error || "删除失败");
      return;
    }
    setItems((prev) => prev.filter((item) => item.id !== id));
  }

  const filters: { key: Filter; label: string; count: number }[] = [
    { key: "done", label: "已完成", count: counts.done },
    { key: "all", label: "全部", count: counts.all },
    { key: "todo", label: "未完成", count: counts.todo },
  ];

  return (
    <div className="space-y-6">
      <SoftCard className="space-y-4">
        <div className="grid grid-cols-3 gap-1 rounded-2xl bg-secondary/80 p-1">
          {filters.map((item) => (
            <button
              key={item.key}
              type="button"
              onClick={() => setFilter(item.key)}
              className={cn(
                "rounded-xl px-2 py-2.5 text-sm transition-all duration-soft ease-soft",
                filter === item.key
                  ? "bg-card text-foreground shadow-xs"
                  : "text-muted-foreground hover:text-foreground"
              )}
            >
              {item.label}
              <span className="ml-1 opacity-70">{item.count}</span>
            </button>
          ))}
        </div>
        <div className="flex items-center gap-2">
          <Input
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            placeholder="搜索甜蜜回忆…"
            className="flex-1"
          />
          <Button type="button" variant="secondary" className="shrink-0">
            查询
          </Button>
        </div>
      </SoftCard>

      {canManage && (
        <SoftCard>
          <form onSubmit={onCreate} className="space-y-3">
            <Input
              value={title}
              onChange={(e) => setTitle(e.target.value)}
              placeholder="新的一起做的事"
              required
            />
            <Input
              value={note}
              onChange={(e) => setNote(e.target.value)}
              placeholder="备注（可选）"
            />
            <Button type="submit" disabled={loading}>
              {loading ? "添加中…" : "添加"}
            </Button>
            {error && <p className="text-sm text-destructive">{error}</p>}
          </form>
        </SoftCard>
      )}

      {filtered.length === 0 ? (
        <EmptyState
          icon={
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
              <path
                d="M5 6h14v12H5V6z"
                stroke="currentColor"
                strokeWidth="1.5"
              />
              <path
                d="M8 10h8M8 14h5"
                stroke="currentColor"
                strokeWidth="1.5"
                strokeLinecap="round"
              />
            </svg>
          }
          title="暂无内容"
          description="换个筛选，或添加一件想一起做的事。"
        />
      ) : (
        <ul className="space-y-3">
          {filtered.map((item, index) => (
            <li key={item.id}>
              <SoftCard delay={index * 0.03} padding="sm" className="px-4 py-4">
                <div className="flex items-start gap-3">
                  <button
                    type="button"
                    disabled={!canManage}
                    onClick={() => toggle(item)}
                    className={cn(
                      "mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full border transition-all duration-soft ease-soft",
                      item.isCompleted
                        ? "border-blush bg-blush text-white"
                        : "border-border bg-card text-transparent hover:border-blush/50",
                      !canManage && "cursor-default"
                    )}
                    aria-label={item.isCompleted ? "已完成" : "未完成"}
                  >
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                      <path
                        d="M2.5 6.2L4.8 8.5 9.5 3.5"
                        stroke="currentColor"
                        strokeWidth="1.6"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                      />
                    </svg>
                  </button>
                  <div className="min-w-0 flex-1">
                    <p
                      className={cn(
                        "font-medium text-foreground",
                        item.isCompleted && "text-muted-foreground line-through"
                      )}
                    >
                      {item.title}
                    </p>
                    {item.note && (
                      <p className="mt-1 text-sm text-muted-foreground">
                        {item.note}
                      </p>
                    )}
                  </div>
                  {canManage && (
                    <button
                      type="button"
                      onClick={() => onDelete(item.id)}
                      className="text-xs text-muted-foreground transition-colors duration-soft hover:text-destructive"
                    >
                      删除
                    </button>
                  )}
                </div>
              </SoftCard>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
