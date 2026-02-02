import React, { useState } from 'react';
import styles from './MobileHeader.module.css';
import MenuToggle from '../MenuToggle/MenuToggle';
import MenuSidebar from '../MenuSidebar/MenuSidebar';

const MobileHeader: React.FC = () => {
  const [sidebarOpen, setSidebarOpen] = useState(false);

  return (
    <header className={styles.mobileHeader}>
      <div className={styles.left}>
        <MenuToggle onClick={() => setSidebarOpen(true)} />
      </div>
      <div className={styles.center}>
        <span className={styles.logo}>RUPCHORCHA</span>
      </div>
      <div className={styles.bottom}>
        <input className={styles.search} type="text" placeholder="Search for Products, Brands..." />
      </div>
      <MenuSidebar open={sidebarOpen} onClose={() => setSidebarOpen(false)} />
    </header>
  );
};

export default MobileHeader;
