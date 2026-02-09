"use client";
// ProductInfo: Title, brand, price, offer, color, rating, breadcrumbs
import React, { useState } from "react";
import type { Product } from "./types";
import styles from "./ProductInfo.module.css";
import ProductBreadcrumbs from "./ProductBreadcrumbs";

export default function ProductInfo({ product }: { product: Product }) {
  const [selectedColor, setSelectedColor] = useState(0);
  const isNew = product.is_new || product.badges?.includes("NEW");
  const isOffer = !!product.sale_price && Number(product.sale_price) < Number(product.price);

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
        <span className={styles.star}>★</span> {product.rating || 0}
        <span className={styles.reviewCount}>({product.reviews_count || 0} reviews)</span>
      </div>
    </div>
  );
}
