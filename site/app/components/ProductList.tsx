import React from "react";
import ProductCard from "./ProductCard";

interface Product {
  id: number;
  name: string;
  price: number;
  image?: string;
  [key: string]: any;
}

interface ProductListProps {
  products: Product[];
  onAddToCart?: (product: Product) => void;
  isAddingToCart?: boolean;
  addingStateMap?: { [key: number]: boolean };
}

const ProductList: React.FC<ProductListProps> = ({ products, onAddToCart, isAddingToCart, addingStateMap }) => {
  if (!products || products.length === 0) {
    return <div>No products found.</div>;
  }
  return (
    <div style={{ display: "grid", gridTemplateColumns: "repeat(auto-fill, minmax(220px, 1fr))", gap: "1.5rem" }}>
      {products.map((product) => (
        <ProductCard
          key={product.id}
          product={product}
          onAddToCart={onAddToCart ? onAddToCart : () => {}}
          isAddingToCart={addingStateMap ? !!addingStateMap[product.id] : isAddingToCart}
        />
      ))}
    </div>
  );
};

export default ProductList;
