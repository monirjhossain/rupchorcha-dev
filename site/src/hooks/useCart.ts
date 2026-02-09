import useSWR from 'swr';
import api, { fetcher } from '@/src/services/apiClient';
import { Cart } from '@/src/types/api';

export function useCart() {
  const { data, error, isLoading, mutate } = useSWR<Cart>(
    '/cart',
    fetcher,
    {
      revalidateOnFocus: true,
      dedupingInterval: 2000, 
    }
  );

  const addItem = async (productId: number, quantity: number = 1, attributes?: Record<string, string>) => {
    try {
      const res = await api.post('/cart/add', { product_id: productId, quantity, attributes });
      if (res.success) {
        await mutate(); // Re-fetch cart
        return true;
      }
      return false;
    } catch (e) {
      console.error(e);
      return false;
    }
  };

  const updateItem = async (itemId: number, quantity: number) => {
    try {
      const res = await api.post('/cart/update', { item_id: itemId, quantity });
      if (res.success) {
        await mutate();
        return true;
      }
      return false;
    } catch (e) {
       console.error(e);
       return false;
    }
  };

  const removeItem = async (itemId: number) => {
    try {
      const res = await api.post('/cart/remove', { item_id: itemId });
      if (res.success) {
        await mutate();
        return true;
      }
      return false;
    } catch (e) {
      console.error(e);
      return false;
    }
  };

  return {
    cart: data,
    isLoading,
    isError: error,
    addItem,
    updateItem,
    removeItem,
    mutate
  };
}
