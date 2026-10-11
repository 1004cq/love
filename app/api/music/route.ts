import { prisma } from "@/lib/prisma";
import { jsonOk } from "@/lib/api";

export async function GET() {
  const tracks = await prisma.musicTrack.findMany({
    orderBy: { sortOrder: "asc" },
  });
  return jsonOk({ tracks });
}
