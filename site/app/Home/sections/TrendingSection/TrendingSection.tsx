import React from 'react';
import styles from './TrendingSection.module.css';
import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/css';
import 'swiper/css/pagination';
import { Pagination, Autoplay } from 'swiper/modules';

import Link from 'next/link';
// Dummy data for now
const trendingItems = [
  {
    id: 1,
    image: '/trending/j-beauty.webp',
    url: '/products/1',
  },
  {
    id: 2,
    image: '/trending/j-beauty2.webp',
    url: '/products/2',
  },
  {
    id: 3,
    image: '/trending/j-beauty3.webp',
    url: '/products/3',
  },
  {
    id: 4,
    image: '/trending/j-beauty4.webp',
    url: '/category/skin-care',
  },
];

const TrendingSection: React.FC = () => (
  <section className={styles.trendingSection}>
    <h2 className={styles.heading}>TRENDING</h2>
    <Swiper
      spaceBetween={12}
      slidesPerView={1}
      breakpoints={{
        640: { slidesPerView: 1 },
        900: { slidesPerView: 2 },
        1200: { slidesPerView: 3 },
      }}
      pagination={{ clickable: true }}
      autoplay={{ delay: 3000, disableOnInteraction: false }}
      modules={[Pagination, Autoplay]}
      className={styles.trendingSwiper}
    >
      {trendingItems.map(item => (
        <SwiperSlide key={item.id} className={styles.trendingSlide}>
          <Link href={item.url} className={styles.trendingCard}>
            <img src={item.image} alt="Trending image" className={styles.trendingImage} />
          </Link>
        </SwiperSlide>
      ))}
    </Swiper>
  </section>
);

export default TrendingSection;
