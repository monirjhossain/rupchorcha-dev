"use client";
// ProductInfo: Title, brand, price, offer, color, rating, breadcrumbs
import React, { useEffect, useState } from "react";
import type { Product } from "./types";
import styles from "./ProductInfo.module.css";
import ProductBreadcrumbs from "./ProductBreadcrumbs";

const API_BASE = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api";

export default function ProductInfo({ product }: { product: Product }) {
  const [selectedColor, setSelectedColor] = useState(0);
  const isNew = product.is_new || product.badges?.includes("NEW");
  const isOffer = !!product.sale_price && Number(product.sale_price) < Number(product.price);

  const [averageRating, setAverageRating] = useState<number>(product.rating || 0);
  const [totalReviews, setTotalReviews] = useState<number>(product.reviews_count || 0);

  // Regular price and sale price logic
  const regularPrice = Number(product.price);
  const salePrice = Number(product.sale_price);
  const hasDiscount = !!salePrice && salePrice < regularPrice;
  const discountPercent = hasDiscount ? Math.round(((regularPrice - salePrice) / regularPrice) * 100) : 0;

  // Support multiple categories
  let categories: { name: string; slug?: string }[] = [];
  if (Array.isArray(product.categories) && product.categories.length > 0) {
    categories = product.categories.filter((cat: any) => !!cat && !!cat.name);
  } else if (product.category?.name) {
    categories = [product.category];
  } else if (product.category_name) {
    categories = [{ name: product.category_name, slug: product.category_slug }];
  }

  // Load live rating summary so the rating under price stays dynamic
  useEffect(() => {
    let isMounted = true;

    const loadRating = async () => {
      try {
        const res = await fetch(`${API_BASE}/products/${product.id}/rating`, {
          cache: "no-store",
        });
        if (!res.ok) return;
        const data = await res.json();
        if (!data || !data.success) return;
        if (!isMounted) return;
        if (typeof data.average_rating === "number") {
          setAverageRating(data.average_rating);
        }
        if (typeof data.total_reviews === "number") {
          setTotalReviews(data.total_reviews);
        }
      } catch (err) {
        // Fail silently; fall back to initial product values
        console.error("Failed to load rating summary", err);
      }
    };

    loadRating();

    return () => {
      isMounted = false;
    };
  }, [product.id]);

  return (
    <div>
      {/* Badges */}
      <div className={styles.badges}>
        {isNew && <span className={styles.badgeNew}>NEW!</span>}
      </div>
      {/* Breadcrumbs */}
      <ProductBreadcrumbs product={product} />
      {/* Title & Brand */}
      <h1 className={styles.title}>{product.name}</h1>
      {/* Category row removed as per request */}
      <div className={styles.brand}>{product.brand?.name || product.brand_name}</div>
      {/* Price & Offer */}
      <div className={styles.priceRow}>
        <span className={styles.price}>৳ {hasDiscount ? salePrice : regularPrice}</span>
        {hasDiscount && (
          <span className={styles.oldPrice}>৳ {regularPrice}</span>
        )}
        {hasDiscount && discountPercent > 0 && (
          <span className={styles.discount}>{discountPercent}% OFF</span>
        )}
      </div>
      {/* Color options */}
      {product.colors && product.colors.length > 0 && (
        <div className={styles.colorRow}>
          <div className={styles.colorLabel}>Choose Color:</div>
          <div className={styles.colorOptions}>
            {product.colors.map((color: string, i: number) => (
              <span
                key={i}
                className={
                  styles.colorCircle +
                  (selectedColor === i ? ' ' + styles.colorCircleActive : '')
                }
                style={{ background: color }}
                onClick={() => setSelectedColor(i)}
              />
            ))}
          </div>
        </div>
      )}
      {/* Rating & reviews */}
      <div className={styles.ratingRow}>
        <span className={styles.star}>★</span>
        <span className={styles.ratingValue}>{averageRating ? averageRating.toFixed(1) : "0.0"}</span>
        <span className={styles.reviewCount}>({totalReviews || 0} reviews)</span>
      </div>
    </div>
  );
}
