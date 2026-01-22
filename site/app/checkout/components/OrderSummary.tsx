"use client";
import React, { useMemo, useState } from 'react';
import { useCart } from '@/app/common/CartContext';
import { useCheckout } from '../CheckoutContext';
import styles from './OrderSummary.module.css';
import Image from 'next/image';
import type { CartItem } from '@/app/common/CartContext';
import toast from 'react-hot-toast';

export default function OrderSummary() {
  const { items, updateCart, removeFromCart } = useCart();
  const { 
    selectedShipping, 
    setSelectedShipping, 
    shippingCost, 
    shippingMethods,
    loading: shippingLoading,
    setDiscountCode, 
    setDiscountAmount 
  } = useCheckout();
  const [couponInput, setCouponInput] = useState('');
  const [discount, setDiscount] = useState(0);
  const [applyingCode, setApplyingCode] = useState(false);
  const [showCoupon, setShowCoupon] = useState(false);

  const subtotal = useMemo(() => {
    return items.reduce((sum: number, item: CartItem) => {
      const price = (item.product?.sale_price as number) || (item.product?.price as number) || 0;
      return sum + price * item.quantity;
    }, 0);
  }, [items]);

  const total = subtotal + shippingCost - discount;

  const backendBase = "http://localhost:8000";
  
  const getImageUrl = (product: unknown): string => {
    const p = product as { images?: Array<{ url?: string; path?: string } | string>; main_image?: string };
    if (p?.images?.length) {
      const first = p.images[0];
      let img: string | undefined;
      if (typeof first === 'string') {
        img = first;
      } else if (first && typeof first === 'object') {
        const obj = first as { url?: string; path?: string };
        img = obj.url || obj.path;
      }
      if (typeof img === "string" && img) {
        return img.startsWith("http") ? img : `${backendBase}/storage/${img.replace(/^storage[\\/]/, "")}`;
      }
    }
    if (typeof p?.main_image === "string" && p.main_image) {
      return p.main_image.startsWith("http")
        ? p.main_image
        : `${backendBase}/storage/${p.main_image.replace(/^storage[\\/]/, "")}`;
    }
    return "https://via.placeholder.com/80x80/f0f0f0/999?text=No+Image";
  };

  const handleApplyDiscount = async () => {
    if (!couponInput.trim()) return;
    setApplyingCode(true);
    
    try {
      const productIds = items.map((item: CartItem) => item.product_id);
      
      const response = await fetch('http://localhost:8000/api/coupons/validate', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          code: couponInput,
          subtotal: subtotal,
          product_ids: productIds,
        }),
      });
      
      const data = await response.json();
      
      console.log('Coupon validation response:', data);
      
      if (data.valid) {
        const discountValue = Number(data.discount) || 0;
        console.log('Setting discount:', discountValue);
        setDiscount(discountValue);
        setDiscountCode(couponInput);
        setDiscountAmount(discountValue);
        toast.success(`Coupon applied! You saved ৳${discountValue.toFixed(2)}`);
      } else {
        toast.error(data.message || 'Invalid coupon code');
        setDiscount(0);
        setDiscountCode('');
        setDiscountAmount(0);
      }
    } catch (error) {
      console.error('Coupon validation error:', error);
      toast.error('Failed to validate coupon. Please try again.');
    } finally {
      setApplyingCode(false);
    }
  };

  return (
    <div className={styles.summary}>
      <button
        type="button"
        className={`${styles.couponToggle} ${showCoupon ? styles.couponToggleExpanded : ''}`}
        onClick={() => setShowCoupon((v: boolean) => !v)}
        aria-expanded={showCoupon}
        aria-controls="coupon-section"
      >
        Have Coupon / Voucher? <span className={styles.couponCaret}>▾</span>
      </button>

      {showCoupon && (
        <div id="coupon-section" className={styles.couponSection}>
          <h3>Have Coupon / Voucher?</h3>
          <div className={styles.discountSection}>
            <input
              type="text"
              placeholder="Enter coupon code"
              value={couponInput}
              onChange={(e) => setCouponInput(e.target.value)}
              className={styles.discountInput}
            />
            <button 
              type="button"
              onClick={handleApplyDiscount}
              disabled={applyingCode || !couponInput.trim()}
              className={styles.applyBtn}
            >
              {applyingCode ? 'Applying...' : 'Apply'}
            </button>
          </div>
        </div>
      )}

      <h2 className={styles.title}>Order Summary</h2>

      {/* Cart Items */}
      <div className={styles.items}>
        {items.map((item: CartItem) => {
          const price = (item.product?.sale_price as number) || (item.product?.price as number) || 0;
          const itemTotal = price * item.quantity;
          
          const handleQuantityChange = (delta: number) => {
            const newQuantity = Math.max(1, item.quantity + delta);
            updateCart(item.product_id, newQuantity);
          };
          
          return (
            <div key={item.product_id} className={styles.item}>
              <div className={styles.itemImage}>
                <Image
                  src={getImageUrl(item.product)}
                  alt={(item.product?.name as string) || 'Product'}
                  width={80}
                  height={80}
                  unoptimized
                  style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                />
              </div>
              <div className={styles.itemInfo}>
                <div className={styles.itemDetails}>
                  <h4>{(item.product?.name as string) || 'Product'}</h4>
                  {item.product?.brand && (
                    <p className={styles.brand}>{(item.product.brand?.name as string) || (item.product.brand as string)}</p>
                  )}
                </div>
                <div className={styles.itemQuantity}>
                  <button
                    type="button"
                    onClick={() => handleQuantityChange(-1)}
                    className={styles.quantityBtn}
                    aria-label="Decrease quantity"
                    title="Decrease quantity"
                  >
                    −
                  </button>
                  <span className={styles.quantityInput}>{item.quantity}</span>
                  <button
                    type="button"
                    onClick={() => handleQuantityChange(1)}
                    className={styles.quantityBtn}
                    aria-label="Increase quantity"
                    title="Increase quantity"
                  >
                    +
                  </button>
                </div>
              </div>
              <div className={styles.itemPrice}>৳{itemTotal.toFixed(2)}</div>
              <button
                type="button"
                onClick={() => removeFromCart(item.product_id)}
                className={styles.removeBtn}
                title="Remove from cart"
              >
                ✕
              </button>
            </div>
          );
        })}
      </div>

      {/* Shipping Method */}
      {shippingMethods.length > 0 && (
        <div style={{marginBottom: '1.5rem', paddingBottom: '1.5rem', borderBottom: '1px solid #f0f0f0'}}>
          <h3 style={{fontSize: '0.95rem', fontWeight: 700, color: '#333', marginBottom: '0.8rem', textTransform: 'uppercase', letterSpacing: '0.5px'}}>
            Choose Shipping Method
            {shippingLoading && <span style={{fontSize: '0.8rem', fontWeight: 400, marginLeft: '0.5rem', color: '#888'}}>Loading...</span>}
          </h3>
          <div style={{display: 'flex', flexDirection: 'column', gap: '0.6rem'}}>
            {shippingMethods.map(method => (
              <label key={method.id} style={{display: 'flex', alignItems: 'flex-start', gap: '0.8rem', padding: '0.8rem', border: '1px solid #e0e0e0', borderRadius: '6px', cursor: 'pointer', transition: 'all 0.3s', backgroundColor: selectedShipping === method.id ? '#fef5f8' : '#fff'}}>
                <input
                  type="radio"
                  name="shipping"
                  value={method.id}
                  checked={selectedShipping === method.id}
                  onChange={() => setSelectedShipping(method.id)}
                  style={{width: '18px', height: '18px', minWidth: '18px', marginTop: '2px', cursor: 'pointer'}}
                />
                <div style={{flex: 1}}>
                  <div style={{fontWeight: 600, fontSize: '0.95rem', color: '#333', marginBottom: '0.2rem'}}>{method.name}</div>
                  <div style={{fontSize: '0.85rem', color: '#888'}}>{method.type === 'free' ? 'Free Delivery' : 'Standard Delivery'}</div>
                </div>
                <div style={{fontWeight: 700, fontSize: '1rem', color: '#e91e63', textAlign: 'right', minWidth: '60px'}}>
                  {method.cost === 0 ? 'FREE' : `৳${method.cost}`}
                </div>
              </label>
            ))}
          </div>
        </div>
      )}

      {/* Coupon toggle and section moved above title */}

      {/* Totals */}
      <div className={styles.totals}>
        <div className={styles.row}>
          <span>Subtotal</span>
          <span>৳{subtotal.toFixed(2)}</span>
        </div>
        <div className={styles.row}>
          <span>Shipping</span>
          <span>৳{shippingCost.toFixed(2)}</span>
        </div>
        {discount > 0 && (
          <div className={styles.row} style={{ color: '#27ae60' }}>
            <span>Discount</span>
            <span>-৳{discount.toFixed(2)}</span>
          </div>
        )}
        <div className={styles.divider} />
        <div className={styles.totalRow}>
          <span>Total</span>
          <span className={styles.totalAmount}>৳{total.toFixed(2)}</span>
        </div>
      </div>

      {/* Security Badge */}
      <div className={styles.securityBadge}>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="#27ae60">
          <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
        </svg>
        <span>Secure Checkout</span>
      </div>
    </div>
  );
}
