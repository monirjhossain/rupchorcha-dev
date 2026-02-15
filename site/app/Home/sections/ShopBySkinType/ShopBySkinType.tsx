import React, { useState } from 'react';
import styles from './ShopBySkinType.module.css';
import { Swiper, SwiperSlide } from 'swiper/react';
import ProductCard from '@/app/components/ProductCard';
import { useCart } from '@/app/common/CartContext';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';
import useSWR from 'swr';

const tags = [
  { label: 'Cleansers', value: 'cleansers' },
  { label: 'Serums & Treatments', value: 'serums' },
  { label: 'Moisturizing Cream', value: 'moisturizers' },
];

const ShopBySkinType: React.FC = () => {
  const [selectedTag, setSelectedTag] = useState(tags[0].value); // Default: Cleansers
  const API_BASE = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';
  const { data, isLoading } = useSWR(`${API_BASE}/tags/${selectedTag}`, async (url) => {
    const res = await fetch(url);
    if (!res.ok) throw new Error('Failed to fetch');
    const json = await res.json();
    return json?.data?.products?.data || [];
  });
  const products = data || [];
  const { addToCart } = useCart();

  return (
    <section className={styles.shopBySkinTypeSection}>
      <div className={styles.headerRow}>
        <h2 className={styles.title} style={{ fontSize: '1.4rem' }}>Shop By Skin Type</h2>
        <a href="#" className={styles.seeAllBtn}>See All <span>&rarr;</span></a>
      </div>
      <div className={styles.tagRow}>
        {tags.map(tag => (
          <button
            key={tag.value}
            className={selectedTag === tag.value ? styles.activeTag : styles.tagBtn}
            onClick={() => setSelectedTag(tag.value)}
          >
            {tag.label}
          </button>
        ))}
      </div>
      {isLoading ? (
        <div>Loading...</div>
      ) : (
        <Swiper
          spaceBetween={20}
          slidesPerView={2}
          breakpoints={{
            0: { slidesPerView: 2 },
            600: { slidesPerView: 2 },
            900: { slidesPerView: 3 },
            1200: { slidesPerView: 4 },
          }}
          autoplay={{ delay: 2500, disableOnInteraction: false }}
          navigation
          pagination={{ clickable: true }}
          modules={[Autoplay, Navigation, Pagination]}
          className={styles.cardSlider}
        >
          {products.map((product: any) => (
            <SwiperSlide key={product.id}>
              <ProductCard product={product} onAddToCart={() => addToCart({ product_id: product.id, quantity: 1, product })} />
            </SwiperSlide>
          ))}
        </Swiper>
      )}
    </section>
  );
};

export default ShopBySkinType;
