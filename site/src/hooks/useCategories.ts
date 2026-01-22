import useSWR from 'swr';

export interface Category {
  id: number;
  name: string;
  slug?: string;
  // add more fields if needed
}

const API_URL = typeof window !== 'undefined' 
  ? (process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api')
  : '';

const fetcher = async (url: string) => {
  const res = await fetch(url);
  if (!res.ok) throw new Error('Failed to fetch');
  return res.json();
};

export function useCategories() {
  const { data, error, isLoading } = useSWR<{ success: boolean; categories: Category[] }>(
    `${API_URL}/categories`,
    fetcher,
    {
      revalidateOnFocus: false,
      revalidateOnReconnect: false,
      dedupingInterval: 60000, // Cache for 1 minute
      fallbackData: typeof window !== 'undefined' 
        ? JSON.parse(localStorage.getItem('categories_cache') || '{}')
        : undefined,
      onSuccess: (data) => {
        // Cache in localStorage for instant load
        if (typeof window !== 'undefined' && data?.categories) {
          localStorage.setItem('categories_cache', JSON.stringify(data));
          localStorage.setItem('categories_cache_time', Date.now().toString());
        }
      },
    }
  );
  
  return {
    categories: data?.categories || [],
    isLoading,
    isError: error
  };
}
