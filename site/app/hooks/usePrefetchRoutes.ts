import { useEffect } from 'react';
import { useRouter } from 'next/navigation';

/**
 * Prefetch common routes on mount for instant navigation
 */
export function usePrefetchRoutes() {
  const router = useRouter();

  useEffect(() => {
    // Prefetch common routes
    const routes = ['/brands', '/cart', '/wishlist'];
    routes.forEach(route => {
      router.prefetch(route);
    });

    // Prefetch categories from cache
    const cachedCategories = localStorage.getItem('categories_cache');
    if (cachedCategories) {
      try {
        const data = JSON.parse(cachedCategories);
        if (data?.categories) {
          data.categories.slice(0, 8).forEach((cat: any) => {
            const slug = cat.slug || cat.name.toLowerCase().replace(/\s+/g, '-');
            router.prefetch(`/category/${slug}`);
          });
        }
      } catch (e) {
        console.error('Failed to prefetch categories:', e);
      }
    }

    // Prefetch brands from cache
    const cachedBrands = localStorage.getItem('brands_cache');
    if (cachedBrands) {
      try {
        const data = JSON.parse(cachedBrands);
        if (data?.brands) {
          data.brands.slice(0, 12).forEach((brand: any) => {
            const slug = brand.slug || brand.name.toLowerCase().replace(/\s+/g, '-');
            router.prefetch(`/brands/${slug}`);
          });
        }
      } catch (e) {
        console.error('Failed to prefetch brands:', e);
      }
    }
  }, [router]);
}
