"use client";
import React from "react";
import HeroSlider from "./components/HeroSlider";
import styles from "./MobileHome.module.css";

const MobileHome: React.FC = () => {
  return (
    <div className={styles.mobileHome}>
      <HeroSlider />

      <section className={styles.bannerStrip}>
        <div className={styles.bannerTall} />
      </section>

      <section className={styles.bannerGridTwo}>
        <div className={styles.bannerCard} />
        <div className={styles.bannerCard} />
      </section>

      <section className={styles.bannerStrip}>
        <div className={styles.bannerWide} />
      </section>

      <section className={styles.labelSection}>
        <h2 className={styles.sectionTitle}>Top Values & Offers</h2>
        <div className={styles.bannerStripRow}>
          <div className={styles.bannerWideSmall} />
        </div>
      </section>

      <section className={styles.labelSection}>
        <h2 className={styles.sectionTitle}>Shop By Category</h2>
        <div className={styles.iconGrid}>
          {Array.from({ length: 8 }).map((_, idx) => (
            <div key={idx} className={styles.iconCard} />
          ))}
        </div>
      </section>

      <section className={styles.labelSection}>
        <h2 className={styles.sectionTitle}>Shop By Concern</h2>
        <div className={styles.iconGrid}>
          {Array.from({ length: 8 }).map((_, idx) => (
            <div key={idx} className={styles.iconCardTall} />
          ))}
        </div>
      </section>
    </div>
  );
};

export default MobileHome;
