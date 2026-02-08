"use client";
import React, { useState } from "react";
import { useCart } from "@/app/common/CartContext";
import ProductCard from "@/app/components/ProductCard";
import gridStyles from "@/app/components/ProductGrid.module.css";
import toast from "react-hot-toast";
import { useBrandProducts } from "@/app/services/useBrandProducts";
import GlobalSortBar from "@/app/components/GlobalSortBar";
import { usePaginationSort } from "@/app/hooks/usePaginationSort";

// Banner component
const BrandBanner = ({ imageUrl }: { imageUrl: string }) => {
  if (!imageUrl) return null;

  const API_BASE = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api";
  const fullUrl = imageUrl.startsWith('http') 
    ? imageUrl 
    : `${API_BASE.replace('/api', '')}/storage/${imageUrl}`;

  return (
    <div style={{
      width: "100%",
      height: "200px",
      borderRadius: "12px",
      overflow: "hidden",
      marginBottom: "1.5rem",
      boxShadow: "0 4px 12px rgba(0,0,0,0.1)"
    }}>
      <img
        src={fullUrl}
        alt="Brand Banner"
        style={{
          width: "100%",
          height: "100%",
          objectFit: "cover"
        }}
      />
    </div>
  );
};

export default function BrandProductsClient({ slug }: { slug: string }) {
  const [isAddingToCart, setIsAddingToCart] = useState<{ [key: number]: boolean }>({});
  const { currentPage, sortBy, handlePageChange, handleSortChange } = usePaginationSort();
  const perPage = 12;

  const { addToCart } = useCart();
  const { products, brand, isLoading, isError, meta } = useBrandProducts(slug, currentPage, perPage, sortBy);

  const handleAddToCart = (product: any) => {
    setIsAddingToCart((prev) => ({ ...prev, [product.id]: true }));
    addToCart({ product_id: product.id, quantity: 1, product });
    toast.success(`${product.name} added to cart!`);
    setTimeout(() => setIsAddingToCart((prev) => ({ ...prev, [product.id]: false })), 400);
  };

  const totalPages = meta?.lastPage || 1;

  // Generate smart pagination numbers (first, last, and around current)
  const getPageNumbers = () => {
    const pages: (number | string)[] = [];
    const showPages = 3; // Pages on each side of current
    const start = Math.max(1, currentPage - showPages);
    const end = Math.min(totalPages, currentPage + showPages);

    if (start > 1) {
      pages.push(1);
      if (start > 2) pages.push('...');
    }

    for (let i = start; i <= end; i++) {
      pages.push(i);
    }

    if (end < totalPages) {
      if (end < totalPages - 1) pages.push('...');
      pages.push(totalPages);
    }

    return pages;
  };

  return (
    <div style={{ display: "flex", flexDirection: "column", gap: "1.25rem" }}>
      {/* Brand Banner */}
      {brand?.banner_image && <BrandBanner imageUrl={brand.banner_image} />}

      <GlobalSortBar
        sortBy={sortBy}
        setSortBy={(value) => handleSortChange(value, `/brands/${slug}`)}
      />

      {isError && <div style={{ color: "#d33" }}>Failed to load products.</div>}

      <div className={gridStyles["shop-container"]}>
        <main className={gridStyles["shop-main"]}>
          {isLoading && products.length === 0 ? (
            <div className="loading-spinner">
              <div className="spinner"></div>
              <p>Loading products...</p>
            </div>
          ) : products.length === 0 ? (
            <div className="no-products">
              <p>No products found for this brand.</p>
            </div>
          ) : (
            <div className={gridStyles["products-grid-wrapper"]}>
              <div
                className={gridStyles["products-grid"]}
                style={isLoading ? { filter: "blur(1.5px)", pointerEvents: "none" } : {}}
              >
                {products.map((product) => (
                  <ProductCard
                    key={product.id}
                    product={product}
                    onAddToCart={() => handleAddToCart(product)}
                    isAddingToCart={!!isAddingToCart[product.id]}
                  />
                ))}
              </div>
            </div>
          )}
        </main>
      </div>

      {totalPages > 1 && (
        <div className="pagination">
          <button
            className="pagination-btn"
            onClick={() => handlePageChange(Math.max(1, currentPage - 1), `/brands/${slug}`)}
            disabled={currentPage === 1 || isLoading}
          >
            {isLoading ? '⏳' : '← Previous'}
          </button>
          <div className="pagination-numbers">
            {getPageNumbers().map((page, idx) =>
              typeof page === 'string' ? (
                <span key={`dots-${idx}`} style={{ padding: '0.5rem 0.25rem', color: '#999' }}>
                  {page}
                </span>
              ) : (
                <button
                  key={page}
                  className={`pagination-number ${currentPage === page ? "active" : ""}`}
                  onClick={() => handlePageChange(page as number, `/brands/${slug}`)}
                  disabled={isLoading}
                >
                  {page}
                </button>
              )
            )}
          </div>
          <button
            className="pagination-btn"
            onClick={() => handlePageChange(Math.min(totalPages, currentPage + 1), `/brands/${slug}`)}
            disabled={currentPage === totalPages || isLoading}
          >
            {isLoading ? '⏳' : 'Next →'}
          </button>
        </div>
      )}
    </div>
  );
}
