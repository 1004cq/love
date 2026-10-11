import { prisma } from "@/lib/prisma";

export async function getPartners() {
  return prisma.user.findMany({
    orderBy: { createdAt: "asc" },
    take: 2,
    select: { displayName: true, avatarUrl: true },
  });
}
