
import React from 'react';
import styles from './FreeDeliveryOffer.module.css';
import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';
import Link from 'next/link';

const brands = [
  {
    id: 1,
    image: '/free-delivery/abib.webp',
    name: 'Abib',
    offer: 'Up to 35% Off',
    link: '/brand/abib',
    sub: 'Free Delivery over ৳1500',
  },
  {
    id: 2,
    image: '/free-delivery/dabo.webp',
    name: 'DABO',
    offer: 'Up to 28% Off',
    link: '/brand/dabo',
    sub: 'Free Delivery over ৳1500',
  },
  {
    id: 3,
    image: '/free-delivery/haruharu.webp',
    name: 'HARUHARU',
    offer: 'Up to 18% Off',
    link: '/brand/haruharu',
    sub: 'Free Delivery over ৳1500',
  },
  {
    id: 4,
    image: '/free-delivery/numbuzin.webp',
    name: 'NUMBUZIN',
    offer: 'Up to 26% Off',
    link: '/brand/numbuzin',
    sub: 'Free Delivery over ৳1500',
  },
  {
    id: 5,
    image: '/free-delivery/vtcosmetics.webp',
    name: 'VT COSMETICS',
    offer: 'Up to 18% Off.',
    link: '/brand/vt-cosmetics',
    sub: 'Free Delivery over ৳1500',
  },
];

const FreeDeliveryOffer: React.FC = () => {
  return (
    <section className={styles.freeDeliveryOfferSection}>
      <h2 className={styles.title} style={{ fontSize: '1.5rem' }}>FREE DELIVERY AVAILABLE</h2>
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
        {brands.map((brand) => (
          <SwiperSlide key={brand.id}>
            <Link href={brand.link} className={styles.card} style={{ border: '1.5px solid transparent', transition: 'border 0.2s, color 0.2s' }}>
              <div className={styles.cardImageWrap}>
                <img src={brand.image} alt={brand.name} className={styles.cardImage} />
              </div>
              <div className={styles.cardInfo}>
                <div className={styles.brandName}>{brand.name}</div>
                <div className={styles.offerText}>{brand.offer}</div>
                <div className={styles.subText}>{brand.sub}</div>
              </div>
            </Link>
          </SwiperSlide>
        ))}
      </Swiper>
    </section>
  );
};

export default FreeDeliveryOffer;
