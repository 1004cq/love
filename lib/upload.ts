import { mkdir, writeFile } from "fs/promises";
import path from "path";
import { randomUUID } from "crypto";

const UPLOAD_DIR = path.join(process.cwd(), "public", "uploads");

const ALLOWED: Record<string, { ext: string; mime: string }> = {
  "ffd8ff": { ext: ".jpg", mime: "image/jpeg" },
  "89504e47": { ext: ".png", mime: "image/png" },
  "47494638": { ext: ".gif", mime: "image/gif" },
  "52494646": { ext: ".webp", mime: "image/webp" }, // RIFF....WEBP checked below
};

function sniffImage(buffer: Buffer): { ext: string; mime: string } | null {
  const head = buffer.subarray(0, 12);
  const hex4 = head.subarray(0, 4).toString("hex");
  const hex3 = head.subarray(0, 3).toString("hex");

  if (hex3 === "ffd8ff") return ALLOWED.ffd8ff;
  if (hex4 === "89504e47") return ALLOWED["89504e47"];
  if (hex4 === "47494638") return ALLOWED["47494638"];
  if (
    hex4 === "52494646" &&
    head.subarray(8, 12).toString("ascii") === "WEBP"
  ) {
    return ALLOWED["52494646"];
  }
  return null;
}

export async function saveUploadedImage(file: File) {
  const maxBytes = 8 * 1024 * 1024;
  if (file.size <= 0 || file.size > maxBytes) {
    throw new Error("图片大小需在 1B–8MB 之间");
  }

  // Reject dangerous declared types early (SVG/HTML can execute scripts).
  const declared = (file.type || "").toLowerCase();
  if (
    declared &&
    !["image/jpeg", "image/png", "image/gif", "image/webp", "image/jpg"].includes(
      declared
    )
  ) {
    throw new Error("仅支持 JPG / PNG / GIF / WEBP");
  }

  const buffer = Buffer.from(await file.arrayBuffer());
  const sniffed = sniffImage(buffer);
  if (!sniffed) {
    throw new Error("文件内容不是受支持的图片格式");
  }

  await mkdir(UPLOAD_DIR, { recursive: true });

  const filename = `${Date.now()}-${randomUUID()}${sniffed.ext}`;
  const filepath = path.join(UPLOAD_DIR, filename);
  await writeFile(filepath, buffer);

  return `/uploads/${filename}`;
}

/** Ensure a public upload URL stays inside /public/uploads */
export function resolveUploadFsPath(publicUrl: string): string | null {
  if (!publicUrl.startsWith("/uploads/")) return null;
  const base = path.resolve(UPLOAD_DIR);
  const target = path.resolve(
    process.cwd(),
    "public",
    publicUrl.replace(/^\//, "")
  );
  if (!target.startsWith(base + path.sep) && target !== base) {
    return null;
  }
  return target;
}
