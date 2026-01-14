"use client";
import React from "react";
import { useParams } from "next/navigation";
import dynamic from "next/dynamic";
const CategoryProductsPage = dynamic(() => import("../../../components/CategoryProductsPage"), { ssr: false });

const CategoryPage = () => {
  const params = useParams();
  const category = params.category as string;
  const displayName = category.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

  return (
    <>
      <div style={{fontSize:'1.7rem',fontWeight:'bold',color:'#222',marginBottom:'0.5rem',marginTop:'0.5rem',textAlign:'center'}}>{displayName} Products</div>
      <CategoryProductsPage params={{ slug: category }} />
    </>
  );
};

export default CategoryPage;
