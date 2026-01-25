"use client";
import { Suspense, use } from 'react';
import { notFound } from 'next/navigation';
import dynamic from 'next/dynamic';

// Lazy load with loading state
const BrandProductsClient = dynamic(
  () => import('../BrandProductsClient'),
  {
    ssr: true,
    loading: () => (
      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(auto-fill, minmax(220px, 1fr))',
        gap: '1.5rem',
        padding: '1rem'
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

export default function BrandPage(props: any) {
  const params = use(props.params);
  const slug = params?.slug || '';
  
  const displayName = slug.replace(/-/g, ' ').replace(/\b\w/g, (l: string) => l.toUpperCase());
  
  return (
    <>
      {/* <h1 style={{ fontSize: '2rem', fontWeight: 'bold', marginBottom: '1.5rem' }}>
        {displayName}
      </h1> */}
      <Suspense fallback={<div style={{textAlign:'center',padding:'2rem'}}>Loading products...</div>}>
        <BrandProductsClient slug={slug} />
      </Suspense>
    </>
  );
}
