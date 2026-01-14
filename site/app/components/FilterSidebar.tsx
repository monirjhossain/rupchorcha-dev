"use client";
import React from "react";
import styles from "./FilterSidebar.module.css";

interface FilterSidebarProps {
  priceRange: [number, number];
  onPriceChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
  categories: Array<{ id: number; name: string; products_count?: number }>;
  selectedCategories: number[];
  onCategoryToggle: (id: number) => void;
  brands: Array<{ id: number; name?: string; admin_name?: string }>;
  selectedBrands: number[];
  onBrandToggle: (id: number) => void;
  showAllCategories: boolean;
  setShowAllCategories: (show: boolean) => void;
  showAllBrands: boolean;
  setShowAllBrands: (show: boolean) => void;
}

const FilterSidebar: React.FC<FilterSidebarProps> = ({
  priceRange,
  onPriceChange,
  categories,
  selectedCategories,
  onCategoryToggle,
  brands,
  selectedBrands,
  onBrandToggle,
  showAllCategories,
  setShowAllCategories,
  showAllBrands,
  setShowAllBrands
}) => {
  return (
    <aside className={styles.shopSidebar}>
      {/* Price Filter */}
      <div className={styles.filterSection}>
        <h3>Filter by Price</h3>
        <div className={styles.priceRange}>
          <input
            type="range"
            min="0"
            max="19500"
            value={priceRange[1]}
            onChange={onPriceChange}
            className={styles.priceSlider}
          />
          <div className={styles.priceLabels}>
            <span>৳ {priceRange[0]}</span>
            <span>৳ {priceRange[1]}</span>
          </div>
        </div>
      </div>
      {/* Categories Filter */}
      <div className={styles.filterSection}>
        <h3>Product Categories</h3>
        <div className={styles.categoryList}>
          {(showAllCategories ? categories : categories.slice(0, 10)).map(cat => (
            <label key={cat.id} className={styles.filterItem}>
              <input
                type="checkbox"
                checked={selectedCategories.includes(cat.id)}
                onChange={() => onCategoryToggle(cat.id)}
              />
              <span className={styles.categoryName}>{cat.name}</span>
              <span className={styles.categoryCount}>{cat.products_count || 0}</span>
            </label>
          ))}
        </div>
        {categories.length > 10 && (
          <button
            className={styles.seeMoreBtn}
            onClick={() => setShowAllCategories(!showAllCategories)}
          >
            {showAllCategories ? "See Less" : "See More"}
          </button>
        )}
      </div>
      {/* Brands Filter */}
      {brands.length > 0 && (
        <div className={styles.filterSection}>
          <h3>Brands</h3>
          <div className={styles.brandList}>
            {(showAllBrands ? brands : brands.slice(0, 10)).map(brand => (
              <label key={brand.id} className={styles.filterItem}>
                <input
                  type="checkbox"
                  checked={selectedBrands.includes(brand.id)}
                  onChange={() => onBrandToggle(brand.id)}
                />
                <span className={styles.brandName}>{brand.name || brand.admin_name}</span>
              </label>
            ))}
          </div>
          {brands.length > 10 && (
            <button
              className={styles.seeMoreBtn}
              onClick={() => setShowAllBrands(!showAllBrands)}
            >
              {showAllBrands ? "See Less" : "See More"}
            </button>
          )}
        </div>
      )}
    </aside>
  );
};

export default FilterSidebar;
