import useSWR from 'swr';
import { fetcher } from '@/src/services/apiClient';
import { Product, PaginatedResponse } from '@/src/types/api';

export interface UseProductsParams {
  page?: number;
  category_id?: number;
  brand_id?: number;
  tag?: string;
  search?: string;
  sort?: string;
  min_price?: number;
  max_price?: number;
}

export function buildProductsKey(params: UseProductsParams = {}) {
  // Construct query parameters in a single place so navigation prefetching
  // can share the exact same SWR key.
  const searchParams = new URLSearchParams();
  if (params.page) searchParams.append('page', params.page.toString());
  if (params.category_id) searchParams.append('category_id', params.category_id.toString());
  if (params.brand_id) searchParams.append('brand_id', params.brand_id.toString());
  if (params.tag) searchParams.append('tag', params.tag);
  if (params.search) searchParams.append('search', params.search);
  if (params.sort) searchParams.append('sort', params.sort);
  if (params.min_price) searchParams.append('min_price', params.min_price.toString());
  if (params.max_price) searchParams.append('max_price', params.max_price.toString());

  const queryString = searchParams.toString();
  return `/products${queryString ? `?${queryString}` : ''}`;
}

export function useProducts(params: UseProductsParams = {}) {
  const key = buildProductsKey(params);

  const { data, error, isLoading, mutate } = useSWR<PaginatedResponse<Product>>(
    key,
    fetcher,
    {
      keepPreviousData: true,
      revalidateOnFocus: false, 
      dedupingInterval: 10000, 
    }
  );

  return {
    products: data?.data || [],
    meta: data?.meta,
    links: data?.links,
    isLoading,
    isError: error,
    mutate
  };
}

export function useProduct(slug?: string) {
  const { data, error, isLoading } = useSWR<Product>(
    slug ? `/products/${slug}` : null,
    fetcher
  );

  return {
    product: data,
    isLoading,
    isError: error
  };
}

export function useFeaturedProducts() {
    const { data, error, isLoading } = useSWR<{data: Product[]}>(
        '/products?is_featured=1',
        fetcher
    );

    // If the API returns a paginated response for this endpoint or a simple list wrapped in data
    // We handle strict array return or paginated wrapper
    const products = Array.isArray(data) ? data : (data?.data || []);

    return {
        products,
        isLoading,
        isError: error
    }
}
