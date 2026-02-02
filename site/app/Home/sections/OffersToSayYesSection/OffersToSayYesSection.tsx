import React from 'react';
import styles from './OffersToSayYesSection.module.css';

// Dummy data for now
const offers = [
  {
    id: 1,
    title: '৳150 OFF',
    description: 'On orders above ৳1499',
    code: 'YES150',
    color: '#ffe6f0',
  },
  {
    id: 2,
    title: 'Free Delivery',
    description: 'On orders above ৳999',
    code: 'YESDEL',
    color: '#e6f7ff',
  },
  {
    id: 3,
    title: '৳100 Cashback',
    description: 'On first order',
    code: 'FIRST100',
    color: '#e6ffe6',
  },
];

const OffersToSayYesSection: React.FC = () => (
  <section className={styles.offersSection}>
    <h2 className={styles.heading}>OFFERS TO SAY YES</h2>
    <div className={styles.offersGrid}>
      {offers.map(offer => (
        <div key={offer.id} className={styles.offerCard} style={{ background: offer.color }}>
          <h3>{offer.title}</h3>
          <p>{offer.description}</p>
          <span className={styles.code}>Use: {offer.code}</span>
        </div>
      ))}
    </div>
  </section>
);

export default OffersToSayYesSection;
