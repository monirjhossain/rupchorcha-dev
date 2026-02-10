"use client";
import React, { useState } from "react";
import { useCart } from "@/app/common/CartContext";
import ProductCard from "@/app/components/ProductCard";
import gridStyles from "@/app/components/ProductGrid.module.css";
import toast from "react-hot-toast";
import GlobalSortBar from "@/app/components/GlobalSortBar";
import PaginationControls from "@/app/components/PaginationControls";
import { usePaginationSort } from "@/app/hooks/usePaginationSort";
import { useProducts } from "@/src/hooks/useProducts";
import { useBrands } from "@/src/hooks/useBrands";

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
  const { brands, isLoading: brandsLoading } = useBrands();
  
  const foundBrand = React.useMemo(() => {
     if (!brands) return null;
     if (!isNaN(Number(slug))) {
        return brands.find(b => b.id === Number(slug));
     }
     return brands.find(b => b.slug === slug);
  }, [brands, slug]);


  const { products: fetchedProducts, meta, isLoading: productsLoading, isError } = useProducts({ 
      page: currentPage, 
      brand_id: foundBrand?.id, 
      sort: sortBy 
  });
  
  const products = fetchedProducts || [];
  const isLoading = brandsLoading || productsLoading;
  const brand = foundBrand;

  if (!brandsLoading && slug && !foundBrand) {
    return (
      <div style={{textAlign:'center', padding:'4rem 0'}}>
         <h2>Brand Not Found</h2>
         <p>We couldn't find the brand you're looking for.</p>
      </div>
    );
 }

  const handleAddToCart = async (product: any) => {
    setIsAddingToCart((prev) => ({ ...prev, [product.id]: true }));
    try {
      await addToCart({ 
        product_id: product.id,
        quantity: 1,
        product,
      });
        toast.success(`${product.name} added to cart!`);
    } catch(e) {
        console.error(e);
        toast.error('Failed to add to cart');
    } finally {
        setTimeout(() => setIsAddingToCart((prev) => ({ ...prev, [product.id]: false })), 400);
    }
  };

  const totalPages = meta?.last_page || 1;

  return (
    <div style={{ display: "flex", flexDirection: "column", gap: "1.25rem" }}>
      {/* Brand Banner */}
      {brand?.logo && <BrandBanner imageUrl={brand.logo} />}
      {/* Fallback to legacy field if logo not used for banner */}

      <GlobalSortBar
        totalProducts={meta?.total || products.length}
        currentSort={sortBy}
        onSortChange={handleSortChange}
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

      <PaginationControls
        currentPage={currentPage}
        totalPages={totalPages}
        isLoading={isLoading}
        onPageChange={(page) => handlePageChange(page, `/brands/${slug}`)}
      />
    </div>
  );
}
