"use client";
import React, { useState } from "react";
import Header from "../components/Header";

// Mock data for demonstration
const product = {
  id: 1,
  name: "Baby Bright Lip & Cheek Velvet Cherry Blossom #02 Coral Sakura (2.4gm)",
  brand: "BABY BRIGHT",
  price: 299,
  oldPrice: 350,
  discount: 14,
  rating: 0,
  reviews: 0,
  sku: "BBLIPVELVET02",
  images: [
    "/img/lip1.jpg",
    "/img/lip2.jpg",
    "/img/lip3.jpg",
    "/img/lip4.jpg",
    "/img/lip5.jpg",
    "/img/lip6.jpg",
    "/img/lip7.jpg",
    "/img/lip8.jpg",
    "/img/lip9.jpg",
  ],
  info: `
    Velvet cream lipstick. Soft and smooth touch. Planner is clear, long lasting, delivers moisture with extracts from horsem serumed. (Chromosomes Oil and red seaweed extract). (Anastatica) helps to retain and care lips with nourishing extracts from cherry blossom. Reduce chafe cracks and protect lips from UV rays and add charm to the lip and cheeks to look extremely beautiful. Like a Korean girl every day.
  `,
  similarProducts: [
    { name: "BROWIT Fairy Eyebrow Mascara", price: 99, image: "/img/similar1.jpg", sale: true },
    { name: "BROWIT Highlight and Contour Pro Palette", price: 165, image: "/img/similar2.jpg", sale: true },
    { name: "PONNY Extra White SPF 50", price: 190, image: "/img/similar3.jpg", sale: true },
  ],
  moreFromBrand: [
    { name: "Baby Bright Tomato & Olive Bright Lip Serum", price: 59, image: "/img/brand1.jpg", sale: false },
    { name: "Baby Bright Snail & Gold Soothing Gel", price: 199, image: "/img/brand2.jpg", sale: true },
    { name: "Baby Bright Egg & Root Booster Collagen Mask", price: 20, image: "/img/brand3.jpg", sale: false },
    { name: "Baby Bright 1st Volume Long & Lifting Mascara", price: 155, image: "/img/brand4.jpg", sale: true },
    { name: "Baby Bright Lip & Cheek Velvet", price: 114, image: "/img/brand5.jpg", sale: true },
  ],
};

