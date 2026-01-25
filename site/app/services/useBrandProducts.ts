import useSWR from "swr";
import axios from "axios";

type BrandProductsResponse = {
  brand?: any;
  products?: {
    data?: any[];
    last_page?: number;
    current_page?: number;
    per_page?: number;
    total?: number;
  } | any[];
  last_page?: number;
  current_page?: number;
  per_page?: number;
  total?: number;
};

const fetcher = (url: string) => axios.get(url).then((res) => res.data as BrandProductsResponse);

export function useBrandProducts(slug: string, page = 1, perPage = 12, sortBy = "default") {
  const query = new URLSearchParams();
  query.set("page", page.toString());
  query.set("per_page", perPage.toString());
  if (sortBy && sortBy !== "default") query.set("sort", sortBy);

  const key = slug ? `${process.env.NEXT_PUBLIC_API_URL}/brands/${slug}/products?${query.toString()}` : null;
  const { data, error, isLoading } = useSWR(key, fetcher, {
    keepPreviousData: true,
    revalidateOnFocus: false,
  });

  const products = Array.isArray(data?.products) ? data?.products : data?.products?.data || [];
  const lastPage = data?.products?.last_page || data?.last_page || 1;
  const currentPage = data?.products?.current_page || data?.current_page || page;
  const total = data?.products?.total || data?.total || products.length;

  return {
    products,
    brand: data?.brand,
    meta: {
      lastPage,
      currentPage,
      perPage: data?.products?.per_page || data?.per_page || perPage,
      total,
    },
    isLoading,
    isError: !!error,
  };
}
