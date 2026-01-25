"use client";
import React, { useState } from "react";
import { useWishlist } from "./WishlistContext";
import { useFeatureAccess } from "@/src/hooks/useFeatureAccess";
import { openLoginModal } from "@/src/utils/loginModal";
import { FaHeart, FaRegHeart } from "react-icons/fa";
import Link from "next/link";
import { useRouter } from "next/navigation";
import styles from "./ProductCard.module.css";

interface ProductCardProps {
  product: any;
  onAddToCart: (product: any) => void;
  isAddingToCart?: boolean;
}

const backendBase = "http://localhost:8000";


const getImageUrl = (product: any, idx = 0): string => {
  if (product.images?.length && product.images[idx]) {
    const img = product.images[idx].url || product.images[idx].path || product.images[idx];
    if (typeof img === "string" && img) {
      return img.startsWith("http") ? img : `${backendBase}/storage/${img.replace(/^storage[\\/]/, "")}`;
    }
  }
  if (typeof product.main_image === "string" && product.main_image) {
    return product.main_image.startsWith("http")
      ? product.main_image
      : `${backendBase}/storage/${product.main_image.replace(/^storage[\\/]/, "")}`;
  }
  if (typeof product.image === "string" && product.image) {
    return product.image.startsWith("http")
      ? product.image
      : `${backendBase}/storage/${product.image.replace(/^storage[\\/]/, "")}`;
  }
  return "https://via.placeholder.com/300x300/f0f0f0/999?text=No+Image";
};


const ProductCard: React.FC<ProductCardProps> = ({ product, onAddToCart, isAddingToCart = false }) => {
  const [hovered, setHovered] = useState(false);
  const [wishlistLoading, setWishlistLoading] = useState(false);
  const router = useRouter();
  const { canAccessWishlist } = useFeatureAccess();
  const images = Array.isArray(product.images) ? product.images : [];
  const imageUrl = getImageUrl({ ...product, images }, 0);
  const secondImageUrl = images[1] ? getImageUrl({ ...product, images }, 1) : null;
  const brand = product.brand?.name || product.brand_name;

  // Discount calculation (robust against NaN and string numbers)
  const price = Number((product.price || "").toString().replace(/[^\d.]/g, ""));
  const sale = Number((product.sale_price || "").toString().replace(/[^\d.]/g, ""));
  const hasDiscount = !isNaN(price) && !isNaN(sale) && sale > 0 && sale < price;
  const discountPercent = hasDiscount ? Math.round(((price - sale) / price) * 100) : 0;

  const rating = product.rating || product.average_rating || 0;
  const ratingCount = product.rating_count || product.reviews_count || 0;

  const isNew = product.is_new || false;

  const { isInWishlist, addToWishlist, removeFromWishlist } = useWishlist();
  const [wishlistError, setWishlistError] = useState<string | null>(null);

  const handleWishlist = async (e: React.MouseEvent) => {
    e.stopPropagation();
    e.preventDefault();
    setWishlistError(null);

    // Check if feature is accessible
    if (!canAccessWishlist) {
      openLoginModal();
      return;
    }

    // Optimistic update - update UI immediately
    try {
      if (isInWishlist(product.id)) {
        removeFromWishlist(product.id).catch((err: any) => {
          const message = err.message || "Failed to remove from wishlist";
          setWishlistError(message);
          console.error("Wishlist error:", err);
        });
      } else {
        addToWishlist(product.id).catch((err: any) => {
          const message = err.message || "Failed to add to wishlist";
          setWishlistError(message);
          console.error("Wishlist error:", err);
          if (message.includes("Please login") || message.includes("login")) {
            setTimeout(() => openLoginModal(), 2000);
          }
        });
      }
    } catch (err: any) {
      const message = err.message || "Wishlist action failed. Please login or try again.";
      setWishlistError(message);
      console.error("Wishlist error:", err);
    }
  };

  return (
    <div className={styles.productCard}>
      {wishlistError && (
        <div style={{ color: 'red', fontSize: 13, marginBottom: 4 }}>{wishlistError}</div>
      )}
      <button
        className={styles.wishlistBtn}
        aria-label={isInWishlist(product.id) ? "Remove from wishlist" : "Add to wishlist"}
        onClick={handleWishlist}
        style={{ position: "absolute", top: 14, right: 14, background: "none", border: "none", cursor: "pointer", zIndex: 2 }}
      >
        {isInWishlist(product.id) ? (
          <FaHeart color="#e91e63" size={24} />
        ) : (
          <FaRegHeart color="#e91e63" size={24} />
        )}
      </button>
      {/* Badges */}
      <div className={styles.badgeContainer}>
        {isNew && <span className={styles.newBadge}>NEW</span>}
        {hasDiscount && discountPercent > 0 && (
          <span className={styles.saleBadge}>-{discountPercent}%</span>
        )}
      </div>
      {/* Product image */}
      <Link 
        href={product.slug ? `/product/${product.slug}` : `/product/${product.id}`} 
        className={styles.productImageLink}
        prefetch={true}
      >
        <img
          src={hovered && secondImageUrl && secondImageUrl !== imageUrl ? secondImageUrl : imageUrl}
          alt={product.name || "No Image Available"}
          className={styles.productImage}
          onMouseEnter={() => setHovered(true)}
          onMouseLeave={() => setHovered(false)}
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
            prefetch={true}
          >
            {brand}
          </Link>
        )}
        {/* Title */}
        <Link 
          href={product.slug ? `/product/${product.slug}` : `/product/${product.id}`}
          prefetch={true}
        >
          <h3 className={styles.productTitle}>{product.name}</h3>
        </Link>
        {/* Rating */}
        <div className={styles.rating}>
          {Array.from({ length: 5 }).map((_, i) => (
            <svg key={i} width="14" height="14" viewBox="0 0 20 20" fill={i < Math.round(rating) ? '#ffb400' : '#eee'}><polygon points="10,1.5 12.6,7.2 18.8,7.6 14,12 15.2,18.2 10,15 4.8,18.2 6,12 1.2,7.6 7.4,7.2"/></svg>
          ))}
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
