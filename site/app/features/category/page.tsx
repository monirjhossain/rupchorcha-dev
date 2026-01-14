"use client";
import React from "react";
import { useSearchParams } from "next/navigation";
import Sidebar from "../components/Sidebar";
import Image from "next/image";

// Remove all mock product data. Only use backend data.

const ProductGrid = () => {
  const searchParams = useSearchParams();
  const cat = searchParams.get("cat");
  return (
    <div style={{display:'flex',gap:'2rem',alignItems:'flex-start',maxWidth:1400,margin:'2rem auto',padding:'0 1rem'}}>
      <Sidebar />
      <div style={{flex:1}}>
        {/* Filter bar */}
        <div style={{display:'flex',justifyContent:'space-between',alignItems:'center',marginBottom:'1.5rem',flexWrap:'wrap',gap:'1rem'}}>
          <div style={{fontSize:'1.1rem',color:'#e91e63',fontWeight:'bold'}}>
            {cat ? `Category: ${cat.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}` : `Showing ${products.length} products`}
          </div>
          <div>
            <input type="text" placeholder="Search here..." style={{padding:'0.5rem 1rem',border:'1px solid #eee',borderRadius:6,marginRight:'0.5rem'}} />
            <select style={{padding:'0.5rem 1rem',border:'1px solid #eee',borderRadius:6}}>
              <option>Default sorting</option>
              <option>Price: Low to High</option>
              <option>Price: High to Low</option>
              <option>Newest</option>
            </select>
          </div>
        </div>
        {/* Product grid */}
        <div style={{display:'grid',gridTemplateColumns:'repeat(auto-fit,minmax(220px,1fr))',gap:'2rem'}}>
          {products.map(product => (
            <div key={product.id} style={{background:'#fff',borderRadius:12,boxShadow:'0 2px 8px #0001',padding:'1rem',position:'relative',display:'flex',flexDirection:'column',alignItems:'center',transition:'box-shadow 0.2s'}}>
              {product.sale && (
                <span style={{position:'absolute',top:16,left:16,background:'#e91e63',color:'#fff',padding:'0.2rem 0.7rem',borderRadius:6,fontWeight:'bold',fontSize:'0.95rem',zIndex:2}}>{product.tag}</span>
              )}
              <Image src={product.image} alt={product.name} width={140} height={140} style={{objectFit:'contain',marginBottom:'1rem',borderRadius:8}} />
              <div style={{fontWeight:'bold',fontSize:'1.08rem',color:'#222',textAlign:'center',marginBottom:'0.5rem',minHeight:48}}>{product.name}</div>
              <div style={{display:'flex',alignItems:'center',gap:'0.5rem',marginBottom:'0.5rem'}}>
                <span style={{fontWeight:'bold',color:'#e91e63',fontSize:'1.1rem'}}>৳{product.price}</span>
                {product.oldPrice && <span style={{textDecoration:'line-through',color:'#aaa',fontSize:'0.98rem'}}>৳{product.oldPrice}</span>}
              </div>
              <div style={{fontSize:'0.97rem',color:'#888',marginBottom:'0.5rem'}}>{product.weight}</div>
              <button style={{width:'100%',padding:'0.6rem 0',background:'#7c32ff',color:'#fff',border:'none',borderRadius:8,fontWeight:'bold',fontSize:'1rem',cursor:'pointer',marginTop:'auto'}}>ADD TO CART</button>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default ProductGrid;
