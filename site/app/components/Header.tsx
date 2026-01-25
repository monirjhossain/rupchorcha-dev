"use client";
import styles from './Header.module.css';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { useCategories } from '@/src/hooks/useCategories';
import { useBrands } from '@/src/hooks/useBrands';
import CartSidebar from './CartSidebar';
import CartIcon from './CartIcon';
import React, { useState } from 'react';
import Image from 'next/image';
import SearchBox from './SearchBox';
import { useWishlist } from './WishlistContext';
import { useAuth } from '@/src/hooks/useAuth';
import LoginModal from './LoginModal';
import Head from 'next/head';


interface HeaderProps {
  onCartClick?: () => void;
}

const Header: React.FC<HeaderProps> = ({ onCartClick }) => {
  const { categories, isLoading } = useCategories();
  const { brands, isLoading: brandsLoading } = useBrands();
  const { wishlist } = useWishlist();
  const { isAuthenticated, user } = useAuth();
  const router = useRouter();
  const [showLoginModal, setShowLoginModal] = useState(false);
  const [, forceUpdate] = React.useReducer((x) => x + 1, 0);

  // Debug logging
  React.useEffect(() => {
    console.log("Header State - isAuthenticated:", isAuthenticated, "user:", user);
  }, [isAuthenticated, user]);

  // Listen for auth state changes
  React.useEffect(() => {
    const handleAuthChange = () => {
      console.log("Auth state changed, forcing Header re-render");
      forceUpdate();
    };
    window.addEventListener('auth-state-changed', handleAuthChange);
    return () => window.removeEventListener('auth-state-changed', handleAuthChange);
  }, []);

  // Listen for global login modal open/close events
  React.useEffect(() => {
    const handleOpenModal = () => setShowLoginModal(true);
    const handleCloseModal = () => setShowLoginModal(false);
    
    window.addEventListener('open-login-modal', handleOpenModal);
    window.addEventListener('close-login-modal', handleCloseModal);
    
    return () => {
      window.removeEventListener('open-login-modal', handleOpenModal);
      window.removeEventListener('close-login-modal', handleCloseModal);
    };
  }, []);

  // Handle modal close - ensure UI updates
  const handleModalClose = () => {
    setShowLoginModal(false);
  };

  return (
    <>
      {/* Prefetch category pages for instant navigation */}
      {!isLoading && categories.length > 0 && (
        <>
          {categories.slice(0, 6).map((cat: any) => (
            <link
              key={cat.id}
              rel="prefetch"
              href={`/category/${cat.slug || cat.name.toLowerCase().replace(/\s+/g, '-')}`}
            />
          ))}
        </>
      )}
      
      {/* Prefetch brand pages for instant navigation */}
      {!brandsLoading && brands.length > 0 && (
        <>
          {brands.slice(0, 12).map((brand: any) => (
            <link
              key={brand.id}
              rel="prefetch"
              href={`/brands/${brand.slug || brand.name.toLowerCase().replace(/\s+/g, '-')}`}
            />
          ))}
        </>
      )}
      
      <header className={styles.header}>
        <div className={styles.headerInner}>
          <div className={styles.topBar}>
            <div className={styles.logoWrap}>
              <Link href="/">
                <Image src="/rupchorcha-logo.png" alt="Logo" height={50} width={160} className={styles.logoImg} />
              </Link>
              <Link href="/brands" className={styles.brandsLink}>BRANDS</Link>
            </div>
            <div className={styles.searchWrap} style={{position:'relative'}}>
              <SearchBox />
            </div>
            <div className={styles.actionBtns}>
              <Link href="/wishlist" aria-label="Wishlist" style={{background:'none',border:'none',cursor:'pointer',position:'relative',padding:'0.2rem 0.7rem',display:'inline-block'}} className={styles.actionBtn + ' ' + styles.actionBtnDark}>
                <span style={{position:'relative',display:'inline-block'}}>
                  {/* Heart SVG icon */}
                  <svg width="26" height="26" viewBox="0 0 24 24" fill="none" style={{display:'block'}}>
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="#7c32ff" strokeWidth="1.5" fill="#fff"/>
                  </svg>
                  {/* Count badge */}
                  <span style={{position:'absolute',top:-8,right:-8,minWidth:22,height:22,background:'#7c32ff',color:'#fff',borderRadius:'50%',fontWeight:700,fontSize:'0.98rem',display:'flex',alignItems:'center',justifyContent:'center',boxShadow:'0 2px 8px #7c32ff22',border:'2px solid #fff'}}>{wishlist.length}</span>
                </span>
              </Link>

              {/* User Profile / Auth Buttons */}
              <div style={{ position: 'relative' }}>
                {isAuthenticated && user ? (
                  <button
                    type="button"
                    className={styles.actionBtn + ' ' + styles.actionBtnLight}
                    onClick={(e) => {
                      e.preventDefault();
                      // Double check auth state before navigation
                      const currentToken = localStorage.getItem('token');
                      if (currentToken && isAuthenticated) {
                        router.push('/profile');
                      } else {
                        setShowLoginModal(true);
                      }
                    }}
                    style={{background:'none',border:'none',cursor:'pointer',position:'relative',padding:'0.2rem 0.7rem',display:'inline-block',textDecoration:'none'}}
                    aria-label="Go to profile"
                  >
                    <span style={{position:'relative',display:'inline-block'}}>
                      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" style={{display:'block'}}>
                        <circle cx="12" cy="8" r="4" stroke="#222" strokeWidth="1.5" fill="#222"/>
                        <path d="M4 20c0-3.31 3.13-6 8-6s8 2.69 8 6" stroke="#222" strokeWidth="1.5" fill="none"/>
                      </svg>
                    </span>
                  </button>
                ) : (
                  <button
                    type="button"
                    className={styles.actionBtn + ' ' + styles.actionBtnLight}
                    onClick={() => setShowLoginModal(true)}
                    style={{background:'none',border:'none',cursor:'pointer',position:'relative',padding:'0.2rem 0.7rem'}}
                    aria-label="Open login modal"
                  >
                    <span style={{position:'relative',display:'inline-block'}}>
                      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" style={{display:'block'}}>
                        <circle cx="12" cy="8" r="4" stroke="#222" strokeWidth="1.5" fill="#222"/>
                        <path d="M4 20c0-3.31 3.13-6 8-6s8 2.69 8 6" stroke="#222" strokeWidth="1.5" fill="none"/>
                      </svg>
                    </span>
                  </button>
                )}
              </div>

              <button
                type="button"
                className={styles.actionBtn + ' ' + styles.actionBtnPink}
                onClick={onCartClick}
                style={{background:'none',border:'none',cursor:'pointer',position:'relative',padding:'0.2rem 0.7rem'}}
                aria-label="Open cart"
              >
                <CartIcon />
              </button>
            </div>
          </div>
          <nav className={styles.categoryBar}>
            <Link href="/shop" className={styles.shopLink} prefetch={true}>
              <span style={{ display: 'inline-flex', alignItems: 'center', gap: '0.5rem' }}>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style={{ display: 'inline-block' }}>
                  <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-0.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l0.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-0.9-2-2-2z"/>
                </svg>
                Shop
              </span>
            </Link>
            {isLoading ? (
              Array.from({ length: 8 }).map((_, i) => (
                <span key={i} className={styles.categoryLink + ' ' + styles.categorySkeleton}></span>
              ))
            ) : (
              categories.map(cat => (
                <Link
                  href={"/category/"+cat.name.toLowerCase().replace(/\s+/g,'-')}
                  key={cat.id}
                  className={styles.categoryLink}
                  prefetch={true}
                >
                  {cat.name}
                </Link>
              ))
            )}
          </nav>
        </div>
      </header>
      <LoginModal isOpen={showLoginModal} onClose={handleModalClose} />
      {/* CartSidebar removed from Header; now only in ClientLayout */}
    </>
  );
};

export default Header;



