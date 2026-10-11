import { getSession } from "@/lib/auth";
import { jsonOk, unauthorized } from "@/lib/api";

export async function GET() {
  const session = await getSession();
  if (!session) return unauthorized();
  return jsonOk({ user: session });
}
