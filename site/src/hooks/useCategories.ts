import useSWR from 'swr';

export interface Category {
  id: number;
  name: string;
  // add more fields if needed
}

const fetcher = (url: string) => fetch(url).then(res => res.json());

export function useCategories() {
  const { data, error, isLoading } = useSWR<{ success: boolean; categories: Category[] }>(
    process.env.NEXT_PUBLIC_API_BASE_URL + '/categories',
    fetcher
  );
  return {
    categories: data?.categories || [],
    isLoading,
    isError: error
  };
}
