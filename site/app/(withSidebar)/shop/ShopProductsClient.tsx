"use client";
import React, { useState, useEffect } from "react";
import { useCart } from "@/app/common/CartContext";
import toast from "react-hot-toast";
import { useShopProducts } from "@/app/services/useShopProducts";
import GlobalSortBar from "@/app/components/GlobalSortBar";
import PaginationControls from "@/app/components/PaginationControls";
import { usePaginationSort } from "@/app/hooks/usePaginationSort";
import ProductCard from "@/app/components/ProductCard";
import gridStyles from "@/app/components/ProductGrid.module.css";

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

  const [displayProducts, setDisplayProducts] = useState<any[]>([]);

  useEffect(() => {
    if (isLoading) return;

    // When we are on the first page (initial load or filters/sort reset),
    // replace the list with the current page's products.
    if (currentPage === 1) {
      setDisplayProducts(products);
      return;
    }

    // For subsequent pages, append new products while avoiding duplicates.
    setDisplayProducts((prev) => {
      const existingIds = new Set(prev.map((p: any) => p.id));
      const merged = [...prev];
      for (const p of products) {
        if (!existingIds.has(p.id)) {
          merged.push(p);
        }
      }
      return merged;
    });
  }, [products, currentPage, isLoading]);

  const handleAddToCart = (product: any) => {
    setIsAddingToCart((prev) => ({ ...prev, [product.id]: true }));
    addToCart({ product_id: product.id, quantity: 1, product });
    toast.success(`${product.name} added to cart!`);
    setTimeout(() => setIsAddingToCart((prev) => ({ ...prev, [product.id]: false })), 400);
  };

  const totalPages = meta?.lastPage || 1;

  return (
    <div className="shop-page" style={{ display: "flex", flexDirection: "column", gap: "1.25rem" }}>
      {/* Banner */}
      <ShopBanner imageUrl={bannerImageUrl} />

      <GlobalSortBar
        sortBy={sortBy}
        setSortBy={(value) => handleSortChange(value, "/shop")}
      />

      {isError && <div style={{ color: "#d33" }}>Failed to load products.</div>}

      <div className={gridStyles["shop-container"]}>
        <main className={gridStyles["shop-main"]}>
          {isLoading && displayProducts.length === 0 ? (
            <div className="loading-spinner">
              <div className="spinner"></div>
              <p>Loading products...</p>
            </div>
          ) : displayProducts.length === 0 ? (
            <div className="no-products">
              <p>No products found.</p>
            </div>
          ) : (
            <>
              <div className={gridStyles["products-grid-wrapper"]}>
                <div
                  className={gridStyles["products-grid"]}
                  style={isLoading ? { filter: 'blur(1.5px)', pointerEvents: 'none' } : {}}
                >
                  {displayProducts.map((product: any) => (
                    <ProductCard
                      key={product.id}
                      product={{ ...product, images: Array.isArray(product.images) ? product.images : [] }}
                      onAddToCart={handleAddToCart}
                      isAddingToCart={!!isAddingToCart[product.id]}
                    />
                  ))}
                </div>
              </div>
            </>
          )}
        </main>
      </div>

      <PaginationControls
        currentPage={currentPage}
        totalPages={totalPages}
        isLoading={isLoading}
        onPageChange={(page) => handlePageChange(page, "/shop")}
        variant="load-more"
      />
    </div>
  );
}
