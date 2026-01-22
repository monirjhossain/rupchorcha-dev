export interface Product {
  id: number;
  name: string;
  price: number;
  sale_price?: number;
  app_price?: number;
  images?: string[];
  image?: string;
  brand?: { name: string; slug?: string };
  brand_name?: string;
  brand_slug?: string;
  category?: { name: string; slug?: string };
  category_name?: string;
  category_slug?: string;
  is_new?: boolean;
  badges?: string[];
  discount?: number;
  colors?: string[];
  rating?: number;
  reviews_count?: number;
  sku?: string;
  [key: string]: unknown;
}