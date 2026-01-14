
"use client";
import React, { useState } from "react";
import Link from "next/link";
import styles from "./ProductCard.module.css";

interface ProductCardProps {
  product: any;
  onAddToCart: (product: any) => void;
  isAddingToCart?: boolean;
}

const backendBase = "http://localhost:8000";

const getImageUrl = (product: any): string => {
  if (product.images?.length) {
    const img = product.images[0].url || product.images[0].path;
    if (img) return img.startsWith("http") ? img : `${backendBase}/storage/${img.replace(/^storage[\\/]/, "")}`;
  }
  if (product.main_image) {
    return product.main_image.startsWith("http")
      ? product.main_image
      : `${backendBase}/storage/${product.main_image.replace(/^storage[\\/]/, "")}`;
  }
  if (product.image) {
    return product.image.startsWith("http")
      ? product.image
      : `${backendBase}/storage/${product.image.replace(/^storage[\\/]/, "")}`;
  }
  return "https://via.placeholder.com/300x300/f0f0f0/999?text=No+Image";
};


const ProductCard: React.FC<ProductCardProps> = ({ product, onAddToCart, isAddingToCart = false }) => {
  const imageUrl = getImageUrl(product);
  const brand = product.brand?.name || product.brand_name;

  // Wishlist removed

  // Discount calculation (robust against NaN and string numbers)
  const price = Number((product.price || "").toString().replace(/[^\d.]/g, ""));
  const sale = Number((product.sale_price || "").toString().replace(/[^\d.]/g, ""));
  const hasDiscount = !isNaN(price) && !isNaN(sale) && sale > 0 && sale < price;
  const discountPercent = hasDiscount ? Math.round(((price - sale) / price) * 100) : 0;
  // Debug print
  console.log("Product price debug:", product.price, product.sale_price, price, sale, hasDiscount);

  // Rating (optional, fallback to 0)
  const rating = product.rating || product.average_rating || 0;
  const ratingCount = product.rating_count || product.reviews_count || 0;

  // Badges
  const isNew = product.is_new || false; // You can set this from backend

  return (
    <div className={styles.productCard}>
      {/* Badges */}
      <div className={styles.badgeContainer}>
        {isNew && <span className={styles.newBadge}>NEW</span>}
        {hasDiscount && discountPercent > 0 && (
          <span className={styles.saleBadge}>-{discountPercent}%</span>
        )}
      </div>
      {/* Product image */}
      <Link href={`/product/${product.id}`} className={styles.productImageLink}>
        <img
          src={imageUrl}
          alt={product.name || "No Image Available"}
          className={styles.productImage}
        />
      </Link>
      {/* Product info */}
      <div className={styles.productInfo}>
        {/* Brand (show if present, as link) */}
        {brand && (
          <Link
            href={product.brand?.slug ? `/brands/${product.brand.slug}` : (brand ? `/brands/${encodeURIComponent(brand)}` : '#')}
            className={styles.brandName}
            title={brand}
          >
            {brand}
          </Link>
        )}
        {/* Title */}
        <Link href={`/product/${product.id}`}>
          <h3 className={styles.productTitle}>{product.name}</h3>
        </Link>
        {/* Rating */}
        <div className={styles.rating}>
          {Array.from({ length: 5 }).map((_, i) => (
            <svg key={i} width="16" height="16" viewBox="0 0 20 20" fill={i < Math.round(rating) ? '#ffb400' : '#eee'}><polygon points="10,1.5 12.6,7.2 18.8,7.6 14,12 15.2,18.2 10,15 4.8,18.2 6,12 1.2,7.6 7.4,7.2"/></svg>
          ))}
          {ratingCount > 0 && <span className={styles.ratingCount}>({ratingCount})</span>}
        </div>
        {/* Price: always show both if sale price exists */}
        <div className={styles.productPrice}>
          {hasDiscount ? (
            <>
              <span className={styles.salePrice}>৳ {Math.round(sale)}</span>
              <span className={styles.regularPrice}>৳ {Math.round(price)}</span>
            </>
          ) : (
            <span className={styles.salePrice}>৳ {Math.round(price)}</span>
          )}
        </div>
        {/* Cart Button */}
        <button
          className={`${styles.addToCartBtn} ${isAddingToCart ? styles.loading : ""}`}
          onClick={() => onAddToCart(product)}
          disabled={isAddingToCart}
        >
          {isAddingToCart ? "Adding..." : "Add to Cart"}
        </button>
      </div>
    </div>
  );
};

export default ProductCard;
