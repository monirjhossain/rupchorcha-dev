"use client";
import styles from './Header.module.css';
import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import { useBrands } from '@/src/hooks/useBrands';
import CartIcon from './CartIcon';
import React, { useState } from 'react';
import Image from 'next/image';
import SearchBox from './SearchBox';
import { useWishlist } from './WishlistContext';
import { useAuth } from '@/app/contexts/AuthContext';
import LoginModal from './login/LoginModal';
import Head from 'next/head';
import MenuToggle from '../../components/MenuToggle/MenuToggle';
import MenuSidebar from '../../components/MenuSidebar/MenuSidebar';
import Sidebar from './Sidebar';

const useIsMobile = () => {
  const [isMobile, setIsMobile] = React.useState(false);
  React.useEffect(() => {
    const check = () => setIsMobile(window.innerWidth <= 900);
    check();
    window.addEventListener('resize', check);
    return () => window.removeEventListener('resize', check);
  }, []);
  return isMobile;
};

interface HeaderProps {
  onCartClick?: () => void;
}

const Header: React.FC<HeaderProps> = ({ onCartClick }) => {
    const { brands } = useBrands();
    const { wishlist } = useWishlist();
    const { isAuthenticated, user } = useAuth();
  const pathname = usePathname();

    // Always sync isAuthenticated with localStorage token
    React.useEffect(() => {
      const syncAuth = () => {
        const token = localStorage.getItem('token');
        if (!token && isAuthenticated) {
          forceUpdate();
        }
      };
      window.addEventListener('auth-state-changed', syncAuth);
      window.addEventListener('storage', syncAuth);
      return () => {
        window.removeEventListener('auth-state-changed', syncAuth);
        window.removeEventListener('storage', syncAuth);
      };
    }, [isAuthenticated]);
  // Removed duplicate useWishlist and useAuth declarations
  const router = useRouter();
  const [showLoginModal, setShowLoginModal] = useState(false);
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [, forceUpdate] = React.useReducer((x) => x + 1, 0);
  const isMobile = useIsMobile();
  const [filterOpen, setFilterOpen] = useState(false);

  const isCategoryRoute = React.useMemo(() => {
    if (!pathname) return false;
    return (
      pathname === '/category' ||
      pathname.startsWith('/category/') ||
      pathname === '/brands' ||
      pathname.startsWith('/brands/') ||
      pathname.startsWith('/tags/')
    );
  }, [pathname]);

  // Debug logging
  React.useEffect(() => {
    console.log('[Header] isAuthenticated:', isAuthenticated, 'user:', user);
  }, [isAuthenticated, user]);

  // Listen for auth state changes and force router refresh for instant UI update
  React.useEffect(() => {
    const handleAuthChange = () => {
      console.log("Auth state changed, refreshing router for instant UI update");
      router.refresh();
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

  // Auto-close login modal only when authenticated becomes true
  React.useEffect(() => {
    if (isAuthenticated) {
      setShowLoginModal(false);
    }
  }, [isAuthenticated]);

  // Handle modal close - always just close modal
  const handleModalClose = () => {
    setShowLoginModal(false);
  };

  // Listen for auth state changes and close modal if logged in
  React.useEffect(() => {
    if (isAuthenticated && showLoginModal) {
      setShowLoginModal(false);
    }
  }, [isAuthenticated, showLoginModal]);

  // Close mobile filter overlay when route changes
  React.useEffect(() => {
    setFilterOpen(false);
  }, [pathname]);

  return (
    <>
      {!isMobile && brands.length > 0 && (
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

      {/* Desktop Header */}
      {!isMobile && (
        <header className={styles.header + ' ' + styles.desktopHeader}>
          <div className={styles.headerInner}>
            <div className={styles.topBar}>
              <div className={styles.logoWrap}>
                <Link href="/">
                  <Image src="/rupchorcha-logo.png" alt="Logo" height={56} width={180} className={styles.logoImg} />
                </Link>
              </div>
              <div className={styles.searchWrap} style={{position:'relative'}}>
                <SearchBox />
              </div>
              <div className={styles.actionBtns}>
                <Link href="/wishlist" aria-label="Wishlist" style={{background:'none',border:'none',cursor:'pointer',position:'relative',padding:'0.2rem 0.7rem',display:'inline-block'}} className={styles.actionBtn + ' ' + styles.actionBtnDark}>
                  <span style={{position:'relative',display:'inline-block'}}>
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" style={{display:'block'}}>
                      <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="#7c32ff" strokeWidth="1.5" fill="#fff"/>
                    </svg>
                    <span style={{position:'absolute',top:-8,right:-8,minWidth:22,height:22,background:'#7c32ff',color:'#fff',borderRadius:'50%',fontWeight:700,fontSize:'0.98rem',display:'flex',alignItems:'center',justifyContent:'center',boxShadow:'0 2px 8px #7c32ff22',border:'2px solid #fff'}}>{wishlist.length}</span>
                  </span>
                </Link>
                <div style={{ position: 'relative' }}>
                  <button
                    type="button"
                    className={styles.actionBtn + ' ' + styles.actionBtnLight}
                    onClick={(e) => {
                      if (isAuthenticated && user) {
                        router.push('/profile');
                      } else {
                        e.preventDefault();
                        setShowLoginModal(true);
                        console.log('[Header] Opening login modal (forced)');
                      }
                    }}
                    style={{background:'none',border:'none',cursor:'pointer',position:'relative',padding:'0.2rem 0.7rem',display:'inline-block',textDecoration:'none'}}
                    aria-label={isAuthenticated && user ? "Go to profile" : "Open login modal"}
                  >
                    <span style={{position:'relative',display:'inline-block'}}>
                      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" style={{display:'block'}}>
                        <circle cx="12" cy="8" r="4" stroke="#222" strokeWidth="1.5" fill="#222"/>
                        <path d="M4 20c0-3.31 3.13-6 8-6s8 2.69 8 6" stroke="#222" strokeWidth="1.5" fill="none"/>
                      </svg>
                      {(isAuthenticated && !user) && (
                        <span style={{position:'absolute',top:0,right:0}}>
                          <svg width="16" height="16" viewBox="0 0 50 50" style={{display:'block'}}>
                            <circle cx="25" cy="25" r="20" fill="#eee" />
                            <circle cx="25" cy="25" r="10" fill="#7c32ff" />
                          </svg>
                        </span>
                      )}
                    </span>
                  </button>
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
              <Link href="/category/skin-care" className={styles.categoryLink} prefetch={true}>Skin Care</Link>
              <Link href="/category/body" className={styles.categoryLink} prefetch={true}>Body</Link>
              <Link href="/category/makeup" className={styles.categoryLink} prefetch={true}>Makeup</Link>
              <Link href="/category/face" className={styles.categoryLink} prefetch={true}>Face</Link>
              <Link href="/category/hair" className={styles.categoryLink} prefetch={true}>Hair</Link>
              <Link href="/category/hair-care" className={styles.categoryLink} prefetch={true}>Hair Care</Link>
              <Link href="/category/shop-by-concern" className={styles.categoryLink} prefetch={true}>Shop By Concern</Link>
              <Link href="/category/acne-treatment" className={styles.categoryLink} prefetch={true}>Acne Treatment</Link>
              <Link href="/category/skin-concern" className={styles.categoryLink} prefetch={true}>Skin Concern</Link>
            </nav>
          </div>
        </header>
      )}

      {/* Mobile Header */}
      {isMobile && (
        <>
          <header className={styles.header + ' ' + styles.mobileHeader}>
            <div className={styles.mobileTopBar}>
              <div className={styles.mobileLeft}>
                <MenuToggle onClick={() => setSidebarOpen(true)} />
              </div>
              <div className={styles.mobileLogoWrap}>
                <Link href="/">
                  <Image
                    src="/rupchorcha-logo.png"
                    alt="Logo"
                    height={44}
                    width={140}
                    className={styles.mobileLogoImg}
                  />
                </Link>
              </div>
            </div>
            <div className={styles.mobileSearchWrap}>
              <SearchBox />
            </div>
            <MenuSidebar open={sidebarOpen} onClose={() => setSidebarOpen(false)} />
          </header>

          {/* Mobile sticky bottom navigation for Wishlist, Account, Cart */}
          <nav className={styles.mobileBottomNav} aria-label="Mobile navigation">
            {isCategoryRoute && (
              <button
                type="button"
                className={styles.mobileNavItem}
                onClick={() => setFilterOpen(true)}
              >
                <span className={styles.mobileNavIcon}>
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path
                      d="M4 5h16M6 12h12M10 19h4"
                      stroke="#fff"
                      strokeWidth="1.8"
                      strokeLinecap="round"
                    />
                  </svg>
                </span>
                <span className={styles.mobileNavLabel}>Filter</span>
              </button>
            )}
            <button
              type="button"
              className={styles.mobileNavItem}
              onClick={() => {
                if (isAuthenticated && user) {
                  router.push('/profile');
                } else {
                  setShowLoginModal(true);
                }
              }}
            >
              <span className={styles.mobileNavIcon}>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                  <circle cx="12" cy="8" r="4" stroke="#fff" strokeWidth="1.6" />
                  <path d="M4 20c0-3.31 3.13-6 8-6s8 2.69 8 6" stroke="#fff" strokeWidth="1.6" />
                </svg>
              </span>
              <span className={styles.mobileNavLabel}>Account</span>
            </button>

            <Link
              href="/wishlist"
              className={styles.mobileNavItem}
              aria-label="Wishlist"
            >
              <span className={styles.mobileNavIcon}>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                  <path
                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                    stroke="#fff"
                    strokeWidth="1.6"
                    fill="none"
                  />
                </svg>
                {wishlist.length > 0 && (
                  <span className={styles.mobileNavBadge}>{wishlist.length}</span>
                )}
              </span>
              <span className={styles.mobileNavLabel}>Wishlist</span>
            </Link>

            <button
              type="button"
              className={styles.mobileNavItem}
              onClick={onCartClick}
              aria-label="Open cart"
            >
              <span className={styles.mobileNavIcon}>
                <CartIcon />
              </span>
              <span className={styles.mobileNavLabel}>Cart</span>
            </button>
          </nav>

          {isCategoryRoute && filterOpen && (
            <div className={styles.mobileFilterOverlay}>
              <div
                className={styles.mobileFilterBackdrop}
                onClick={() => setFilterOpen(false)}
              />
              <div className={styles.mobileFilterPanel}>
                <div className={styles.mobileFilterHeader}>
                  <span className={styles.mobileFilterTitle}>Filters</span>
                  <button
                    type="button"
                    className={styles.mobileFilterClose}
                    onClick={() => setFilterOpen(false)}
                    aria-label="Close filters"
                  >
                    ✕
                  </button>
                </div>
                <div className={styles.mobileFilterBody}>
                  <Sidebar />
                </div>
              </div>
            </div>
          )}
        </>
      )}

      <LoginModal isOpen={showLoginModal} onClose={handleModalClose} />
    </>
  );
};

export default Header;



