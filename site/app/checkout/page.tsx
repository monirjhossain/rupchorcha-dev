"use client";
import React from 'react';
import { useCart } from '../common/CartContext';
import { useRouter } from 'next/navigation';
import CheckoutForm from '@/app/checkout/components/CheckoutForm';
import OrderSummary from '@/app/checkout/components/OrderSummary';
import CheckoutSteps from './components/CheckoutSteps';
import styles from './checkout.module.css';
import { CheckoutProvider } from './CheckoutContext';

export default function CheckoutPage() {
  const { items } = useCart();
  const router = useRouter();

  // Redirect if cart is empty
  React.useEffect(() => {
    if (items && items.length === 0) {
      router.push('/cart');
    }
  }, [items, router]);

  if (!items || items.length === 0) {
    return null; // Will redirect
  }

  return (
    <div className={styles.checkoutPage}>
      <div className={styles.container}>
        <CheckoutSteps currentStep={2} />
        <CheckoutProvider>
          <div className={styles.checkoutGrid}>
            <div className={styles.formSection}>
              <CheckoutForm />
            </div>
            <div className={styles.summarySection}>
              <OrderSummary />
            </div>
          </div>
        </CheckoutProvider>
      </div>
    </div>
  );
}
