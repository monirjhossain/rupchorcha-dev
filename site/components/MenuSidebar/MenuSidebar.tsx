import React from 'react';
import styles from './MenuSidebar.module.css';
import { categories } from '../../data/categories';

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
      <div className={styles.profileRow}>
        <span className={styles.profileIcon}>👤</span>
        <span>Login</span>
      </div>
      <ul className={styles.categoryList}>
        {categories.map(cat => (
          <li key={cat.name} className={styles.categoryItem}>
            {cat.name}
            <span className={styles.plus}>+</span>
          </li>
        ))}
      </ul>
    </aside>
  </div>
);

export default MenuSidebar;
