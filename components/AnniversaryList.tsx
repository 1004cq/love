"use client";

import { AnimatePresence, motion, useReducedMotion } from "framer-motion";
import { FormEvent, useMemo, useState } from "react";
import { EmptyState } from "@/components/EmptyState";
import { SoftCard } from "@/components/SoftCard";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { daysUntil, formatDate } from "@/lib/format";
import { duration, easeOutSoft } from "@/lib/motion";

export type Anniversary = {
  id: string;
  title: string;
  date: string;
  note: string | null;
  isImportant: boolean;
  isCompleted: boolean;
};

function CountdownDigits({ days }: { days: number }) {
  const reduce = useReducedMotion();
  const label =
    days > 0 ? `${days}` : days === 0 ? "今天" : `${Math.abs(days)}`;
  const suffix = days > 0 ? "天后" : days === 0 ? "" : "天前";

  return (
    <div className="flex items-baseline gap-1">
      <AnimatePresence mode="popLayout" initial={false}>
        <motion.span
          key={label}
          initial={reduce ? false : { opacity: 0, y: 8 }}
          animate={{ opacity: 1, y: 0 }}
          exit={reduce ? undefined : { opacity: 0, y: -8 }}
          transition={{ duration: duration.base, ease: easeOutSoft }}
          className="font-display text-2xl text-foreground"
        >
          {label}
        </motion.span>
      </AnimatePresence>
      {suffix && (
        <span className="text-sm text-muted-foreground">{suffix}</span>
      )}
    </div>
  );
}

