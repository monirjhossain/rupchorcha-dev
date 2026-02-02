"use client";
import React, { useState, useEffect } from "react";
import HeroSlider, { Slide } from "./Home/components/HeroSlider";
import BrandOfferSection from "./Home/sections/BrandOfferSection";
import ShopByCategorySection from "./Home/sections/ShopByCategorySection";
import TrendingSection from "./Home/sections/TrendingSection/TrendingSection";
import OffersToSayYesSection from "./Home/sections/OffersToSayYesSection/OffersToSayYesSection";
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

  useEffect(() => {
    // Try to load sliders from cache
    const cachedSliders = typeof window !== 'undefined' ? localStorage.getItem('sliders_cache') : null;
    const slidersCacheTime = typeof window !== 'undefined' ? localStorage.getItem('sliders_cache_time') : null;
    const now = Date.now();

    const fetchSliders = async () => {
      // TODO: Replace with actual API call
      const fallbackSlides: Slide[] = [
        {
          id: 1,
          image: '/hero/slide1.jpg',
          title: 'Mega Sale!',
          description: 'Up to 50% off on selected products.'
        }
      ];
      setSlides(fallbackSlides);
      if (typeof window !== 'undefined') {
        localStorage.setItem('sliders_cache', JSON.stringify(fallbackSlides));
        localStorage.setItem('sliders_cache_time', Date.now().toString());
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
  }, []);

  return (
    <div className={styles.home}>
      {/* Hero Slider */}
      <HeroSlider slides={slides} />
      {/* Shop By Category Section */}
      <ShopByCategorySection />
      {/* Trending Section */}
      <TrendingSection />
      {/* Offers To Say Yes Section */}
      <OffersToSayYesSection />
      {/* Featured Products */}
    </div>
  );
};

export default Home;
