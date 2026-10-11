"use client";

import { AnimatePresence, motion, useReducedMotion } from "framer-motion";
import { FormEvent, useEffect, useRef, useState } from "react";
import { EmptyState } from "@/components/EmptyState";
import { MessageBubble } from "@/components/MessageBubble";
import { SoftCard } from "@/components/SoftCard";
import { Button } from "@/components/ui/button";
import { Textarea } from "@/components/ui/textarea";
import { formatDateTime } from "@/lib/format";

export type BoardMessage = {
  id: string;
  content: string;
  createdAt: string;
  author: {
    id: string;
    displayName: string;
  };
};

export function MessageBoard({
  initialMessages,
  currentUserId,
  canManage = false,
}: {
  initialMessages: BoardMessage[];
  currentUserId: string | null;
  canManage?: boolean;
}) {
  const reduce = useReducedMotion();
  const [messages, setMessages] = useState(initialMessages);
  const [content, setContent] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const bottomRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    bottomRef.current?.scrollIntoView({ behavior: reduce ? "auto" : "smooth" });
  }, [messages, reduce]);

  async function onSend(e: FormEvent) {
    e.preventDefault();
    if (!content.trim()) return;
    setLoading(true);
    setError("");
    try {
      const res = await fetch("/api/message", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ content: content.trim() }),
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || "发送失败");
      setMessages((prev) => [...prev, data.message]);
      setContent("");
    } catch (err) {
      setError(err instanceof Error ? err.message : "发送失败");
    } finally {
      setLoading(false);
    }
  }

  async function onDelete(id: string) {
    const res = await fetch(`/api/message?id=${id}`, { method: "DELETE" });
    const data = await res.json();
    if (!res.ok) {
      setError(data.error || "删除失败");
      return;
    }
    setMessages((prev) => prev.filter((m) => m.id !== id));
  }

  return (
    <div className="space-y-8">
      <SoftCard padding="sm" className="min-h-[360px]">
        {messages.length === 0 ? (
          <EmptyState
            icon={
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                <path
                  d="M4 6h16v10H8l-4 3V6z"
                  stroke="currentColor"
                  strokeWidth="1.5"
                  strokeLinejoin="round"
                />
              </svg>
            }
            title="还没有留言"
            description="说一句想对对方说的话吧。"
          />
        ) : (
          <div className="flex max-h-[520px] flex-col gap-4 overflow-y-auto p-2 pr-1">
            <AnimatePresence initial={false}>
              {messages.map((message) => {
                const mine = Boolean(
                  currentUserId && message.author.id === currentUserId
                );
                return (
                  <MessageBubble
                    key={message.id}
                    content={message.content}
                    authorName={message.author.displayName}
                    meta={formatDateTime(message.createdAt)}
                    mine={mine}
                    actions={
                      canManage ? (
                        <button
                          type="button"
                          onClick={() => onDelete(message.id)}
                          className={`text-xs transition-opacity duration-soft hover:opacity-100 ${
                            mine
                              ? "text-primary-foreground/75"
                              : "text-muted-foreground"
                          }`}
                        >
                          删除
                        </button>
                      ) : null
                    }
                  />
                );
              })}
            </AnimatePresence>
            <div ref={bottomRef} />
          </div>
        )}
      </SoftCard>

      {canManage ? (
        <SoftCard padding="sm">
          <form onSubmit={onSend} className="flex flex-col gap-3 sm:flex-row">
            <Textarea
              className="min-h-[52px] flex-1 resize-none"
              placeholder="写给对方的一句话…"
              value={content}
              onChange={(e) => setContent(e.target.value)}
              rows={2}
            />
            <motion.div
              whileTap={reduce ? undefined : { scale: 0.96 }}
              transition={{ duration: 0.2 }}
              className="self-stretch sm:self-end"
            >
              <Button
                type="submit"
                disabled={loading || !content.trim()}
                className="w-full sm:w-auto"
              >
                {loading ? "发送中…" : "发送"}
              </Button>
            </motion.div>
          </form>
          {error && <p className="mt-3 text-sm text-destructive">{error}</p>}
        </SoftCard>
      ) : (
        <p className="text-center text-sm text-muted-foreground">
          浏览模式。管理员登录后可发布留言。
        </p>
      )}
    </div>
  );
}
