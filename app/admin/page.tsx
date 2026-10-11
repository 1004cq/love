import { Suspense } from "react";
import { redirect } from "next/navigation";
import { LoginForm } from "@/app/admin/LoginForm";
import { PageTransition } from "@/components/PageTransition";
import { SkeletonBlock } from "@/components/SkeletonBlock";
import { getSession } from "@/lib/auth";

export const metadata = {
  title: "管理员登录 · Ours",
  description: "隐藏管理入口，登录后进入仪表盘",
};

export default async function AdminLoginPage() {
  const session = await getSession();
  if (session) {
    redirect("/admin/dashboard");
  }

  return (
    <PageTransition>
      <div className="mx-auto max-w-md px-4 py-6 sm:px-6">
        <div className="mb-8 text-center">
          <p className="text-[11px] font-medium uppercase tracking-[0.18em] text-blush">
            Admin
          </p>
          <h1 className="mt-2 font-display text-3xl text-foreground sm:text-4xl">
            管理员登录
          </h1>
          <p className="section-desc mx-auto">
            隐藏入口。登录后可管理相册、日记、一起做的事与留言。
          </p>
        </div>
        <Suspense fallback={<SkeletonBlock className="h-64 w-full" />}>
          <LoginForm />
        </Suspense>
      </div>
    </PageTransition>
  );
}
