import React, { useState } from 'react';
import styles from './Header.module.css';
import MenuToggle from '../MenuToggle/MenuToggle';
import MenuSidebar from '../MenuSidebar/MenuSidebar';

const Header: React.FC = () => {
  const [sidebarOpen, setSidebarOpen] = useState(false);

  return (
    <header className={styles.rupchorchaHeader}>
      <div className={styles.left}>
        <MenuToggle onClick={() => setSidebarOpen(true)} />
        <span className={styles.logo}>RUPCHORCHA</span>
      </div>
      <div className={styles.center}>
        <input className={styles.search} type="text" placeholder="Search for Products, Brands..." />
      </div>
      <div className={styles.right}>
        {/* Cart/User icons here */}
      </div>
      <MenuSidebar open={sidebarOpen} onClose={() => setSidebarOpen(false)} />
    </header>
  );
};

export default Header;
