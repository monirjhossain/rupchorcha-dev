import React from 'react';
import Link from 'next/link';
import styles from './OrderSummary.module.css';

interface OrderSummaryProps {
  totalItems: number;
  totalQuantity: number;
  subtotal: number;
}

const OrderSummary: React.FC<OrderSummaryProps> = ({
  totalItems,
  totalQuantity,
  subtotal,
}) => {
  return (
    <div className={styles.orderSummary}>
      <div className={styles.summaryHeader}>
        <h2 className={styles.summaryTitle}>Order summary</h2>
        <span className={styles.infoIcon}>ⓘ</span>
      </div>

      <div className={styles.summaryDetails}>
        <div className={styles.summaryRow}>
          <span className={styles.label}>Item(s)</span>
          <span className={styles.value}>{totalItems} pcs</span>
        </div>
        <div className={styles.summaryRow}>
          <span className={styles.label}>Quantity(s)</span>
          <span className={styles.value}>{totalQuantity} pcs</span>
        </div>
        <div className={styles.summaryRow}>
          <span className={styles.label}>Subtotal</span>
          <span className={styles.value}>৳ {subtotal.toFixed(0)}</span>
        </div>
      </div>

      <Link href="/checkout" className={styles.checkoutBtn}>
        Checkout ››
      </Link>

      <div className={styles.paymentMethods}>
        <div className={styles.paymentText}>We accept</div>
        <div className={styles.paymentIcons}>
          <div className={styles.paymentIcon} data-payment="visa">
            {/* Replace with actual Visa logo */}
            <span style={{ fontSize: '10px', color: '#1434CB', fontWeight: 'bold' }}>VISA</span>
          </div>
          <div className={styles.paymentIcon} data-payment="mastercard">
            {/* Replace with actual Mastercard logo */}
            <span style={{ fontSize: '10px', color: '#EB001B', fontWeight: 'bold' }}>MC</span>
          </div>
          <div className={styles.paymentIcon} data-payment="amex">
            {/* Replace with actual Amex logo */}
            <span style={{ fontSize: '10px', color: '#006FCF', fontWeight: 'bold' }}>AMEX</span>
          </div>
          <div className={styles.paymentIcon} data-payment="bkash">
            {/* Replace with actual bKash logo */}
            <span style={{ fontSize: '10px', color: '#E2136E', fontWeight: 'bold' }}>bKash</span>
          </div>
          <div className={styles.paymentIcon} data-payment="nagad">
            {/* Replace with actual Nagad logo */}
            <span style={{ fontSize: '10px', color: '#EE4023', fontWeight: 'bold' }}>Nagad</span>
          </div>
        </div>
      </div>
    </div>
  );
};

export default OrderSummary;
