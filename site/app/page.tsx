"use client";
import React, { useState, useEffect } from "react";
import Header from "./components/Header";
import HeroSlider from "./components/Home/HeroSlider";
import ShopByCategorySection from "./Home/sections/ShopByCategorySection";
import BrandOfferSection from "./Home/sections/BrandOfferSection";
import styles from "./Home/Home.module.css";
import Image from "next/image";
import Link from "next/link";

interface Product {
  id: number;
  name: string;
  price: number;
  images?: { url: string }[];
}

const Home: React.FC = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const cachedProducts = typeof window !== "undefined" ? localStorage.getItem("products_cache") : null;
    const productsCacheTime = typeof window !== "undefined" ? localStorage.getItem("products_cache_time") : null;
    const now = Date.now();

    const fetchProducts = async () => {
      // TODO: Replace with actual API call
      setTimeout(() => {
        setProducts([]);
        setLoading(false);
      }, 0);
    };

    if (cachedProducts && productsCacheTime && now - parseInt(productsCacheTime) < 300000) {
      setTimeout(() => {
        setProducts(JSON.parse(cachedProducts));
        setLoading(false);
      }, 0);
    } else {
      fetchProducts();
    }
  }, []);

  return (
    <main>
      <div className={styles.home}>
        {/* Hero Slider */}
        <HeroSlider />
        <BrandOfferSection />
        {/* Mega Deals Section */}
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
                {products.map((product) => (
                  <div key={product.id} className={styles.productCard}>
                    <Link href={`/products/${product.id}`}>
                      <Image
                        src={product.images?.[0]?.url || "https://via.placeholder.com/300"}
                        alt={product.name}
                        width={300}
                        height={300}
                        style={{ objectFit: "cover" }}
                      />
                      <h3>{product.name}</h3>
                      <p className={styles.price}>${product.price}</p>
                      <button className="btn-primary">View Details</button>
                    </Link>
                  </div>
                ))}
              </div>
            )}
          </div>
        </section>
      </div>
    </main>
  );
};

export default Home;
