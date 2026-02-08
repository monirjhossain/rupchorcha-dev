"use client";
import React, { useState } from "react";
import { useWishlist } from "../components/WishlistContext";
import { useCart } from "../common/CartContext";
import Link from "next/link";
import { FaTrash, FaShoppingCart, FaArrowLeft } from "react-icons/fa";
import { openLoginModal } from "@/src/utils/loginModal";

const WishlistPage: React.FC = () => {
  const { wishlist, loading, isAuthenticated, removeFromWishlist } = useWishlist();
  const { addToCart } = useCart();
  const [addingToCart, setAddingToCart] = useState<{ [key: number]: boolean }>({});
  const [removingItem, setRemovingItem] = useState<{ [key: number]: boolean }>({});
  const [showDeleteConfirm, setShowDeleteConfirm] = useState<number | null>(null);

  const handleAddToCart = async (product: any) => {
    if (!product || !product.id) return;
    setAddingToCart(prev => ({ ...prev, [product.id]: true }));
    try {
      await addToCart({ 
        product_id: product.id, 
        quantity: 1,
        product: product
      });
      // Remove from wishlist after adding to cart
      await removeFromWishlist(product.id);
    } catch (error) {
      console.error("Failed to add to cart:", error);
    } finally {
      setAddingToCart(prev => ({ ...prev, [product.id]: false }));
    }
  };

  const handleRemoveFromWishlist = async (productId: number) => {
    setRemovingItem(prev => ({ ...prev, [productId]: true }));
    try {
      await removeFromWishlist(productId);
      setShowDeleteConfirm(null);
    } catch (error) {
      console.error("Failed to remove from wishlist:", error);
    } finally {
      setRemovingItem(prev => ({ ...prev, [productId]: false }));
    }
  };

  const backendBase = "http://localhost:8000";

  const getImageUrl = (product: any): string => {
    if (product?.images?.length) {
      const img = product.images[0].url || product.images[0].path || product.images[0];
      if (typeof img === "string" && img) {
        return img.startsWith("http") ? img : `${backendBase}/storage/${img.replace(/^storage[\\/]/, "")}`;
      }
    }
    if (typeof product?.main_image === "string" && product.main_image) {
      return product.main_image.startsWith("http")
        ? product.main_image
        : `${backendBase}/storage/${product.main_image.replace(/^storage[\\/]/, "")}`;
    }
    if (typeof product?.image === "string" && product.image) {
      return product.image.startsWith("http")
        ? product.image
        : `${backendBase}/storage/${product.image.replace(/^storage[\\/]/, "")}`;
    }
    return "https://via.placeholder.com/250x250/f0f0f0/999?text=No+Image";
  };

  if (loading) {
    return (
      <div style={{ minHeight: "60vh", display: "flex", alignItems: "center", justifyContent: "center" }}>
        <div className="spinner" style={{ width: 50, height: 50 }} />
      </div>
    );
  }

  if (!isAuthenticated) {
    return (
      <div style={{ maxWidth: 1200, margin: "3rem auto", padding: "0 1rem", textAlign: "center" }}>
        <h1 style={{ fontSize: "2rem", fontWeight: 800, color: "#a004b0", marginBottom: "2rem" }}>My Wishlist</h1>
        <div style={{ background: "#f9f9f9", padding: "3rem 2rem", borderRadius: 12, marginBottom: "2rem" }}>
          <p style={{ fontSize: "1.1rem", color: "#666", marginBottom: "2rem" }}>
            Please log in to view your wishlist
          </p>
          <button
            onClick={() => openLoginModal()}
            style={{
              display: "inline-block",
              background: "#e91e63",
              color: "#fff",
              padding: "0.8rem 2rem",
              borderRadius: 8,
              fontWeight: 700,
              border: "none",
              fontSize: "1.05rem",
              cursor: "pointer",
              transition: "all 0.3s"
            }}
          >
            Sign In Now
          </button>
        </div>
      </div>
    );
  }

  if (wishlist.length === 0) {
    return (
      <div style={{ maxWidth: 1200, margin: "3rem auto", padding: "0 1rem" }}>
        <h1 style={{ fontSize: "2rem", fontWeight: 800, color: "#a004b0", marginBottom: "2rem" }}>My Wishlist</h1>
        <div style={{ background: "#f9f9f9", padding: "3rem 2rem", borderRadius: 12, textAlign: "center" }}>
          <div style={{ fontSize: "3.5rem", marginBottom: "1rem", color: "#ddd" }}>♡</div>
          <p style={{ fontSize: "1.2rem", color: "#666", marginBottom: "2rem", fontWeight: 600 }}>
            Your wishlist is empty
          </p>
          <p style={{ fontSize: "1rem", color: "#999", marginBottom: "2rem" }}>
            Start adding your favorite products to your wishlist!
          </p>
          <Link 
            href="/" 
            style={{
              display: "inline-flex",
              alignItems: "center",
              gap: "0.5rem",
              background: "#e91e63",
              color: "#fff",
              padding: "0.8rem 2rem",
              borderRadius: 8,
              fontWeight: 700,
              textDecoration: "none",
              fontSize: "1.05rem",
              transition: "all 0.3s"
            }}
          >
            <FaArrowLeft size={16} /> Continue Shopping
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div style={{ maxWidth: 1200, margin: "0 auto", padding: "2rem 1rem" }}>
      {/* Header */}
      <div style={{ marginBottom: "3rem" }}>
        <h1 style={{ fontSize: "2rem", fontWeight: 800, color: "#a004b0", marginBottom: "0.5rem" }}>
          My Wishlist
        </h1>
        <p style={{ color: "#666", fontSize: "1rem" }}>
          You have <strong style={{ color: "#e91e63" }}>{wishlist.length}</strong> item{wishlist.length !== 1 ? "s" : ""} in your wishlist
        </p>
      </div>

      {/* Wishlist Items Grid */}
      <div style={{
        display: "grid",
        gridTemplateColumns: "repeat(2, minmax(0, 1fr))",
        gap: "0.85rem",
        marginBottom: "2rem"
      }}>
        {wishlist.map((item) => {
          const product = item.product;
          const price = parseFloat(product?.sale_price || product?.price || 0);
          const originalPrice = parseFloat(product?.price || 0);
          const discount = originalPrice > price ? Math.round(((originalPrice - price) / originalPrice) * 100) : 0;
          const isAdding = addingToCart[product?.id];
          const isRemoving = removingItem[product?.id];
          const confirmingDelete = showDeleteConfirm === product?.id;

          return (
            <div
              key={item.product_id}
              style={{
                background: "#fff",
                borderRadius: 10,
                overflow: "hidden",
                boxShadow: "0 1px 4px rgba(15, 23, 42, 0.06)",
                border: "1px solid #f1f1f1",
                display: "flex",
                flexDirection: "column",
                position: "relative",
                minHeight: 220
              }}
            >
              {/* Product Image */}
              <Link 
                href={`/product/${product?.slug || product?.id}`} 
                style={{ 
                  position: "relative", 
                  overflow: "hidden", 
                  display: "block",
                  background: "linear-gradient(135deg, #fafafa 0%, #f0f0f0 100%)"
                }}
              >
                <img
                  src={getImageUrl(product)}
                  alt={product?.name}
                  style={{
                    width: "100%",
                    height: 140,
                    objectFit: "cover",
                    display: "block"
                  }}
                />
                {discount > 0 && (
                  <div style={{
                    position: "absolute",
                    top: 8,
                    left: 8,
                    background: "linear-gradient(135deg, #e91e63 0%, #c2185b 100%)",
                    color: "#fff",
                    padding: "2px 6px",
                    borderRadius: 5,
                    fontWeight: 600,
                    fontSize: "0.7rem",
                    boxShadow: "0 1px 4px rgba(233, 30, 99, 0.18)",
                    letterSpacing: "0.2px"
                  }}>
                    -{discount}% OFF
                  </div>
                )}
                {/* Remove button overlay */}
                <button
                  onClick={(e) => {
                    e.preventDefault();
                    setShowDeleteConfirm(confirmingDelete ? null : product?.id);
                  }}
                  disabled={isRemoving}
                  style={{
                    position: "absolute",
                    top: 10,
                    right: 10,
                    width: 32,
                    height: 32,
                    padding: 0,
                    background: confirmingDelete ? "#e91e63" : "rgba(255, 255, 255, 0.95)",
                    color: confirmingDelete ? "#fff" : "#e91e63",
                    border: "none",
                    borderRadius: "50%",
                    fontWeight: 700,
                    fontSize: "0.95rem",
                    cursor: isRemoving ? "default" : "pointer",
                    transition: "all 0.2s",
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "center",
                    boxShadow: "0 2px 8px rgba(0, 0, 0, 0.12)",
                    backdropFilter: "blur(10px)"
                  }}
                  title="Remove from wishlist"
                  onMouseEnter={undefined}
                  onMouseLeave={undefined}
                >
                  {isRemoving ? "..." : <FaTrash size={14} />}
                </button>
              </Link>

              {/* Product Info */}
              <div style={{ padding: "0.7rem 0.8rem 0.85rem 0.8rem", flex: 1, display: "flex", flexDirection: "column" }}>
                {/* Brand */}
                {product?.brand?.name && (
                  <div style={{ 
                    fontSize: "0.8rem", 
                    color: "#999", 
                    textTransform: "uppercase", 
                    letterSpacing: "1px",
                    fontWeight: 600,
                    marginBottom: "0.5rem" 
                  }}>
                    {product.brand.name}
                  </div>
                )}

                {/* Name */}
                <Link href={`/product/${product?.slug || product?.id}`} style={{ textDecoration: "none" }}>
                  <h3 style={{
                    fontSize: "0.9rem",
                    fontWeight: 600,
                    color: "#111827",
                    marginBottom: 4,
                    lineHeight: 1.3,
                    display: "-webkit-box",
                    WebkitLineClamp: 2,
                    WebkitBoxOrient: "vertical",
                    overflow: "hidden",
                    cursor: "pointer"
                  }}>
                    {product?.name}
                  </h3>
                </Link>

                {/* Price */}
                <div style={{ display: "flex", alignItems: "center", gap: 6, marginBottom: 10 }}>
                  <span style={{ fontWeight: 800, color: "#e91e63", fontSize: "0.98rem" }}>৳ {price.toFixed(2)}</span>
                  {originalPrice > price && (
                    <span style={{ textDecoration: "line-through", color: "#9ca3af", fontSize: "0.8rem" }}>৳ {originalPrice.toFixed(2)}</span>
                  )}
                </div>

                {/* Add to Cart Button (minimal) */}
                <div style={{ marginTop: "auto" }}>
                  <button
                    onClick={() => handleAddToCart(product)}
                    disabled={isAdding}
                    style={{
                      width: "100%",
                      display: "inline-flex",
                      alignItems: "center",
                      justifyContent: "center",
                      gap: 6,
                      padding: "0.45rem 0.6rem",
                      background: isAdding ? "#9ca3af" : "#111827",
                      color: "#fff",
                      border: "none",
                      borderRadius: 999,
                      fontWeight: 600,
                      fontSize: "0.85rem",
                      cursor: isAdding ? "default" : "pointer",
                      boxShadow: "0 3px 10px rgba(15, 23, 42, 0.25)",
                      transition: "background 0.2s, box-shadow 0.2s"
                    }}
                  >
                    {isAdding ? (
                      "Adding..."
                    ) : (
                      <>
                        <FaShoppingCart size={13} />
                        <span>Add to Cart</span>
                      </>
                    )}
                  </button>
                </div>

                {/* Delete Confirmation (kept but visually lighter) */}
                {confirmingDelete && (
                  <div style={{
                    marginTop: "0.75rem",
                    padding: "0.8rem 0.75rem",
                    background: "#fff7ed",
                    border: "1px solid #fed7aa",
                    borderRadius: 8,
                    fontSize: "0.85rem"
                  }}>
                    <p style={{
                      margin: "0 0 0.75rem 0",
                      color: "#c05621",
                      fontWeight: 600,
                      fontSize: "0.85rem"
                    }}>
                      Remove this item from wishlist?
                    </p>
                    <div style={{ display: "flex", gap: "0.5rem" }}>
                      <button
                        onClick={() => handleRemoveFromWishlist(product?.id)}
                        disabled={isRemoving}
                        style={{
                          flex: 1,
                          background: "#e11d48",
                          color: "#fff",
                          border: "none",
                          padding: "0.55rem",
                          borderRadius: 6,
                          fontWeight: 600,
                          fontSize: "0.8rem",
                          cursor: isRemoving ? "default" : "pointer"
                        }}
                      >
                        {isRemoving ? "Removing..." : "Yes, remove"}
                      </button>
                      <button
                        onClick={() => setShowDeleteConfirm(null)}
                        disabled={isRemoving}
                        style={{
                          flex: 1,
                          background: "#fff",
                          color: "#4b5563",
                          border: "1px solid #e5e7eb",
                          padding: "0.55rem",
                          borderRadius: 6,
                          fontWeight: 600,
                          fontSize: "0.8rem",
                          cursor: isRemoving ? "default" : "pointer"
                        }}
                      >
                        Cancel
                      </button>
                    </div>
                  </div>
                )}
              </div>
            </div>
          );
        })}
      </div>

      {/* Continue Shopping Button */}
      <div style={{ textAlign: "center", paddingTop: "2rem", borderTop: "1px solid #f0f0f0" }}>
        <Link 
          href="/" 
          style={{
            display: "inline-flex",
            alignItems: "center",
            gap: "0.5rem",
            background: "#f5f5f5",
            color: "#666",
            padding: "0.8rem 2rem",
            borderRadius: 8,
            fontWeight: 700,
            textDecoration: "none",
            fontSize: "1rem",
            transition: "all 0.3s"
          }}
          onMouseEnter={(e) => {
            (e.currentTarget as HTMLElement).style.background = "#e91e63";
            (e.currentTarget as HTMLElement).style.color = "#fff";
          }}
          onMouseLeave={(e) => {
            (e.currentTarget as HTMLElement).style.background = "#f5f5f5";
            (e.currentTarget as HTMLElement).style.color = "#666";
          }}
        >
          <FaArrowLeft size={16} /> Continue Shopping
        </Link>
      </div>
    </div>
  );
};

export default WishlistPage;
