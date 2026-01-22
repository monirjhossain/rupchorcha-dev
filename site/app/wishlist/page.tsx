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
        gridTemplateColumns: "repeat(auto-fill, minmax(260px, 1fr))",
        gap: "1.5rem",
        marginBottom: "3rem"
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
                borderRadius: 16,
                overflow: "hidden",
                boxShadow: "0 4px 16px rgba(0, 0, 0, 0.06)",
                border: "1px solid #e8e8e8",
                transition: "all 0.35s cubic-bezier(0.4, 0, 0.2, 1)",
                display: "flex",
                flexDirection: "column",
                position: "relative"
              }}
              onMouseEnter={(e) => {
                (e.currentTarget as HTMLElement).style.boxShadow = "0 12px 32px rgba(233, 30, 99, 0.15)";
                (e.currentTarget as HTMLElement).style.transform = "translateY(-8px)";
                (e.currentTarget as HTMLElement).style.borderColor = "#e91e63";
              }}
              onMouseLeave={(e) => {
                (e.currentTarget as HTMLElement).style.boxShadow = "0 4px 16px rgba(0, 0, 0, 0.06)";
                (e.currentTarget as HTMLElement).style.transform = "translateY(0)";
                (e.currentTarget as HTMLElement).style.borderColor = "#e8e8e8";
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
                    height: 220,
                    objectFit: "cover",
                    display: "block",
                    transition: "transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)"
                  }}
                  onMouseEnter={(e) => {
                    (e.target as HTMLElement).style.transform = "scale(1.08)";
                  }}
                  onMouseLeave={(e) => {
                    (e.target as HTMLElement).style.transform = "scale(1)";
                  }}
                />
                {discount > 0 && (
                  <div style={{
                    position: "absolute",
                    top: 16,
                    left: 16,
                    background: "linear-gradient(135deg, #e91e63 0%, #c2185b 100%)",
                    color: "#fff",
                    padding: "0.5rem 1rem",
                    borderRadius: 8,
                    fontWeight: 800,
                    fontSize: "0.95rem",
                    boxShadow: "0 4px 12px rgba(233, 30, 99, 0.3)",
                    letterSpacing: "0.5px"
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
                    top: 16,
                    right: 16,
                    width: 40,
                    height: 40,
                    padding: 0,
                    background: confirmingDelete ? "#e91e63" : "rgba(255, 255, 255, 0.95)",
                    color: confirmingDelete ? "#fff" : "#e91e63",
                    border: "none",
                    borderRadius: "50%",
                    fontWeight: 700,
                    fontSize: "1.1rem",
                    cursor: isRemoving ? "default" : "pointer",
                    transition: "all 0.3s",
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "center",
                    boxShadow: "0 4px 12px rgba(0, 0, 0, 0.15)",
                    backdropFilter: "blur(10px)"
                  }}
                  title="Remove from wishlist"
                  onMouseEnter={(e) => {
                    if (!isRemoving) {
                      (e.target as HTMLElement).style.transform = "scale(1.1) rotate(90deg)";
                      (e.target as HTMLElement).style.background = confirmingDelete ? "#c2185b" : "#fff";
                    }
                  }}
                  onMouseLeave={(e) => {
                    if (!isRemoving) {
                      (e.target as HTMLElement).style.transform = "scale(1) rotate(0deg)";
                      (e.target as HTMLElement).style.background = confirmingDelete ? "#e91e63" : "rgba(255, 255, 255, 0.95)";
                    }
                  }}
                >
                  {isRemoving ? "..." : <FaTrash size={14} />}
                </button>
              </Link>

              {/* Product Info */}
              <div style={{ padding: "1.2rem", flex: 1, display: "flex", flexDirection: "column" }}>
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
                    fontSize: "1rem",
                    fontWeight: 700,
                    color: "#1a1a1a",
                    marginBottom: "0.8rem",
                    lineHeight: 1.4,
                    display: "-webkit-box",
                    WebkitLineClamp: 2,
                    WebkitBoxOrient: "vertical",
                    overflow: "hidden",
                    cursor: "pointer",
                    transition: "color 0.2s",
                    minHeight: "2.8rem"
                  }}
                  onMouseEnter={(e) => {
                    (e.target as HTMLElement).style.color = "#e91e63";
                  }}
                  onMouseLeave={(e) => {
                    (e.target as HTMLElement).style.color = "#1a1a1a";
                  }}>
                    {product?.name}
                  </h3>
                </Link>

                {/* Price */}
                <div style={{ marginBottom: "1.2rem" }}>
                  {discount > 0 ? (
                    <div style={{ display: "flex", gap: "0.6rem", alignItems: "baseline", flexWrap: "wrap" }}>
                      <span style={{ 
                        fontSize: "1.3rem", 
                        fontWeight: 800, 
                        color: "#e91e63",
                        letterSpacing: "-0.5px"
                      }}>
                        ৳ {Math.round(price).toLocaleString()}
                      </span>
                      <span style={{ 
                        fontSize: "0.95rem", 
                        color: "#aaa", 
                        textDecoration: "line-through",
                        fontWeight: 500
                      }}>
                        ৳ {Math.round(originalPrice).toLocaleString()}
                      </span>
                      <span style={{
                        fontSize: "0.75rem",
                        color: "#4caf50",
                        fontWeight: 700,
                        background: "#e8f5e9",
                        padding: "0.15rem 0.5rem",
                        borderRadius: 4
                      }}>
                        Save ৳ {Math.round(originalPrice - price).toLocaleString()}
                      </span>
                    </div>
                  ) : (
                    <span style={{ 
                      fontSize: "1.3rem", 
                      fontWeight: 800, 
                      color: "#e91e63",
                      letterSpacing: "-0.5px"
                    }}>
                      ৳ {Math.round(price).toLocaleString()}
                    </span>
                  )}
                </div>

                {/* Spacer */}
                <div style={{ flex: 1 }} />

                {/* Add to Cart Button */}
                <button
                  onClick={() => handleAddToCart(product)}
                  disabled={isAdding}
                  style={{
                    width: "100%",
                    background: isAdding ? "#f5f5f5" : "linear-gradient(135deg, #e91e63 0%, #c2185b 100%)",
                    color: isAdding ? "#999" : "#fff",
                    border: "none",
                    padding: "0.85rem",
                    borderRadius: 8,
                    fontWeight: 700,
                    fontSize: "0.9rem",
                    cursor: isAdding ? "default" : "pointer",
                    transition: "all 0.3s",
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "center",
                    gap: "0.5rem",
                    boxShadow: isAdding ? "none" : "0 4px 12px rgba(233, 30, 99, 0.3)",
                    letterSpacing: "0.5px",
                    textTransform: "uppercase"
                  }}
                  onMouseEnter={(e) => {
                    if (!isAdding) {
                      (e.target as HTMLElement).style.transform = "translateY(-2px)";
                      (e.target as HTMLElement).style.boxShadow = "0 6px 20px rgba(233, 30, 99, 0.4)";
                    }
                  }}
                  onMouseLeave={(e) => {
                    if (!isAdding) {
                      (e.target as HTMLElement).style.transform = "translateY(0)";
                      (e.target as HTMLElement).style.boxShadow = "0 4px 12px rgba(233, 30, 99, 0.3)";
                    }
                  }}
                >
                  {isAdding ? (
                    <>
                      <div className="spinner" style={{ width: 16, height: 16, borderWidth: 2 }} />
                      Adding to Cart...
                    </>
                  ) : (
                    <>
                      <FaShoppingCart size={18} />
                      Add to Cart
                    </>
                  )}
                </button>

                {/* Delete Confirmation */}
                {confirmingDelete && (
                  <div style={{
                    marginTop: "1rem",
                    padding: "1rem",
                    background: "linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%)",
                    border: "2px solid #ff9800",
                    borderRadius: 10,
                    fontSize: "0.95rem",
                    animation: "slideDown 0.3s ease-out"
                  }}>
                    <p style={{ 
                      margin: "0 0 1rem 0", 
                      color: "#e65100", 
                      fontWeight: 600,
                      fontSize: "0.95rem"
                    }}>
                      ⚠️ Remove this item from wishlist?
                    </p>
                    <div style={{ display: "flex", gap: "0.7rem" }}>
                      <button
                        onClick={() => handleRemoveFromWishlist(product?.id)}
                        disabled={isRemoving}
                        style={{
                          flex: 1,
                          background: "linear-gradient(135deg, #e91e63 0%, #c2185b 100%)",
                          color: "#fff",
                          border: "none",
                          padding: "0.7rem",
                          borderRadius: 8,
                          fontWeight: 700,
                          fontSize: "0.9rem",
                          cursor: isRemoving ? "default" : "pointer",
                          boxShadow: "0 2px 8px rgba(233, 30, 99, 0.3)",
                          transition: "all 0.2s"
                        }}
                        onMouseEnter={(e) => {
                          if (!isRemoving) (e.target as HTMLElement).style.transform = "scale(1.02)";
                        }}
                        onMouseLeave={(e) => {
                          if (!isRemoving) (e.target as HTMLElement).style.transform = "scale(1)";
                        }}
                      >
                        {isRemoving ? "Removing..." : "✓ Yes, Remove"}
                      </button>
                      <button
                        onClick={() => setShowDeleteConfirm(null)}
                        disabled={isRemoving}
                        style={{
                          flex: 1,
                          background: "#fff",
                          color: "#666",
                          border: "2px solid #ddd",
                          padding: "0.7rem",
                          borderRadius: 8,
                          fontWeight: 700,
                          fontSize: "0.9rem",
                          cursor: isRemoving ? "default" : "pointer",
                          transition: "all 0.2s"
                        }}
                        onMouseEnter={(e) => {
                          if (!isRemoving) {
                            (e.target as HTMLElement).style.borderColor = "#999";
                            (e.target as HTMLElement).style.background = "#f5f5f5";
                          }
                        }}
                        onMouseLeave={(e) => {
                          if (!isRemoving) {
                            (e.target as HTMLElement).style.borderColor = "#ddd";
                            (e.target as HTMLElement).style.background = "#fff";
                          }
                        }}
                      >
                        ✕ Cancel
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
