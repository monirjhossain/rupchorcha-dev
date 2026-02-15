import React from 'react';
import styles from './ExclusiveAccessories.module.css';
import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';

const accessories = [
  {
    id: 1,
    image: '/accessories/bath-brush.webp',
    title: 'Blink',
    desc: 'Electric Waterproof Silicone Body Bath Brush',
    sub: 'Electric Bath Brush',
    link: '/accessory/bath-brush',
  },
  {
    id: 2,
    image: '/accessories/makeup-brush-cleaner.webp',
    title: 'Blink',
    desc: 'Automatic Makeup Brush Cleaner With Dryer Machine',
    sub: 'Automatic Makeup Brush Cleaner',
    link: '/accessory/makeup-brush-cleaner',
  },
  {
    id: 3,
    image: '/accessories/makeup-case.webp',
    title: 'Blink',
    desc: 'Transparent Green Elegant Multi Function Make Up Case Cosmetic Storage Box',
    sub: 'Multi Function Make Up Case',
    link: '/accessory/makeup-case',
  },
];

const ExclusiveAccessories: React.FC = () => {
  return (
    <section className={styles.exclusiveAccessoriesSection}>
      <h2 className={styles.title}>EXCLUSIVE ACCESSORIES</h2>
      <Swiper
        spaceBetween={20}
        slidesPerView={2}
        breakpoints={{
          0: { slidesPerView: 1 },
          600: { slidesPerView: 1 },
          900: { slidesPerView: 2 },
          1200: { slidesPerView: 3 },
        }}
        autoplay={{ delay: 3500, disableOnInteraction: false }}
        navigation
        pagination={{ clickable: true }}
        modules={[Autoplay, Navigation, Pagination]}
        className={styles.cardSlider}
      >
        {accessories.map((item) => (
          <SwiperSlide key={item.id}>
            <a href={item.link} className={styles.card}>
              <div className={styles.cardImageWrap}>
                <img src={item.image} alt={item.title} className={styles.cardImage} />
                <div className={styles.overlay}>
                  <div className={styles.overlayText}>
                    <div className={styles.brandTitle}>{item.title}</div>
                    <div className={styles.desc}>{item.desc}</div>
                  </div>
                  <div className={styles.subAndBtn}>
                    <div className={styles.sub}>{item.sub}</div>
                    <button className={styles.shopBtn}>Shop Now <span>&rarr;</span></button>
                  </div>
                </div>
              </div>
            </a>
          </SwiperSlide>
        ))}
      </Swiper>
    </section>
  );
};

export default ExclusiveAccessories;
