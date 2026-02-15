import React, { useState } from 'react';
import styles from './BuyOneGetOne.module.css';
import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';

const tags = ['All', 'Face', 'Body', 'Hair', 'Korean', 'Popular'];

const offers = [
  {
    id: 1,
    image: '/bogo/medicube.webp',
    title: 'MEDICUBE',
    subtitle: 'Bogo 15',
    oldPrice: '৳ 14000',
    newPrice: '৳ 12900',
    tags: ['Face', 'Korean'],
  },
  {
    id: 2,
    image: '/bogo/skin1004.webp',
    title: 'SKIN1004',
    subtitle: 'Bogo 33',
    oldPrice: '৳ 1999',
    newPrice: '৳ 1999',
    tags: ['Face', 'Popular'],
  },
  {
    id: 3,
    image: '/bogo/laneige.webp',
    title: 'LANEIGE',
    subtitle: 'Bogo 34',
    oldPrice: '৳ 2700',
    newPrice: '৳ 1999',
    tags: ['Body', 'Korean'],
  },
  {
    id: 4,
    image: '/bogo/skinfood.webp',
    title: 'SKINFOOD',
    subtitle: 'Bogo 36',
    oldPrice: '৳ 1300',
    newPrice: '৳ 1170',
    tags: ['Hair', 'Popular'],
  },
  {
    id: 5,
    image: '/bogo/marymay.webp',
    title: 'MARY&MAY',
    subtitle: 'Bogo 35',
    oldPrice: '৳ 1450',
    newPrice: '৳ 1450',
    tags: ['Face', 'Body'],
  },
];

const BuyOneGetOne: React.FC = () => {
  const [selectedTag, setSelectedTag] = useState('All');
  const filteredOffers = selectedTag === 'All' ? offers : offers.filter(o => o.tags.includes(selectedTag));

  return (
    <section className={styles.buyOneGetOneSection}>
      <div className={styles.headerRow}>
        <h2 className={styles.title}>BUY ONE GET ONE</h2>
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
          0: { slidesPerView: 2 }, // Always 2 on mobile
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
        {filteredOffers.map((offer) => (
          <SwiperSlide key={offer.id}>
            <div className={styles.card}>
              <div className={styles.cardImageWrap}>
                <span className={styles.onSale}>ON SALE</span>
                <img src={offer.image} alt={offer.title} className={styles.cardImage} />
                <span className={styles.bogoLabel}>BUY 1 GET {offer.id}</span>
                <span className={styles.offerPrice}>Offer Price<br /><b>{offer.newPrice}</b></span>
              </div>
              <div className={styles.cardInfo}>
                <div className={styles.productTitle}>{offer.title}</div>
                <div className={styles.productSubtitle}>{offer.subtitle}</div>
                <div className={styles.priceRow}>
                  <span className={styles.oldPrice}>{offer.oldPrice}</span>
                  <span className={styles.newPrice}>{offer.newPrice}</span>
                </div>
              </div>
            </div>
          </SwiperSlide>
        ))}
      </Swiper>
    </section>
  );
};

export default BuyOneGetOne;
