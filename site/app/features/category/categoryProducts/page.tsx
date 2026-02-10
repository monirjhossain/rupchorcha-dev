"use client";

import React, { useState } from "react";
import { useSearchParams } from "next/navigation";
import ProductCard from "../../../components/ProductCard";
import gridStyles from "../../../components/ProductGrid.module.css";
import GlobalSortBar from "../../../components/GlobalSortBar";
import PaginationControls from "@/app/components/PaginationControls";
import { useCart } from "@/app/common/CartContext";
import { usePaginationSort } from "@/app/hooks/usePaginationSort";
import { useProducts } from "@/src/hooks/useProducts";
import { useCategories } from "@/src/hooks/useCategories";

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
  
  const { currentPage, sortBy, handlePageChange, handleSortChange } = usePaginationSort();
  
  const basePath = `/category/${slug}`;
  const onSortChange = (val: string) => handleSortChange(val, basePath);
  const onPageChange = (page: number) => handlePageChange(page, basePath);

  const { categories, isLoading: categoriesLoading } = useCategories();
  
  // Find category by slug or ID
  const foundCategory = React.useMemo(() => {
    if (!categories) return null;
    if (!isNaN(Number(slug))) {
        return categories.find((c) => c.id === Number(slug));
    }
    return categories.find((c) => c.slug === slug);
  }, [categories, slug]);

  // Read price filter from URL reactively
  const priceMin = parseInt(searchParams.get("price_min") || "0", 10);
  const priceMax = parseInt(searchParams.get("price_max") || "15000", 10);

  // Fetch products
  const { products: fetchedProducts, meta, isLoading: productsLoading } = useProducts({
    page: currentPage,
    category_id: foundCategory?.id,
    sort: sortBy,
    min_price: priceMin,
    max_price: priceMax,
  });

  const products = fetchedProducts || [];
  const totalPages = meta?.last_page || 1;
  const loading = categoriesLoading || productsLoading;

  // New: Handle 404 case for invalid slug
  if (!categoriesLoading && slug && !foundCategory) {
     return (
       <div className={gridStyles.container}>
         <div style={{textAlign:'center', padding:'4rem 0'}}>
            <h2>Category Not Found</h2>
            <p>We couldn't find the category you're looking for.</p>
         </div>
       </div>
     );
  }
  
  const [addingToCart, setAddingToCart] = useState<{ [key: string]: boolean }>({});
  const [showToast, setShowToast] = useState(false);
  const [toastMessage, setToastMessage] = useState("");


  const { addToCart } = useCart();

  const handleAddToCart = async (product: any) => {
    setAddingToCart((prev) => ({ ...prev, [product.id]: true }));
    try {
      await addToCart({
        product_id: product.id,
        quantity: 1,
        product,
      });
      // Use simple toast if available or console log (removed setShotToast to match brand behaviour mostly)
      // setToastMessage(`${product.name} added to cart!`);
      // setShowToast(true);
      // setTimeout(() => setShowToast(false), 3000);
    } catch (error) {
      console.error("Failed to add to cart", error);
    } finally {
      setTimeout(() => setAddingToCart((prev) => ({ ...prev, [product.id]: false })), 400);
    }
  };

  return (
    <div style={{ display: "flex", flexDirection: "column", gap: "1.25rem" }}>
       {foundCategory?.image && <CategoryBanner imageUrl={foundCategory.image} />}

       <GlobalSortBar
        sortBy={sortBy}
        setSortBy={onSortChange}
      />

      <div className={gridStyles["shop-container"]}>
        <main className={gridStyles["shop-main"]}>
            {loading && products.length === 0 ? (
                <div style={{textAlign:'center', padding:'3rem', color:'#666'}}>
                <div className={gridStyles.loader} style={{margin:'0 auto 1rem auto'}}></div>
                <p>Loading products...</p>
                </div>
            ) : products.length > 0 ? (
                <div className={gridStyles["products-grid-wrapper"]}>
                    <div className={gridStyles["products-grid"]} style={loading ? { filter: "blur(1.5px)", pointerEvents: "none" } : {}}>
                    {products.map((product) => (
                        <ProductCard
                        key={product.id}
                        product={product}
                        onAddToCart={() => handleAddToCart(product)}
                        isAddingToCart={addingToCart[product.id] || false}
                        />
                    ))}
                    </div>
                </div>
            ) : (
                <div className={gridStyles.noProducts} style={{textAlign:'center', padding:'3rem', color:'#888'}}>
                <p>No products found in this category.</p>
                </div>
            )}
        </main>
      </div>

      <PaginationControls
        currentPage={currentPage}
        totalPages={totalPages}
        isLoading={loading}
        onPageChange={(page) => onPageChange(page)}
      />
    </div>
  );
};

export default CategoryProductsPage;
