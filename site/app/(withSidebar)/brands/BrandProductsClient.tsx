"use client";
import React, { useState } from "react";
import { useCart } from "@/app/common/CartContext";
import ProductList from "@/app/components/ProductList";
import toast from "react-hot-toast";
import { useBrandProducts } from "@/app/services/useBrandProducts";

export default function BrandProductsClient({ slug }: { slug: string }) {
  const [isAddingToCart, setIsAddingToCart] = useState(false);
  const { addToCart } = useCart();
  const { products, isLoading, isError } = useBrandProducts(slug);

  const handleAddToCart = (product: any) => {
    setIsAddingToCart(true);
    addToCart({ product_id: product.id, quantity: 1, product });
    toast.success(`${product.name} added to cart!`);
    setTimeout(() => setIsAddingToCart(false), 500); // Simulate async
  };

  if (isLoading) return <div>Loading products...</div>;
  if (isError) return <div>Failed to load products.</div>;

  return (
    <ProductList
      products={products || []}
      onAddToCart={handleAddToCart}
      isAddingToCart={isAddingToCart}
    />
  );
}
