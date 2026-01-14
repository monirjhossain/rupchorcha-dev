"use client";

import styles from "./BrandOfferSection.module.css";
import Image from "next/image";

const brandOffers = [
  {
    id: 1,
    image: "/brand/simpls.webp",
    alt: "Pond's Offer",
    link: "/brand/ponds"
  },
  {
    id: 2,
    image: "/brand/wetnwild.webp",
    alt: "L'Oreal Offer",
    link: "/brand/loreal"
  },
  {
    id: 3,
    image: "/brand/anua.webp",
    alt: "Garnier Offer",
    link: "/brand/garnier"
  },
  {
    id: 4,
    image: "/brand/cosrxx.webp",
    alt: "Vaseline Offer",
    link: "/brand/vaseline"
  }
];

export default function BrandOfferSection() {
  return (
    <section className={styles.brandOfferSection}>
      <h2 className={styles.brandOfferTitle}>Trending Brands</h2>
      <div className={styles.brandOfferContainer}>
        {brandOffers.map(offer => (
          <a
            key={offer.id}
            href={offer.link}
            className={styles.brandOfferCard}
          >
            <Image
              src={offer.image}
              alt={offer.alt}
              width={260}
              height={120}
              className={styles.brandOfferImage}
              quality={90}
            />
          </a>
        ))}
      </div>
    </section>
  );
}
