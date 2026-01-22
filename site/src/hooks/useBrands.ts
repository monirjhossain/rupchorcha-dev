import useSWR from 'swr';

export interface Brand {
  id: number;
  name: string;
  slug?: string;
  logo?: string;
  description?: string;
}

const API_URL = typeof window !== 'undefined' 
  ? (process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api')
  : '';

const fetcher = async (url: string) => {
  const res = await fetch(url);
  if (!res.ok) throw new Error('Failed to fetch');
  return res.json();
};

export function useBrands() {
  const { data, error, isLoading } = useSWR<{ success: boolean; brands: Brand[] }>(
    `${API_URL}/brands`,
    fetcher,
    {
      revalidateOnFocus: false,
      revalidateOnReconnect: false,
      dedupingInterval: 60000, // Cache for 1 minute
      fallbackData: typeof window !== 'undefined' 
        ? JSON.parse(localStorage.getItem('brands_cache') || '{}')
        : undefined,
      onSuccess: (data) => {
        // Cache in localStorage for instant load
        if (typeof window !== 'undefined' && data?.brands) {
          localStorage.setItem('brands_cache', JSON.stringify(data));
          localStorage.setItem('brands_cache_time', Date.now().toString());
        }
      },
    }
  );
  
  return {
    brands: data?.brands || [],
    isLoading,
    isError: error
  };
}