const ProductDetailsPage = () => {
  const [selectedImage, setSelectedImage] = useState(0);
  return (
    <>
      <Header />
      <main style={{background:'#fafafd',padding:'0 0 2rem 0',fontFamily:'inherit'}}>
      {/* Header & Breadcrumbs */}
      <div style={{background:'#fff',borderBottom:'1px solid #eee',padding:'1.2rem 0 0.7rem 0',marginBottom:'1.2rem',boxShadow:'0 2px 8px #0001'}}>
        <div style={{maxWidth:1200,margin:'0 auto',padding:'0 1.5rem'}}>
          <div style={{fontSize:'0.97rem',color:'#888',marginBottom:'0.5rem'}}>
            Home &gt; Lip Tints &gt; BABY BRIGHT &gt; Baby Bright Lip & Cheek Velvet Cherry Blossom #02 Coral Sakura
          </div>
        </div>
      </div>
      {/* Main Product Section */}
      <div style={{maxWidth:1200,margin:'0 auto',display:'flex',gap:'2.5rem',padding:'0 1.5rem',background:'#fff',borderRadius:18,boxShadow:'0 4px 24px #0002',marginBottom:'2.5rem'}}>
        {/* Left: Product Images */}
        <div style={{flex:'0 0 420px',display:'flex',flexDirection:'column',alignItems:'center',padding:'2rem 0'}}>
          <div style={{width:420,height:420,background:'#f7f7fa',borderRadius:16,boxShadow:'0 2px 16px #0002',marginBottom:'1.2rem',display:'flex',alignItems:'center',justifyContent:'center',overflow:'hidden',position:'relative'}}>
            <img src={product.images[selectedImage]} alt="Product" style={{maxWidth:'100%',maxHeight:'100%',transition:'all 0.2s',borderRadius:12}} />
            <span style={{position:'absolute',top:18,left:18,background:'#e91e63',color:'#fff',fontWeight:700,borderRadius:8,padding:'0.3rem 1rem',fontSize:'1.05rem',boxShadow:'0 2px 8px #e91e6322'}}>FEATURED</span>
          </div>
          <div style={{display:'flex',gap:'0.5rem',flexWrap:'wrap',justifyContent:'center',marginTop:'0.5rem'}}>
            {product.images.map((img, i) => (
              <img key={i} src={img} alt="thumb" style={{width:54,height:54,borderRadius:8,border:selectedImage===i?'2.5px solid #e91e63':'2px solid #eee',cursor:'pointer',objectFit:'cover',boxShadow:selectedImage===i?'0 2px 8px #e91e6322':'none',transition:'all 0.2s'}} onClick={()=>setSelectedImage(i)} />
            ))}
          </div>
        </div>
        {/* Right: Product Info */}
        <div style={{flex:1,padding:'2rem 0 2rem 0'}}>
          <div style={{fontSize:'1.1rem',fontWeight:700,color:'#e91e63',marginBottom:'0.5rem',display:'flex',alignItems:'center',gap:'1.2rem'}}>
            <span>Sale Ends In <span style={{color:'#222',fontWeight:600}}>11Hr 12m 32s</span></span>
            <span style={{background:'#ffe6f0',color:'#e91e63',fontWeight:600,borderRadius:6,padding:'0.2rem 0.7rem',fontSize:'1rem'}}>Limited Time</span>
          </div>
          <h1 style={{fontSize:'2rem',fontWeight:800,color:'#222',marginBottom:'0.7rem',lineHeight:1.2}}>{product.name}</h1>
          <div style={{fontSize:'1.15rem',fontWeight:700,color:'#e91e63',marginBottom:'0.5rem',letterSpacing:'0.01em'}}>{product.brand}</div>
          <div style={{display:'flex',alignItems:'center',gap:'1.2rem',marginBottom:'1.1rem'}}>
            <span style={{fontSize:'2rem',fontWeight:800,color:'#e91e63',letterSpacing:'-1px'}}>৳ {product.price}</span>
            <span style={{fontSize:'1.15rem',fontWeight:500,color:'#aaa',textDecoration:'line-through'}}>৳ {product.oldPrice}</span>
            <span style={{fontSize:'1.1rem',fontWeight:700,color:'#fff',background:'#e91e63',borderRadius:8,padding:'0.3rem 1rem',boxShadow:'0 2px 8px #e91e6322'}}>14% OFF</span>
          </div>
          <div style={{display:'flex',gap:'1rem',marginBottom:'1.2rem'}}>
            <button style={{background:'#e91e63',color:'#fff',fontWeight:700,padding:'0.8rem 2.5rem',borderRadius:10,border:'none',fontSize:'1.15rem',cursor:'pointer',boxShadow:'0 2px 8px #e91e6322',transition:'all 0.2s'}}>Add to bag</button>
            <button style={{background:'#fff',color:'#e91e63',fontWeight:700,padding:'0.8rem 2.5rem',borderRadius:10,border:'2px solid #e91e63',fontSize:'1.15rem',cursor:'pointer',boxShadow:'0 2px 8px #e91e6322',transition:'all 0.2s'}}>Buy Now</button>
            <span style={{fontWeight:700,color:'#222',fontSize:'1.1rem'}}>Avg Price: ৳ 197.9</span>
          </div>
          <div style={{marginBottom:'1.2rem',display:'flex',alignItems:'center',gap:'1.2rem',flexWrap:'wrap'}}>
            <span style={{fontWeight:700,color:'#222',background:'#f7f7fa',borderRadius:6,padding:'0.2rem 0.7rem'}}>100% Authentic Product</span>
            <span style={{fontWeight:700,color:'#222',background:'#f7f7fa',borderRadius:6,padding:'0.2rem 0.7rem'}}>Easy Returns Policy</span>
            <span style={{fontWeight:700,color:'#222',background:'#f7f7fa',borderRadius:6,padding:'0.2rem 0.7rem'}}>Free Delivery</span>
            <span style={{fontWeight:700,color:'#222',background:'#f7f7fa',borderRadius:6,padding:'0.2rem 0.7rem'}}>Cruelty Free</span>
          </div>
          <div style={{marginBottom:'1.2rem',fontSize:'1.05rem',color:'#444',background:'#f7f7fa',borderRadius:8,padding:'1rem 1.2rem'}}>
            <div style={{marginBottom:'0.5rem',fontWeight:700,color:'#222'}}><b>Delivery Info</b></div>
            <div>People viewing this product: <b>9</b> &nbsp;|&nbsp; Sold: <b>2,011</b> &nbsp;|&nbsp; Last sold: <b>24 min</b></div>
            <div>SKU Code: <b>{product.sku}</b></div>
          </div>
        </div>
      </div>
      {/* Similar Products */}
      <div style={{maxWidth:1200,margin:'2.5rem auto 0 auto',padding:'0 1.5rem'}}>
        <h2 style={{fontSize:'1.3rem',fontWeight:800,color:'#222',marginBottom:'1.2rem',letterSpacing:'0.01em'}}>Similar Products</h2>
        <div style={{display:'flex',gap:'1.2rem'}}>
          {product.similarProducts.map((p,i)=>(
            <div key={i} style={{width:220,background:'#fff',borderRadius:14,boxShadow:'0 2px 12px #0002',padding:'1.2rem',display:'flex',flexDirection:'column',alignItems:'center',position:'relative',transition:'box-shadow 0.2s',border:'1px solid #f2f2f2'}}>
              {p.sale && <span style={{position:'absolute',top:10,left:10,background:'#e91e63',color:'#fff',fontWeight:700,borderRadius:8,padding:'0.3rem 1rem',fontSize:'1rem',boxShadow:'0 2px 8px #e91e6322'}}>ON SALE</span>}
              <img src={p.image} alt={p.name} style={{width:120,height:120,objectFit:'cover',borderRadius:10,marginBottom:'0.7rem',boxShadow:'0 2px 8px #0001'}} />
              <div style={{fontWeight:700,color:'#222',marginBottom:'0.3rem',textAlign:'center',fontSize:'1.05rem'}}>{p.name}</div>
              <div style={{fontWeight:800,color:'#e91e63',fontSize:'1.15rem'}}>৳ {p.price}</div>
            </div>
          ))}
        </div>
      </div>
      {/* Product Info */}
      <div style={{maxWidth:1200,margin:'2.5rem auto 0 auto',padding:'0 1.5rem'}}>
        <h2 style={{fontSize:'1.3rem',fontWeight:800,color:'#222',marginBottom:'1.2rem',letterSpacing:'0.01em'}}>Product Info</h2>
        <div style={{fontSize:'1.08rem',color:'#444',marginBottom:'0.7rem',background:'#fff',borderRadius:10,padding:'1.2rem',boxShadow:'0 2px 8px #0001',border:'1px solid #f2f2f2'}}>{product.info}</div>
        <button style={{background:'none',border:'none',color:'#e91e63',fontWeight:700,fontSize:'1.08rem',cursor:'pointer',textDecoration:'underline'}}>read more &gt;</button>
      </div>
      {/* More From This Brand */}
      <div style={{maxWidth:1200,margin:'2.5rem auto 0 auto',padding:'0 1.5rem'}}>
        <h2 style={{fontSize:'1.3rem',fontWeight:800,color:'#222',marginBottom:'1.2rem',letterSpacing:'0.01em'}}>More From This Brand</h2>
        <div style={{display:'flex',gap:'1.2rem'}}>
          {product.moreFromBrand.map((p,i)=>(
            <div key={i} style={{width:220,background:'#fff',borderRadius:14,boxShadow:'0 2px 12px #0002',padding:'1.2rem',display:'flex',flexDirection:'column',alignItems:'center',position:'relative',transition:'box-shadow 0.2s',border:'1px solid #f2f2f2'}}>
              {p.sale && <span style={{position:'absolute',top:10,left:10,background:'#e91e63',color:'#fff',fontWeight:700,borderRadius:8,padding:'0.3rem 1rem',fontSize:'1rem',boxShadow:'0 2px 8px #e91e6322'}}>ON SALE</span>}
              <img src={p.image} alt={p.name} style={{width:120,height:120,objectFit:'cover',borderRadius:10,marginBottom:'0.7rem',boxShadow:'0 2px 8px #0001'}} />
              <div style={{fontWeight:700,color:'#222',marginBottom:'0.3rem',textAlign:'center',fontSize:'1.05rem'}}>{p.name}</div>
              <div style={{fontWeight:800,color:'#e91e63',fontSize:'1.15rem'}}>৳ {p.price}</div>
              <span style={{position:'absolute',bottom:10,right:10,background:'#222',color:'#fff',fontWeight:700,borderRadius:8,padding:'0.3rem 1rem',fontSize:'1rem',boxShadow:'0 2px 8px #2222'}}>★ Join Waitlist</span>
            </div>
          ))}
        </div>
      </div>
      {/* Reviews & Ratings */}
      <div style={{maxWidth:1200,margin:'2.5rem auto 0 auto',padding:'0 1.5rem'}}>
        <h2 style={{fontSize:'1.3rem',fontWeight:800,color:'#222',marginBottom:'1.2rem',letterSpacing:'0.01em'}}>Reviews & Ratings</h2>
        <div style={{background:'#f7f7fa',borderRadius:14,padding:'1.5rem',marginBottom:'1.2rem',display:'flex',alignItems:'center',gap:'1.2rem',boxShadow:'0 2px 8px #0001'}}>
          <div style={{flex:1}}>
            <div style={{fontWeight:700,color:'#222',marginBottom:'0.5rem',fontSize:'1.08rem'}}>Review & Get Coins</div>
            <div style={{fontSize:'1.05rem',color:'#888'}}>Write the first review! Earn 5 reward coins.</div>
          </div>
          <button style={{background:'#fff',color:'#e91e63',fontWeight:700,padding:'0.8rem 2.5rem',borderRadius:10,border:'2px solid #e91e63',fontSize:'1.15rem',cursor:'pointer',boxShadow:'0 2px 8px #e91e6322',transition:'all 0.2s'}}>Write a review</button>
        </div>
      </div>
      {/* Popular Searches & Footer */}
      <div style={{maxWidth:1200,margin:'2.5rem auto 0 auto',padding:'0 1.5rem'}}>
        <div style={{marginBottom:'1.2rem',fontSize:'1rem',color:'#888'}}>
          <b>Popular Searches</b><br />
          Skin Care | Serums | Treatments | Cleansers | Body Care | Makeup | Toner | cathy-doll | baby-bright | mediheal | face-shop | innisfree | the-ordinary | rom&nd | peripera | l'oreal | cerave | laneige | tony moly | dear klairs | shampoo | redness | beauty of joseon | beauty | serum | the ordinary | new skin | daily | doll | skin care
        </div>
        <div style={{display:'flex',alignItems:'center',justifyContent:'space-between',fontSize:'0.97rem',color:'#888',padding:'1.2rem 0'}}>
          <div>
            <b>e-Cab</b><br />Need Help?<br />+8801974381084
          </div>
          <div>
            <ul style={{listStyle:'none',padding:0,margin:0}}>
              <li>Home</li>
              <li>FAQ</li>
              <li>Career</li>
              <li>Contact us</li>
              <li>Blog</li>
            </ul>
          </div>
          <div>
            <ul style={{listStyle:'none',padding:0,margin:0}}>
              <li>Privacy Policies</li>
              <li>Terms & Conditions</li>
              <li>Return & Refund Policy</li>
            </ul>
          </div>
          <div>
            <b>Download our App</b><br />
            <span style={{display:'flex',gap:'0.5rem',marginTop:'0.5rem'}}>
              <img src="/img/appstore.png" alt="App Store" style={{height:32}} />
              <img src="/img/playstore.png" alt="Play Store" style={{height:32}} />
            </span>
          </div>
        </div>
        <div style={{textAlign:'center',fontSize:'0.95rem',color:'#aaa',marginTop:'1.2rem'}}>© 2026 Beauty Booth. All rights reserved.</div>
      </div>
      </main>
    </>
  );
};

export default ProductDetailsPage;
