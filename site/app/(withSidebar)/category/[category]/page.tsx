"use client";
import React, { Suspense } from "react";
import { useParams } from "next/navigation";
import dynamic from "next/dynamic";

// Lazy load with loading state
const CategoryProductsPage = dynamic(
  () => import("../../../components/CategoryProductsPage"),
  {
    ssr: false,
    loading: () => (
      <div style={{
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        minHeight: '300px',
        fontSize: '1.1rem',
        color: '#666'
      }}>
        Loading products...
      </div>
    )
  }
);

const CategoryPage = () => {
  const params = useParams();
  const category = params.category as string;
  const displayName = category.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

  return (
    <>
      <div style={{fontSize:'1.7rem',fontWeight:'bold',color:'#222',marginBottom:'0.5rem',marginTop:'0.5rem',textAlign:'center'}}>{displayName} Products</div>
      <Suspense fallback={<div style={{textAlign:'center',padding:'2rem'}}>Loading...</div>}>
        <CategoryProductsPage params={{ slug: category }} />
      </Suspense>
    </>
  );
};

export default CategoryPage;
