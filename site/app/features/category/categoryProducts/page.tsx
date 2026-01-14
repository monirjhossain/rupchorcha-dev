"use client";

import React, { useState, useEffect } from "react";
import { useSearchParams } from "next/navigation";
import ProductCard from "../../../components/ProductCard";
// import styles from "./page.module.css";
import GlobalSortBar from "../../../components/GlobalSortBar";
import { productsAPI, categoriesAPI } from "../../../services/api";
import { cartStorage } from "../../../utils/cartStorage";
import Link from "next/link";

const CategoryProductsPage = ({ params }: { params?: { slug?: string } }) => {
  const searchParams = useSearchParams();
  const slug = params?.slug || "";
  type Product = { id: number; name: string; [key: string]: any };
  type Category = { id: number; name: string; slug: string };
  const [products, setProducts] = useState<Product[]>([]);
  const [category, setCategory] = useState<Category | null>(null);
  const [loading, setLoading] = useState(false);
  // Removed unused setInitialLoading
  const [addingToCart, setAddingToCart] = useState<{ [key: string]: boolean }>({});
  const [showToast, setShowToast] = useState(false);
  const [toastMessage, setToastMessage] = useState("");
  const [sortBy, setSortBy] = useState("default");
  // Removed unused setSearchQuery
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [perPage] = useState(12);

  // Optimized: Fetch products as soon as possible, fetch category in parallel for display
  // Category cache (in-memory, shared across renders)
  const categoryCacheRef = React.useRef<Category[] | null>(null);

  // Read price filter from URL reactively
  const priceMin = parseInt(searchParams.get("price_min") || "0", 10);
  const priceMax = parseInt(searchParams.get("price_max") || "15000", 10);

  useEffect(() => {
    let cancelled = false;
    const tryFetch = async () => {
      let categoryId: number | null = null;
      let categoriesData: Category[] = [];

      // Try in-memory cache first
      if (categoryCacheRef.current) {
        categoriesData = categoryCacheRef.current;
      } else {
        // Try localStorage cache
        const cached = typeof window !== 'undefined' ? localStorage.getItem('categoriesCache') : null;
        if (cached) {
          try {
            categoriesData = JSON.parse(cached);
            categoryCacheRef.current = categoriesData;
          } catch {}
        }
      }

      // If still no categories, fetch from API
      if (!categoriesData || categoriesData.length === 0) {
        const categoriesResponse = await categoriesAPI.getAll();
        categoriesData = categoriesResponse.categories || categoriesResponse.data?.data || categoriesResponse.data || categoriesResponse;
        if (Array.isArray(categoriesData)) {
          categoryCacheRef.current = categoriesData;
          if (typeof window !== 'undefined') {
            localStorage.setItem('categoriesCache', JSON.stringify(categoriesData));
          }
        } else {
          categoriesData = [];
        }
      }

      // Find category by slug or ID
      if (!isNaN(Number(slug))) {
        categoryId = Number(slug);
        const foundCategory = categoriesData.find((cat: Category) => cat.id === categoryId);
        if (!cancelled) setCategory(foundCategory || null);
      } else {
        const foundCategory = categoriesData.find((cat: Category) => cat.slug === slug);
        if (foundCategory) {
          categoryId = foundCategory.id;
          if (!cancelled) setCategory(foundCategory);
        } else {
          if (!cancelled) setCategory(null);
        }
      }

      if (categoryId) {
        // Fetch products immediately
        setLoading(true);
        try {
          const params: Record<string, any> = {
            page: currentPage,
            per_page: perPage,
            categories: categoryId.toString(),
            price_min: priceMin,
            price_max: priceMax,
          };
          if (sortBy !== "default") {
            params.sort = sortBy;
          }
          const response = await productsAPI.getAll(currentPage, perPage, params);
          setProducts(response.products?.data || []);
          if (response.products?.last_page) {
            setTotalPages(response.products.last_page);
          }
        } catch (error) {
          setProducts([]);
          setTotalPages(1);
          console.error("Error fetching products:", error);
        } finally {
          setLoading(false);
        }
      } else {
        setProducts([]);
        setTotalPages(1);
      }
    };
    tryFetch();
    return () => { cancelled = true; };
  }, [slug, currentPage, sortBy, perPage, priceMin, priceMax, searchParams]);

  // fetchProducts is not used, so no update needed

  const handleAddToCart = async (product: Product) => {
    setAddingToCart((prev) => ({ ...prev, [product.id]: true }));
    try {
      // Add item to cart manually since cartStorage.addItem does not exist
      const cart = cartStorage.getCart();
      const existing = cart.items.find((item: Product) => item.id === product.id);
      if (existing) {
        existing.quantity = (existing.quantity || 1) + 1;
      } else {
        cart.items.push({ ...product, quantity: 1 });
      }
      if (typeof window !== 'undefined') {
        localStorage.setItem('cart', JSON.stringify(cart));
      }
      setToastMessage(`${product.name} added to cart!`);
      setShowToast(true);
      setTimeout(() => setShowToast(false), 3000);
    } catch (error) {
      console.error("Error adding to cart:", error);
    } finally {
      setAddingToCart((prev) => ({ ...prev, [product.id]: false }));
    }
  };

  return (
    <div className="shop-page">
      <GlobalSortBar sortBy={sortBy} setSortBy={setSortBy} />
      {showToast && <div className="toast-notification">{toastMessage}</div>}
      <div className="shop-container">
        <aside className="shop-sidebar"></aside>
        <main className="shop-main">
          {category ? (
            loading ? (
              <div className="loading-spinner">
                <div className="spinner"></div>
                <p>Loading products...</p>
              </div>
            ) : products.length === 0 ? (
              <div className="no-products">
                <p>No products found in this category.</p>
              </div>
            ) : (
              <>
                <div
                  className="products-grid"
                  style={{
                    display: 'grid',
                    gridTemplateColumns: 'repeat(4, 1fr)',
                    gap: '1.2rem',
                    alignItems: 'stretch',
                    margin: '0 auto',
                    maxWidth: '1200px',
                  }}
                >
                  {products.map((product) => (
                    <ProductCard
                      key={product.id}
                      product={product}
                      onAddToCart={handleAddToCart}
                      isAddingToCart={!!addingToCart[product.id]}
                    />
                  ))}
                </div>
                {totalPages > 1 && (
                  <div className="pagination">
                    <button
                      className="pagination-btn"
                      onClick={() => setCurrentPage((prev) => Math.max(1, prev - 1))}
                      disabled={currentPage === 1}
                    >
                      Previous
                    </button>
                    <div className="pagination-numbers">
                      {Array.from({ length: totalPages }).map((_, index) => (
                        <button
                          key={index + 1}
                          className={`pagination-number ${currentPage === index + 1 ? "active" : ""}`}
                          onClick={() => setCurrentPage(index + 1)}
                        >
                          {index + 1}
                        </button>
                      ))}
                    </div>
                    <button
                      className="pagination-btn"
                      onClick={() => setCurrentPage((prev) => Math.min(totalPages, prev + 1))}
                      disabled={currentPage === totalPages}
                    >
                      Next
                    </button>
                  </div>
                )}
              </>
            )
          ) : (
            <div style={{ minHeight: 120, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
              <span>Loading category...</span>
            </div>
          )}
        </main>
      </div>
    </div>
  );
};

export default CategoryProductsPage;