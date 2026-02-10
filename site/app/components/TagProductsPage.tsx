
"use client";
import React, { useState } from "react";
import { useSearchParams } from "next/navigation";
import useSWR from "swr";
import ProductCard from "./ProductCard";
import GlobalSortBar from "./GlobalSortBar";
import Sidebar from "./Sidebar";
import { useCart } from "@/app/common/CartContext";
import gridStyles from "./ProductGrid.module.css";
import { usePaginationSort } from "../hooks/usePaginationSort";
import { fetcher } from "@/src/services/apiClient";
import toast from "react-hot-toast";
import PaginationControls from "@/app/components/PaginationControls";

const TagProductsPage = ({ params }: { params?: { slug?: string } }) => {
  const searchParams = useSearchParams();
  const slug = params?.slug || "";
  
  const { currentPage, sortBy, handlePageChange, handleSortChange } = usePaginationSort();
  const [perPage] = useState(12);
  const { addToCart } = useCart();
  const [addingToCart, setAddingToCart] = useState<{ [key: number]: boolean }>({});

  const handleAddToCart = async (product: any) => {
    setAddingToCart(prev => ({ ...prev, [product.id]: true }));
    try {
      await addToCart({ 
        product_id: product.id, 
        quantity: 1, 
        product,
      });
      toast.success(`${product.name} added to cart!`);
    } catch (error) {
      console.error("Failed to add to cart:", error);
      toast.error("Failed to add to cart");
    } finally {
      setTimeout(() => setAddingToCart(prev => ({ ...prev, [product.id]: false })), 400);
    }
  };

  // Build SWR key
  const paramsObj: Record<string, any> = { page: currentPage, per_page: perPage };
  if (sortBy !== "default") paramsObj.sort = sortBy;
  const paramStr = Object.entries(paramsObj).map(([k, v]) => `${k}=${v}`).join("&");
  const key = slug ? `/tags/${slug}?${paramStr}` : null;

  const { data, isLoading } = useSWR(key, fetcher);

  const products = data?.products?.data || [];
  const tag = data?.tag;
  const totalPages = data?.products?.last_page || 1;

  return (
    <div className="shop-page">
      <GlobalSortBar 
        totalProducts={data?.products?.total || products.length}
        sortBy={sortBy} 
        setSortBy={(value) => handleSortChange(value, `/tags/${slug}`)}
      />
      <div className={gridStyles["shop-container"]}>
        <div className="responsive-sidebar">
          <Sidebar />
        </div>
        <main className={gridStyles["shop-main"]}>
          {tag ? (
            isLoading ? (
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
                          {isLoading && (
                            <div className={gridStyles["grid-loading-overlay"]}>
                              <div className="spinner"></div>
                            </div>
                          )}
                          <div className={gridStyles["products-grid"]} style={isLoading ? {filter:'blur(1.5px)', pointerEvents:'none'} : {}}>
                            {products.map((product: any) => (
                              <ProductCard 
                                key={product.id} 
                                product={product} 
                                onAddToCart={() => handleAddToCart(product)}
                                isAddingToCart={addingToCart[product.id]}
                              />
                            ))}
                          </div>
                        </div>
                <PaginationControls
                  currentPage={currentPage}
                  totalPages={totalPages}
                  isLoading={loading}
                  onPageChange={(page) => handlePageChange(page, `/tags/${slug}`)}
                />
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
