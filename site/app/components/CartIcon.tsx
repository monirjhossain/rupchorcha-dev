"use client";
import React, { useEffect, useState } from "react";
import { useCart } from "../common/CartContext";

const CartIcon: React.FC<{ animate?: boolean }> = ({ animate = false }) => {
  const { items } = useCart();
  const totalCount = items.reduce((sum, item) => sum + item.quantity, 0);
  const [isAnimating, setIsAnimating] = useState(false);

  useEffect(() => {
    if (animate) {
      setIsAnimating(true);
      const timer = setTimeout(() => setIsAnimating(false), 500);
      return () => clearTimeout(timer);
    }
  }, [animate]);

  return (
    <div style={{ position: "relative", display: "inline-block" }}>
      <svg
        width="28"
        height="28"
        fill="none"
        viewBox="0 0 24 24"
        className={isAnimating ? "cart-animate" : ""}
        style={{ transition: "transform 0.3s", transform: isAnimating ? "scale(1.2) rotate(-10deg)" : "none" }}
      >
        <path d="M6 6h15l-1.5 9h-13z" stroke="#222" strokeWidth="2" fill="#fff" />
        <circle cx="9" cy="21" r="1" fill="#222" />
        <circle cx="18" cy="21" r="1" fill="#222" />
      </svg>
      {totalCount > 0 && (
        <span style={{
          position: "absolute",
          top: 0,
          right: 0,
          background: "#e11d48",
          color: "#fff",
          borderRadius: "50%",
          padding: "2px 6px",
          fontSize: 12,
          fontWeight: 600,
        }}>
          {totalCount}
        </span>
      )}
    </div>
  );
};

export default CartIcon;
