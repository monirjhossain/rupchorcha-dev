"use client";
import styles from './Header.module.css';
import Link from 'next/link';
import { useCategories } from '@/src/hooks/useCategories';
import CartSidebar from './CartSidebar';
import React, { useState } from 'react';
import Image from 'next/image';
import SearchBox from './SearchBox';


const Header: React.FC = () => {
  const { categories, isLoading } = useCategories();
  const [cartSidebarOpen, setCartSidebarOpen] = useState(false);

  return (
    <>
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
              <button
                type="button"
                className={styles.actionBtn + ' ' + styles.actionBtnDark}
                style={{background:'none',border:'none',cursor:'pointer',position:'relative',padding:'0.2rem 0.7rem'}}
                aria-label="Wishlist"
              >
                <span style={{position:'relative',display:'inline-block'}}>
                  {/* Heart SVG icon */}
                  <svg width="26" height="26" viewBox="0 0 24 24" fill="none" style={{display:'block'}}>
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="#7c32ff" strokeWidth="1.5" fill="#fff"/>
                  </svg>
                  {/* Count badge */}
                  <span style={{position:'absolute',top:-8,right:-8,minWidth:22,height:22,background:'#7c32ff',color:'#fff',borderRadius:'50%',fontWeight:700,fontSize:'0.98rem',display:'flex',alignItems:'center',justifyContent:'center',boxShadow:'0 2px 8px #7c32ff22',border:'2px solid #fff'}}>1</span>
                </span>
              </button>
              <button
                type="button"
                className={styles.actionBtn + ' ' + styles.actionBtnLight}
                style={{background:'none',border:'none',cursor:'pointer',position:'relative',padding:'0.2rem 0.7rem'}}
                aria-label="Login"
              >
                <span style={{position:'relative',display:'inline-block'}}>
                  {/* User SVG icon */}
                  <svg width="26" height="26" viewBox="0 0 24 24" fill="none" style={{display:'block'}}>
                    <circle cx="12" cy="8" r="4" stroke="#222" strokeWidth="1.5" fill="#fff"/>
                    <path d="M4 20c0-3.31 3.13-6 8-6s8 2.69 8 6" stroke="#222" strokeWidth="1.5" fill="#fff"/>
                  </svg>
                  {/* Count badge */}
                  <span style={{position:'absolute',top:-8,right:-8,minWidth:22,height:22,background:'#222',color:'#fff',borderRadius:'50%',fontWeight:700,fontSize:'0.98rem',display:'flex',alignItems:'center',justifyContent:'center',boxShadow:'0 2px 8px #2222',border:'2px solid #fff'}}>0</span>
                </span>
              </button>
              <button
                type="button"
                className={styles.actionBtn + ' ' + styles.actionBtnPink}
                onClick={()=>setCartSidebarOpen(true)}
                style={{background:'none',border:'none',cursor:'pointer',position:'relative',padding:'0.2rem 0.7rem'}}
                aria-label="Open cart"
              >
                <span style={{position:'relative',display:'inline-block'}}>
                  {/* Modern cart SVG icon */}
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" style={{display:'block'}}>
                    <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM7.16 14l.84-2h7l.84 2H7.16zM6.16 12l1.25-3h9.18l1.25 3H6.16zM7.25 7l.75-2h7l.75 2H7.25z" stroke="#e91e63" strokeWidth="1.5" fill="#fff"/>
                  </svg>
                  {/* Count badge */}
                  <span style={{position:'absolute',top:-8,right:-8,minWidth:22,height:22,background:'#e91e63',color:'#fff',borderRadius:'50%',fontWeight:700,fontSize:'0.98rem',display:'flex',alignItems:'center',justifyContent:'center',boxShadow:'0 2px 8px #e91e6322',border:'2px solid #fff'}}>2</span>
                </span>
              </button>
            </div>
          </div>
          <nav className={styles.categoryBar}>
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
                >
                  {cat.name}
                </Link>
              ))
            )}
          </nav>
        </div>
      </header>
      <CartSidebar isOpen={cartSidebarOpen} onClose={()=>setCartSidebarOpen(false)} />
    </>
  );
};

export default Header;



