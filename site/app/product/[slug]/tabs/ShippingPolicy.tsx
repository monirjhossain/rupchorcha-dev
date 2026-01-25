"use client";
import React from "react";
import type { Product } from "../types";
import styles from "./ShippingPolicy.module.css";

interface ShippingPolicyProps {
  product: Product;
}

export default function ShippingPolicy({ }: ShippingPolicyProps) {
  return (
    <div className={styles.container}>
      <h2 className={styles.heading}>Shipping & Returns</h2>

      {/* Shipping Information */}
      <div className={styles.section}>
        <div className={styles.iconHeader}>
          <span className={styles.icon}>🚚</span>
          <h3 className={styles.subheading}>Delivery Information</h3>
        </div>
        <div className={styles.infoGrid}>
          <div className={styles.infoCard}>
            <h4 className={styles.infoTitle}>Inside Dhaka</h4>
            <p className={styles.infoText}>2-3 Business Days</p>
            <p className={styles.infoPrice}>৳60 Delivery Charge</p>
          </div>
          <div className={styles.infoCard}>
            <h4 className={styles.infoTitle}>Outside Dhaka</h4>
            <p className={styles.infoText}>3-5 Business Days</p>
            <p className={styles.infoPrice}>৳120 Delivery Charge</p>
          </div>
          <div className={styles.infoCard}>
            <h4 className={styles.infoTitle}>Cash on Delivery</h4>
            <p className={styles.infoText}>Available</p>
            <p className={styles.infoPrice}>Pay when you receive</p>
          </div>
        </div>
      </div>

      {/* Return Policy */}
      <div className={styles.section}>
        <div className={styles.iconHeader}>
          <span className={styles.icon}>↩️</span>
          <h3 className={styles.subheading}>Return Policy</h3>
        </div>
        <ul className={styles.policyList}>
          <li>✓ 7-day return policy for most products</li>
          <li>✓ Product must be unused and in original packaging</li>
          <li>✓ Return shipping cost may apply based on the reason</li>
          <li>✓ Refunds processed within 7-10 business days</li>
          <li>✓ Exchange available for defective or damaged items</li>
        </ul>
      </div>

      {/* Exchange Policy */}
      <div className={styles.section}>
        <div className={styles.iconHeader}>
          <span className={styles.icon}>🔄</span>
          <h3 className={styles.subheading}>Exchange Policy</h3>
        </div>
        <p className={styles.description}>
          We offer easy exchange for size, color, or defective products within 7 days of delivery. 
          Contact our customer support to initiate an exchange request.
        </p>
      </div>

      {/* Refund Policy */}
      <div className={styles.section}>
        <div className={styles.iconHeader}>
          <span className={styles.icon}>💰</span>
          <h3 className={styles.subheading}>Refund Policy</h3>
        </div>
        <p className={styles.description}>
          Once your return is received and inspected, we will send you an email notification. 
          If approved, your refund will be processed to your original payment method within 7-10 business days.
        </p>
      </div>

      {/* Contact Info */}
      <div className={styles.contactBox}>
        <h4 className={styles.contactTitle}>Need Help?</h4>
        <p className={styles.contactText}>
          For any shipping or return queries, contact our customer support team:
        </p>
        <div className={styles.contactDetails}>
          <p>📞 +880 1234-567890</p>
          <p>📧 support@rupchorcha.com</p>
          <p>⏰ 10 AM - 8 PM (Sat-Thu)</p>
        </div>
      </div>
    </div>
  );
}
