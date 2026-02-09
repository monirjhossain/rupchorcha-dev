"use client";
import React, { useState } from "react";
import { useWishlist } from "../components/WishlistContext";
import { useCart } from "../common/CartContext";
import Link from "next/link";
import { FaArrowLeft } from "react-icons/fa";
import { openLoginModal } from "@/src/utils/loginModal";
import ProductCard from "../components/ProductCard";
import gridStyles from "../components/ProductGrid.module.css";

const WishlistPage: React.FC = () => {
  const { wishlist, loading, isAuthenticated, removeFromWishlist } = useWishlist();
  const { addToCart } = useCart();
  const [addingToCart, setAddingToCart] = useState<{ [key: number]: boolean }>({});

  const handleAddToCart = async (product: any) => {
    if (!product || !product.id) return;
    setAddingToCart(prev => ({ ...prev, [product.id]: true }));
    try {
      await addToCart({ 
        product_id: product.id,
        name: product.name,
        price: product.sale_price || product.price,
        image: product.images && product.images.length > 0 ? product.images[0].url : (product.image || "/placeholder.png"),
        quantity: 1,
      });
      // Remove from wishlist after adding to cart
      await removeFromWishlist(product.id);
    } catch (error) {
      console.error("Failed to remove from wishlist:", error);
    } finally {
      setTimeout(() => {
          setAddingToCart(prev => ({ ...prev, [product.id]: false }));
      }, 400);
    }
  };

  if (loading) {
    return (
      <div style={{ minHeight: "60vh", display: "flex", alignItems: "center", justifyContent: "center" }}>
        <div className="spinner" style={{ width: 50, height: 50, border: "3px solid #f3f3f3", borderTop: "3px solid #e91e63", borderRadius: "50%", animation: "spin 1s linear infinite" }} />
        <style jsx>{`
          @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
          }
        `}</style>
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
      <div className={gridStyles["products-grid"]}>
        {wishlist.map((item) => (
            <ProductCard
              key={item.product_id}
              product={item.product}
              onAddToCart={() => handleAddToCart(item.product)}
              isAddingToCart={addingToCart[item.product?.id || 0]}
              showRemoveButton={true}
            />
        ))}
      </div>

      {/* Continue Shopping Button */}
      <div style={{ textAlign: "center", paddingTop: "2rem", borderTop: "1px solid #f0f0f0", marginTop: "3rem" }}>
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
