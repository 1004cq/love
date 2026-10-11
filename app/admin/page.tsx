import { Suspense } from "react";
import { redirect } from "next/navigation";
import { LoginForm } from "@/app/admin/LoginForm";
import { PageTransition } from "@/components/PageTransition";
import { SkeletonBlock } from "@/components/SkeletonBlock";
import { getSession } from "@/lib/auth";

export const metadata = {
  title: "管理员登录 · 模块栈",
  description: "后台登录入口，登录后进入仪表盘",
};

export default async function AdminLoginPage() {
  const session = await getSession();
  if (session) {
    redirect("/admin/dashboard");
  }

  return (
    <PageTransition>
      <div className="mx-auto max-w-md px-4 py-10 sm:px-6">
        <div className="mb-8 text-center">
          <p className="text-xs font-medium tracking-[0.16em] text-teal-ink">ADMIN</p>
          <h1 className="mt-2 font-display text-3xl tracking-tight text-foreground sm:text-4xl">
            管理员登录
          </h1>
          <p className="section-desc mx-auto">
            登录后进入仪表盘，查看数据概览与模块栈状态。
          </p>
        </div>
        <Suspense fallback={<SkeletonBlock className="h-64 w-full" />}>
          <LoginForm />
        </Suspense>
      </div>
    </PageTransition>
  );
}
