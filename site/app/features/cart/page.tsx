"use client";
import React, { useState, useEffect } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { cartStorage } from "../../utils/cartStorage";
import styles from "./cartPage.module.css";
import Header from "../components/Header";

const shippingCosts = {
  outside: 130,
  inside: 70
};

const freeShippingThreshold = 3000;

const CartPage: React.FC<{ updateCartCount?: () => void }> = ({ updateCartCount }) => {
  const [cartItems, setCartItems] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [subtotal, setSubtotal] = useState(0);
  const [shippingMethod, setShippingMethod] = useState<'outside' | 'inside'>('outside');
  const [couponCode, setCouponCode] = useState("");
  const router = useRouter();

  useEffect(() => {
    fetchCart();
  }, []);

  useEffect(() => {
    calculateSubtotal();
  }, [cartItems]);

  const fetchCart = () => {
    try {
      setLoading(true);
      const cart = cartStorage.getCart();
      setCartItems(cart.items);
    } catch (error) {
      console.error("Error fetching cart:", error);
    } finally {
      setLoading(false);
    }
  };

  const calculateSubtotal = () => {
    const total = cartItems.reduce((sum, item) => {
      const price = parseFloat(item.price || 0);
      const quantity = parseInt(item.quantity || 0);
      return sum + price * quantity;
    }, 0);
    setSubtotal(total);
  };

  const updateQuantity = (itemId: number, newQuantity: number) => {
    if (newQuantity < 1) return;
    try {
      cartStorage.updateQuantity(itemId, newQuantity);
      setCartItems(prevItems =>
        prevItems.map(item =>
          item.id === itemId ? { ...item, quantity: newQuantity, total: item.price * newQuantity } : item
        )
      );
      if (updateCartCount) updateCartCount();
    } catch (error) {
      console.error("Error updating quantity:", error);
      alert("Failed to update quantity");
    }
  };

  const removeItem = (itemId: number) => {
    try {
      cartStorage.removeItem(itemId);
      setCartItems(prevItems => prevItems.filter(item => item.id !== itemId));
      if (updateCartCount) updateCartCount();
    } catch (error) {
      console.error("Error removing item:", error);
      alert("Failed to remove item");
    }
  };

  const applyCoupon = () => {
    alert("Coupon feature coming soon!");
  };

  const handleCheckout = () => {
    if (cartItems.length === 0) {
      alert("Your cart is empty!");
      return;
    }
    router.push("/checkout");
  };

  const progressPercentage = Math.min((subtotal / freeShippingThreshold) * 100, 100);
  const remainingForFreeShipping = Math.max(freeShippingThreshold - subtotal, 0);
  const shippingCost = shippingCosts[shippingMethod];
  const total = subtotal + shippingCost;

  if (loading) {
    return (
      <>
        <Header />
        <div className={styles.cartPage}>
          <div className={styles.container}>
            <div className={styles.loading}>Loading cart...</div>
          </div>
        </div>
      </>
    );
  }

  return (
    <>
      <Header />
      <div className={styles.cartPage}>
        <div className={styles.container}>
        {/* Progress Steps */}
        <div className={styles.cartProgress}>
          <div className={`${styles.progressStep} ${styles.active}`}>
            <div className={styles.stepNumber}>1</div>
            <div className={styles.stepLabel}>Shopping Cart</div>
          </div>
          <div className={styles.progressLine}></div>
          <div className={styles.progressStep}>
            <div className={styles.stepNumber}>2</div>
            <div className={styles.stepLabel}>Shipping and Checkout</div>
          </div>
          <div className={styles.progressLine}></div>
          <div className={styles.progressStep}>
            <div className={styles.stepNumber}>3</div>
            <div className={styles.stepLabel}>Confirmation</div>
          </div>
        </div>
        <div className={styles.cartContent}>
          {/* Left Side - Cart Items */}
          <div className={styles.cartItemsSection}>
            {cartItems.length === 0 ? (
              <div className={styles.emptyCart}>
                <h3>Your cart is empty</h3>
                <Link href="/shop" className={styles.continueShopping}>Continue Shopping</Link>
              </div>
            ) : (
              <>
                {cartItems.map(item => (
                  <div key={item.id} className={styles.cartItem}>
                    <button
                      className={styles.removeItem}
                      onClick={() => removeItem(item.id)}
                    >
                      🗑️
                    </button>
                    <div className={styles.itemImage}>
                      <img
                        src={item.image || "https://via.placeholder.com/80"}
                        alt={item.name}
                      />
                    </div>
                    <div className={styles.itemDetails}>
                      <h4>{item.name}</h4>
                      {item.product?.meta && (
                        <p className={styles.itemMeta}>Made in: {item.product.meta}</p>
                      )}
                    </div>
                    <div className={styles.itemQuantity}>
                      <button
                        onClick={() => updateQuantity(item.id, item.quantity - 1)}
                        disabled={item.quantity <= 1}
                      >
                        −
                      </button>
                      <span>{item.quantity}</span>
                      <button onClick={() => updateQuantity(item.id, item.quantity + 1)}>
                        +
                      </button>
                    </div>
                    <div className={styles.itemPrice}>
                      ৳ {parseFloat(item.price).toFixed(2)}
                    </div>
                  </div>
                ))}
                {/* Free Shipping Progress */}
                <div className={styles.shippingProgress}>
                  <div className={styles.progressBarContainer}>
                    <div
                      className={styles.progressBarFill}
                      style={{ width: `${progressPercentage}%` }}
                    ></div>
                  </div>
                  <p className={styles.progressText}>
                    {remainingForFreeShipping > 0 ? (
                      <>
                        Get <strong>free shipping</strong> for orders over ৳ {freeShippingThreshold.toFixed(2)}{' '}
                        <Link href="/shop">Continue Shopping</Link>
                      </>
                    ) : (
                      <strong>🎉 You qualify for free shipping!</strong>
                    )}
                  </p>
                </div>
                {/* Coupon Code */}
                <div className={styles.couponSection}>
                  <input
                    type="text"
                    placeholder="Coupon code"
                    value={couponCode}
                    onChange={e => setCouponCode(e.target.value)}
                  />
                  <button onClick={applyCoupon}>Apply coupon</button>
                </div>
              </>
            )}
          </div>
          {/* Right Side - Order Summary */}
          <div className={styles.orderSummary}>
            <div className={styles.summaryContent}>
              <div className={styles.summaryRow}>
                <span>Subtotal</span>
                <span>৳ {subtotal.toFixed(2)}</span>
              </div>
              <div className={styles.shippingOptions}>
                <h4>Shipping</h4>
                <label className={styles.radioOption}>
                  <input
                    type="radio"
                    name="shipping"
                    value="outside"
                    checked={shippingMethod === 'outside'}
                    onChange={() => setShippingMethod('outside')}
                  />
                  <span>Outside Dhaka:</span>
                  <span>৳ {shippingCosts.outside.toFixed(2)}</span>
                </label>
                <label className={styles.radioOption}>
                  <input
                    type="radio"
                    name="shipping"
                    value="inside"
                    checked={shippingMethod === 'inside'}
                    onChange={() => setShippingMethod('inside')}
                  />
                  <span>Inside Dhaka:</span>
                  <span>৳ {shippingCosts.inside.toFixed(2)}</span>
                </label>
                <p className={styles.shippingLocation}>
                  Shipping to <strong>Dhaka</strong>. <Link href="/account">Change address</Link>
                </p>
              </div>
              <div className={`${styles.summaryRow} ${styles.totalRow}`}>
                <span>Total</span>
                <span className={styles.totalPrice}>৳ {total.toFixed(2)}</span>
              </div>
              <button
                className={styles.checkoutBtn}
                onClick={handleCheckout}
                disabled={cartItems.length === 0}
              >
                🔒 Proceed to checkout
              </button>
              <div className={styles.paymentMethods}>
                <img src="https://via.placeholder.com/40x25?text=SSL" alt="SSL" />
                <img src="https://via.placeholder.com/40x25?text=Visa" alt="Visa" />
                <img src="https://via.placeholder.com/40x25?text=MC" alt="Mastercard" />
                <img src="https://via.placeholder.com/40x25?text=PayPal" alt="PayPal" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </>
  );
}
export default CartPage;








