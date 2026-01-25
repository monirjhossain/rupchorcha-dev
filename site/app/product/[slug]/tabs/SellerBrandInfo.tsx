"use client";
import React, { useEffect, useState } from "react";
import type { Product } from "../types";
import styles from "./SellerBrandInfo.module.css";
import Link from "next/link";

interface SellerBrandInfoProps {
  product: Product;
}

export default function SellerBrandInfo({ product }: SellerBrandInfoProps) {
  const [brandProducts, setBrandProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);

  const brand = product.brand;
  const brandName = brand?.name || product.brand_name || "Unknown Brand";
  const brandSlug = brand?.slug || product.brand_slug;

  useEffect(() => {
    if (!brandSlug) {
      setLoading(false);
      return;
    }
    
    const API_BASE = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api";
    fetch(`${API_BASE}/brands/${brandSlug}?per_page=4`)
      .then(res => res.json())
      .then(data => {
        const products = data.products?.data || data.products || [];
        setBrandProducts(products.filter((p: Product) => p.id !== product.id).slice(0, 4));
        setLoading(false);
      })
      .catch(() => {
        setBrandProducts([]);
        setLoading(false);
      });
  }, [brandSlug, product.id]);

  const getImageUrl = (prod: Product): string => {
    const API_BASE = "http://localhost:8000";
    if (Array.isArray(prod.images) && prod.images.length > 0) {
      const img = prod.images[0];
      if (typeof img === 'string') {
        return img.startsWith('http') ? img : `${API_BASE}/storage/${img.replace(/^storage[\\/]/, '')}`;
      }
    }
    return "https://via.placeholder.com/200x200/f0f0f0/999?text=No+Image";
  };

  return (
    <div className={styles.container}>
      <h2 className={styles.heading}>Brand Information</h2>

      {/* Brand Profile */}
      <div className={styles.brandProfile}>
        <div className={styles.brandHeader}>
          <div className={styles.brandIcon}>🏷️</div>
          <div>
            <h3 className={styles.brandName}>{brandName}</h3>
            <p className={styles.brandTagline}>Trusted Quality Products</p>
          </div>
        </div>
        <p className={styles.brandDescription}>
          {brandName} is committed to delivering high-quality products with excellent customer satisfaction.
          All products are authentic and come with manufacturer warranty.
        </p>
        {brandSlug && (
          <Link href={`/brands/${brandSlug}`} className={styles.visitShopBtn}>
            View All {brandName} Products →
          </Link>
        )}
      </div>

      {/* Brand Stats */}
      <div className={styles.statsGrid}>
        <div className={styles.statCard}>
          <div className={styles.statIcon}>⭐</div>
          <div className={styles.statValue}>4.8/5</div>
          <div className={styles.statLabel}>Average Rating</div>
        </div>
        <div className={styles.statCard}>
          <div className={styles.statIcon}>📦</div>
          <div className={styles.statValue}>5000+</div>
          <div className={styles.statLabel}>Products Sold</div>
        </div>
        <div className={styles.statCard}>
          <div className={styles.statIcon}>👥</div>
          <div className={styles.statValue}>2000+</div>
          <div className={styles.statLabel}>Happy Customers</div>
        </div>
      </div>

      {/* More from this brand */}
      {brandProducts.length > 0 && (
        <div className={styles.moreProducts}>
          <h3 className={styles.subheading}>More from {brandName}</h3>
          <div className={styles.productsGrid}>
            {brandProducts.map((prod) => (
              <Link
                key={prod.id}
                href={`/product/${prod.id}`}
                className={styles.productCard}
              >
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img
                  src={getImageUrl(prod)}
                  alt={prod.name}
                  className={styles.productImage}
                />
                <div className={styles.productInfo}>
                  <h4 className={styles.productName}>{prod.name}</h4>
                  <p className={styles.productPrice}>৳{prod.sale_price || prod.price}</p>
                </div>
              </Link>
            ))}
          </div>
        </div>
      )}

      {loading && (
        <div className={styles.loading}>Loading brand products...</div>
      )}
    </div>
  );
}
