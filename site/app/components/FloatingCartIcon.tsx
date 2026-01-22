"use client";
import React, { useEffect, useState } from "react";
import { useCart } from "../common/CartContext";

const FloatingCartIcon: React.FC<{ onClick: () => void }> = ({ onClick }) => {
  const { items } = useCart();
  const totalCount = items.reduce((sum, item) => sum + item.quantity, 0);
  const totalPrice = items.reduce((sum, item) => {
    const price = parseFloat(item.product?.sale_price || item.product?.price || item.price || 0);
    return sum + price * item.quantity;
  }, 0);
  const [animate, setAnimate] = useState(false);
  const [prevPrice, setPrevPrice] = useState(totalPrice);

  useEffect(() => {
    if (totalPrice !== prevPrice) {
      setAnimate(true);
      setPrevPrice(totalPrice);
      const timer = setTimeout(() => setAnimate(false), 600);
      return () => clearTimeout(timer);
    }
  }, [totalPrice, prevPrice]);

  return (
    <div
      onClick={onClick}
      style={{
        position: "fixed",
        right: 32,
        top: "40%",
        zIndex: 9999,
        background: "#fff",
        borderRadius: 16,
        boxShadow: "0 4px 24px #0002",
        padding: "12px 22px 12px 18px",
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        cursor: "pointer",
        border: "2px solid #e11d48",
        minWidth: 90,
        transition: "box-shadow 0.2s",
      }}
    >
      <svg width="26" height="32" fill="none" viewBox="0 0 24 24" style={{ marginBottom: 4 }}>
        <rect x="3" y="7" width="18" height="12" rx="3" fill="#e11d48" />
        <path d="M6 6h15l-1.5 9h-13z" stroke="#fff" strokeWidth="2" fill="#e11d48" />
        <circle cx="9" cy="21" r="1" fill="#e11d48" />
        <circle cx="18" cy="21" r="1" fill="#e11d48" />
      </svg>
      <div style={{ fontWeight: 700, fontSize: 15, color: "#222", marginBottom: 2 }}>{totalCount} items</div>
      <div
        style={{
          fontWeight: 800,
          fontSize: 17,
          color: "#fff",
          background: "#e11d48",
          borderRadius: 8,
          padding: "2px 12px",
          minWidth: 60,
          marginTop: 2,
          transition: "transform 0.4s",
          transform: animate ? "scale(1.15)" : "none",
        }}
      >
        ৳ {totalPrice}
      </div>
    </div>
  );
};

export default FloatingCartIcon;
