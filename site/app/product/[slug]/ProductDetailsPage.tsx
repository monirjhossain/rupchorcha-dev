import ProductGallery from "./ProductGallery";
import ProductInfo from "./ProductInfo";
import ProductActions from "./ProductActions";
import ProductDeliveryInfo from "./ProductDeliveryInfo";
import ProductBreadcrumbs from "./ProductBreadcrumbs";
import styles from "./ProductDetailsPage.module.css";
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
    <main className={styles.main}>
      <div className={styles.container}>
        {/* Left: Gallery */}
        <div className={styles.left}>
          <div className={styles.mobileBreadcrumb}>
            <ProductBreadcrumbs product={product} />
          </div>
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
        <div className={styles.right}>
          <ProductInfo product={product} />
          <ProductActions product={product} />
          <ProductDeliveryInfo product={product} />
        </div>
      </div>
    </main>
  );
}
