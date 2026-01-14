"use client";
import React, { useState, useEffect } from 'react';

import MegaDealsSection from './sections/MegaDealsSection';
import TopBannerSlider from './sections/TopBannerSlider';
import HeroSlider, { Slide } from './components/HeroSlider';
import BrandOfferSection from './sections/BrandOfferSection';
import ShopByCategorySection from './sections/ShopByCategorySection';
import styles from './Home.module.css';
import Image from 'next/image';


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

    // ...existing code...

    return (
        <div className={styles.home}>
            {/* Top Banner Slider removed */}
            {/* Hero Slider */}
            <HeroSlider slides={slides} />
            <BrandOfferSection />
            {/* Mega Deals Section */}
            <MegaDealsSection />
            {/* Shop By Category Section */}
            <ShopByCategorySection />
            {/* Featured Products */}
            <section className={styles.featuredProducts}>
                <div className="container">
                    <h2 className="section-title">Featured Products</h2>
                    {loading ? (
                        <div className={styles.loading}>Loading products...</div>
                    ) : (
                        <div className={styles.productsGrid}>
                            {products.map(product => (
                                <div key={product.id} className={styles.productCard}>
                                    {/* Replace with Next.js Link and Image */}
                                    <Image 
                                        src={product.images?.[0]?.url || 'https://via.placeholder.com/300'} 
                                        alt={product.name}
                                        width={300}
                                        height={300}
                                        style={{objectFit:'cover'}}
                                    />
                                    <h3>{product.name}</h3>
                                    <p className={styles.price}>${product.price}</p>
                                    <button className="btn-primary">View Details</button>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </section>
        </div>
    );
};

export default Home;
