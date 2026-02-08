import React from "react";
import styles from "./Footer.module.css";

const Footer: React.FC = () => {
  const year = new Date().getFullYear();

  return (
    <footer className={styles.footer}>
      {/* Top stats strip */}
      <div className={styles.statsRow}>
        <div className={styles.statItem}>
          <span className={styles.statLabel}>100% Authentic</span>
          <span className={styles.statSub}>Beauty Products</span>
        </div>
        <div className={styles.statItem}>
          <span className={styles.statLabel}>15000+ Products</span>
          <span className={styles.statSub}>From Trusted Brands</span>
        </div>
        <div className={styles.statItem}>
          <span className={styles.statLabel}>Fast Delivery</span>
          <span className={styles.statSub}>Across Bangladesh</span>
        </div>
        <div className={styles.statItem}>
          <span className={styles.statLabel}>Secure Payments</span>
          <span className={styles.statSub}>Multiple Options</span>
        </div>
      </div>

      {/* Main footer columns */}
      <div className={styles.footerInner}>
        <div className={styles.brandBlock}>
          <h2 className={styles.brandName}>RUPCHORCHA</h2>
          <ul className={styles.linkList}>
            <li>Our Story</li>
            <li>Store Locations</li>
            <li>Join Our Team</li>
            <li>Authenticity</li>
          </ul>
        </div>

        <div className={styles.columnsWrap}>
          <div className={styles.column}>
            <h3 className={styles.columnTitle}>Top Categories</h3>
            <ul className={styles.linkList}>
              <li>Makeup</li>
              <li>Skin</li>
              <li>Hair Care</li>
              <li>Bath &amp; Body</li>
              <li>Mom &amp; Baby</li>
            </ul>
          </div>
          <div className={styles.column}>
            <h3 className={styles.columnTitle}>Quick Links</h3>
            <ul className={styles.linkList}>
              <li>Offers</li>
              <li>New Arrivals</li>
              <li>Best Sellers</li>
              <li>Gift Cards</li>
            </ul>
          </div>
          <div className={styles.column}>
            <h3 className={styles.columnTitle}>Help</h3>
            <ul className={styles.linkList}>
              <li>Contact Us</li>
              <li>Shipping Policy</li>
              <li>Returns &amp; Refunds</li>
              <li>FAQs</li>
            </ul>
          </div>
        </div>
      </div>

      {/* Bottom bar */}
      <div className={styles.bottomBar}>
        <span className={styles.copy}>© {year} Rupchorcha. All Rights Reserved.</span>
        <div className={styles.bottomLinks}>
          <span>Privacy Policy</span>
          <span>Terms &amp; Conditions</span>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
