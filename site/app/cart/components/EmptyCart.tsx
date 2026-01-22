import React from 'react';
import Link from 'next/link';
import styles from './EmptyCart.module.css';

const EmptyCart: React.FC = () => {
  return (
    <div className={styles.emptyCartContainer}>
      <div className={styles.emptyCartContent}>
        <div className={styles.iconWrapper}>
          <svg
            className={styles.cartIcon}
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth={1.5}
              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
            />
          </svg>
        </div>
        
        <h2 className={styles.emptyTitle}>Your cart is empty</h2>
        <p className={styles.emptyMessage}>
          Looks like you haven't added anything to your cart yet.
        </p>
        
        <Link href="/" className={styles.shopNowBtn}>
          Continue Shopping
        </Link>
      </div>
    </div>
  );
};

export default EmptyCart;
