import { Metadata } from 'next';
import ProductGallery from "./ProductGallery";
import ProductInfo from "./ProductInfo";
import ProductActions from "./ProductActions";
import ProductDeliveryInfo from "./ProductDeliveryInfo";
import FrequentlyBoughtTogether from "./FrequentlyBoughtTogether";
import ProductTabs from "./ProductTabs";
import ProductSideActions from "./ProductSideActions";
import ProductReviews from "./ProductReviews";
import styles from "./ProductDetailsPage.module.css";
import { notFound } from "next/navigation";

const API_BASE = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api";
const SITE_URL = process.env.NEXT_PUBLIC_SITE_URL || "http://localhost:3000";

// Fetch product by slug from backend API
async function getProduct(slug: string) {
  try {
    const res = await fetch(`${API_BASE}/products/slug/${slug}`, { 
      cache: "force-cache",
      next: { revalidate: 3600 } // Cache for 1 hour
    });
    if (!res.ok) return null;
    const data = await res.json();
    return data.data || data.product || null;
  } catch {
    return null;
  }
}

// Generate dynamic metadata for SEO
export async function generateMetadata({ params }: { params: Promise<{ slug: string }> }): Promise<Metadata> {
  const { slug } = await params;
  const product = await getProduct(slug);

  if (!product) {
    return {
      title: 'Product Not Found',
      description: 'The product you are looking for does not exist.',
    };
  }

  const brandName = typeof product.brand === 'object' ? product.brand?.name : product.brand || 'Rupchorcha';
  const price = product.sale_price || product.price;
  const imageUrl = product.images?.[0]?.url || product.main_image || '';
  const fullImageUrl = imageUrl.startsWith('http') 
    ? imageUrl 
    : `${API_BASE.replace('/api', '')}/storage/${imageUrl}`;

  return {
    title: `${product.name} - ${brandName}`,
    description: product.description?.substring(0, 160) || `Buy ${product.name} from ${brandName} at Rupchorcha. Price: ৳${price}`,
    keywords: [product.name, brandName, 'buy online', 'Bangladesh', 'e-commerce', 'Rupchorcha'].join(', '),
    
    openGraph: {
      title: `${product.name} - ${brandName}`,
      description: product.description?.substring(0, 160) || `Buy ${product.name} at ৳${price}`,
      images: [fullImageUrl],
      url: `${SITE_URL}/product/${slug}`,
      type: 'website',
      siteName: 'Rupchorcha',
    },
    
    twitter: {
      card: 'summary_large_image',
      title: `${product.name} - ${brandName}`,
      description: product.description?.substring(0, 160) || `Buy ${product.name} at ৳${price}`,
      images: [fullImageUrl],
    },
    
    alternates: {
      canonical: `${SITE_URL}/product/${slug}`,
    },
    
    robots: {
      index: true,
      follow: true,
    },
  };
}

export default async function ProductDetailsPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const product = await getProduct(slug);
  if (!product) return notFound();

  // Generate breadcrumb structured data
  const brandName = typeof product.brand === 'object' ? product.brand?.name : product.brand || 'Rupchorcha';
  const categoryName = product.category?.name || product.category_name || 'Products';
  const price = product.sale_price || product.price;
  const imageUrl = product.images?.[0]?.url || product.main_image || '';
  const fullImageUrl = imageUrl.startsWith('http') 
    ? imageUrl 
    : `${API_BASE.replace('/api', '')}/storage/${imageUrl}`;

  // Breadcrumb structured data for SEO
  const breadcrumbSchema = {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
      {
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": SITE_URL
      },
      {
        "@type": "ListItem",
        "position": 2,
        "name": categoryName,
        "item": `${SITE_URL}/category/${product.category_slug || categoryName.toLowerCase().replace(/\s+/g, '-')}`
      },
      {
        "@type": "ListItem",
        "position": 3,
        "name": product.name,
        "item": `${SITE_URL}/product/${slug}`
      }
    ]
  };

  // Product structured data for SEO
  const productSchema = {
    "@context": "https://schema.org",
    "@type": "Product",
    "name": product.name,
    "description": product.description || product.short_description,
    "image": fullImageUrl,
    "brand": {
      "@type": "Brand",
      "name": brandName
    },
    "offers": {
      "@type": "Offer",
      "priceCurrency": "BDT",
      "price": Math.round(price),
      "priceCurrency": "BDT"
    },
    "aggregateRating": {
      "@type": "AggregateRating",
      "ratingValue": product.rating || 0,
      "reviewCount": product.reviews_count || 0
    }
  };

  return (
    <>
      {/* Structured Data for SEO */}
      <script 
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbSchema) }}
      />
      <script 
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(productSchema) }}
      />
      <main className={styles.main}>
        <div className={styles.container}>
          {/* Left: Gallery + side icons */}
          <div className={styles.left}>
            <div className={styles.galleryWrapper}>
              <ProductGallery
                images={
                  Array.isArray(product.images)
                    ? product.images
                    : product.image
                      ? [product.image]
                      : []
                }
                name={product.name}
              />
              <ProductSideActions product={product} />
            </div>
          </div>
          {/* Right: Info & Actions */}
          <div className={styles.right}>
            <ProductInfo product={product} />
            <ProductActions product={product} />
            <ProductDeliveryInfo product={product} />
          </div>
        </div>
      </main>
      
      {/* Product Information Tabs */}
      <ProductTabs product={product} />
      {/* Reviews & Ratings */}
      <ProductReviews
        productId={product.id}
        initialAverage={product.rating}
        initialCount={product.reviews_count}
      />
      
      {/* Frequently Bought Together Section */}
      <FrequentlyBoughtTogether productId={product.id} />
    </>
  );
}
