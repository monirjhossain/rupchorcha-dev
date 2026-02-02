import api from "@/src/services/apiClient";

export interface Order {
  id: number;
  order_number: string;
  status: string;
  total: number;
  created_at: string;
  // Add more fields as needed
}

export const fetchMyOrders = async (): Promise<Order[]> => {
  const res = await api.get<any>("/orders");
  console.log('API /orders FULL response:', res);
  // Try to extract orders from all possible locations
  if (res?.data?.orders?.data && Array.isArray(res.data.orders.data)) {
    return res.data.orders.data;
  }
  if (res?.orders?.data && Array.isArray(res.orders.data)) {
    return res.orders.data;
  }
  if (Array.isArray(res.orders)) {
    return res.orders;
  }
  if (Array.isArray(res.data)) {
    return res.data;
  }
  return [];
};
