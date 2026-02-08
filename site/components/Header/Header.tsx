import React, { useState } from 'react';
import { useRouter } from 'next/navigation';
import { useAuth } from '@/app/contexts/AuthContext';
import LoginModal from '@/app/components/login/LoginModal';
import styles from './Header.module.css';
import MenuToggle from '../MenuToggle/MenuToggle';
import MenuSidebar from '../MenuSidebar/MenuSidebar';

const Header: React.FC = () => {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [loginOpen, setLoginOpen] = useState(false);
  const { user, isLoading } = useAuth();
  const router = useRouter();

  const handleAccountClick = () => {
    if (user) {
      router.push('/profile');
    } else {
      setLoginOpen(true);
    }
  };

  return (
    <>
      <header className={styles.rupchorchaHeader}>
        <div className={styles.left}>
          <MenuToggle onClick={() => setSidebarOpen(true)} />
          <span className={styles.logo}>RUPCHORCHA</span>
        </div>
        <div className={styles.center}>
          <input className={styles.search} type="text" placeholder="Search for Products, Brands..." />
        </div>
        <div className={styles.right}>
          {/* Cart icon এখানে রাখতে পারেন */}
          <button
            onClick={handleAccountClick}
            style={{
              background: 'none',
              border: 'none',
              cursor: 'pointer',
              padding: 0,
              marginLeft: 8,
              display: 'flex',
              alignItems: 'center',
            }}
            aria-label={user ? 'Profile' : 'Login'}
          >
            {/* Simple user icon SVG */}
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#7c32ff" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-2.5 3.5-4 8-4s8 1.5 8 4"/></svg>
          </button>
        </div>
        <MenuSidebar open={sidebarOpen} onClose={() => setSidebarOpen(false)} />
      </header>
      <LoginModal isOpen={loginOpen} onClose={() => setLoginOpen(false)} />
    </>
  );
};

export default Header;
