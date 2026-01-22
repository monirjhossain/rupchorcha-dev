"use client";
import React, { useEffect, useState } from 'react';
import { useRouter, useSearchParams } from 'next/navigation';
import Link from 'next/link';
import { FaCheckCircle, FaBox, FaTruck, FaEnvelope, FaClock, FaShoppingBag, FaArrowRight, FaBarcode } from 'react-icons/fa';
import styles from './order-success.module.css';

export default function OrderSuccessPage() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const orderId = searchParams.get('orderId');
  const [orderDetails, setOrderDetails] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!orderId) {
      router.push('/');
      return;
    }

    // Fetch order details
    fetchOrderDetails();
  }, [orderId, router]);

  const fetchOrderDetails = async () => {
    try {
      const token = localStorage.getItem('token');
      const response = await fetch(`http://localhost:8000/api/orders/${orderId}`, {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
        },
      });
      
      if (response.ok) {
        const data = await response.json();
        setOrderDetails(data.order || data.data);
      }
    } catch (error) {
      console.error('Failed to fetch order details:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className={styles.loadingContainer}>
        <div className={styles.spinner}></div>
        <p>Processing your order...</p>
      </div>
    );
  }

  return (
    <div className={styles.successPage}>
      <div className={styles.bgDecorator}></div>
      
      <div className={styles.container}>
        {/* Success Card */}
        <div className={styles.successCard}>
          {/* Animated Success Icon */}
          <div className={styles.iconWrapper}>
            <div className={styles.iconCircle}>
              <FaCheckCircle className={styles.successIcon} />
            </div>
          </div>

          {/* Main Message */}
          <h1 className={styles.title}>Order Confirmed!</h1>
          <p className={styles.subtitle}>
            Thank you for your purchase. Your order has been successfully placed and is being prepared for shipment.
          </p>

          {/* Order Number */}
          {orderId && (
            <div className={styles.orderNumberBox}>
              <p className={styles.orderLabel}>Order Reference</p>
              <p className={styles.orderValue}>#{orderId}</p>
            </div>
          )}

          {/* Order Details Grid */}
          {orderDetails && (
            <div className={styles.orderDetailsGrid}>
              <div className={styles.detailItem}>
                <div className={styles.detailIcon}>
                  <FaEnvelope />
                </div>
                <div className={styles.detailText}>
                  <p className={styles.detailLabel}>Confirmation Email</p>
                  <p className={styles.detailValue}>{orderDetails.customer_email || 'N/A'}</p>
                </div>
              </div>

              <div className={styles.detailItem}>
                <div className={styles.detailIcon}>
                  <FaTruck />
                </div>
                <div className={styles.detailText}>
                  <p className={styles.detailLabel}>Estimated Delivery</p>
                  <p className={styles.detailValue}>3-5 Business Days</p>
                </div>
              </div>

              <div className={styles.detailItem}>
                <div className={styles.detailIcon}>
                  <FaClock />
                </div>
                <div className={styles.detailText}>
                  <p className={styles.detailLabel}>Order Status</p>
                  <p className={styles.detailValue} style={{ textTransform: 'capitalize' }}>
                    {orderDetails.status || 'Pending'}
                  </p>
                </div>
              </div>

              <div className={styles.detailItem}>
                <div className={styles.detailIcon}>
                  <FaBox />
                </div>
                <div className={styles.detailText}>
                  <p className={styles.detailLabel}>Total Amount</p>
                  <p className={styles.detailValue}>৳{parseFloat(orderDetails.total || 0).toFixed(2)}</p>
                </div>
              </div>
            </div>
          )}

          {/* Next Steps */}
          <div className={styles.nextStepsSection}>
            <h3>📋 What Happens Next?</h3>
            <div className={styles.stepsList}>
              <div className={styles.step}>
                <div className={styles.stepNumber}>1</div>
                <div className={styles.stepContent}>
                  <p className={styles.stepTitle}>Order Confirmed</p>
                  <p className={styles.stepDesc}>We've received your order and sent a confirmation email</p>
                </div>
              </div>

              <div className={styles.step}>
                <div className={styles.stepNumber}>2</div>
                <div className={styles.stepContent}>
                  <p className={styles.stepTitle}>Payment Verification</p>
                  <p className={styles.stepDesc}>We're verifying your payment and preparing items for packing</p>
                </div>
              </div>

              <div className={styles.step}>
                <div className={styles.stepNumber}>3</div>
                <div className={styles.stepContent}>
                  <p className={styles.stepTitle}>Package Preparation</p>
                  <p className={styles.stepDesc}>Our warehouse team is packing your items with care</p>
                </div>
              </div>

              <div className={styles.step}>
                <div className={styles.stepNumber}>4</div>
                <div className={styles.stepContent}>
                  <p className={styles.stepTitle}>Shipped</p>
                  <p className={styles.stepDesc}>Your package is on its way! You'll receive tracking number via email</p>
                </div>
              </div>

              <div className={styles.step}>
                <div className={styles.stepNumber}>5</div>
                <div className={styles.stepContent}>
                  <p className={styles.stepTitle}>Delivered</p>
                  <p className={styles.stepDesc}>Your package arrives at your doorstep within 3-5 business days</p>
                </div>
              </div>
            </div>
          </div>

          {/* Action Buttons */}
          <div className={styles.actionButtons}>
            <Link href="/" className={styles.btnPrimary}>
              <FaShoppingBag /> Continue Shopping
            </Link>
            <Link href="/profile" className={styles.btnSecondary}>
              <FaBarcode /> Track Order
            </Link>
          </div>

          {/* Help Section */}
          <div className={styles.helpSection}>
            <p>
              <strong>Need help?</strong> Check your email for order details and tracking updates. Contact our support team if you have any questions.
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}
