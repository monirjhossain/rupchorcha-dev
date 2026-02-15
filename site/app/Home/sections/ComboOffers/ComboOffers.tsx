
import React, { useState } from 'react';
import styles from './ComboOffers.module.css';
import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';
import Link from 'next/link';

const tags = ['All', 'Face', 'Body', 'Hair', 'Popular'];

const combos = [
  {
    id: 1,
    image: '/combo/combo1.webp',
    title: 'Combo Pack 1',
    subtitle: 'Face & Body',
    oldPrice: '৳ 2500',
    newPrice: '৳ 1999',
    tags: ['Face', 'Popular'],
  },
  {
    id: 2,
    image: '/combo/combo2.webp',
    title: 'Combo Pack 2',
    subtitle: 'Hair Care',
    oldPrice: '৳ 1800',
    newPrice: '৳ 1450',
    tags: ['Hair'],
  },
  {
    id: 3,
    image: '/combo/combo3.webp',
    title: 'Combo Pack 3',
    subtitle: 'Body Special',
    oldPrice: '৳ 2200',
    newPrice: '৳ 1750',
    tags: ['Body'],
  },
  {
    id: 4,
    image: '/combo/combo4.webp',
    title: 'Combo Pack 4',
    subtitle: 'Face Glow',
    oldPrice: '৳ 2000',
    newPrice: '৳ 1600',
    tags: ['Face'],
  },
  {
    id: 5,
    image: '/combo/combo5.webp',
    title: 'Combo Pack 5',
    subtitle: 'Popular Choice',
    oldPrice: '৳ 2100',
    newPrice: '৳ 1700',
    tags: ['Popular'],
  },
];

const ComboOffers: React.FC = () => {
  const [selectedTag, setSelectedTag] = useState('All');
  const filteredCombos = selectedTag === 'All' ? combos : combos.filter(c => c.tags.includes(selectedTag));

  return (
    <section className={styles.comboOffersSection}>
      <div className={styles.headerRow}>
        <h2 className={styles.title} style={{ fontSize: '1.5rem' }}>COMBO OFFERS</h2>
        <a href="#" className={styles.seeAllBtn}>See All <span>&rarr;</span></a>
      </div>
      <div className={styles.tagRow}>
        {tags.map(tag => (
          <button
            key={tag}
            className={selectedTag === tag ? styles.activeTag : styles.tagBtn}
            onClick={() => setSelectedTag(tag)}
          >
            {tag}
          </button>
        ))}
      </div>
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
        {filteredCombos.map((combo) => (
          <SwiperSlide key={combo.id}>
            <Link href="#" className={styles.card} style={{ border: '1.5px solid transparent', transition: 'border 0.2s, color 0.2s' }}>
              <div className={styles.cardImageWrap}>
                <span className={styles.onSale}>ON SALE</span>
                <img src={combo.image} alt={combo.title} className={styles.cardImage} />
                <span className={styles.comboLabel}>COMBO</span>
                <span className={styles.offerPrice}>Offer Price<br /><b>{combo.newPrice}</b></span>
              </div>
              <div className={styles.cardInfo}>
                <div className={styles.productTitle}>{combo.title}</div>
                <div className={styles.productSubtitle}>{combo.subtitle}</div>
                <div className={styles.priceRow}>
                  <span className={styles.oldPrice}>{combo.oldPrice}</span>
                  <span className={styles.newPrice}>{combo.newPrice}</span>
                </div>
              </div>
            </Link>
          </SwiperSlide>
        ))}
      </Swiper>
    </section>
  );
};

export default ComboOffers;
