import { cookies } from "next/headers";
import { NextRequest } from "next/server";
import bcrypt from "bcryptjs";
import { prisma } from "@/lib/prisma";
import {
  AUTH_COOKIE,
  SessionUser,
  clearSessionCookie,
  createSessionToken,
  setSessionCookie,
  verifySessionToken,
} from "@/lib/session";

export {
  AUTH_COOKIE,
  type SessionUser,
  clearSessionCookie,
  createSessionToken,
  setSessionCookie,
  verifySessionToken,
};

export async function getSession(): Promise<SessionUser | null> {
  const token = cookies().get(AUTH_COOKIE)?.value;
  if (!token) return null;
  return verifySessionToken(token);
}

export async function requireSession(): Promise<SessionUser> {
  const session = await getSession();
  if (!session) {
    throw new Error("UNAUTHORIZED");
  }
  return session;
}

export async function loginWithCredentials(
  username: string,
  password: string
): Promise<SessionUser | null> {
  const user = await prisma.user.findUnique({ where: { username } });
  if (!user) return null;
  const ok = await bcrypt.compare(password, user.passwordHash);
  if (!ok) return null;
  return {
    id: user.id,
    username: user.username,
    displayName: user.displayName,
  };
}

export async function getSessionFromRequest(
  request: NextRequest
): Promise<SessionUser | null> {
  const token = request.cookies.get(AUTH_COOKIE)?.value;
  if (!token) return null;
  return verifySessionToken(token);
}
