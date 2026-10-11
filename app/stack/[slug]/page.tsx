import Link from "next/link";
import { notFound } from "next/navigation";
import { PageTransition } from "@/components/PageTransition";
import { Button } from "@/components/ui/button";
import { getTechModule, techModules } from "@/lib/tech-modules";

export function generateStaticParams() {
  return techModules.map((m) => ({ slug: m.slug }));
}

export function generateMetadata({ params }: { params: { slug: string } }) {
  const item = getTechModule(params.slug);
  if (!item) return { title: "模块 · 模块栈" };
  return {
    title: `${item.category} · ${item.name} · 模块栈`,
    description: `${item.name} — ${item.tagline}。${item.summary}`,
  };
}

export default function StackModulePage({ params }: { params: { slug: string } }) {
  const item = getTechModule(params.slug);
  if (!item) notFound();

  const index = techModules.findIndex((m) => m.slug === item.slug);
  const prev = index > 0 ? techModules[index - 1] : null;
  const next = index < techModules.length - 1 ? techModules[index + 1] : null;

  return (
    <PageTransition>
      <article className="mx-auto max-w-2xl px-5 py-12 sm:px-8">
        <p className="text-xs font-medium tracking-[0.16em] text-teal-ink">
          {item.category}
        </p>
        <h1 className="mt-3 font-display text-4xl tracking-tight">{item.name}</h1>
        <p className="mt-3 text-lg font-medium text-foreground/85">{item.tagline}</p>
        <p className="mt-6 text-base leading-relaxed text-muted-foreground">{item.summary}</p>

        <ul className="mt-8 space-y-3">
          {item.points.map((point) => (
            <li
              key={point}
              className="rounded-2xl border border-border/70 bg-card px-4 py-3.5 text-sm shadow-xs"
            >
              {point}
            </li>
          ))}
        </ul>

        <div className="mt-8 flex flex-wrap gap-3">
          {item.href ? (
            <Button asChild className="rounded-xl">
              <a href={item.href} target="_blank" rel="noreferrer">
                官方文档
              </a>
            </Button>
          ) : null}
          <Button asChild variant="secondary" className="rounded-xl">
            <Link href="/stack">全部模块</Link>
          </Button>
        </div>

        <nav className="mt-12 flex items-center justify-between gap-4 border-t border-border/60 pt-6 text-sm">
          {prev ? (
            <Link href={`/stack/${prev.slug}`} className="text-muted-foreground hover:text-foreground">
              ← {prev.category}
            </Link>
          ) : (
            <span />
          )}
          {next ? (
            <Link href={`/stack/${next.slug}`} className="text-muted-foreground hover:text-foreground">
              {next.category} →
            </Link>
          ) : (
            <span />
          )}
        </nav>
      </article>
    </PageTransition>
  );
}
