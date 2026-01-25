"use client";
import React, { Suspense } from "react";
import dynamic from "next/dynamic";

// Lazy load with SSR enabled and loading skeleton
const ShopProductsClient = dynamic(
  () => import("./ShopProductsClient"),
  {
    ssr: true,
    loading: () => (
      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(4, 1fr)',
        gap: '1.2rem',
        padding: '1rem',
        maxWidth: '1200px',
        margin: '0 auto'
      }}>
        {Array.from({ length: 8 }).map((_, i) => (
          <div key={i} style={{
            background: '#f5f5f5',
            borderRadius: '12px',
            height: '320px',
            animation: 'pulse 1.5s ease-in-out infinite'
          }} />
        ))}
      </div>
    )
  }
);

const ShopPage = () => {
  return (
    <>
      {/* <div style={{fontSize:'1.7rem',fontWeight:'bold',color:'#222',marginBottom:'0.5rem',marginTop:'0.5rem',textAlign:'center'}}>All Products</div> */}
      <Suspense fallback={<div style={{textAlign:'center',padding:'2rem'}}>Loading...</div>}>
        <ShopProductsClient />
      </Suspense>
    </>
  );
};

export default ShopPage;
