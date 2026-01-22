import useSWR from 'swr';
import axios from 'axios';

const fetcher = (url: string) => axios.get(url).then(res => res.data);

export function useCategories() {
  const { data, error, isLoading } = useSWR(
    `${process.env.NEXT_PUBLIC_API_URL}/categories`,
    fetcher,
    { revalidateOnFocus: false }
  );
  // Always return an array, regardless of API response shape
  const categories = Array.isArray(data)
    ? data
    : data?.categories || data?.data || [];
  return {
    categories,
    isLoading,
    isError: !!error,
  };
}

export function useBrands() {
  const { data, error, isLoading } = useSWR(
    `${process.env.NEXT_PUBLIC_API_URL}/brands`,
    fetcher,
    { revalidateOnFocus: false }
  );
  // Always return an array, regardless of API response shape
  const brands = Array.isArray(data)
    ? data
    : data?.brands || data?.data || [];
  return {
    brands,
    isLoading,
    isError: !!error,
  };
}
