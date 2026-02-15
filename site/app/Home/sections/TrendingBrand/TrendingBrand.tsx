
import React from 'react';
import styles from './TrendingBrand.module.css';
import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';
import Link from 'next/link';

const trending = [
  {
    id: 1,
    image: '/trending/glam-makeup.webp',
    link: '/trending/makeup',
    alt: 'Glam it your way! Makeup',
  },
  {
    id: 2,
    image: '/trending/thai-beauty.webp',
    link: '/trending/thai-beauty',
    alt: 'Bright, Calm, Glowing Skin',
  },
  {
    id: 3,
    image: '/trending/self-care-under-1k.webp',
    link: '/trending/self-care-under-1k',
    alt: 'Self Care Under 1K',
  },
  // Add more if needed
];

const TrendingBrand: React.FC = () => {
  return (
    <section className={styles.trendingBrandSection}>
      <h2 className={styles.title} style={{ fontSize: '1.5rem' }}>TRENDING</h2>
      <Swiper
        spaceBetween={20}
        slidesPerView={4}
        breakpoints={{
          0: { slidesPerView: 1 },
          600: { slidesPerView: 1 },
          900: { slidesPerView: 2 },
          1200: { slidesPerView: 4 },
        }}
        autoplay={{ delay: 2500, disableOnInteraction: false }}
        navigation
        pagination={{ clickable: true }}
        modules={[Autoplay, Navigation, Pagination]}
        className={styles.cardSlider}
      >
        {trending.map((item) => (
          <SwiperSlide key={item.id}>
            <Link href={item.link} className={styles.card} style={{ border: '1.5px solid transparent', transition: 'border 0.2s, color 0.2s' }}>
              <div className={styles.cardImageWrap}>
                <img src={item.image} alt={item.alt} className={styles.cardImage} />
              </div>
            </Link>
          </SwiperSlide>
        ))}
      </Swiper>
    </section>
  );
};

export default TrendingBrand;
