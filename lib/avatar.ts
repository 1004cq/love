/** PHP LGNewUI: QQ number → qlogo URL when not using custom avatar URL. */
export function resolveAvatarInput(value: string): string {
  const trimmed = value.trim();
  if (!trimmed) return trimmed;
  if (/^https?:\/\//i.test(trimmed) || trimmed.startsWith("/")) {
    return trimmed;
  }
  if (/^\d{5,12}$/.test(trimmed)) {
    return `https://q1.qlogo.cn/g?b=qq&nk=${trimmed}&s=640`;
  }
  return trimmed;
}
