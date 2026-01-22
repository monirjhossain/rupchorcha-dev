"use client";

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { useCart } from '@/app/common/CartContext';
import CartItemRow from './components/CartItemRow';
import OrderSummary from './components/OrderSummary';
import PickedForYou from './components/PickedForYou';
import FrequentlyBought from './components/FrequentlyBought';
import EmptyCart from './components/EmptyCart';
import styles from './cart.module.css';

export default function CartPage() {
  const { items, updateCart, removeFromCart } = useCart();
  const [selectedItems, setSelectedItems] = useState<Set<number>>(new Set());

  // Select all items by default when page loads
  useEffect(() => {
    if (items.length > 0) {
      setSelectedItems(new Set(items.map(item => item.product_id)));
    }
  }, [items.length]);

  const toggleSelectAll = () => {
    if (selectedItems.size === items.length) {
      setSelectedItems(new Set());
    } else {
      setSelectedItems(new Set(items.map(item => item.product_id)));
    }
  };

  const toggleSelectItem = (productId: number) => {
    const newSelected = new Set(selectedItems);
    if (newSelected.has(productId)) {
      newSelected.delete(productId);
    } else {
      newSelected.add(productId);
    }
    setSelectedItems(newSelected);
  };

  const removeSelectedItems = () => {
    if (selectedItems.size === 0) return;
    if (confirm(`Are you sure you want to remove ${selectedItems.size} item(s)?`)) {
      selectedItems.forEach(productId => {
        removeFromCart(productId);
      });
      setSelectedItems(new Set());
    }
  };

  // Calculate totals based on selected items
  const itemsToCalculate = items.filter(item => selectedItems.has(item.product_id));
  
  const totalItems = itemsToCalculate.length;
  const totalQuantity = itemsToCalculate.reduce((sum, item) => sum + item.quantity, 0);
  const subtotal = itemsToCalculate.reduce((sum, item) => {
    const price = Number(item.product?.sale_price || item.product?.price || 0);
    return sum + (price * item.quantity);
  }, 0);

  if (items.length === 0) {
    return <EmptyCart />;
  }

  return (
    <div className={styles.cartPageContainer}>
      {/* Breadcrumb */}
      <div className={styles.breadcrumb}>
        <Link href="/">Home</Link>
        <span className={styles.separator}>&gt;</span>
        <span>Cart</span>
      </div>

      {/* Free Gift Banner */}
      <div className={styles.freeGiftBanner}>
        <div className={styles.bannerContent}>
          <span className={styles.bannerText}>You are eligible for</span>
          <span className={styles.freeGift}>Free Gift</span>
        </div>
        <div className={styles.giftIcon}>🎁</div>
      </div>

      {/* Main Content */}
      <div className={styles.mainContent}>
        {/* Cart Items Section */}
        <div className={styles.cartSection}>
          <div className={styles.cartHeader}>
            <div style={{ display: 'flex', alignItems: 'center', gap: '16px' }}>
              <label style={{ display: 'flex', alignItems: 'center', gap: '8px', cursor: 'pointer' }}>
                <input
                  type="checkbox"
                  checked={selectedItems.size === items.length && items.length > 0}
                  onChange={toggleSelectAll}
                  style={{ width: '18px', height: '18px', cursor: 'pointer', accentColor: '#e91e63' }}
                />
                <span style={{ fontSize: '15px', fontWeight: 600, color: '#666' }}>Select All</span>
              </label>
              <h1 className={styles.cartTitle}>Cart ({items.length})</h1>
            </div>
            {selectedItems.size > 0 && (
              <button
                onClick={removeSelectedItems}
                style={{
                  background: 'transparent',
                  border: '2px solid #e91e63',
                  color: '#e91e63',
                  padding: '8px 20px',
                  borderRadius: '8px',
                  fontSize: '14px',
                  fontWeight: 600,
                  cursor: 'pointer',
                  transition: 'all 0.2s'
                }}
                onMouseOver={(e) => {
                  e.currentTarget.style.background = '#e91e63';
                  e.currentTarget.style.color = 'white';
                }}
                onMouseOut={(e) => {
                  e.currentTarget.style.background = 'transparent';
                  e.currentTarget.style.color = '#e91e63';
                }}
              >
                Remove Selected ({selectedItems.size})
              </button>
            )}
          </div>

          <div className={styles.cartItems}>
            {items.map((item) => (
              <CartItemRow
                key={item.product_id}
                item={item}
                isSelected={selectedItems.has(item.product_id)}
                onToggleSelect={() => toggleSelectItem(item.product_id)}
                onUpdateQuantity={(quantity) => updateCart(item.product_id, quantity)}
                onRemove={() => removeFromCart(item.product_id)}
              />
            ))}
          </div>
        </div>

        {/* Order Summary Section */}
        <OrderSummary
          totalItems={totalItems}
          totalQuantity={totalQuantity}
          subtotal={subtotal}
        />
      </div>

      {/* Frequently Bought Together Section */}
      <FrequentlyBought />

      {/* Picked For You Section */}
      <PickedForYou />

      {/* Popular Searches Section */}
      <div className={styles.popularSearches}>
        <h3 className={styles.popularTitle}>Popular Searches</h3>
        <div className={styles.searchLinks}>
          <Link href="/search?q=skin+care">Skin Care</Link>
          <span>|</span>
          <Link href="/search?q=serums">Serums</Link>
          <span>|</span>
          <Link href="/search?q=moisturizers">Moisturizers</Link>
          <span>|</span>
          <Link href="/search?q=cleansers">Cleansers</Link>
          <span>|</span>
          <Link href="/search?q=body+care">Body Care</Link>
          <span>|</span>
          <Link href="/search?q=makeup">Makeup</Link>
          <span>|</span>
          <Link href="/search?q=lipstick">Lipstick</Link>
          <span>|</span>
          <Link href="/search?q=sunscreen">Sunscreen</Link>
        </div>
      </div>
    </div>
  );
}
