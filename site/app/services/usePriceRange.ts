import useSWR from "swr";
import axios from "axios";

const API_BASE = "http://127.0.0.1:8000/api";

const fetcher = async (url: string) => {
  try {
    const response = await axios.get(url);
    return response.data;
  } catch (error) {
    console.error("Price Range API Error:", error);
    // Return default values on error instead of throwing
    return { min_price: 0, max_price: 15000 };
  }
};

export function usePriceRange() {
  const { data, error, isLoading, mutate } = useSWR(
    `${API_BASE}/products/price-range`,
    fetcher,
    {
      revalidateOnFocus: false,
      revalidateOnReconnect: false,
      revalidateIfStale: true,
      dedupingInterval: 2000,
      shouldRetryOnError: false,
    }
  );

  return {
    minPrice: data?.min_price ?? 0,
    maxPrice: data?.max_price ?? 15000,
    isLoading,
    isError: error,
  };
}
