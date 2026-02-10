import useSWR from "swr";
import axios from "axios";

type ShopProductsResponse = {
  data?: any[];
  last_page?: number;
  current_page?: number;
  per_page?: number;
  total?: number;
};

export const SHOP_API_BASE = process.env.NEXT_PUBLIC_API_URL || "http://127.0.0.1:8000/api";

export const shopFetcher = (url: string) =>
  axios.get(url)
    .then((res) => res.data)
    .catch((error) => {
      console.error("Shop API Error:", error);
      throw error;
    });

export const buildShopProductsKey = (
  page = 1,
  perPage = 20,
  sortBy: string = "default",
  priceMin?: string | null,
  priceMax?: string | null,
) => {
  const query = new URLSearchParams();
  query.set("page", page.toString());
  query.set("per_page", perPage.toString());
  if (sortBy && sortBy !== "default") query.set("sort", sortBy);
  if (priceMin) query.set("price_min", priceMin);
  if (priceMax) query.set("price_max", priceMax);

  return `${SHOP_API_BASE}/products?${query.toString()}`;
};

export function useShopProducts(page = 1, perPage = 20, sortBy = "default") {
  // Read URL search params for price filtering
  const searchParams = typeof window !== 'undefined' ? new URLSearchParams(window.location.search) : null;
  const priceMin = searchParams?.get('price_min');
  const priceMax = searchParams?.get('price_max');

  const key = buildShopProductsKey(page, perPage, sortBy, priceMin, priceMax);
  const { data, error, isLoading } = useSWR(key, shopFetcher, {
    keepPreviousData: true,
    revalidateOnFocus: false,
    revalidateOnReconnect: false,
    revalidateIfStale: false,
    focusThrottleInterval: 600000,
    dedupingInterval: 120000,
  });

  // Extract products from nested response structure
  let products: any[] = [];
  let lastPage = 1;
  let currentPage = page;
  let total = 0;
  let perPageValue = perPage;

  if (data) {
    // API returns: { success: true, products: { current_page, data, last_page, ... } }
    const productsObj = data.products || data.data || data;
    
    if (productsObj && typeof productsObj === "object") {
      // Extract products array from pagination structure
      if (Array.isArray(productsObj.data)) {
        products = productsObj.data;
        lastPage = productsObj.last_page || 1;
        currentPage = productsObj.current_page || page;
        total = productsObj.total || 0;
        perPageValue = productsObj.per_page || perPage;
      } else if (Array.isArray(productsObj)) {
        products = productsObj;
      }
    }
  }
  console.log("Extracted products:", products, "Count:", products.length);

  return {
    products,
    meta: {
      lastPage,
      currentPage,
      perPage: perPageValue,
      total,
    },
    isLoading,
    isError: !!error,
  };
}
