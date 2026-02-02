import React from 'react';
import styles from './MenuToggle.module.css';

type Props = {
  onClick: () => void;
};

const MenuToggle: React.FC<Props> = ({ onClick }) => (
  <button className={styles.menuToggle} onClick={onClick} aria-label="Open menu">
    <span className={styles.bar}></span>
    <span className={styles.bar}></span>
    <span className={styles.bar}></span>
  </button>
);

export default MenuToggle;
