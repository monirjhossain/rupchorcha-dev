import { redirect } from "next/navigation";

export default function CategoryRedirect() {
  redirect("/category");
  return null;
}
