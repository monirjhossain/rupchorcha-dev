import ProductGallery from "./ProductGallery";
import ProductInfo from "./ProductInfo";
import ProductActions from "./ProductActions";
import ProductDeliveryInfo from "./ProductDeliveryInfo";
import { notFound } from "next/navigation";
import type { Product } from "./types";

// Fetch product by slug from backend API
export async function getProduct(slug: string): Promise<Product | null> {
  try {
    const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api"}/products/slug/${slug}`, { cache: "no-store" });
    if (!res.ok) return null;
    const data = await res.json();
    return data.product || null;
  } catch {
    return null;
  }
}

export default async function ProductDetailsPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const product = await getProduct(slug);
  if (!product) return notFound();

  return (
    <main style={{ display: "flex", justifyContent: "center", background: "#fafafd", minHeight: "100vh", padding: "2rem 0" }}>
      <div style={{ display: "flex", gap: 48, maxWidth: 1200, width: "100%", background: "#fff", borderRadius: 18, boxShadow: "0 4px 24px #0002", padding: 32 }}>
        {/* Left: Gallery */}
        <div style={{ flex: 1, minWidth: 0 }}>
          <ProductGallery
            images={
              Array.isArray(product.images)
                ? product.images
                : product.image
                  ? [product.image]
                  : []
            }
            name={product.name}
          />
        </div>
        {/* Right: Info & Actions */}
        <div style={{ flex: 1.2, minWidth: 0, display: "flex", flexDirection: "column", gap: 24 }}>
          <ProductInfo product={product} />
          <ProductActions product={product} />
          <ProductDeliveryInfo product={product} />
        </div>
      </div>
    </main>
  );
}
