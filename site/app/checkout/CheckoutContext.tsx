"use client";
import React, { createContext, useContext, useState, ReactNode } from 'react';

type ShippingMethod = {
  id: number;
  name: string;
  type: string;
  cost: number;
  min_order: number | null;
  max_order: number | null;
};

interface CheckoutContextValue {
  selectedShipping: number | null;
  shippingCost: number;
  shippingMethods: ShippingMethod[];
  discountCode: string;
  discountAmount: number;
  loading: boolean;
  setSelectedShipping: (id: number) => void;
  updateShippingByArea: (district: string, area: string, cartTotal?: number) => Promise<void>;
  setDiscountCode: (code: string) => void;
  setDiscountAmount: (amount: number) => void;
}

const CheckoutContext = createContext<CheckoutContextValue | undefined>(undefined);

export function CheckoutProvider({ children }: { children: ReactNode }) {
  const [selectedShipping, setSelectedShippingId] = useState<number | null>(null);
  const [shippingCost, setShippingCost] = useState<number>(0);
  const [shippingMethods, setShippingMethods] = useState<ShippingMethod[]>([]);
  const [discountCode, setDiscountCode] = useState<string>('');
  const [discountAmount, setDiscountAmount] = useState<number>(0);
  const [loading, setLoading] = useState<boolean>(false);
  const [shippingCache, setShippingCache] = useState<Record<string, {methods: ShippingMethod[], timestamp: number}>>({});

  const updateShippingByArea = async (district: string, area: string, cartTotal: number = 0) => {
    const cacheKey = `${district}:${area}:${cartTotal}`;
    const cacheExpiry = 5 * 60 * 1000; // 5 minutes
    
    // Instant optimistic update for common cases
    if (district === 'Dhaka') {
      const optimisticCost = area === 'Dhaka Sadar' ? 60 : 120;
      setShippingCost(optimisticCost);
    } else {
      setShippingCost(150); // Outside Dhaka
    }
    
    // Check cache first
    const cached = shippingCache[cacheKey];
    if (cached && Date.now() - cached.timestamp < cacheExpiry) {
      setShippingMethods(cached.methods);
      const defaultMethod = cached.methods.find((m: ShippingMethod) => m.type === 'flat') || cached.methods[0];
      if (defaultMethod) {
        setSelectedShippingId(defaultMethod.id);
        setShippingCost(defaultMethod.cost);
      }
      return;
    }

    setLoading(true);
    try {
      const response = await fetch('http://localhost:8000/api/shipping/methods-by-location', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({ district, area, cart_total: cartTotal }),
      });

      const data = await response.json();

      if (data.success && data.methods && data.methods.length > 0) {
        setShippingMethods(data.methods);
        
        // Cache the result
        setShippingCache(prev => ({
          ...prev,
          [cacheKey]: {
            methods: data.methods,
            timestamp: Date.now()
          }
        }));
        
        // Auto-select first non-free method or first method
        const defaultMethod = data.methods.find((m: ShippingMethod) => m.type === 'flat') || data.methods[0];
        setSelectedShippingId(defaultMethod.id);
        setShippingCost(defaultMethod.cost);
      }
    } catch (error) {
      console.error('Failed to fetch shipping methods:', error);
      // Fallback to default
      setShippingMethods([]);
      setShippingCost(0);
    } finally {
      setLoading(false);
    }
  };

  const setSelectedShipping = (id: number) => {
    const method = shippingMethods.find(m => m.id === id);
    if (method) {
      setSelectedShippingId(id);
      setShippingCost(method.cost);
    }
  };

  return (
    <CheckoutContext.Provider value={{ 
      selectedShipping, 
      shippingCost, 
      shippingMethods,
      discountCode, 
      discountAmount, 
      loading,
      setSelectedShipping, 
      updateShippingByArea, 
      setDiscountCode, 
      setDiscountAmount 
    }}>
      {children}
    </CheckoutContext.Provider>
  );
}

export function useCheckout() {
  const ctx = useContext(CheckoutContext);
  if (!ctx) {
    throw new Error('useCheckout must be used within a CheckoutProvider');
  }
  return ctx;
}
