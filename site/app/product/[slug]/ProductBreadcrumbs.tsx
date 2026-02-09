"use client";
import React from "react";
import type { Product } from "./types";
import Link from "next/link";
import styles from "./ProductInfo.module.css";

interface Props {
  product: Product;
}

const ProductBreadcrumbs: React.FC<Props> = ({ product }) => {
  let categories: { name: string; slug?: string }[] = [];
  if (Array.isArray(product.categories) && product.categories.length > 0) {
    categories = product.categories.filter((cat: any) => !!cat && !!cat.name);
  } else if (product.category?.name) {
    categories = [product.category];
  } else if (product.category_name) {
    categories = [{ name: product.category_name, slug: product.category_slug }];
  }

  return (
    <nav className={styles.breadcrumbs} aria-label="breadcrumb">
      <Link href="/" className={styles.breadcrumbLink}>Home</Link>
      <span className={styles.breadcrumbSeparator}>/</span>
      {categories.length > 0 ? (
        categories.map((cat, idx) => (
          <React.Fragment key={cat.slug || cat.name || idx}>
            {cat.slug ? (
              <a href={`/category/${cat.slug}`} className={styles.breadcrumbLink}>{cat.name}</a>
            ) : (
              <span className={styles.breadcrumbText}>{cat.name}</span>
            )}
            {idx < categories.length - 1 && <span className={styles.breadcrumbSeparator}>,</span>}
          </React.Fragment>
        ))
      ) : (
        <span className={styles.breadcrumbText}>Category</span>
      )}
      <span className={styles.breadcrumbSeparator}>/</span>
      {product.brand?.slug ? (
        <a href={`/brand/${product.brand.slug}`} className={styles.breadcrumbLink}>{product.brand.name}</a>
      ) : (
        <span className={styles.breadcrumbText}>{product.brand?.name || product.brand_name || "Brand"}</span>
      )}
      <span className={styles.breadcrumbSeparator}>/</span>
      <span className={styles.breadcrumbCurrent}>{product.name}</span>
    </nav>
  );
};

export default ProductBreadcrumbs;
