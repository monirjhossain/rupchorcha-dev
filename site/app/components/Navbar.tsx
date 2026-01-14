"use client";
import React, { useState, useEffect } from "react";
import Link from "next/link";
import CartSidebar from "./CartSidebar";
import SearchBox from "../SearchBox";
import styles from "./Navbar.module.css";
// TODO: Replace with real API hooks
// import { useCategories } from "@/src/hooks/useCategories";
// import useBrands from "@/src/hooks/useBrands";

interface NavbarProps {
  cartCount?: number;
  wishlistCount?: number;
  updateCartCount?: () => void;
  updateWishlistCount?: () => void;
}

const Navbar: React.FC<NavbarProps> = ({ cartCount = 0, wishlistCount = 0, updateCartCount, updateWishlistCount }) => {
  const [categories, setCategories] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [cartSidebarOpen, setCartSidebarOpen] = useState(false);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  // Dummy fetch for migration (replace with real API)
  useEffect(() => {
    setLoading(true);
    setTimeout(() => {
      setCategories([
        { id: 1, name: "Makeup" },
        { id: 2, name: "Skin" },
        { id: 3, name: "Hair" },
        { id: 4, name: "Personal care" },
        { id: 5, name: "Mom & Baby" },
        { id: 6, name: "Fragrance" }
      ]);
      setLoading(false);
    }, 500);
  }, []);

  const handleMobileLinkClick = () => setMobileMenuOpen(false);

  return (
    <nav className={styles.navbar}>
      <div className={styles.navbarInner}>
        <div className={styles.logoWrap}>
          <Link href="/">
            <img src="/rupchorcha-logo.png" alt="Logo" className={styles.logoImg} />
          </Link>
        </div>
        <div className={styles.searchWrap}>
          <SearchBox />
        </div>
        <div className={styles.actionBtns}>
          <button className={styles.cartBtn} onClick={() => setCartSidebarOpen(true)}>
            🛒 <span className={styles.cartCount}>{cartCount}</span>
          </button>
          <Link href="/wishlist" className={styles.wishlistBtn}>
            ❤️ <span className={styles.wishlistCount}>{wishlistCount}</span>
          </Link>
        </div>
      </div>
      <div className={styles.categoryBar}>
        {loading ? (
          <span>Loading...</span>
        ) : (
          categories.map(cat => (
            <Link
              href={`/category/${encodeURIComponent(cat.name.toLowerCase().replace(/\s+/g, "-"))}`}
              key={cat.id}
              className={styles.categoryLink}
              onClick={handleMobileLinkClick}
            >
              {cat.name}
            </Link>
          ))
        )}
      </div>
      <CartSidebar isOpen={cartSidebarOpen} onClose={() => setCartSidebarOpen(false)} updateCartCount={updateCartCount} />
    </nav>
  );
};

export default Navbar;
