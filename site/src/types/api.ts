export interface User {
    id: number;
    name: string;
    email: string;
    phone?: string;
    role: string;
    avatar?: string;
    email_verified_at?: string;
    created_at: string;
}

export interface Category {
    id: number;
    parent_id?: number | null;
    name: string;
    slug: string;
    description?: string;
    image?: string;
    icon?: string;
    sort_order?: number;
    is_active: boolean;
    children?: Category[];
    products_count?: number; // Added field
}

export interface Brand {
    id: number;
    name: string;
    slug: string;
    description?: string;
    logo?: string;
    banner_image?: string;
    website?: string;
    is_active: boolean;
    products_count?: number; // Added field
}

export interface ProductImage {
    id: number;
    image: string;
    is_primary: boolean;
    sort_order: number;
}

export interface ProductVariant {
    id: number;
    sku: string;
    price: number;
    stock_quantity: number;
    attributes: Record<string, string>;
}

export interface Product {
    id: number;
    name: string;
    slug: string;
    sku: string;
    short_description?: string;
    description?: string;
    price: number;
    sale_price?: number;
    discount_price?: number; // Added to match backend
    stock_quantity: number;
    is_in_stock: boolean;
    is_featured: boolean;
    is_new: boolean;
    rating?: number;
    reviews_count?: number;
    category?: Category;
    brand?: Brand;
    images: ProductImage[];
    variants?: ProductVariant[];
    tags?: string[];
    created_at: string;
}

export interface CartItem {
    id: number;
    product_id: number;
    product_name: string;
    product_image: string;
    price: number;
    quantity: number;
    subtotal: number;
    attributes?: Record<string, string>;
}

export interface Cart {
    id: number;
    items: CartItem[];
    total_quantity: number;
    subtotal: number;
    discount: number;
    tax?: number;
    shipping_cost?: number;
    total: number;
}

export interface Address {
    id: number;
    type: 'billing' | 'shipping';
    first_name: string;
    last_name?: string;
    email?: string;
    phone: string;
    address: string;
    city: string;
    state?: string;
    zip_code?: string;
    country: string;
    is_default: boolean;
}

export interface PaginationLinks {
    first?: string;
    last?: string;
    prev?: string;
    next?: string;
}

export interface PaginationMeta {
    current_page: number;
    from: number;
    last_page: number;
    path: string;
    per_page: number;
    to: number;
    total: number;
}

export interface PaginatedResponse<T> {
    data: T[];
    links: PaginationLinks;
    meta: PaginationMeta;
}
