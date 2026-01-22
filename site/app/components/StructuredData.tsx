import Script from 'next/script';

interface ProductStructuredDataProps {
  product: {
    name: string;
    description?: string;
    price: number;
    sale_price?: number;
    images?: any[];
    main_image?: string;
    brand?: { name: string } | string;
    slug: string;
  };
}

export function ProductStructuredData({ product }: ProductStructuredDataProps) {
  const brandName = typeof product.brand === 'object' ? product.brand?.name : product.brand || 'Rupchorcha';
  const price = product.sale_price || product.price;
  const imageUrl = product.images?.[0]?.url || product.main_image || '';
  const SITE_URL = process.env.NEXT_PUBLIC_SITE_URL || 'http://localhost:3000';
  
  const structuredData = {
    '@context': 'https://schema.org',
    '@type': 'Product',
    name: product.name,
    description: product.description || `Buy ${product.name} from ${brandName}`,
    image: imageUrl,
    brand: {
      '@type': 'Brand',
      name: brandName,
    },
    offers: {
      '@type': 'Offer',
      url: `${SITE_URL}/product/${product.slug}`,
      priceCurrency: 'BDT',
      price: price,
      availability: 'https://schema.org/InStock',
      seller: {
        '@type': 'Organization',
        name: 'Rupchorcha',
      },
    },
  };

  return (
    <Script
      id="product-structured-data"
      type="application/ld+json"
      dangerouslySetInnerHTML={{ __html: JSON.stringify(structuredData) }}
    />
  );
}

export function OrganizationStructuredData() {
  const SITE_URL = process.env.NEXT_PUBLIC_SITE_URL || 'http://localhost:3000';
  
  const structuredData = {
    '@context': 'https://schema.org',
    '@type': 'Organization',
    name: 'Rupchorcha',
    url: SITE_URL,
    logo: `${SITE_URL}/rupchorcha-logo.png`,
    description: 'Your trusted online shopping destination in Bangladesh',
    sameAs: [
      // Add your social media links here
    ],
  };

  return (
    <Script
      id="organization-structured-data"
      type="application/ld+json"
      dangerouslySetInnerHTML={{ __html: JSON.stringify(structuredData) }}
    />
  );
}
