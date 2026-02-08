"use client";
import React, { useEffect, useState } from "react";
import { useCart } from "../common/CartContext";
import styles from "./FloatingCartIcon.module.css";

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
    <button
      type="button"
      onClick={onClick}
      className={`${styles.floatingCart} ${animate ? styles.shake : ""}`}
      aria-label="Open cart"
    >
      <div className={styles.iconWrap}>
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
          <path
            d="M6 6h15l-1.5 9h-13z"
            stroke="#fff"
            strokeWidth="1.8"
            fill="none"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
          <circle cx="10" cy="19" r="1.4" fill="#fff" />
          <circle cx="18" cy="19" r="1.4" fill="#fff" />
        </svg>
        {totalCount > 0 && <span className={styles.badge}>{totalCount}</span>}
      </div>
      <div className={styles.details}>
        <span className={styles.label}>{totalCount} items</span>
        <span className={styles.total}>৳ {totalPrice}</span>
      </div>
    </button>
  );
};

export default FloatingCartIcon;
