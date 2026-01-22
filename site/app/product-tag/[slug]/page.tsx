"use client";
import { useParams } from "next/navigation";
import TagProductsPage from "../../components/TagProductsPage";

export default function TagPageWrapper() {
  const params = useParams();
  return <TagProductsPage params={params} />;
}
