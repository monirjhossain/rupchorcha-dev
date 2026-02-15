import React from 'react';
import styles from './TopBrands.module.css';
import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';

const brands = [
  {
    id: 1,
    image: '/brands/cosrx.webp',
    name: 'COSRX',
    offer: 'Up to 44% Off',
    sub: 'K-Beauty Must Have',
    link: '/brand/cosrx',
  },
  {
    id: 2,
    image: '/brands/skin1004.webp',
    name: 'SKIN1004',
    offer: 'Up to 26% off',
    sub: 'On Entire Range',
    link: '/brand/skin1004',
  },
  {
    id: 3,
    image: '/brands/anua.webp',
    name: 'ANUA',
    offer: 'Up to 35% Off',
    sub: 'On Entire Brand',
    link: '/brand/anua',
  },
  {
    id: 4,
    image: '/brands/beautyofjoseon.webp',
    name: 'BEAUTY OF JOSEON',
    offer: 'Up to 29% off',
    sub: 'On Bestsellers',
    link: '/brand/beautyofjoseon',
  },
  {
    id: 5,
    image: '/brands/dotkey.webp',
    name: 'DOT & KEY',
    offer: 'Up to 22% Off',
    sub: 'On Entire Brand',
    link: '/brand/dotkey',
  },
];

const TopBrands: React.FC = () => {
  return (
    <section className={styles.topBrandsSection}>
      <h2 className={styles.title}>BEST OFFER BRANDS</h2>
      <div className={styles.desktopGrid}>
        {brands.map((brand) => (
          <a href={brand.link} className={styles.card} key={brand.id}>
            <div className={styles.cardImageWrap}>
              <img src={brand.image} alt={brand.name} className={styles.cardImage} />
            </div>
            <div className={styles.cardInfo}>
              <div className={styles.brandName}>{brand.name}</div>
              <div className={styles.offerText}>{brand.offer}</div>
              <div className={styles.subText}>{brand.sub}</div>
            </div>
          </a>
        ))}
      </div>
      <div className={styles.mobileSlider}>
        <Swiper
          spaceBetween={20}
          slidesPerView={2}
          breakpoints={{
            0: { slidesPerView: 2 },
            600: { slidesPerView: 2 },
          }}
          autoplay={{ delay: 2500, disableOnInteraction: false }}
          navigation
          pagination={{ clickable: true }}
          modules={[Autoplay, Navigation, Pagination]}
          className={styles.cardSlider}
        >
          {brands.map((brand) => (
            <SwiperSlide key={brand.id}>
              <a href={brand.link} className={styles.card}>
                <div className={styles.cardImageWrap}>
                  <img src={brand.image} alt={brand.name} className={styles.cardImage} />
                </div>
                <div className={styles.cardInfo}>
                  <div className={styles.brandName}>{brand.name}</div>
                  <div className={styles.offerText}>{brand.offer}</div>
                  <div className={styles.subText}>{brand.sub}</div>
                </div>
              </a>
            </SwiperSlide>
          ))}
        </Swiper>
      </div>
    </section>
  );
};

export default TopBrands;
