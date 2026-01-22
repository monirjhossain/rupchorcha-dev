"use client";
// ProductActions: Add to bag, app price, wishlist, share
import React from "react";
import styles from "./ProductActions.module.css";
import { useCart } from "@/app/common/CartContext";
import { useWishlist } from "@/app/components/WishlistContext";
import { useFeatureAccess } from "@/src/hooks/useFeatureAccess";
import { openLoginModal } from "@/src/utils/loginModal";
import { FaHeart, FaRegHeart } from "react-icons/fa";
import { useRouter } from "next/navigation";
import type { Product } from "./types";

export default function ProductActions({ product }: { product: Product }) {
  const router = useRouter();
  const { addToCart } = useCart();
  const { canAccessWishlist } = useFeatureAccess();
  const { isInWishlist, addToWishlist, removeFromWishlist, loading: wishlistLoading, isAuthenticated } = useWishlist();
  const [adding, setAdding] = React.useState(false);
  const [wishlistError, setWishlistError] = React.useState<string | null>(null);

  const handleAddToCart = async () => {
    setAdding(true);
    addToCart({ product_id: product.id, quantity: 1, product });
    setTimeout(() => setAdding(false), 800); // Simulate feedback
  };

  const handleWishlist = async (e: React.MouseEvent) => {
    e.preventDefault();
    setWishlistError(null);

    // Check if feature is accessible
    if (!canAccessWishlist) {
      openLoginModal();
      return;
    }

    try {
      if (isInWishlist(product.id)) {
        await removeFromWishlist(product.id);
      } else {
        await addToWishlist(product.id);
      }
    } catch (err: any) {
      const message = err.message || "Wishlist action failed. Please login or try again.";
      setWishlistError(message);
      console.error("Wishlist error:", err);
      if (message.includes("Please login") || message.includes("login")) {
        setTimeout(() => openLoginModal(), 2000);
      }
    }
  };

  return (
    <div className={styles.actions}>
      <button
        className={styles.addToCartBtn}
        style={adding ? { cursor: "not-allowed", opacity: 0.7 } : {}}
        onClick={handleAddToCart}
        disabled={adding}
      >
        {adding ? "Adding..." : "Add to bag"}
      </button>
      <button className={styles.appPriceBtn}>
        App Price: ৳{product.app_price || product.price}
        <span style={{ fontSize: 18, marginLeft: 2 }}>→</span>
      </button>
      {/* Wishlist & Share icons */}
      <button
        className={styles.iconBtn + ' ' + styles.iconHeart}
        onClick={handleWishlist}
        disabled={wishlistLoading}
        aria-label={isInWishlist(product.id) ? "Remove from wishlist" : "Add to wishlist"}
        title={isInWishlist(product.id) ? "Remove from wishlist" : "Add to wishlist"}
      >
        {wishlistLoading ? (
          <span style={{ fontSize: 12, fontWeight: "bold" }}>...</span>
        ) : isInWishlist(product.id) ? (
          <FaHeart size={20} color="#e53935" />
        ) : (
          <FaRegHeart size={20} color="#e53935" />
        )}
      </button>
      <button className={styles.iconBtn + ' ' + styles.iconShare}>⤴</button>
      {wishlistError && (
        <span style={{ color: "red", fontSize: 13, marginLeft: 8 }}>{wishlistError}</span>
      )}
    </div>
  );
}
