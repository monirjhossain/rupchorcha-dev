import useSWR from 'swr';
import { fetcher } from '@/src/services/apiClient';
import { Brand } from '@/src/types/api';

export function useBrands() {
  const { data, error, isLoading } = useSWR<Brand[]>(
    '/brands',
    fetcher,
    {
      revalidateOnFocus: false,
      revalidateOnReconnect: false,
      dedupingInterval: 60000,
      fallbackData: typeof window !== 'undefined' 
        ? JSON.parse(localStorage.getItem('brands_cache') || '[]')
        : [],
      onSuccess: (data) => {
        if (typeof window !== 'undefined' && data) {
          localStorage.setItem('brands_cache', JSON.stringify(data));
          localStorage.setItem('brands_cache_time', Date.now().toString());
        }
      },
    }
  );
  
  return {
    brands: Array.isArray(data) ? data : [],
    isLoading,
    isError: error
  };
}
