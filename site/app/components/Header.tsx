"use client";
import React, { useEffect, useReducer, useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { usePathname, useRouter } from 'next/navigation';
import styles from './Header.module.css';
import CartIcon from './CartIcon';
import SearchBox from './SearchBox';
import { useWishlist } from './WishlistContext';
import { useAuth } from '@/app/contexts/AuthContext';
import LoginModal from './login/LoginModal';
import MenuToggle from '../../components/MenuToggle/MenuToggle';
import MenuSidebar from '../../components/MenuSidebar/MenuSidebar';
import Sidebar from './Sidebar';
import { useCategories } from '@/src/hooks/useCategories';
import { useBrands } from '@/src/hooks/useBrands';

// Simple viewport hook to toggle mobile/desktop layouts
const useIsMobile = () => {
  const [isMobile, setIsMobile] = useState(false);

  useEffect(() => {
    const check = () => setIsMobile(typeof window !== 'undefined' && window.innerWidth < 992);
    check();
    window.addEventListener('resize', check);
    return () => window.removeEventListener('resize', check);
  }, []);

  return isMobile;
};

type HeaderProps = {
  onCartClick?: () => void;
};

const Header: React.FC<HeaderProps> = ({ onCartClick }) => {
  const router = useRouter();
  const pathname = usePathname();
  const { categories = [] } = useCategories();
  const { brands = [] } = useBrands();
  const { wishlist } = useWishlist();
  const { isAuthenticated, user } = useAuth();

  const isMobile = useIsMobile();
  const [, forceUpdate] = useReducer((x) => x + 1, 0);

  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [filterOpen, setFilterOpen] = useState(false);
  const [activeMenu, setActiveMenu] = useState<string | null>(null);
  const [showLoginModal, setShowLoginModal] = useState(false);

  const topCategories = categories.slice(0, 6).map((cat: any) => ({
    label: cat.name,
    href: `/category/${cat.slug ?? cat.id}`,
  }));

  const topBrands = brands.slice(0, 6).map((brand: any) => ({
    label: brand.name,
    href: `/brands/${brand.slug ?? brand.id}`,
  }));

  const menuConfig: Record<string, { columns: { title: string; links: { label: string; href: string }[] }[] }> = {
    Shop: {
      columns: [
        {
          title: 'Top Categories',
          links: topCategories.length
            ? topCategories
            : [
                { label: 'Skin Care', href: '/category/skin-care' },
                { label: 'Body', href: '/category/body' },
                { label: 'Makeup', href: '/category/makeup' },
                { label: 'Hair', href: '/category/hair' },
              ],
        },
        {
          title: 'Top Brands',
          links: topBrands.length
            ? topBrands
            : [
                { label: 'Ezeze', href: '/brands/ezeze' },
                { label: 'Tocobo', href: '/brands/tocobo' },
                { label: 'Torriden', href: '/brands/torriden' },
              ],
        },
        {
          title: 'Highlights',
          links: [
            { label: 'New Arrivals', href: '/shop?sort=newest' },
            { label: 'Best Sellers', href: '/shop?sort=default' },
            { label: 'Sale', href: '/shop?sort=price_low' },
          ],
        },
      ],
    },
    'Skin Care': {
      columns: [
        { title: 'Cleanse', links: [ { label: 'Face Wash', href: '/category/skin-care' }, { label: 'Oil Cleanser', href: '/category/skin-care' } ] },
        { title: 'Treat', links: [ { label: 'Serums', href: '/category/skin-care' }, { label: 'Exfoliators', href: '/category/skin-care' } ] },
        { title: 'Moisturize', links: [ { label: 'Day Cream', href: '/category/skin-care' }, { label: 'Night Cream', href: '/category/skin-care' } ] },
      ],
    },
    Body: {
      columns: [
        { title: 'Bath', links: [ { label: 'Body Wash', href: '/category/body' }, { label: 'Soaps', href: '/category/body' } ] },
        { title: 'Hydrate', links: [ { label: 'Body Lotion', href: '/category/body' }, { label: 'Body Butter', href: '/category/body' } ] },
        { title: 'Care', links: [ { label: 'Deodorant', href: '/category/body' }, { label: 'Hand Cream', href: '/category/body' } ] },
      ],
    },
    Makeup: {
      columns: [
        { title: 'Face', links: [ { label: 'Foundation', href: '/category/makeup' }, { label: 'Concealer', href: '/category/makeup' } ] },
        { title: 'Eyes', links: [ { label: 'Mascara', href: '/category/makeup' }, { label: 'Liner', href: '/category/makeup' } ] },
        { title: 'Lips', links: [ { label: 'Lipstick', href: '/category/makeup' }, { label: 'Gloss', href: '/category/makeup' } ] },
      ],
    },
    Face: {
      columns: [
        { title: 'Prep', links: [ { label: 'Primer', href: '/category/face' }, { label: 'Sunscreen', href: '/category/face' } ] },
        { title: 'Treat', links: [ { label: 'Masks', href: '/category/face' }, { label: 'Toners', href: '/category/face' } ] },
        { title: 'Finish', links: [ { label: 'Setting Spray', href: '/category/face' }, { label: 'Powder', href: '/category/face' } ] },
      ],
    },
    Hair: {
      columns: [
        { title: 'Cleanse', links: [ { label: 'Shampoo', href: '/category/hair' }, { label: 'Clarifying', href: '/category/hair' } ] },
        { title: 'Condition', links: [ { label: 'Conditioner', href: '/category/hair' }, { label: 'Mask', href: '/category/hair' } ] },
        { title: 'Style', links: [ { label: 'Serum', href: '/category/hair' }, { label: 'Heat Protect', href: '/category/hair' } ] },
      ],
    },
    'Hair Care': {
      columns: [
        { title: 'Basics', links: [ { label: 'Daily Shampoo', href: '/category/hair-care' }, { label: 'Conditioners', href: '/category/hair-care' } ] },
        { title: 'Treat', links: [ { label: 'Hair Oil', href: '/category/hair-care' }, { label: 'Scalp Toner', href: '/category/hair-care' } ] },
        { title: 'Concerns', links: [ { label: 'Anti-Frizz', href: '/category/hair-care' }, { label: 'Hair Fall', href: '/category/hair-care' } ] },
      ],
    },
    'Shop By Concern': {
      columns: [
        { title: 'Acne', links: [ { label: 'BHA', href: '/category/shop-by-concern' }, { label: 'Retinol', href: '/category/shop-by-concern' } ] },
        { title: 'Brightening', links: [ { label: 'Vitamin C', href: '/category/shop-by-concern' }, { label: 'Niacinamide', href: '/category/shop-by-concern' } ] },
        { title: 'Repair', links: [ { label: 'Ceramides', href: '/category/shop-by-concern' }, { label: 'Peptides', href: '/category/shop-by-concern' } ] },
      ],
    },
    'Acne Treatment': {
      columns: [
        { title: 'Spot Care', links: [ { label: 'Patches', href: '/category/acne-treatment' }, { label: 'Gels', href: '/category/acne-treatment' } ] },
        { title: 'Routine', links: [ { label: 'Cleanser', href: '/category/acne-treatment' }, { label: 'Moisturizer', href: '/category/acne-treatment' } ] },
        { title: 'Support', links: [ { label: 'SPF', href: '/category/acne-treatment' }, { label: 'Soothing', href: '/category/acne-treatment' } ] },
      ],
    },
    'Skin Concern': {
      columns: [
        { title: 'Dryness', links: [ { label: 'Hydrators', href: '/category/skin-concern' }, { label: 'Oils', href: '/category/skin-concern' } ] },
        { title: 'Sensitivity', links: [ { label: 'Soothing', href: '/category/skin-concern' }, { label: 'Barrier Care', href: '/category/skin-concern' } ] },
        { title: 'Texture', links: [ { label: 'Exfoliation', href: '/category/skin-concern' }, { label: 'Pore Care', href: '/category/skin-concern' } ] },
      ],
    },
  };

  const currentMenu = menuConfig[activeMenu || 'Shop'] || menuConfig['Shop'];
  const shouldShowMega = !!activeMenu && Array.isArray(currentMenu?.columns) && currentMenu.columns.length > 0;

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

  const isProductRoute = React.useMemo(() => {
    if (!pathname) return false;
    return pathname.startsWith('/product/');
  }, [pathname]);

  // Prefetch core navigation targets so header clicks feel instant
  useEffect(() => {
    const routesToPrefetch = [
      '/shop',
      '/category/skin-care',
      '/category/body',
      '/category/makeup',
      '/category/face',
      '/category/hair',
      '/category/hair-care',
      '/category/shop-by-concern',
      '/category/acne-treatment',
      '/category/skin-concern',
    ];

    routesToPrefetch.forEach((route) => {
      try {
        router.prefetch(route);
      } catch (e) {
        // ignore prefetch errors (Turbopack might not support all cases)
      }
    });
  }, [router]);

  // Keep auth UI in sync with localStorage token
  useEffect(() => {
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

  // Listen for auth state changes and refresh router so header updates immediately
  useEffect(() => {
    const handleAuthChange = () => router.refresh();
    window.addEventListener('auth-state-changed', handleAuthChange);
    return () => window.removeEventListener('auth-state-changed', handleAuthChange);
  }, [router]);

  // Global events to open/close login modal
  useEffect(() => {
    const handleOpenModal = () => setShowLoginModal(true);
    const handleCloseModal = () => setShowLoginModal(false);
    window.addEventListener('open-login-modal', handleOpenModal);
    window.addEventListener('close-login-modal', handleCloseModal);
    return () => {
      window.removeEventListener('open-login-modal', handleOpenModal);
      window.removeEventListener('close-login-modal', handleCloseModal);
    };
  }, []);

  // Close login modal once authenticated
  useEffect(() => {
    if (isAuthenticated) {
      setShowLoginModal(false);
    }
  }, [isAuthenticated]);

  // Close mobile filter overlay when route changes
  useEffect(() => {
    setFilterOpen(false);
  }, [pathname]);

  const handleModalClose = () => setShowLoginModal(false);

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
              <div className={styles.searchWrap} style={{ position: 'relative' }}>
                <SearchBox />
              </div>
              <div className={styles.actionBtns}>
                <Link
                  href="/wishlist"
                  aria-label="Wishlist"
                  style={{ background: 'none', border: 'none', cursor: 'pointer', position: 'relative', padding: '0.2rem 0.7rem', display: 'inline-block' }}
                  className={styles.actionBtn + ' ' + styles.actionBtnDark}
                >
                  <span style={{ position: 'relative', display: 'inline-block' }}>
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" style={{ display: 'block' }}>
                      <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="#7c32ff" strokeWidth="1.5" fill="#fff" />
                    </svg>
                    <span
                      style={{
                        position: 'absolute',
                        top: -8,
                        right: -8,
                        minWidth: 22,
                        height: 22,
                        background: '#7c32ff',
                        color: '#fff',
                        borderRadius: '50%',
                        fontWeight: 700,
                        fontSize: '0.98rem',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        boxShadow: '0 2px 8px #7c32ff22',
                        border: '2px solid #fff',
                      }}
                    >
                      {wishlist.length}
                    </span>
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
                      }
                    }}
                    style={{ background: 'none', border: 'none', cursor: 'pointer', position: 'relative', padding: '0.2rem 0.7rem', display: 'inline-block', textDecoration: 'none' }}
                    aria-label={isAuthenticated && user ? 'Go to profile' : 'Open login modal'}
                  >
                    <span style={{ position: 'relative', display: 'inline-block' }}>
                      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" style={{ display: 'block' }}>
                        <circle cx="12" cy="8" r="4" stroke="#222" strokeWidth="1.5" fill="#222" />
                        <path d="M4 20c0-3.31 3.13-6 8-6s8 2.69 8 6" stroke="#222" strokeWidth="1.5" fill="none" />
                      </svg>
                      {isAuthenticated && !user && (
                        <span style={{ position: 'absolute', top: 0, right: 0 }}>
                          <svg width="16" height="16" viewBox="0 0 50 50" style={{ display: 'block' }}>
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
                  style={{ background: 'none', border: 'none', cursor: 'pointer', position: 'relative', padding: '0.2rem 0.7rem' }}
                  aria-label="Open cart"
                >
                  <CartIcon />
                </button>
              </div>
            </div>
            <nav
              className={`${styles.nav} ${isCategoryRoute || isProductRoute ? styles.navSecondary : ''}`}
              onMouseLeave={() => !isMobile && setActiveMenu(null)}
            >
              <Link
                href="/shop"
                className={`${styles.navLink} ${styles.shopNav} ${pathname === '/shop' ? styles.shopNavActive : ''}`}
                onMouseEnter={() => !isMobile && setActiveMenu(null)}
              >
                Shop
              </Link>
              <Link href="/category/skin-care" className={styles.navLink} onMouseEnter={() => !isMobile && setActiveMenu('Skin Care')}>
                Skin Care
              </Link>
              <Link href="/category/body" className={styles.navLink} onMouseEnter={() => !isMobile && setActiveMenu('Body')}>
                Body
              </Link>
              <Link href="/category/makeup" className={styles.navLink} onMouseEnter={() => !isMobile && setActiveMenu('Makeup')}>
                Makeup
              </Link>
              <Link href="/category/face" className={styles.navLink} onMouseEnter={() => !isMobile && setActiveMenu('Face')}>
                Face
              </Link>
              <Link href="/category/hair" className={styles.navLink} onMouseEnter={() => !isMobile && setActiveMenu('Hair')}>
                Hair
              </Link>
              <Link href="/category/hair-care" className={styles.navLink} onMouseEnter={() => !isMobile && setActiveMenu('Hair Care')}>
                Hair Care
              </Link>
              <Link href="/category/shop-by-concern" className={styles.navLink} onMouseEnter={() => !isMobile && setActiveMenu('Shop By Concern')}>
                Shop By Concern
              </Link>
              <Link href="/category/acne-treatment" className={styles.navLink} onMouseEnter={() => !isMobile && setActiveMenu('Acne Treatment')}>
                Acne Treatment
              </Link>
              <Link href="/category/skin-concern" className={styles.navLink} onMouseEnter={() => !isMobile && setActiveMenu('Skin Concern')}>
                Skin Concern
              </Link>
            </nav>
            {shouldShowMega && !isMobile && (
              <div
                className={styles.megaMenu}
                onMouseEnter={() => setActiveMenu(activeMenu)}
                onMouseLeave={() => setActiveMenu(null)}
              >
                <div className={styles.megaContent}>
                  {currentMenu.columns.map((col, idx) => (
                    <div className={styles.megaColumn} key={`${col.title}-${idx}`}>
                      <div className={styles.megaTitle}>{col.title}</div>
                      {col.links.map((link) => (
                        <Link key={link.href + link.label} href={link.href} className={styles.megaLink}>
                          {link.label}
                        </Link>
                      ))}
                    </div>
                  ))}
                  <div className={styles.megaBanner}>
                    <div className={styles.bannerContent}>
                      <p className={styles.badge}>Limited Offer</p>
                      <h3>Up to 40% Off</h3>
                      <p>Discover curated bundles for your routine.</p>
                      <Link href="/shop" className={styles.bannerCta}>
                        Shop Now
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            )}
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
                  <Image src="/rupchorcha-logo.png" alt="Logo" height={44} width={140} className={styles.mobileLogoImg} />
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
              <button type="button" className={styles.mobileNavItem} onClick={() => setFilterOpen(true)}>
                <span className={styles.mobileNavIcon}>
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M4 5h16M6 12h12M10 19h4" stroke="#fff" strokeWidth="1.8" strokeLinecap="round" />
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

            <Link href="/wishlist" className={styles.mobileNavItem} aria-label="Wishlist">
              <span className={styles.mobileNavIcon}>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                  <path
                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                    stroke="#fff"
                    strokeWidth="1.6"
                    fill="none"
                  />
                </svg>
                {wishlist.length > 0 && <span className={styles.mobileNavBadge}>{wishlist.length}</span>}
              </span>
              <span className={styles.mobileNavLabel}>Wishlist</span>
            </Link>

            <button type="button" className={styles.mobileNavItem} onClick={onCartClick} aria-label="Open cart">
              <span className={styles.mobileNavIcon}>
                <CartIcon />
              </span>
              <span className={styles.mobileNavLabel}>Cart</span>
            </button>
          </nav>

          {isCategoryRoute && filterOpen && (
            <div className={styles.mobileFilterOverlay}>
              <div className={styles.mobileFilterBackdrop} onClick={() => setFilterOpen(false)} />
              <div className={styles.mobileFilterPanel}>
                <div className={styles.mobileFilterHeader}>
                  <span className={styles.mobileFilterTitle}>Filters</span>
                  <button
                    type="button"
                    className={styles.mobileFilterClose}
                    onClick={() => setFilterOpen(false)}
                    aria-label="Close filters"
                  >
                    X
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
