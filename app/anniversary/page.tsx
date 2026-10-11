import { redirect } from "next/navigation";

/** PHP loveday cards live on home; list page covers 恋爱事件 */
export default function AnniversaryRedirect() {
  redirect("/list");
}
