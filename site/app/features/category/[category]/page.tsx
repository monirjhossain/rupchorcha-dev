import { redirect } from "next/navigation";

export default function CategorySlugRedirect({ params }: { params: { category: string } }) {
  redirect(`/category/${params.category}`);
  return null;
}
