
"use client";
import Image from 'next/image';
import Link from 'next/link';


import React, { useState } from 'react';

const categories = [
  { id: 1, image: '/category-section/accessories.webp', alt: 'Accessories', slug: 'accessories' },
  { id: 2, image: '/category-section/bath-body.webp', alt: 'Bath & Body', slug: 'bath-body' },
  { id: 3, image: '/category-section/hair-care.webp', alt: 'Hair Care', slug: 'hair-care' },
  { id: 4, image: '/category-section/makeup.webp', alt: 'Makeup', slug: 'makeup' },
  { id: 5, image: '/category-section/mom-baby-care.webp', alt: 'Mom & Baby Care', slug: 'mom-baby-care' },
  { id: 6, image: '/category-section/skincare.webp', alt: 'Skincare', slug: 'skin-care' },
];

const ShopByCategorySection = () => {
  const [showAll, setShowAll] = useState(false);
  const visibleCategories = showAll ? categories : categories.slice(0, 6);

  return (
    <section style={{margin:'2rem auto',maxWidth:1200,background:'#fff',borderRadius:12,boxShadow:'0 2px 8px #0001',padding:'2rem 1rem',position:'relative'}}>
      <div style={{display:'flex',justifyContent:'space-between',alignItems:'center',marginBottom:'1.5rem'}}>
        <h2 style={{fontSize:'2rem',fontWeight:'bold',color:'#111',margin:0}}>TOP CATEGORIES</h2>
        <Link href="/categories" style={{textDecoration:'none'}}>
          <button
            style={{
              padding:'0.5rem 1.5rem',
              background:'#fff',
              color:'#111',
              border:'1px solid #ddd',
              borderRadius:8,
              fontWeight:'bold',
              fontSize:'1rem',
              cursor:'pointer',
              boxShadow:'0 1px 4px #0001',
              display:'flex',
              alignItems:'center',
              gap:'0.5rem',
              transition:'border 0.2s,box-shadow 0.2s',
            }}
          >
            See All <span style={{fontSize:'1.1em',display:'inline-block',transform:'translateY(1px)'}}>&rarr;</span>
          </button>
        </Link>
      </div>
      <div style={{display:'flex',gap:'2.5rem',overflowX:'auto',paddingBottom:'0.5rem',justifyContent:'flex-start'}}>
        {visibleCategories.map(cat => (
          <Link
            key={cat.id}
            href={`/category/${cat.slug}`}
            style={{textDecoration:'none', color: 'inherit'}}>
            <div style={{display:'flex',flexDirection:'column',alignItems:'center',justifyContent:'center'}}>
              <div style={{width:150,height:150,display:'flex',alignItems:'center',justifyContent:'center',background:'#fff',borderRadius:'50%',boxShadow:'0 1px 4px #0001',border:'5px solid #f7c6e0',overflow:'hidden',transition:'box-shadow 0.2s',cursor:'pointer'}}>
                <Image src={cat.image} alt={cat.alt} width={140} height={140} style={{objectFit:'cover',borderRadius:'50%'}} />
              </div>
              <span style={{marginTop:'0.75rem',fontWeight:500,color:'#222',fontSize:'1.08rem',textAlign:'center',letterSpacing:'0.01em'}}>{cat.alt}</span>
            </div>
          </Link>
        ))}
      </div>
    </section>
  );
};

export default ShopByCategorySection;
