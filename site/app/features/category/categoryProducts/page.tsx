"use client";

import React, { useState, useEffect } from "react";
import { useSearchParams } from "next/navigation";
import ProductCard from "../../../components/ProductCard";
import gridStyles from "../../../components/ProductGrid.module.css";
import GlobalSortBar from "../../../components/GlobalSortBar";
import { productsAPI, categoriesAPI } from "../../../services/api";
import { useCart } from "@/app/common/CartContext";
import Link from "next/link";
import { usePaginationSort } from "@/app/hooks/usePaginationSort";

// Banner component
const CategoryBanner = ({ imageUrl }: { imageUrl: string }) => {
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
        alt="Category Banner"
        style={{
          width: "100%",
          height: "100%",
          objectFit: "cover"
        }}
      />
    </div>
  );
};

const CategoryProductsPage = ({ params }: { params?: { slug?: string } }) => {
  const searchParams = useSearchParams();
  const slug = params?.slug || "";
  type Product = { id: number; name: string; [key: string]: any };
  type Category = { id: number; name: string; slug: string; banner_image?: string };
  const [products, setProducts] = useState<Product[]>([]);
  const [category, setCategory] = useState<Category | null>(null);
  const [loading, setLoading] = useState(true);
  const [addingToCart, setAddingToCart] = useState<{ [key: string]: boolean }>({});
  const [showToast, setShowToast] = useState(false);
  const [toastMessage, setToastMessage] = useState("");
  const { currentPage, sortBy, handlePageChange, handleSortChange } = usePaginationSort();
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

  const { addToCart } = useCart();
  const handleAddToCart = async (product: Product) => {
    setAddingToCart((prev) => ({ ...prev, [product.id]: true }));
    try {
      addToCart({ product_id: product.id, quantity: 1, product });
      setToastMessage(`${product.name} added to cart!`);
      setShowToast(true);
      setTimeout(() => setShowToast(false), 3000);
    } catch (error) {
      console.error("Error adding to cart:", error);
    } finally {
      setAddingToCart((prev) => ({ ...prev, [product.id]: false }));
    }
  };

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
    <div className="shop-page">
      {/* Category Banner */}
      {category?.banner_image && <CategoryBanner imageUrl={category.banner_image} />}

      <GlobalSortBar 
        sortBy={sortBy} 
        setSortBy={(value) => handleSortChange(value, `/category/${slug}`)}
      />
      {showToast && <div className="toast-notification">{toastMessage}</div>}
      <div className={gridStyles["shop-container"]}>
        <main className={gridStyles["shop-main"]}>
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
                <div className={gridStyles["products-grid-wrapper"]}>
                  <div
                    className={gridStyles["products-grid"]}
                    style={loading ? { filter: 'blur(1.5px)', pointerEvents: 'none' } : {}}
                  >
                    {products.map((product) => (
                      <ProductCard
                        key={product.id}
                        product={{ ...product, images: Array.isArray(product.images) ? product.images : [] }}
                        onAddToCart={handleAddToCart}
                        isAddingToCart={!!addingToCart[product.id]}
                      />
                    ))}
                  </div>
                </div>
                {totalPages > 1 && (
                  <div className="pagination">
                    <button
                      className="pagination-btn"
                      onClick={() => handlePageChange(Math.max(1, currentPage - 1), `/category/${slug}`)}
                      disabled={currentPage === 1 || loading}
                    >
                      {loading ? '⏳' : '← Previous'}
                    </button>
                    <div className="pagination-numbers">
                      {getPageNumbers().map((page, index) =>
                        typeof page === 'string' ? (
                          <span key={`dots-${index}`} style={{ padding: '0.5rem 0.25rem', color: '#999' }}>
                            {page}
                          </span>
                        ) : (
                          <button
                            key={page}
                            className={`pagination-number ${currentPage === page ? "active" : ""}`}
                            onClick={() => handlePageChange(page as number, `/category/${slug}`)}
                            disabled={loading}
                          >
                            {page}
                          </button>
                        )
                      )}
                    </div>
                    <button
                      className="pagination-btn"
                      onClick={() => handlePageChange(Math.min(totalPages, currentPage + 1), `/category/${slug}`)}
                      disabled={currentPage === totalPages || loading}
                    >
                      {loading ? '⏳' : 'Next →'}
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