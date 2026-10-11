export type TechModule = {
  slug: string;
  category: string;
  name: string;
  tagline: string;
  summary: string;
  points: string[];
  href?: string;
};

/** 模块技术说明 — 选型与职责一览 */
export const techModules: TechModule[] = [
  {
    slug: "nextjs",
    category: "框架",
    name: "Next.js 14+ (App Router)",
    tagline: "前后端一体",
    summary:
      "用 App Router 把页面、路由与 API 收进同一套工程：服务端组件拉取数据，Route Handlers 承接接口，本地与 Vercel 部署同一套心智。",
    points: [
      "App Router 文件系统路由",
      "Server Components + Route Handlers",
      "前后端共享 TypeScript 类型",
    ],
    href: "https://nextjs.org/docs",
  },
  {
    slug: "typescript",
    category: "语言",
    name: "TypeScript",
    tagline: "类型安全",
    summary:
      "从组件 props、API 请求体到 Prisma 模型，类型一路贯穿，减少运行时惊喜，重构时有编译器兜底。",
    points: ["严格模式与共享类型", "Zod 校验与类型推导", "与 Prisma Client 类型联动"],
    href: "https://www.typescriptlang.org/docs/",
  },
  {
    slug: "tailwind",
    category: "样式",
    name: "Tailwind CSS",
    tagline: "快速控制视觉",
    summary:
      "工具类直接表达间距、层级与响应式断点，配合 CSS 变量做主题，改视觉不必在样式表里来回翻。",
    points: ["原子类快速布局", "设计 token（CSS 变量）", "响应式与暗色扩展位"],
    href: "https://tailwindcss.com/docs",
  },
  {
    slug: "shadcn",
    category: "组件库",
    name: "shadcn/ui",
    tagline: "可深度定制",
    summary:
      "组件代码落在仓库里，而不是黑盒依赖。基于 Radix 无障碍原语，按需拷贝、按项目改样式与行为。",
    points: ["源码级可改", "Radix 无障碍基础", "与 Tailwind 主题对齐"],
    href: "https://ui.shadcn.com/",
  },
  {
    slug: "framer-motion",
    category: "动效",
    name: "Framer Motion",
    tagline: "细腻动画",
    summary:
      "页面进入、列表错落、交互反馈用同一套运动令牌；尊重 prefers-reduced-motion，动效服务于层级而非喧闹。",
    points: ["进入淡入与微位移", "交错列表动画", "减弱动效友好"],
    href: "https://www.framer.com/motion/",
  },
  {
    slug: "prisma-sqlite",
    category: "数据库",
    name: "Prisma + SQLite",
    tagline: "简单够用",
    summary:
      "Prisma schema 定义模型，SQLite 本地零运维即可跑通；开发快速，结构清晰，需要时再迁到更重的库。",
    points: ["声明式 schema", "类型安全查询", "本地文件库开箱即用"],
    href: "https://www.prisma.io/docs",
  },
  {
    slug: "vercel",
    category: "部署",
    name: "Vercel",
    tagline: "一键上线",
    summary:
      "对接 Git 仓库即可预览与生产发布；Next.js 原生适配，环境变量与构建命令开箱可用。",
    points: ["Git 推送即部署", "Preview / Production", "与 App Router 深度契合"],
    href: "https://vercel.com/docs",
  },
];

export function getTechModule(slug: string) {
  return techModules.find((m) => m.slug === slug);
}
