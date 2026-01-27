"use client";

import React, { createContext, useContext, useEffect, useState, ReactNode } from 'react';
import axios from 'axios';
import FEATURE_CONFIG from '@/app/config/features';

export interface CartItem {
  product_id: number;
  quantity: number;
  product?: any;
}

interface CartContextType {
  items: CartItem[];
  addToCart: (item: CartItem) => Promise<void>;
  updateCart: (product_id: number, quantity: number) => void;
  removeFromCart: (product_id: number) => void;
  clearCart: () => void;
  fetchCart: () => Promise<void>;
}

const CartContext = createContext<CartContextType | undefined>(undefined);

export const useCart = () => {
  const context = useContext(CartContext);
  if (!context) throw new Error('useCart must be used within CartProvider');
  return context;
};

export const CartProvider = ({ children }: { children: ReactNode }) => {
  const [items, setItems] = useState<CartItem[]>([]);


  // LocalStorage key
  const STORAGE_KEY = 'cart_items';
  const API_BASE = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';

  // Helper: check if user is logged in (simple token check, adjust as needed)
  const isLoggedIn = () => {
    if (typeof window === 'undefined') return false;
    return !!localStorage.getItem('token'); // or use your auth logic
  };

  // Load cart from backend or localStorage
  const fetchCart = async () => {
    // If guest carts are allowed, use localStorage; otherwise use API
    if (isLoggedIn()) {
      try {
        const res = await axios.get(`${API_BASE}/cart`, {
          headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
        });
        if (res.data && Array.isArray(res.data.items)) {
          setItems(res.data.items.map((item: any) => ({
            product_id: item.product_id,
            quantity: item.quantity,
            product: item.product || item,
          })));
        }
      } catch (e) {
        setItems([]);
      }
    } else {
      const stored = localStorage.getItem(STORAGE_KEY);
      if (stored) {
        try {
          const parsed = JSON.parse(stored);
          if (Array.isArray(parsed)) {
            setItems(parsed);
          } else if (Array.isArray(parsed.items)) {
            setItems(parsed.items);
          } else {
            setItems([]);
          }
        } catch {
          setItems([]);
          localStorage.removeItem(STORAGE_KEY);
        }
      } else {
        setItems([]);
      }
    }
  };

  useEffect(() => {
    fetchCart();
    // eslint-disable-next-line
  }, []);

  // Save cart to localStorage for guests (only if guests are allowed to use cart)
  useEffect(() => {
    if (!isLoggedIn() && FEATURE_CONFIG.ALLOW_GUEST_CART) {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
    }
  }, [items]);

  // Add item to cart
  const addToCart = async (item: CartItem) => {
    console.log('[CartContext] Adding to cart:', item);
    // Optimistic update - update UI immediately
    setItems(prev => {
      const found = prev.find(i => i.product_id === item.product_id);
      if (found) {
        return prev.map(i =>
          i.product_id === item.product_id ? { ...i, quantity: i.quantity + item.quantity } : i
        );
      }
      return [...prev, item];
    });
    try {
      const headers = isLoggedIn()
        ? { Authorization: `Bearer ${localStorage.getItem('token')}` }
        : {};
      await axios.post(`${API_BASE}/cart/add`, { product_id: item.product_id, quantity: item.quantity }, { headers });
      console.log('[CartContext] Cart API call successful');
    } catch (error) {
      console.error('[CartContext] Failed to add to cart:', error);
      // Revert optimistic update on error
      await fetchCart();
      throw error;
    }
  };

  // Update item quantity
  const updateCart = (product_id: number, quantity: number) => {
    // Optimistic update
    setItems(prev =>
      prev.map(i => (i.product_id === product_id ? { ...i, quantity } : i))
    );
    const headers = isLoggedIn()
      ? { Authorization: `Bearer ${localStorage.getItem('token')}` }
      : {};
    axios.post(`${API_BASE}/cart/update`, { product_id, quantity }, { headers })
      .catch(() => {
        // Revert on error by fetching fresh cart
        fetchCart();
      });
  };

  // Remove item
  const removeFromCart = (product_id: number) => {
    // Optimistic update
    setItems(prev => prev.filter(i => i.product_id !== product_id));
    const headers = isLoggedIn()
      ? { Authorization: `Bearer ${localStorage.getItem('token')}` }
      : {};
    axios.post(`${API_BASE}/cart/remove`, { product_id }, { headers })
      .catch(() => {
        // Revert on error by fetching fresh cart
        fetchCart();
      });
  };

  // Clear cart
  const clearCart = () => {
    setItems([]);
    if (isLoggedIn()) {
      // Optionally, call backend to clear cart
    } else {
      localStorage.removeItem(STORAGE_KEY);
    }
  };

  return (
    <CartContext.Provider value={{ items, addToCart, updateCart, removeFromCart, clearCart, fetchCart }}>
      {children}
    </CartContext.Provider>
  );
};
