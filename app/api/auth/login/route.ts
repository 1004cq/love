import { NextRequest } from "next/server";
import { z } from "zod";
import {
  createSessionToken,
  loginWithCredentials,
  setSessionCookie,
} from "@/lib/auth";
import { jsonError, jsonOk } from "@/lib/api";
import { clientKey, rateLimit } from "@/lib/rate-limit";

const schema = z.object({
  username: z.string().min(1).max(64),
  password: z.string().min(1).max(128),
});

export async function POST(request: NextRequest) {
  const limited = rateLimit(clientKey(request, "login"), 10, 15 * 60 * 1000);
  if (!limited.ok) {
    return jsonError(`登录尝试过多，请 ${limited.retryAfterSec} 秒后再试`, 429);
  }

  try {
    const body = schema.parse(await request.json());
    const user = await loginWithCredentials(body.username, body.password);
    if (!user) {
      return jsonError("用户名或密码不正确", 401);
    }

    const token = await createSessionToken(user);
    const response = jsonOk({ user });
    setSessionCookie(response, token);
    return response;
  } catch (error) {
    if (error instanceof z.ZodError) {
      return jsonError("请填写用户名和密码");
    }
    return jsonError("登录失败", 500);
  }
}
