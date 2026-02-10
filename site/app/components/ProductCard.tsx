"use client";
import React, { useState } from "react";
import { useWishlist } from "./WishlistContext";
import { useFeatureAccess } from "@/src/hooks/useFeatureAccess";
import { openLoginModal } from "@/src/utils/loginModal";
import { FaHeart, FaRegHeart, FaTimes } from "react-icons/fa";
import Link from "next/link";
import { useRouter } from "next/navigation";
import styles from "./ProductCard.module.css";

interface ProductCardProps {
  product: any;
  onAddToCart: (product: any) => void;
  isAddingToCart?: boolean;
  showRemoveButton?: boolean;
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

const ProductCard: React.FC<ProductCardProps> = ({ product, onAddToCart, isAddingToCart = false, showRemoveButton = false }) => {
  const [hovered, setHovered] = useState(false);
  const router = useRouter();
  const { canAccessWishlist } = useFeatureAccess();
  
  // Guard clause for missing product
  if (!product) return null;

  const images = Array.isArray(product.images) ? product.images : [];
  const imageUrl = getImageUrl({ ...product, images }, 0);
  const secondImageUrl = images[1] ? getImageUrl({ ...product, images }, 1) : null;
  const brand = product.brand?.name || product.brand_name;
  
  // Determine Product URL
  const productUrl = product.slug ? `/product/${product.slug}` : `/product/${product.id}`;

  // Discount calculation
  const price = Number((product.price || "").toString().replace(/[^\d.]/g, ""));
  const sale = Number((product.discount_price || product.sale_price || "").toString().replace(/[^\d.]/g, ""));
  const hasDiscount = !isNaN(price) && !isNaN(sale) && sale > 0 && sale < price;
  const discountPercent = hasDiscount ? Math.round(((price - sale) / price) * 100) : 0;

  const rating = product.rating || product.average_rating || 0;
  const reviewCount = product.reviews_count || product.total_reviews || 0;
  const isNew = product.is_new || false;

  const { isInWishlist, addToWishlist, removeFromWishlist } = useWishlist();
  const [wishlistError, setWishlistError] = useState<string | null>(null);

  const handleWishlist = async (e: React.MouseEvent) => {
    e.stopPropagation(); // Stop bubbling to card click
    e.preventDefault(); // Prevent default button behavior
    setWishlistError(null);

    if (!canAccessWishlist) {
      openLoginModal();
      return;
    }

    try {
      if (isInWishlist(product.id)) {
        removeFromWishlist(product.id).catch((err: any) => {
          setWishlistError(err.message || "Failed to remove");
        });
      } else {
        addToWishlist(product.id).catch((err: any) => {
          setWishlistError(err.message || "Failed to add");
          if (err.message && (err.message.includes("Please login") || err.message.includes("login"))) {
            setTimeout(() => openLoginModal(), 2000);
          }
        });
      }
    } catch (err: any) {
      setWishlistError(err.message || "Action failed");
    }
  };

  const handleCardClick = (e: React.MouseEvent) => {
    // If the click was on a link or button, or inside one, do nothing (let default behavior happen)
    const target = e.target as HTMLElement;
    if (target.closest('a') || target.closest('button')) {
      return;
    }
    
    // Otherwise, programmatic navigation
    router.push(productUrl);
  };

  return (
    <div className={styles.productCard} onClick={handleCardClick} style={{ cursor: 'pointer' }}>
      {wishlistError && (
        <div style={{ color: 'red', fontSize: 13, marginBottom: 4 }}>{wishlistError}</div>
      )}
      
      {/* Top-right quick add-to-cart */}
      <button
        className={styles.quickAddMobile}
        aria-label="Add to cart"
        onClick={(e) => {
          e.preventDefault();
          e.stopPropagation();
          onAddToCart(product);
        }}
      >
        <svg
          className={styles.quickAddIcon}
          viewBox="0 0 24 24"
          aria-hidden="true"
        >
          <path
            d="M4 5h2l1.2 7.2A2 2 0 0 0 9.18 14h7.64a2 2 0 0 0 1.98-1.8L20 8H7"
            fill="none"
            stroke="currentColor"
            strokeWidth="1.8"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
          <circle cx="10" cy="19" r="1.3" fill="currentColor" />
          <circle cx="17" cy="19" r="1.3" fill="currentColor" />
        </svg>
      </button>

      {/* Cross remove button next to quick add-to-cart on wishlist cards */}
      {showRemoveButton && (
        <button
          type="button"
          className={styles.removeBtnTop}
          onClick={(e) => {
            e.preventDefault();
            e.stopPropagation();
            removeFromWishlist(product.id);
          }}
          aria-label="Remove from wishlist"
        >
          <FaTimes size={10} />
        </button>
      )}

      {/* Top-right wishlist heart (hidden in wishlist view) */}
      {!showRemoveButton && (
        <button
          className={styles.wishlistBtn}
          aria-label={isInWishlist(product.id) ? "Remove from wishlist" : "Add to wishlist"}
          onClick={handleWishlist}
          style={{ position: "absolute", top: 14, right: 14, background: "none", border: "none", cursor: "pointer", zIndex: 10 }}
        >
          {isInWishlist(product.id) ? (
            <FaHeart color="#e91e63" size={24} />
          ) : (
            <FaRegHeart color="#e91e63" size={24} />
          )}
        </button>
      )}

      {/* Badges */}
      <div className={styles.badgeContainer}>
        {isNew && <span className={styles.newBadge}>NEW</span>}
        {hasDiscount && discountPercent > 0 && <span className={styles.saleBadge}>-{discountPercent}%</span>}
      </div>

      {/* Product Image - LINKED */}
      <Link 
        href={productUrl} 
        className={styles.productImageLink}
        prefetch={false} // Disable prefetch to avoid potential issues, or enable if stable
        draggable={false}
      >
        <img
          src={hovered && secondImageUrl && secondImageUrl !== imageUrl ? secondImageUrl : imageUrl}
          alt={product.name || "Product Image"}
          className={styles.productImage}
          onMouseEnter={() => setHovered(true)}
          onMouseLeave={() => setHovered(false)}
        />
      </Link>

      {/* Product Info */}
      <div className={styles.productInfo}>
        {/* Brand */}
        {brand && (
          <Link
            href={product.brand?.slug ? `/brands/${product.brand.slug}` : (brand ? `/brands/${encodeURIComponent(brand)}` : '#')}
            className={styles.brandName}
            onClick={(e) => e.stopPropagation()} // Stop propagation for brand link specifically
          >
            {brand}
          </Link>
        )}

        {/* Title - LINKED */}
        <Link 
          href={productUrl}
          className={styles.productTitleLink}
        >
          <h3 className={styles.productTitle}>{product.name}</h3>
        </Link>

        {/* Rating & review count */}
        <div className={styles.rating}>
          {Array.from({ length: 5 }).map((_, i) => (
            <svg
              key={i}
              width="14"
              height="14"
              viewBox="0 0 20 20"
              fill={i < Math.round(rating) ? "#ffb400" : "#eee"}
            >
              <polygon points="10,1.5 12.6,7.2 18.8,7.6 14,12 15.2,18.2 10,15 4.8,18.2 6,12 1.2,7.6 7.4,7.2" />
            </svg>
          ))}
          <span className={styles.reviewCount}>
            {rating ? rating.toFixed(1) : "0.0"}
            {" "}
            <span className={styles.reviewTotal}>
              ({reviewCount || 0})
            </span>
          </span>
        </div>

        {/* Price */}
        <div className={styles.productPrice}>
          {hasDiscount ? (
            <>
              <span className={styles.salePrice}>৳{Math.round(sale)}</span>
              <span className={styles.regularPrice}>৳{Math.round(price)}</span>
            </>
          ) : (
            <span className={styles.salePrice}>৳{Math.round(price)}</span>
          )}
        </div>

        {/* (Hidden) bottom Add to Cart button, unused in current layout */}
        <button
          className={`${styles.addToCartBtn} ${isAddingToCart ? styles.loading : ""}`}
          onClick={(e) => {
            e.preventDefault();
            e.stopPropagation();
            onAddToCart(product);
          }}
          disabled={isAddingToCart}
        >
          {isAddingToCart ? "Adding..." : "Add to Cart"}
        </button>
      </div>
    </div>
  );
};

export default ProductCard;
