"use client";
import React, { useState } from "react";
import styles from "./ProductTabs.module.css";
import ProductDescription from "./tabs/ProductDescription";
import TrustBadges from "./tabs/TrustBadges";
import ShippingPolicy from "./tabs/ShippingPolicy";
import FAQSection from "./tabs/FAQSection";
import SellerBrandInfo from "./tabs/SellerBrandInfo";
import type { Product } from "./types";

interface ProductTabsProps {
  product: Product;
}

type TabKey = "description" | "trust" | "shipping" | "faq" | "brand";

interface Tab {
  key: TabKey;
  label: string;
  icon: string;
}

const tabs: Tab[] = [
  { key: "description", label: "Description", icon: "📄" },
  { key: "trust", label: "Trust & Safety", icon: "🛡️" },
  { key: "shipping", label: "Shipping & Returns", icon: "🚚" },
  { key: "faq", label: "FAQs", icon: "❓" },
  { key: "brand", label: "Brand Info", icon: "🏷️" },
];

export default function ProductTabs({ product }: ProductTabsProps) {
  const [activeTab, setActiveTab] = useState<TabKey>("description");

  const renderTabContent = () => {
    switch (activeTab) {
      case "description":
        return <ProductDescription product={product} />;
      case "trust":
        return <TrustBadges product={product} />;
      case "shipping":
        return <ShippingPolicy product={product} />;
      case "faq":
        return <FAQSection product={product} />;
      case "brand":
        return <SellerBrandInfo product={product} />;
      default:
        return null;
    }
  };

  return (
    <section className={styles.tabsContainer}>
      {/* Tab Navigation */}
      <div className={styles.tabNav}>
        {tabs.map((tab) => (
          <button
            key={tab.key}
            className={`${styles.tabButton} ${activeTab === tab.key ? styles.active : ""}`}
            onClick={() => setActiveTab(tab.key)}
            aria-selected={activeTab === tab.key}
            role="tab"
          >
            <span className={styles.tabIcon}>{tab.icon}</span>
            <span className={styles.tabLabel}>{tab.label}</span>
          </button>
        ))}
      </div>

      {/* Tab Content */}
      <div className={styles.tabContent} role="tabpanel">
        {renderTabContent()}
      </div>
    </section>
  );
}
