


import styles from './TopCategories.module.css';
import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';
import Link from 'next/link';
import Image from 'next/image';

const categories = [
  {
    id: 1,
    image: '/category-section/skincare.webp',
    name: 'Skin Care',
    link: '/category/skin-care',
  },
  {
    id: 2,
    image: '/category-section/makeup.webp',
    name: 'Make up',
    link: '/category/makeup',
  },
  {
    id: 3,
    image: '/category-section/hair-care.webp',
    name: 'Hair Care',
    link: '/category/hair',
  },
  {
    id: 4,
    image: '/category-section/makeup.webp',
    name: 'Bath & Body Care',
    link: '/category/bath-body-care',
  },
  {
    id: 5,
    image: '/category-section/mom-baby-care.webp',
    name: 'Mom & Baby Care',
    link: '/category/mom-baby-care',
  },
  {
    id: 6,
    image: '/category-section/accessories.webp',
    name: 'Accessories',
    link: '/category/accessories',
  },
];

const TopCategories: React.FC = () => {
  return (
    <section className={styles.topCategoriesSection}>
      <div className={styles.headerRow}>
        <h2 className={styles.title}>TOP CATEGORIES</h2>
        <a href="#" className={styles.seeAllBtn}>See All <span>&rarr;</span></a>
      </div>
      <div className={styles.desktopGrid}>
        {categories.map((cat) => (
          <Link href={cat.link} className={styles.catCard} key={cat.id} prefetch>
            <div className={styles.catImageWrap}>
              <Image src={cat.image} alt={cat.name} className={styles.catImage} width={140} height={140} priority />
            </div>
            <div className={styles.catName}>{cat.name}</div>
          </Link>
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
          className={styles.catSlider}
        >
          {categories.map((cat) => (
            <SwiperSlide key={cat.id}>
              <Link href={cat.link} className={styles.catCard} prefetch>
                <div className={styles.catImageWrap}>
                  <Image src={cat.image} alt={cat.name} className={styles.catImage} width={140} height={140} priority />
                </div>
                <div className={styles.catName}>{cat.name}</div>
              </Link>
            </SwiperSlide>
          ))}
        </Swiper>
      </div>
    </section>
  );
};

export default TopCategories;
