/**
 * Normalize avatar input from admin settings.
 * Allows: same-origin paths, https URLs, or QQ numbers → qlogo.
 */
export function resolveAvatarInput(value: string): string {
  const trimmed = value.trim();
  if (!trimmed) return trimmed;

  if (/^\d{5,12}$/.test(trimmed)) {
    return `https://q1.qlogo.cn/g?b=qq&nk=${trimmed}&s=640`;
  }

  if (trimmed.startsWith("/uploads/") || trimmed.startsWith("/lg/")) {
    if (trimmed.includes("..") || trimmed.includes("\\")) {
      throw new Error("头像路径不合法");
    }
    return trimmed;
  }

  let url: URL;
  try {
    url = new URL(trimmed);
  } catch {
    throw new Error("头像需为 https 链接、/uploads 路径或 QQ 号");
  }

  if (url.protocol !== "https:") {
    throw new Error("头像外链仅允许 https");
  }

  // Block obvious script/data schemes already handled by URL parser;
  // still reject credentials in URL.
  if (url.username || url.password) {
    throw new Error("头像链接不合法");
  }

  return url.toString();
}
