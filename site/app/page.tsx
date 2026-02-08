"use client";
import React, { useState, useEffect } from "react";
import HeroSlider, { Slide } from "./Home/components/HeroSlider";
import MobileHome from "./Home/MobileHome";
import styles from "./Home/Home.module.css";
import Image from "next/image";

interface Product {
  id: number;
  name: string;
  price: number;
  images?: { url: string }[];
}

const Home: React.FC = () => {
  const [slides, setSlides] = useState<Slide[]>([]);
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
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
      // TODO: Replace with actual API call
      // Fallback uses the same local slider assets as the default slider,
      // so desktop view always shows images correctly.
      const fallbackSlides: Slide[] = [
        {
          id: 1,
          image: '/slider/newyear.webp',
        },
        {
          id: 2,
          image: '/slider/christmas.png',
        },
        {
          id: 3,
          image: '/slider/Freedom.webp',
        },
        {
          id: 4,
          image: '/slider/Freedom1.webp',
        },
        {
          id: 5,
          image: '/slider/Freedom2.webp',
        },
      ];
      setSlides(fallbackSlides);
      if (typeof window !== 'undefined') {
        localStorage.setItem(SLIDER_CACHE_KEY, JSON.stringify(fallbackSlides));
        localStorage.setItem(SLIDER_CACHE_TIME_KEY, Date.now().toString());
      }
    };

    if (cachedSliders && slidersCacheTime && (now - parseInt(slidersCacheTime)) < 300000) {
      setTimeout(() => setSlides(JSON.parse(cachedSliders)), 0);
    } else {
      fetchSliders();
    }

    // Try to load products from cache
    const cachedProducts = typeof window !== 'undefined' ? localStorage.getItem('products_cache') : null;
    const productsCacheTime = typeof window !== 'undefined' ? localStorage.getItem('products_cache_time') : null;

    const fetchProducts = async () => {
      // TODO: Replace with actual API call
      setTimeout(() => {
        setProducts([]);
        setLoading(false);
      }, 0);
    };

    if (cachedProducts && productsCacheTime && (now - parseInt(productsCacheTime)) < 300000) {
      setTimeout(() => {
        setProducts(JSON.parse(cachedProducts));
        setLoading(false);
      }, 0);
    } else {
      fetchProducts();
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

        {/* Limited time offers pink cards */}
        <section className={styles.limitedOffers}>
          <h2 className={styles.sectionHeading}>Limited Time Offers</h2>
          <div className={styles.offerRow}>
            <div className={styles.offerCard} />
            <div className={styles.offerCard} />
            <div className={styles.offerCard} />
            <div className={styles.offerCard} />
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
