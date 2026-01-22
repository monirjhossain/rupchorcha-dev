"use client";
import React from "react";
import Link from "next/link";
import { useCart } from "../common/CartContext";
import styles from "./CartSidebar.module.css";

interface CartSidebarProps {
  isOpen: boolean;
  onClose: () => void;
  updateCartCount?: () => void;
}

const backendBase = "http://127.0.0.1:8000";

const getImageUrl = (product: any): string => {
  // Check if images array exists and has items
  if (product?.images?.length) {
    const img = product.images[0].url || product.images[0].path || product.images[0];
    if (typeof img === "string" && img) {
      return img.startsWith("http") ? img : `${backendBase}/storage/${img.replace(/^storage[\\/]/, "")}`;
    }
  }
  
  // Check main_image
  if (typeof product?.main_image === "string" && product.main_image) {
    return product.main_image.startsWith("http")
      ? product.main_image
      : `${backendBase}/storage/${product.main_image.replace(/^storage[\\/]/, "")}`;
  }
  
  // Check image field
  if (typeof product?.image === "string" && product.image) {
    return product.image.startsWith("http")
      ? product.image
      : `${backendBase}/storage/${product.image.replace(/^storage[\\/]/, "")}`;
  }
  
  return "https://via.placeholder.com/64x64?text=No+Image";
};


const CartSidebar: React.FC<CartSidebarProps> = ({ isOpen, onClose }) => {
  const { items, updateCart, removeFromCart } = useCart();
  const subtotal = items.reduce((sum, item) => {
    const price = parseFloat(item.product?.sale_price || item.product?.price || item.price || 0);
    const quantity = parseInt(item.quantity as any || 0);
    return sum + price * quantity;
  }, 0);
  if (!isOpen) return null;

  return (
    <>
      <div className={styles.overlay} onClick={onClose} aria-hidden="true" />
      <aside className={styles.cartSidebar} role="complementary" aria-label="Shopping cart">
        <div className={styles.cartSidebarHeader}>
          <h2 className={styles.title}>Your Cart</h2>
          <button aria-label="Close cart" className={styles.closeBtn} onClick={onClose}>×</button>
        </div>
        <div className={styles.cartSidebarBody}>
          {items.length === 0 ? (
            <div className={styles.emptyCart}>Your cart is empty.</div>
          ) : (
            items.map((item) => (
              <div key={item.product_id} className={styles.cartItem}>
                <img
                  className={styles.cartItemImg}
                  src={getImageUrl(item.product)}
                  alt={item.product?.name || "No Image"}
                />
                <div className={styles.cartItemInfo}>
                  <div className={styles.cartItemName}>{item.product?.name}</div>
                  <div className={styles.cartItemPrice}>৳ {item.product?.sale_price || item.product?.price || item.price}</div>
                  <div className={styles.cartItemQtyWrap}>
                    <button
                      className={styles.qtyBtn}
                      onClick={() => updateCart(item.product_id, Math.max(1, item.quantity - 1))}
                    >
                      –
                    </button>
                    <span className={styles.qtyValue}>{item.quantity}</span>
                    <button
                      className={styles.qtyBtn}
                      onClick={() => updateCart(item.product_id, item.quantity + 1)}
                    >
                      +
                    </button>
                  </div>
                </div>
                <button
                  aria-label="Remove item"
                  className={styles.removeBtn}
                  onClick={() => removeFromCart(item.product_id)}
                >
                  ×
                </button>
              </div>
            ))
          )}
        </div>
        <div className={styles.cartSidebarFooter}>
          <div className={styles.subtotal}>
            <span>Total</span>
            <span className={styles.subtotalValue}>৳ {subtotal}</span>
          </div>
          <Link href="/checkout" onClick={onClose} className={styles.checkoutBtn}>Checkout</Link>
          <Link href="/cart" onClick={onClose} className={styles.secondaryBtn}>View Cart</Link>
        </div>
      </aside>
    </>
  );
};

export default CartSidebar;
