"use client";
import { useBrands } from '@/src/hooks/useBrands';
import Link from 'next/link';

export default function BrandsPage() {
  const { brands, isLoading } = useBrands();

  if (isLoading) {
    return (
      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(auto-fill, minmax(150px, 1fr))',
        gap: '1.5rem',
        padding: '1rem'
      }}>
        {[1, 2, 3, 4, 5, 6].map((i) => (
          <div
            key={i}
            style={{
              height: '120px',
              background: '#f5f5f5',
              borderRadius: '12px',
              animation: 'pulse 1.5s ease-in-out infinite'
            }}
          />
        ))}
      </div>
    );
  }

  if (brands.length === 0) {
    return (
      <div style={{ textAlign: 'center', padding: '3rem', color: '#666' }}>
        <h2>No brands available</h2>
        <p>Check back later for our brand collection</p>
      </div>
    );
  }

  return (
    <div>
      <h1 style={{ fontSize: '2rem', fontWeight: 'bold', marginBottom: '2rem' }}>
        All Brands
      </h1>
      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(auto-fill, minmax(150px, 1fr))',
        gap: '1.5rem'
      }}>
        {brands.map((brand) => (
          <Link
            key={brand.id}
            href={`/brands/${brand.slug || brand.name.toLowerCase().replace(/\\s+/g, '-')}`}
            style={{
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
              justifyContent: 'center',
              padding: '1.5rem',
              background: 'white',
              borderRadius: '12px',
              border: '1px solid #e0e0e0',
              textDecoration: 'none',
              color: '#333',
              transition: 'all 0.2s',
              cursor: 'pointer'
            }}
            onMouseOver={(e) => {
              e.currentTarget.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
              e.currentTarget.style.transform = 'translateY(-2px)';
            }}
            onMouseOut={(e) => {
              e.currentTarget.style.boxShadow = 'none';
              e.currentTarget.style.transform = 'translateY(0)';
            }}
          >
            {brand.logo && (
              <img
                src={brand.logo}
                alt={brand.name}
                style={{
                  width: '80px',
                  height: '80px',
                  objectFit: 'contain',
                  marginBottom: '0.5rem'
                }}
              />
            )}
            <div style={{
              fontSize: '1rem',
              fontWeight: 600,
              textAlign: 'center'
            }}>
              {brand.name}
            </div>
          </Link>
        ))}
      </div>
    </div>
  );
}
