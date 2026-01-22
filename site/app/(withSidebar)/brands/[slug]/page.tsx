"use client";
import { Suspense, use } from 'react';
import { notFound } from 'next/navigation';
import dynamic from 'next/dynamic';

// Lazy load with loading state
const BrandProductsClient = dynamic(
  () => import('../BrandProductsClient'),
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
        Loading brand products...
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
      <h1 style={{ fontSize: '2rem', fontWeight: 'bold', marginBottom: '1.5rem' }}>
        {displayName}
      </h1>
      <Suspense fallback={<div style={{textAlign:'center',padding:'2rem'}}>Loading products...</div>}>
        <BrandProductsClient slug={slug} />
      </Suspense>
    </>
  );
}
