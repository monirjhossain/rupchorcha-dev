"use client";

import React, { useEffect, useState } from 'react';
import Link from 'next/link';
import { useCart } from '@/app/common/CartContext';
import styles from './PickedForYou.module.css';

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

const PickedForYou: React.FC = () => {
  const { items } = useCart();
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (items.length > 0) {
      fetchRelatedProducts();
    } else {
      fetchRecommendedProducts();
    }
  }, [items]);

  const fetchRelatedProducts = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';
      const productIds = items.map(item => item.product_id);
      
      const response = await fetch(`${apiUrl}/products/related-by-cart`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          product_ids: productIds,
          limit: 8
        })
      });
      
      const data = await response.json();
      if (data.success && data.data) {
        setProducts(data.data);
      }
    } catch (error) {
      console.error('Failed to fetch related products:', error);
      // Fallback to random products
      fetchRecommendedProducts();
    } finally {
      setLoading(false);
    }
  };

  const fetchRecommendedProducts = async () => {
    try {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';
      const response = await fetch(`${apiUrl}/products?limit=6`);
      const data = await response.json();
      if (data.success && data.data) {
        setProducts(data.data.slice(0, 6));
      }
    } catch (error) {
      console.error('Failed to fetch recommendations:', error);
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

  if (loading) {
    return (
      <div className={styles.pickedForYou}>
        <h2 className={styles.sectionTitle}>Picked For You</h2>
        <div className={styles.productsGrid}>
          {[1, 2, 3, 4].map((i) => (
            <div key={i} className={styles.productCardSkeleton}></div>
          ))}
        </div>
      </div>
    );
  }

  if (products.length === 0) {
    return null;
  }

  return (
    <div className={styles.pickedForYou}>
      <h2 className={styles.sectionTitle}>Picked For You</h2>
      <div className={styles.productsGrid}>
        {products.map((product) => {
          const discount = getDiscountPercentage(product);

          return (
            <Link
              key={product.id}
              href={`/product/${product.slug}`}
              className={styles.productCard}
            >
              {discount && (
                <div className={styles.discountBadge}>-{discount}%</div>
              )}
              
              <div className={styles.productImageWrapper}>
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img
                  src={getImageUrl(product)}
                  alt={product.name}
                  className={styles.productImage}
                />
              </div>

              <div className={styles.productInfo}>
                <div className={styles.brandName}>{getBrandName(product)}</div>
                <div className={styles.productName}>{product.name}</div>
                <div className={styles.priceRow}>
                  {product.sale_price && product.sale_price < product.price ? (
                    <>
                      <span className={styles.originalPrice}>৳ {Number(product.price).toFixed(0)}</span>
                      <span className={styles.salePrice}>৳ {Number(product.sale_price).toFixed(0)}</span>
                    </>
                  ) : (
                    <span className={styles.salePrice}>৳ {Number(product.price).toFixed(0)}</span>
                  )}
                </div>
              </div>
            </Link>
          );
        })}
      </div>
    </div>
  );
};

export default PickedForYou;
