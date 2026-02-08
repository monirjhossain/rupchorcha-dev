import React from 'react';
import Link from 'next/link';
import styles from './MenuSidebar.module.css';
import { openLoginModal } from '@/src/utils/loginModal';

type Props = {
  open: boolean;
  onClose: () => void;
};

const MenuSidebar: React.FC<Props> = ({ open, onClose }) => (
  <div className={styles.overlay} style={{ display: open ? 'block' : 'none' }} onClick={onClose}>
    <aside className={styles.sidebar} onClick={e => e.stopPropagation()}>
      <div className={styles.header}>
        <span className={styles.menuTitle}>Menu</span>
        <button className={styles.closeBtn} onClick={onClose} aria-label="Close menu">&times;</button>
      </div>
      <button
        type="button"
        className={styles.profileRow}
        onClick={() => {
          openLoginModal();
          onClose();
        }}
      >
        <span className={styles.profileIcon}>👤</span>
        <span>Login</span>
      </button>
      <nav className={styles.navSection} aria-label="Main navigation">
        <ul className={styles.categoryList}>
          <li className={styles.categoryItem}>
            <Link href="/shop" onClick={onClose} className={styles.navLink}>
              <span>Shop</span>
            </Link>
          </li>
          <li className={styles.categoryItem}>
            <Link href="/category/skin-care" onClick={onClose} className={styles.navLink}>
              <span>Skin Care</span>
            </Link>
          </li>
          <li className={styles.categoryItem}>
            <Link href="/category/body" onClick={onClose} className={styles.navLink}>
              <span>Body</span>
            </Link>
          </li>
          <li className={styles.categoryItem}>
            <Link href="/category/makeup" onClick={onClose} className={styles.navLink}>
              <span>Makeup</span>
            </Link>
          </li>
          <li className={styles.categoryItem}>
            <Link href="/category/face" onClick={onClose} className={styles.navLink}>
              <span>Face</span>
            </Link>
          </li>
          <li className={styles.categoryItem}>
            <Link href="/category/hair" onClick={onClose} className={styles.navLink}>
              <span>Hair</span>
            </Link>
          </li>
          <li className={styles.categoryItem}>
            <Link href="/category/hair-care" onClick={onClose} className={styles.navLink}>
              <span>Hair Care</span>
            </Link>
          </li>
          <li className={styles.categoryItem}>
            <Link href="/category/shop-by-concern" onClick={onClose} className={styles.navLink}>
              <span>Shop By Concern</span>
            </Link>
          </li>
          <li className={styles.categoryItem}>
            <Link href="/category/acne-treatment" onClick={onClose} className={styles.navLink}>
              <span>Acne Treatment</span>
            </Link>
          </li>
          <li className={styles.categoryItem}>
            <Link href="/category/skin-concern" onClick={onClose} className={styles.navLink}>
              <span>Skin Concern</span>
            </Link>
          </li>
        </ul>
      </nav>
    </aside>
  </div>
);

export default MenuSidebar;
