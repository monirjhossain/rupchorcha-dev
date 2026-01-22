
"use client";
import React, { useState, useEffect, useRef } from "react";
import ProductCard from "./ProductCard";
import GlobalSortBar from "./GlobalSortBar";
import Sidebar from "./Sidebar";
import { useCart } from "../common/CartContext";
import { useSearchParams } from "next/navigation";
import gridStyles from "./ProductGrid.module.css";

const TagProductsPage = ({ params }: { params?: { slug?: string } }) => {
  const searchParams = useSearchParams();
  const slug = params?.slug || "";
  const [products, setProducts] = useState<any[]>([]);
  const [tag, setTag] = useState<any>(null);
  const [loading, setLoading] = useState(false);
  const [sortBy, setSortBy] = useState("default");
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [perPage] = useState(12);
  const { addToCart } = useCart();
  const [addingToCart, setAddingToCart] = useState<{ [key: number]: boolean }>({});

  const handleAddToCart = async (product: any) => {
    setAddingToCart(prev => ({ ...prev, [product.id]: true }));
    try {
      await addToCart({ product_id: product.id, quantity: 1, product });
    } catch (error) {
      console.error("Failed to add to cart:", error);
    } finally {
      setAddingToCart(prev => ({ ...prev, [product.id]: false }));
    }
  };

  // Debounce filter/api calls for fast UX
  const debounceRef = useRef<NodeJS.Timeout | null>(null);
  useEffect(() => {
    let cancelled = false;
    if (debounceRef.current) clearTimeout(debounceRef.current);
    debounceRef.current = setTimeout(() => {
      const fetchProducts = async () => {
        setLoading(true);
        try {
          const apiUrl = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api";
          const paramsObj: Record<string, any> = { page: currentPage, per_page: perPage };
          if (sortBy !== "default") paramsObj.sort = sortBy;
          const paramStr = Object.entries(paramsObj).map(([k, v]) => `${k}=${v}`).join("&");
          const res = await fetch(`${apiUrl}/tags/${slug}?${paramStr}`);
          const data = await res.json();
          if (!cancelled) {
            setTag(data.tag || null);
            setProducts(data.products?.data || data.products || []);
            setTotalPages(data.products?.last_page || 1);
          }
        } catch (e) {
          if (!cancelled) {
            setProducts([]);
            setTotalPages(1);
          }
        } finally {
          if (!cancelled) setLoading(false);
        }
      };
      if (slug) fetchProducts();
    }, 350); // 350ms debounce
    return () => {
      cancelled = true;
      if (debounceRef.current) clearTimeout(debounceRef.current);
    };
  }, [slug, currentPage, sortBy, perPage]);

  return (
    <div className="shop-page">
      <GlobalSortBar sortBy={sortBy} setSortBy={setSortBy} />
      <div className={gridStyles["shop-container"]}>
        <Sidebar />
        <main className={gridStyles["shop-main"]}>
          {tag ? (
            loading ? (
              <div className="loading-spinner">
                <div className="spinner"></div>
                <p>Loading products...</p>
              </div>
            ) : products.length === 0 ? (
              <div className="no-products">
                <p>No products found for this tag.</p>
              </div>
            ) : (
              <>
                <div style={{fontSize:'1.7rem',fontWeight:'bold',color:'#222',marginBottom:'0.5rem',marginTop:'0.5rem',textAlign:'center'}}>
                  {tag.name} Products
                </div>
                        <div className={gridStyles["products-grid-wrapper"]}>
                          {loading && (
                            <div className={gridStyles["grid-loading-overlay"]}>
                              <div className="spinner"></div>
                            </div>
                          )}
                          <div className={gridStyles["products-grid"]} style={loading ? {filter:'blur(1.5px)', pointerEvents:'none'} : {}}>
                            {products.map((product) => (
                              <ProductCard 
                                key={product.id} 
                                product={product} 
                                onAddToCart={() => handleAddToCart(product)}
                                isAddingToCart={addingToCart[product.id]}
                              />
                            ))}
                          </div>
                        </div>
                {totalPages > 1 && (
                  <div className="pagination">
                    <button className="pagination-btn" onClick={() => setCurrentPage((prev) => Math.max(1, prev - 1))} disabled={currentPage === 1}>Previous</button>
                    <div className="pagination-numbers">
                      {Array.from({ length: totalPages }).map((_, index) => (
                        <button key={index + 1} className={`pagination-number ${currentPage === index + 1 ? "active" : ""}`} onClick={() => setCurrentPage(index + 1)}>
                          {index + 1}
                        </button>
                      ))}
                    </div>
                    <button className="pagination-btn" onClick={() => setCurrentPage((prev) => Math.min(totalPages, prev + 1))} disabled={currentPage === totalPages}>Next</button>
                  </div>
                )}
              </>
            )
          ) : (
            <div style={{ minHeight: 120, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
              <span>Loading tag...</span>
            </div>
          )}
        </main>
      </div>
    </div>
  );
};

export default TagProductsPage;
