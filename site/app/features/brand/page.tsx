import { redirect } from "next/navigation";

export default function BrandRedirect() {
  redirect("/brands");
  return null;
}
