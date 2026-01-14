"use client";
import React, { useEffect, useState } from "react";
import { useParams } from "next/navigation";
import Header from "../../components/Header";

// TODO: Replace with your actual API endpoint
const API_URL = "http://localhost/Ecommerce/backend/public/api/products/slug/";

const ProductDetailsPage = () => {
  const { slug } = useParams();
  const [product, setProduct] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    if (!slug) return;
    setLoading(true);
    fetch(`${API_URL}${slug}`)
      .then(res => res.json())
      .then(data => {
        setProduct(data.data || data);
        setError("");
      })
      .catch(() => setError("Product not found"))
      .finally(() => setLoading(false));
  }, [slug]);

  if (loading) return <div>Loading...</div>;
  if (error || !product) return <div>{error || "Product not found"}</div>;

  return (
    <>
      <Header />
      <main style={{background:'#fafafd',padding:'0 0 2rem 0',fontFamily:'inherit'}}>
        <div style={{background:'#fff',borderBottom:'1px solid #eee',padding:'1.2rem 0 0.7rem 0',marginBottom:'1.2rem',boxShadow:'0 2px 8px #0001'}}>
          <div style={{maxWidth:1200,margin:'0 auto',padding:'0 1.5rem'}}>
            <div style={{fontSize:'0.97rem',color:'#888',marginBottom:'0.5rem'}}>
              Home &gt; {product.category || "Category"} &gt; {product.brand || "Brand"} &gt; {product.name}
            </div>
          </div>
        </div>
        {/* ...rest of your product details UI here, using product data... */}
        <div style={{maxWidth:1200,margin:'0 auto',padding:'2rem',background:'#fff',borderRadius:18,boxShadow:'0 4px 24px #0002'}}>
          <h1>{product.name}</h1>
          <p>Brand: {product.brand}</p>
          <p>Price: ৳ {product.price}</p>
          {/* Add more product info as needed */}
        </div>
      </main>
    </>
  );
};

export default ProductDetailsPage;
