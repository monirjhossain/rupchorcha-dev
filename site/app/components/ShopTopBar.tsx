"use client";
import React from "react";
import styles from "./ShopTopBar.module.css";

interface ShopTopBarProps {
  searchQuery: string;
  onSearchChange: (value: string) => void;
  onSearchSubmit: (e: React.FormEvent) => void;
  sortBy: string;
  onSortChange: (value: string) => void;
}

const ShopTopBar: React.FC<ShopTopBarProps> = ({
  searchQuery,
  onSearchChange,
  onSearchSubmit,
  sortBy,
  onSortChange
}) => {
  return (
    <div className={styles.shopHeader}>
      <form onSubmit={onSearchSubmit} className={styles.shopSearch}>
        <input
          type="text"
          placeholder="Search here..."
          value={searchQuery}
          onChange={e => onSearchChange(e.target.value)}
        />
        <button type="submit">🔍</button>
      </form>
      <select
        className={styles.sortSelect}
        value={sortBy}
        onChange={e => onSortChange(e.target.value)}
      >
        <option value="default">Default sorting</option>
        <option value="price_low">Price: Low to High</option>
        <option value="price_high">Price: High to Low</option>
        <option value="name_asc">Name: A to Z</option>
        <option value="name_desc">Name: Z to A</option>
        <option value="newest">Newest First</option>
      </select>
    </div>
  );
};

export default ShopTopBar;
