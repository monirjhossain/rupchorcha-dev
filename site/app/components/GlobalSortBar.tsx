// Modern, reusable global sort bar for all pages
import React from "react";
import styles from "./GlobalSortBar.module.css";

interface GlobalSortBarProps {
  sortBy: string;
  setSortBy: (value: string) => void;
}

const GlobalSortBar: React.FC<GlobalSortBarProps> = ({ sortBy, setSortBy }) => {
  return (
    <div className={styles["global-sort-bar"]}>
      <div style={{ flex: 1 }} />
      <select
        className={styles["sort-select"]}
        value={sortBy}
        onChange={(e) => setSortBy(e.target.value)}
        aria-label="Sort products"
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

export default GlobalSortBar;
