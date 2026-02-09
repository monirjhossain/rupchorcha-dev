"use client";
import React, { useState, useEffect } from "react";
import HeroSlider, { Slide } from "./Home/components/HeroSlider";
import { heroSlides } from "./Home/heroSlides";
import MobileHome from "./Home/MobileHome";
import styles from "./Home/Home.module.css";
// import Image from "next/image"; // Unused
import { useFeaturedProducts } from "@/src/hooks/useProducts";

const Home: React.FC = () => {
  const [slides, setSlides] = useState<Slide[]>([]);
  // Use custom hook for products
  const { products, isLoading: productsLoading } = useFeaturedProducts();
  const [isMobile, setIsMobile] = useState(false);

  useEffect(() => {
    const checkMobile = () => {
      if (typeof window !== "undefined") {
        setIsMobile(window.innerWidth <= 768);
      }
    };
    checkMobile();
    if (typeof window !== "undefined") {
      window.addEventListener("resize", checkMobile);
    }

    const SLIDER_CACHE_KEY = 'sliders_cache_v2';
    const SLIDER_CACHE_TIME_KEY = 'sliders_cache_time_v2';
    // Try to load sliders from cache
    const cachedSliders = typeof window !== 'undefined' ? localStorage.getItem(SLIDER_CACHE_KEY) : null;
    const slidersCacheTime = typeof window !== 'undefined' ? localStorage.getItem(SLIDER_CACHE_TIME_KEY) : null;
    const now = Date.now();

    const fetchSliders = async () => {
      // TODO: Replace with actual API call if backend supports sliders
      setSlides(heroSlides);
      if (typeof window !== 'undefined') {
        localStorage.setItem(SLIDER_CACHE_KEY, JSON.stringify(heroSlides));
        localStorage.setItem(SLIDER_CACHE_TIME_KEY, Date.now().toString());
      }
    };

    if (cachedSliders && slidersCacheTime && (now - parseInt(slidersCacheTime)) < 300000) {
      setTimeout(() => setSlides(JSON.parse(cachedSliders)), 0);
    } else {
      fetchSliders();
    }

    return () => {
      if (typeof window !== "undefined") {
        window.removeEventListener("resize", checkMobile);
      }
    };
  }, []);

  if (isMobile) {
    return <MobileHome />;
  }

  return (
    <div className={styles.home}>
      {/* Hero Slider */}
      <HeroSlider slides={slides} />

      {/* Desktop-only stacked sections (header excluded) */}
      <main className={styles.desktopMain}>
        {/* Thin double banner row */}
        <section className={styles.thinBannerRow}>
          <div className={styles.thinBanner} />
          <div className={styles.thinBanner} />
        </section>

        {/* 3-column deals row */}
        <section className={styles.dealsGrid}>
          <div className={styles.dealCard} />
          <div className={styles.dealCard} />
          <div className={styles.dealCard} />
        </section>

        {/* Top brands & offers style grid */}
        <section className={styles.topBrandGrid}>
          <div className={styles.topBrandWide} />
          <div className={styles.topBrandCard} />
          <div className={styles.topBrandCard} />
          <div className={styles.topBrandCard} />
          <div className={styles.topBrandCard} />
        </section>

        {/* Limited time offers pink cards - Now using real data count to render placeholders or cards */}
        <section className={styles.limitedOffers}>
          <h2 className={styles.sectionHeading}>Limited Time Offers</h2>
          <div className={styles.offerRow}>
            {!productsLoading && products && products.length > 0 ? (
                products.slice(0, 4).map((p, i) => (
                    <div key={p.id} className={styles.offerCard} title={p.name}>
                        <div style={{padding: '10px', height: '100%', display: 'flex', flexDirection: 'column', justifyContent: 'flex-end'}}>
                           <p style={{fontWeight: 'bold', fontSize: '0.9rem', marginBottom: '5px', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap'}}>{p.name}</p>
                           <p style={{color: '#e91e63', fontWeight: 'bold'}}>Tk {p.price}</p>
                        </div>
                    </div>
                ))
            ) : (
                <>
                    <div className={styles.offerCard} />
                    <div className={styles.offerCard} />
                    <div className={styles.offerCard} />
                    <div className={styles.offerCard} />
                </>
            )}
          </div>
        </section>

        {/* Shop by category */}
        <section className={styles.categorySection}>
          <h2 className={styles.sectionHeading}>Shop Beauty Products By Category</h2>
          <div className={styles.categoryGrid}>
            {Array.from({ length: 8 }).map((_, idx) => (
              <div key={idx} className={styles.categoryCard} />
            ))}
          </div>
        </section>

        {/* Shop by concern */}
        <section className={styles.concernSection}>
          <h2 className={styles.sectionHeading}>Shop By Concern</h2>
          <div className={styles.concernGrid}>
            {Array.from({ length: 12 }).map((_, idx) => (
              <div key={idx} className={styles.concernCard} />
            ))}
          </div>
        </section>
      </main>
    </div>
  );
};

export default Home;
