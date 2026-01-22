import useSWR from "swr";
import axios from "axios";

const fetcher = (url: string) => axios.get(url).then(res => res.data.products);

export function useBrandProducts(slug: string) {
  const { data, error, isLoading } = useSWR(
    slug ? `${process.env.NEXT_PUBLIC_API_URL}/brands/${slug}/products` : null,
    fetcher
  );
  return {
    products: data,
    isLoading,
    isError: !!error,
  };
}