export function AnniversaryList({
  initialItems,
  canManage = false,
}: {
  initialItems: Anniversary[];
  canManage?: boolean;
}) {
  const reduce = useReducedMotion();
  const [items, setItems] = useState(initialItems);
  const [title, setTitle] = useState("");
  const [date, setDate] = useState("");
  const [note, setNote] = useState("");
  const [isImportant, setIsImportant] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [editingId, setEditingId] = useState<string | null>(null);
  const [editTitle, setEditTitle] = useState("");
  const [editDate, setEditDate] = useState("");
  const [editNote, setEditNote] = useState("");

  const sorted = useMemo(
    () =>
      [...items].sort(
        (a, b) => new Date(a.date).getTime() - new Date(b.date).getTime()
      ),
    [items]
  );

  async function onCreate(e: FormEvent) {
    e.preventDefault();
    setLoading(true);
    setError("");
    try {
      const res = await fetch("/api/anniversary", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ title, date, note, isImportant }),
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || "添加失败");
      setItems((prev) => [...prev, data.anniversary]);
      setTitle("");
      setDate("");
      setNote("");
      setIsImportant(false);
    } catch (err) {
      setError(err instanceof Error ? err.message : "添加失败");
    } finally {
      setLoading(false);
    }
  }

  function startEdit(item: Anniversary) {
    setEditingId(item.id);
    setEditTitle(item.title);
    setEditDate(item.date.slice(0, 10));
    setEditNote(item.note || "");
  }

  async function onSaveEdit(id: string) {
    setLoading(true);
    setError("");
    try {
      const res = await fetch("/api/anniversary", {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id,
          title: editTitle.trim(),
          date: editDate,
          note: editNote.trim() || null,
        }),
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || "更新失败");
      setItems((prev) =>
        prev.map((row) => (row.id === id ? data.anniversary : row))
      );
      setEditingId(null);
    } catch (err) {
      setError(err instanceof Error ? err.message : "更新失败");
    } finally {
      setLoading(false);
    }
  }

  async function toggleCompleted(item: Anniversary) {
    const res = await fetch("/api/anniversary", {
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
      prev.map((row) => (row.id === item.id ? data.anniversary : row))
    );
  }

  async function onDelete(id: string) {
    const res = await fetch(`/api/anniversary?id=${id}`, { method: "DELETE" });
    const data = await res.json();
    if (!res.ok) {
      setError(data.error || "删除失败");
      return;
    }
    setItems((prev) => prev.filter((item) => item.id !== id));
  }

  return (
    <div className="space-y-10">
      {canManage && (
        <SoftCard>
          <form onSubmit={onCreate} className="space-y-4">
            <div className="grid gap-3 sm:grid-cols-2">
              <Input
                placeholder="纪念日名称"
                value={title}
                onChange={(e) => setTitle(e.target.value)}
                required
              />
              <Input
                type="date"
                value={date}
                onChange={(e) => setDate(e.target.value)}
                required
              />
            </div>
            <Input
              placeholder="备注（可选）"
              value={note}
              onChange={(e) => setNote(e.target.value)}
            />
            <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
              <Label className="flex items-center gap-2 font-normal">
                <input
                  type="checkbox"
                  checked={isImportant}
                  onChange={(e) => setIsImportant(e.target.checked)}
                  className="rounded border-border text-blush focus:ring-ring/30"
                />
                标记为重要
              </Label>
              <Button type="submit" disabled={loading}>
                {loading ? "添加中…" : "添加纪念日"}
              </Button>
            </div>
            {error && <p className="text-sm text-destructive">{error}</p>}
          </form>
        </SoftCard>
      )}

      {sorted.length === 0 ? (
        <EmptyState
          icon={
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
              <rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" strokeWidth="1.5" />
              <path d="M8 3v4M16 3v4M3 10h18" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
            </svg>
          }
          title="还没有纪念日"
          description="把值得期待的日子放进来，一起倒数。"
        />
      ) : (
        <div className="grid gap-4 sm:grid-cols-2 sm:gap-5">
          {sorted.map((item, index) => {
            const remaining = daysUntil(item.date);
            return (
              <motion.div
                key={item.id}
                initial={reduce ? false : { opacity: 0, y: 8 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true, margin: "-20px" }}
                transition={{
                  duration: duration.enter,
                  ease: easeOutSoft,
                  delay: index * 0.04,
                }}
              >
                <SoftCard
                  animateOnView={false}
                  className={`${item.isImportant ? "border-blush/25" : ""} ${
                    item.isCompleted ? "bg-sage-soft/50" : ""
                  }`}
                >
                  {editingId === item.id && canManage ? (
                    <div className="space-y-3">
                      <Input
                        value={editTitle}
                        onChange={(e) => setEditTitle(e.target.value)}
                      />
                      <Input
                        type="date"
                        value={editDate}
                        onChange={(e) => setEditDate(e.target.value)}
                      />
                      <Input
                        value={editNote}
                        onChange={(e) => setEditNote(e.target.value)}
                        placeholder="备注"
                      />
                      <div className="flex gap-2">
                        <Button
                          size="sm"
                          disabled={loading}
                          onClick={() => onSaveEdit(item.id)}
                        >
                          保存
                        </Button>
                        <Button
                          size="sm"
                          variant="secondary"
                          onClick={() => setEditingId(null)}
                        >
                          取消
                        </Button>
                      </div>
                    </div>
                  ) : (
                    <>
                      <div className="flex items-start justify-between gap-3">
                        <div>
                          <h3 className="font-display text-lg text-foreground">
                            {item.title}
                          </h3>
                          <p className="mt-1 text-sm text-muted-foreground">
                            {formatDate(item.date)}
                          </p>
                        </div>
                        <CountdownDigits days={remaining} />
                      </div>

                      {item.note && (
                        <p className="mt-3 text-sm leading-relaxed text-muted-foreground">
                          {item.note}
                        </p>
                      )}

                      {canManage && (
                        <div className="mt-5 flex items-center justify-between gap-3">
                          <motion.div
                            whileTap={reduce ? undefined : { scale: 0.94 }}
                          >
                            <Button
                              type="button"
                              variant={item.isCompleted ? "soft" : "secondary"}
                              size="sm"
                              onClick={() => toggleCompleted(item)}
                              className={
                                item.isCompleted
                                  ? "bg-sage text-white hover:bg-sage/90"
                                  : undefined
                              }
                            >
                              {item.isCompleted ? "已纪念" : "标记完成"}
                            </Button>
                          </motion.div>
                          <div className="flex gap-3">
                            <button
                              type="button"
                              onClick={() => startEdit(item)}
                              className="text-xs text-muted-foreground transition-colors duration-soft hover:text-foreground"
                            >
                              编辑
                            </button>
                            <button
                              type="button"
                              onClick={() => onDelete(item.id)}
                              className="text-xs text-muted-foreground transition-colors duration-soft hover:text-destructive"
                            >
                              删除
                            </button>
                          </div>
                        </div>
                      )}
                    </>
                  )}
                </SoftCard>
              </motion.div>
            );
          })}
        </div>
      )}

      {!canManage && error && (
        <p className="text-sm text-destructive">{error}</p>
      )}
    </div>
  );
}
