"use client";

import React, { useEffect, useState } from 'react';
import Link from 'next/link';
import { useCart } from '@/app/common/CartContext';
import styles from './FrequentlyBought.module.css';

interface Product {
  id: number;
  name: string;
  slug: string;
  price: number;
  sale_price?: number;
  main_image?: string;
  images?: { url?: string; path?: string }[] | string[];
  brand?: { name: string } | string;
}

const FrequentlyBought: React.FC = () => {
  const { items } = useCart();
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (items.length > 0) {
      // Delay to not block initial render
      const timer = setTimeout(() => {
        fetchFrequentlyBought();
      }, 100);
      return () => clearTimeout(timer);
    }
  }, [items]);

  const fetchFrequentlyBought = async () => {
    setLoading(true);
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';
      const productIds = items.map(item => item.product_id);
      
      const response = await fetch(`${apiUrl}/products/frequently-bought-together`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          product_ids: productIds,
          limit: 10
        })
      });
      
      const data = await response.json();
      if (data.success && data.data && data.data.length > 0) {
        setProducts(data.data);
      }
    } catch (error) {
      console.error('Failed to fetch frequently bought products:', error);
    } finally {
      setLoading(false);
    }
  };

  const getImageUrl = (product: Product): string => {
    const backendBase = process.env.NEXT_PUBLIC_API_URL?.replace('/api', '') || 'http://127.0.0.1:8000';
    
    if (product?.images?.length) {
      const firstImage = product.images[0];
      const img = typeof firstImage === 'string' ? firstImage : (firstImage.url || firstImage.path || '');
      if (img) {
        return img.startsWith('http') ? img : `${backendBase}/storage/${img.replace(/^storage[\\/]/, '')}`;
      }
    }
    
    if (typeof product?.main_image === 'string' && product.main_image) {
      return product.main_image.startsWith('http')
        ? product.main_image
        : `${backendBase}/storage/${product.main_image.replace(/^storage[\\/]/, '')}`;
    }
    
    return 'https://via.placeholder.com/200x200?text=No+Image';
  };

  const getDiscountPercentage = (product: Product): number | null => {
    if (product.sale_price && product.price && product.sale_price < product.price) {
      return Math.round(((product.price - product.sale_price) / product.price) * 100);
    }
    return null;
  };

  const getBrandName = (product: Product): string => {
    if (typeof product.brand === 'object' && product.brand?.name) {
      return product.brand.name;
    }
    if (typeof product.brand === 'string') {
      return product.brand;
    }
    return 'BRAND';
  };

  const scrollLeft = () => {
    const slider = document.getElementById('frequently-bought-cart-slider');
    if (slider) {
      slider.scrollBy({ left: -300, behavior: 'smooth' });
    }
  };

  const scrollRight = () => {
    const slider = document.getElementById('frequently-bought-cart-slider');
    if (slider) {
      slider.scrollBy({ left: 300, behavior: 'smooth' });
    }
  };

  if (loading) {
    return (
      <div className={styles.container}>
        <h2 className={styles.title}>Frequently Bought Together</h2>
        <div className={styles.sliderWrapper}>
          <div className={styles.productsSlider}>
            {[1, 2, 3].map((i) => (
              <div key={i} className={styles.skeleton}></div>
            ))}
          </div>
        </div>
      </div>
    );
  }

  if (products.length === 0) {
    return null;
  }

  return (
    <div className={styles.container}>
      <div className={styles.header}>
        <h2 className={styles.title}>Frequently Bought Together</h2>
        <div className={styles.controls}>
          <button onClick={scrollLeft} className={styles.navButton} aria-label="Scroll left">
            ‹
          </button>
          <button onClick={scrollRight} className={styles.navButton} aria-label="Scroll right">
            ›
          </button>
        </div>
      </div>
      <div className={styles.sliderWrapper}>
        <div className={styles.productsSlider} id="frequently-bought-cart-slider">
        {products.map((product) => {
          const discount = getDiscountPercentage(product);
          const displayPrice = Number(product.sale_price || product.price);
          const originalPrice = Number(product.price);

          return (
            <Link 
              href={`/product/${product.slug}`} 
              key={product.id}
              className={styles.productCard}
            >
              {discount && (
                <span className={styles.discountBadge}>{discount}%</span>
              )}
              <div className={styles.productImage}>
                <img 
                  src={getImageUrl(product)} 
                  alt={product.name}
                  loading="lazy"
                />
              </div>
              <div className={styles.productInfo}>
                <p className={styles.brandName}>{getBrandName(product)}</p>
                <h3 className={styles.productName}>{product.name}</h3>
                <div className={styles.priceContainer}>
                  <span className={styles.currentPrice}>
                    ৳{displayPrice.toFixed(2)}
                  </span>
                  {product.sale_price && originalPrice > Number(product.sale_price) && (
                    <span className={styles.originalPrice}>
                      ৳{originalPrice.toFixed(2)}
                    </span>
                  )}
                </div>
              </div>
            </Link>
          );
        })}
        </div>
      </div>
    </div>
  );
};

export default FrequentlyBought;
