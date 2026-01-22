// ProductDeliveryInfo: Delivery info, SKU, fast selling, badges
import React from "react";
import type { Product } from "./types";
import styles from "./ProductDeliveryInfo.module.css";
import Link from "next/link";

export default function ProductDeliveryInfo({ product }: { product: Product }) {
  // Show only category name(s), never fallback to ID
  // Prepare categories array for link rendering
  let categories: { name: string; slug?: string }[] = [];
  if (Array.isArray(product.categories) && product.categories.length > 0) {
    categories = product.categories.filter((cat: any) => !!cat && !!cat.name);
  } else if (product.category?.name) {
    categories = [product.category];
  } else if (product.category_name) {
    categories = [{ name: product.category_name, slug: product.category_slug }];
  }

  return (
    <div className={styles.deliveryInfo}>
      <div className={styles.infoRow}>
        <span className={styles.icon}>
          <svg width="20" height="20" fill="none" viewBox="0 0 20 20"><path d="M3 10.5V7.8A2.8 2.8 0 0 1 5.8 5h8.4A2.8 2.8 0 0 1 17 7.8v2.7" stroke="#a004b0" strokeWidth="1.5" strokeLinecap="round"/></svg>
        </span>
        <b>Brand:</b> {product.brand?.name || product.brand_name || "N/A"}
      </div>
      <div className={styles.infoRow}>
        <span className={styles.icon}>
          <svg width="20" height="20" fill="none" viewBox="0 0 20 20"><rect x="3" y="5" width="14" height="10" rx="2" stroke="#a004b0" strokeWidth="1.5"/></svg>
        </span>
        <b>SKU:</b> {product.sku || "N/A"}
      </div>
      <div className={styles.infoRow}>
        <span className={styles.icon}>
          <svg width="20" height="20" fill="none" viewBox="0 0 20 20"><path d="M10 3.5a6.5 6.5 0 1 1 0 13a6.5 6.5 0 0 1 0-13zm0 2.5a4 4 0 1 0 0 8a4 4 0 0 0 0-8z" stroke="#a004b0" strokeWidth="1.5" fill="none"/></svg>
        </span>
        <b>Category:</b> {categories.length > 0 ? (
          categories.map((cat, idx) => (
            <React.Fragment key={cat.slug || cat.name || idx}>
              {cat.slug ? (
                <Link href={`/category/${cat.slug}`} className={styles.categoryLink}>{cat.name}</Link>
              ) : (
                <span className={styles.breadcrumbText}>{cat.name}</span>
              )}
              {idx < categories.length - 1 && <span>, </span>}
            </React.Fragment>
          ))
        ) : (
          <span className={styles.breadcrumbText}>N/A</span>
        )}
      </div>
      {Array.isArray(product.tags) && product.tags.length > 0 && (
        <div className={styles.infoRow}>
          <span className={styles.icon}>
            <svg width="20" height="20" fill="none" viewBox="0 0 20 20"><path d="M3 10l7-7 7 7-7 7-7-7z" stroke="#a004b0" strokeWidth="1.5" fill="none"/><circle cx="10" cy="10" r="2.5" fill="#a004b0"/></svg>
          </span>
          <b>Tags:</b> {product.tags.map((tag: any, idx: number) => {
            const tagObj = typeof tag === 'string' ? { name: tag } : tag;
            return tagObj?.slug ? (
              <Link key={tagObj.slug} href={`/product-tag/${tagObj.slug}`} className={styles.tagLink}>{tagObj.name}</Link>
            ) : (
              <span key={tagObj.name || idx} className={styles.tagLink}>{tagObj.name}</span>
            );
          })}
        </div>
      )}
      <div className={styles.badgeRow}>
        <span className={styles.badge}>
          <span className={styles.icon}>
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="9" stroke="#a004b0" strokeWidth="1.5"/><path d="M6.5 10.5l2.5 2.5 4.5-5" stroke="#a004b0" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/></svg>
          </span>
          100% Authentic Product
        </span>
        <span className={styles.badge}>
          <span className={styles.icon}>
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><rect x="3" y="5" width="14" height="10" rx="2" stroke="#a004b0" strokeWidth="1.5"/><path d="M7 10l2 2 4-4" stroke="#a004b0" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/></svg>
          </span>
          Easy Returns Policy
        </span>
        <span className={styles.badge}>
          <span className={styles.icon}>
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><rect x="2.5" y="7" width="15" height="6" rx="2" stroke="#a004b0" strokeWidth="1.5"/><path d="M17.5 10h1.5M2.5 10H1" stroke="#a004b0" strokeWidth="1.2" strokeLinecap="round"/><circle cx="5.5" cy="14" r="1" fill="#a004b0"/><circle cx="14.5" cy="14" r="1" fill="#a004b0"/></svg>
          </span>
          Free Delivery on Any Order
        </span>
        <span className={styles.badge}>
          <span className={styles.icon}>
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><ellipse cx="10" cy="10" rx="8" ry="7" stroke="#a004b0" strokeWidth="1.5"/><path d="M7.5 13c.5-1 2.5-1 3 0M8 8c0-1.5 4-1.5 4 0" stroke="#a004b0" strokeWidth="1.2" strokeLinecap="round"/><circle cx="7.5" cy="9" r=".7" fill="#a004b0"/><circle cx="12.5" cy="9" r=".7" fill="#a004b0"/></svg>
          </span>
          Cruelty-Free
        </span>
      </div>
    </div>
  );
}
