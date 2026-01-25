"use client";
import React, { useState } from "react";
import { useCart } from "@/app/common/CartContext";
import ProductList from "@/app/components/ProductList";
import toast from "react-hot-toast";
import { useShopProducts } from "@/app/services/useShopProducts";
import GlobalSortBar from "@/app/components/GlobalSortBar";
import { usePaginationSort } from "@/app/hooks/usePaginationSort";

// Banner component
const ShopBanner = ({ imageUrl }: { imageUrl: string }) => (
  <div style={{
    width: "100%",
    height: "200px",
    borderRadius: "12px",
    overflow: "hidden",
    marginBottom: "1.5rem",
    boxShadow: "0 4px 12px rgba(0,0,0,0.1)"
  }}>
    <img
      src={imageUrl}
      alt="Shop Banner"
      style={{
        width: "100%",
        height: "100%",
        objectFit: "cover"
      }}
    />
  </div>
);

export default function ShopProductsClient() {
  const [isAddingToCart, setIsAddingToCart] = useState<{ [key: number]: boolean }>({});
  const { currentPage, sortBy, handlePageChange, handleSortChange } = usePaginationSort();
  
  // Banner image URL - Change this to update banner
  const bannerImageUrl = "https://images.unsplash.com/photo-1556740738-b6a63e27c4df?w=1200&h=200&fit=crop";
  
  const perPage = 20;

  const { addToCart } = useCart();
  const { products, isLoading, isError, meta } = useShopProducts(currentPage, perPage, sortBy);

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
      {/* Banner */}
      <ShopBanner imageUrl={bannerImageUrl} />

      <GlobalSortBar
        sortBy={sortBy}
        setSortBy={(value) => handleSortChange(value, "/shop")}
      />

      {isError && <div style={{ color: "#d33" }}>Failed to load products.</div>}

      {products.length === 0 && isLoading ? (
        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fill, minmax(220px, 1fr))',
          gap: '1.5rem'
        }}>
          {Array.from({ length: 20 }).map((_, i) => (
            <div key={i} style={{
              background: '#f5f5f5',
              borderRadius: '12px',
              height: '380px',
              animation: 'pulse 1.5s ease-in-out infinite'
            }} />
          ))}
        </div>
      ) : products.length === 0 ? (
        <div style={{ textAlign: "center", padding: "2rem", color: "#666" }}>No products found.</div>
      ) : (
        <>
          {isLoading && (
            <div style={{
              display: 'grid',
              gridTemplateColumns: 'repeat(auto-fill, minmax(220px, 1fr))',
              gap: '1.5rem',
              opacity: 0.6,
              pointerEvents: 'none'
            }}>
              {Array.from({ length: 20 }).map((_, i) => (
                <div key={i} style={{
                  background: '#f5f5f5',
                  borderRadius: '12px',
                  height: '380px',
                  animation: 'pulse 1.5s ease-in-out infinite'
                }} />
              ))}
            </div>
          )}
          {!isLoading && (
            <ProductList
              products={products}
              onAddToCart={handleAddToCart}
              addingStateMap={isAddingToCart}
            />
          )}
        </>
      )}

      {totalPages > 1 && (
        <div className="pagination">
          <button
            className="pagination-btn"
            onClick={() => handlePageChange(Math.max(1, currentPage - 1), "/shop")}
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
                  onClick={() => handlePageChange(page as number, "/shop")}
                  disabled={isLoading}
                >
                  {page}
                </button>
              )
            )}
          </div>
          <button
            className="pagination-btn"
            onClick={() => handlePageChange(Math.min(totalPages, currentPage + 1), "/shop")}
            disabled={currentPage === totalPages || isLoading}
          >
            {isLoading ? '⏳' : 'Next →'}
          </button>
        </div>
      )}
    </div>
  );
}
