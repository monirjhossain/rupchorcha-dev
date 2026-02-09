import useSWR from 'swr';
import { fetcher } from '@/src/services/apiClient';
import { Category } from '@/src/types/api';

export function useCategories() {
  const { data, error, isLoading } = useSWR<Category[]>(
    '/categories',
    fetcher,
    {
      revalidateOnFocus: false,
      revalidateOnReconnect: false,
      dedupingInterval: 60000,
      fallbackData: typeof window !== 'undefined' 
        ? JSON.parse(localStorage.getItem('categories_cache') || '[]')
        : [],
      onSuccess: (data) => {
        if (typeof window !== 'undefined' && data) {
          localStorage.setItem('categories_cache', JSON.stringify(data));
          localStorage.setItem('categories_cache_time', Date.now().toString());
        }
      },
    }
  );
  
  return {
    categories: Array.isArray(data) ? data : [],
    isLoading,
    isError: error
  };
}
