import { Suspense } from "react";
import { LoginForm } from "@/app/admin/LoginForm";
import { PageTransition } from "@/components/PageTransition";
import { SkeletonBlock } from "@/components/SkeletonBlock";

export default function AdminLoginPage() {
  return (
    <PageTransition>
      <div className="mx-auto max-w-md">
        <div className="mb-8 text-center">
          <p className="text-[11px] font-medium uppercase tracking-[0.18em] text-blush">
            Admin
          </p>
          <h1 className="mt-2 font-display text-3xl text-foreground sm:text-4xl">
            管理员登录
          </h1>
          <p className="section-desc mx-auto">
            隐藏入口。用于演示鉴权与 Prisma 数据写入示例。
          </p>
        </div>
        <Suspense fallback={<SkeletonBlock className="h-64 w-full" />}>
          <LoginForm />
        </Suspense>
      </div>
    </PageTransition>
  );
}
