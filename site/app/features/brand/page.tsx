"use client";
import React from "react";
import Sidebar from "../components/Sidebar";
import Header from "../components/Header";
import Image from "next/image";

// Remove all mock brand icons and product data. Only use backend data.

const BrandPage = () => {
  // For demo, use a fixed brand name
  const brand = "NOXIA";
  const displayName = brand.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

  return (
    <>
      <Header />
      <div style={{display:'flex',gap:'2rem',alignItems:'flex-start',maxWidth:1400,margin:'2rem auto',padding:'0 1rem'}}>
        <Sidebar />
        <div style={{flex:1}}>
          {/* Brand Title */}
          <div style={{fontSize:'1.7rem',fontWeight:'bold',color:'#222',marginBottom:'0.5rem',marginTop:'0.5rem',textAlign:'center'}}>{displayName} Products</div>
          {/* Brand Icons Row */}
          <div style={{display:'flex',gap:'2.5rem',justifyContent:'center',alignItems:'center',margin:'1.5rem 0'}}>
            {brandIcons.map(icon => (
              <div key={icon.name} style={{display:'flex',flexDirection:'column',alignItems:'center',justifyContent:'center'}}>
                <div style={{width:70,height:70,borderRadius:'50%',background:'#fff',boxShadow:'0 1px 4px #0001',border:'3px solid #f7c6e0',overflow:'hidden',display:'flex',alignItems:'center',justifyContent:'center'}}>
                  <Image src={icon.image} alt={icon.name} width={60} height={60} style={{objectFit:'cover',borderRadius:'50%'}} />
                </div>
                <span style={{marginTop:'0.5rem',fontWeight:500,color:'#222',fontSize:'0.98rem',textAlign:'center'}}>{icon.name}</span>
              </div>
            ))}
          </div>
          {/* Product Grid */}
          <div style={{display:'grid',gridTemplateColumns:'repeat(auto-fit,minmax(180px,1fr))',gap:'1.5rem',marginTop:'2rem'}}>
            {products.map(product => (
              <div key={product.id} style={{background:'#fff',borderRadius:12,boxShadow:'0 2px 8px #0001',padding:'1rem',position:'relative',display:'flex',flexDirection:'column',alignItems:'center',transition:'box-shadow 0.2s'}}>
                {product.sale && (
                  <span style={{position:'absolute',top:12,left:12,background:'#e91e63',color:'#fff',padding:'0.18rem 0.65rem',borderRadius:6,fontWeight:'bold',fontSize:'0.92rem',zIndex:2}}>{product.tag}</span>
                )}
                <Image src={product.image} alt={product.name} width={110} height={110} style={{objectFit:'contain',marginBottom:'0.7rem',borderRadius:8}} />
                <div style={{fontWeight:'bold',fontSize:'1.01rem',color:'#222',textAlign:'center',marginBottom:'0.4rem',minHeight:38}}>{product.name}</div>
                <div style={{display:'flex',alignItems:'center',gap:'0.5rem',marginBottom:'0.4rem'}}>
                  <span style={{fontWeight:'bold',color:'#e91e63',fontSize:'1.05rem'}}>৳{product.price}</span>
                  {product.oldPrice && <span style={{textDecoration:'line-through',color:'#aaa',fontSize:'0.97rem'}}>৳{product.oldPrice}</span>}
                </div>
                <div style={{fontSize:'0.93rem',color:'#888',marginBottom:'0.4rem'}}>{product.weight}</div>
                <button style={{width:'100%',padding:'0.5rem 0',background:'#7c32ff',color:'#fff',border:'none',borderRadius:8,fontWeight:'bold',fontSize:'0.98rem',cursor:'pointer',marginTop:'auto'}}>ADD TO CART</button>
              </div>
            ))}
          </div>
        </div>
      </div>
    </>
  );
};

export default BrandPage;
