"use client";
import React from "react";
import {
  FaHeart,
  FaRegHeart,
  FaLink,
  FaFacebookF,
  FaInstagram,
  FaYoutube,
  FaWhatsapp,
  FaFacebookMessenger,
} from "react-icons/fa";
import { useWishlist } from "@/app/components/WishlistContext";
import { useFeatureAccess } from "@/src/hooks/useFeatureAccess";
import { openLoginModal } from "@/src/utils/loginModal";
import type { Product } from "./types";
import styles from "./ProductSideActions.module.css";

interface Props {
  product: Product;
}

const ProductSideActions: React.FC<Props> = ({ product }) => {
  const { isInWishlist, addToWishlist, removeFromWishlist, loading: wishlistLoading } = useWishlist();
  const { canAccessWishlist } = useFeatureAccess();
  const [wishlistError, setWishlistError] = React.useState<string | null>(null);
  const [shareOpen, setShareOpen] = React.useState(false);
  const [copied, setCopied] = React.useState(false);

  const handleWishlist = async (e: React.MouseEvent) => {
    e.preventDefault();
    setWishlistError(null);

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
      const message = err?.message || "Wishlist action failed. Please login or try again.";
      setWishlistError(message);
      if (message.toLowerCase().includes("login")) {
        setTimeout(() => openLoginModal(), 2000);
      }
    }
  };

  const shareUrl =
    typeof window !== "undefined"
      ? window.location.href
      : `${process.env.NEXT_PUBLIC_SITE_URL || ""}/product/${product.slug || product.id}`;

  const handleCopy = async () => {
    try {
      await navigator.clipboard.writeText(shareUrl);
      setCopied(true);
      setTimeout(() => setCopied(false), 2000);
    } catch {
      setCopied(false);
    }
  };

  const handlePlatformShare = async (
    platform: "facebook" | "instagram" | "youtube" | "whatsapp" | "messenger"
  ) => {
    if (typeof window === "undefined") return;

    const encoded = encodeURIComponent(shareUrl);
    let url: string | null = null;

    switch (platform) {
      case "facebook":
        url = `https://www.facebook.com/sharer/sharer.php?u=${encoded}`;
        break;
      case "whatsapp":
        url = `https://api.whatsapp.com/send?text=${encoded}`;
        break;
      case "messenger":
        // Simple fallback: use Facebook share as Messenger alternative
        url = `https://www.facebook.com/sharer/sharer.php?u=${encoded}`;
        break;
      case "instagram":
      case "youtube":
        // No direct web share URL for these; use Web Share API or copy
        break;
    }

    if (url) {
      window.open(url, "_blank", "noopener,noreferrer");
      return;
    }

    if (typeof navigator !== "undefined" && (navigator as any).share) {
      try {
        await (navigator as any).share({ url: shareUrl });
        return;
      } catch {
        // fall through to copy
      }
    }

    handleCopy();
  };

  return (
    <>
      <div className={styles.sideActions}>
        <button
          type="button"
          className={styles.sideButton}
          onClick={handleWishlist}
          disabled={wishlistLoading}
          aria-label={isInWishlist(product.id) ? "Remove from wishlist" : "Add to wishlist"}
        >
          {wishlistLoading ? (
            <span className={styles.loadingDots}>...</span>
          ) : isInWishlist(product.id) ? (
            <FaHeart size={18} color="#e91e63" />
          ) : (
            <FaRegHeart size={18} color="#e91e63" />
          )}
        </button>

        <button
          type="button"
          className={styles.sideButton}
          onClick={() => setShareOpen(true)}
          aria-label="Share product"
        >
          <FaLink size={16} color="#e91e63" />
        </button>
      </div>

      {wishlistError && (
        <div className={styles.errorText}>{wishlistError}</div>
      )}

      {shareOpen && (
        <div className={styles.shareOverlay} onClick={() => setShareOpen(false)}>
          <div className={styles.shareModal} onClick={(e) => e.stopPropagation()}>
            <div className={styles.shareHeader}>
              <h3 className={styles.shareTitle}>Social Share</h3>
              <button
                type="button"
                className={styles.closeBtn}
                onClick={() => setShareOpen(false)}
                aria-label="Close share"
              >
                ×
              </button>
            </div>
            <p className={styles.shareSubtitle}>Copy and Share</p>
            <div className={styles.linkRow}>
              <input
                className={styles.linkInput}
                readOnly
                value={shareUrl}
                onFocus={(e) => e.currentTarget.select()}
              />
            </div>
            <button
              type="button"
              className={styles.copyBtn}
              onClick={handleCopy}
            >
              {copied ? "Copied" : "Copy"}
            </button>
            <div className={styles.iconRow}>
              <button
                type="button"
                className={styles.socialIcon}
                style={{ background: "#1877f2" }}
                onClick={() => handlePlatformShare("facebook")}
                aria-label="Share to Facebook"
              >
                <FaFacebookF size={16} />
              </button>
              <button
                type="button"
                className={styles.socialIcon}
                style={{ background: "#e1306c" }}
                onClick={() => handlePlatformShare("instagram")}
                aria-label="Share via Instagram"
              >
                <FaInstagram size={16} />
              </button>
              <button
                type="button"
                className={styles.socialIcon}
                style={{ background: "#ff0000" }}
                onClick={() => handlePlatformShare("youtube")}
                aria-label="Share via YouTube"
              >
                <FaYoutube size={16} />
              </button>
              <button
                type="button"
                className={styles.socialIcon}
                style={{ background: "#25D366" }}
                onClick={() => handlePlatformShare("whatsapp")}
                aria-label="Share to WhatsApp"
              >
                <FaWhatsapp size={17} />
              </button>
              <button
                type="button"
                className={styles.socialIcon}
                style={{ background: "#0084ff" }}
                onClick={() => handlePlatformShare("messenger")}
                aria-label="Share via Messenger"
              >
                <FaFacebookMessenger size={18} />
              </button>
            </div>
          </div>
        </div>
      )}
    </>
  );
};

export default ProductSideActions;
