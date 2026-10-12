import { SignJWT, jwtVerify } from "jose";
import { NextResponse } from "next/server";

export const AUTH_COOKIE = "couple_session";

export type SessionUser = {
  id: string;
  username: string;
  displayName: string;
};

function getSecret() {
  const secret = process.env.AUTH_SECRET;
  const fallback = "couple-space-dev-secret-change-me";

  if (process.env.NODE_ENV === "production") {
    if (!secret || secret === fallback || secret.length < 32) {
      throw new Error(
        "AUTH_SECRET must be set to a random string (≥32 chars) in production"
      );
    }
    return new TextEncoder().encode(secret);
  }

  if (!secret || secret === "replace-with-a-long-random-string") {
    return new TextEncoder().encode(fallback);
  }
  return new TextEncoder().encode(secret);
}

export async function createSessionToken(user: SessionUser) {
  return new SignJWT({
    id: user.id,
    username: user.username,
    displayName: user.displayName,
  })
    .setProtectedHeader({ alg: "HS256" })
    .setIssuedAt()
    .setExpirationTime("7d")
    .sign(getSecret());
}

export async function verifySessionToken(
  token: string
): Promise<SessionUser | null> {
  try {
    const { payload } = await jwtVerify(token, getSecret());
    if (
      typeof payload.id !== "string" ||
      typeof payload.username !== "string" ||
      typeof payload.displayName !== "string"
    ) {
      return null;
    }
    return {
      id: payload.id,
      username: payload.username,
      displayName: payload.displayName,
    };
  } catch {
    return null;
  }
}

export function setSessionCookie(response: NextResponse, token: string) {
  response.cookies.set({
    name: AUTH_COOKIE,
    value: token,
    httpOnly: true,
    sameSite: "lax",
    secure: process.env.NODE_ENV === "production",
    path: "/",
    maxAge: 60 * 60 * 24 * 7,
  });
}

export function clearSessionCookie(response: NextResponse) {
  response.cookies.set({
    name: AUTH_COOKIE,
    value: "",
    httpOnly: true,
    sameSite: "lax",
    secure: process.env.NODE_ENV === "production",
    path: "/",
    maxAge: 0,
  });
}
