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
  const { addToCart, items } = useCart();
  const { canAccessWishlist } = useFeatureAccess();
  const { isInWishlist, addToWishlist, removeFromWishlist, loading: wishlistLoading } = useWishlist();
  const [adding, setAdding] = React.useState(false);
  const [buying, setBuying] = React.useState(false);
  const [wishlistError, setWishlistError] = React.useState<string | null>(null);

  const handleAddToCart = async () => {
    setAdding(true);
    try {
      await addToCart({ product_id: product.id, quantity: 1, product });
    } finally {
      setAdding(false);
    }
  };

  const handleBuyNow = async () => {
    if (buying) return;
    setBuying(true);
    try {
      const alreadyInCart = items.some(item => item.product_id === product.id);
      if (!alreadyInCart) {
        await addToCart({ product_id: product.id, quantity: 1, product });
      }
      router.push("/checkout");
    } catch (err) {
      console.error("Buy Now failed", err);
    } finally {
      setBuying(false);
    }
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
        disabled={adding || buying}
      >
        {adding ? "Adding..." : "Add to bag"}
      </button>
      <button
        className={styles.appPriceBtn}
        onClick={handleBuyNow}
        disabled={buying}
        aria-label="Buy now"
      >
        {buying ? "Processing..." : "Buy Now"}
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
