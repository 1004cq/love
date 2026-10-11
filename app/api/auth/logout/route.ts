import { NextRequest, NextResponse } from "next/server";
import { clearSessionCookie } from "@/lib/auth";
import { jsonOk } from "@/lib/api";

export async function POST() {
  const response = jsonOk({ ok: true });
  clearSessionCookie(response);
  return response;
}

export async function GET(request: NextRequest) {
  const response = NextResponse.redirect(new URL("/", request.url));
  clearSessionCookie(response);
  return response;
}
