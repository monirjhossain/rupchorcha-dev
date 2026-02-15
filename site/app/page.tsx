"use client";
import React, { useState, useEffect } from "react";
import HeroSlider, { Slide } from "./Home/components/HeroSlider";
import { heroSlides } from "./Home/heroSlides";
import MobileHome from "./Home/MobileHome";
import styles from "./Home/Home.module.css";
import TopCategories from "./Home/sections/TopCategories/TopCategories";
import TrendingBrand from "./Home/sections/TrendingBrand/TrendingBrand";
import ShopBySkinType from "./Home/sections/ShopBySkinType/ShopBySkinType";
import TopBrands from "./Home/sections/TopBrands/TopBrands";
import FreeDeliveryOffer from "./Home/sections/FreeDeliveryOffer/FreeDeliveryOffer";
import ExclusiveAccessories from "./Home/sections/ExclusiveAccessories/ExclusiveAccessories";
import ComboOffers from "./Home/sections/ComboOffers/ComboOffers";
import BuyOneGetOne from "./Home/sections/BuyOneGetOne/BuyOneGetOne";

const Home: React.FC = () => {
  const [slides, setSlides] = useState<Slide[]>([]);
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
      <main className={styles.desktopMain}>
        <TopCategories />
        <TrendingBrand />
        <ShopBySkinType />
        <TopBrands />
        <FreeDeliveryOffer />
        <ExclusiveAccessories />
        <ComboOffers />
        <BuyOneGetOne />
      </main>
    </div>
  );
};

export default Home;
