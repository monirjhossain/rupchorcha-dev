"use client";
import React from "react";
import type { Product } from "../types";
import styles from "./TrustBadges.module.css";

interface TrustBadgesProps {
  product: Product;
}

export default function TrustBadges({ product }: TrustBadgesProps) {
  const warranty = (product as Record<string, unknown>).warranty as string || "1 Year Warranty";
  
  const badges = [
    {
      icon: "🔒",
      title: "100% Secure Payment",
      description: "Your payment information is processed securely with SSL encryption",
    },
    {
      icon: "✓",
      title: "100% Authentic Products",
      description: "We guarantee all products are genuine and sourced directly from authorized suppliers",
    },
    {
      icon: "💰",
      title: "Money-Back Guarantee",
      description: "If you're not satisfied, return within 7 days for a full refund",
    },
    {
      icon: "🛡️",
      title: warranty,
      description: "Manufacturer warranty included with every purchase",
    },
    {
      icon: "📞",
      title: "24/7 Customer Support",
      description: "Our support team is always here to help you with any questions",
    },
    {
      icon: "🚚",
      title: "Fast & Safe Delivery",
      description: "Products are carefully packed and delivered to your doorstep",
    },
  ];

  return (
    <div className={styles.container}>
      <h2 className={styles.heading}>Trust & Safety</h2>
      <p className={styles.intro}>
        Shop with confidence! We prioritize your safety and satisfaction with every purchase.
      </p>

      <div className={styles.badgeGrid}>
        {badges.map((badge, index) => (
          <div key={index} className={styles.badgeCard}>
            <div className={styles.badgeIcon}>{badge.icon}</div>
            <h3 className={styles.badgeTitle}>{badge.title}</h3>
            <p className={styles.badgeDescription}>{badge.description}</p>
          </div>
        ))}
      </div>

      {/* Payment Methods */}
      <div className={styles.paymentSection}>
        <h3 className={styles.subheading}>Accepted Payment Methods</h3>
        <div className={styles.paymentMethods}>
          <div className={styles.paymentBadge}>💳 Credit/Debit Card</div>
          <div className={styles.paymentBadge}>📱 bKash</div>
          <div className={styles.paymentBadge}>📱 Nagad</div>
          <div className={styles.paymentBadge}>💵 Cash on Delivery</div>
          <div className={styles.paymentBadge}>🏦 Bank Transfer</div>
        </div>
      </div>
    </div>
  );
}
