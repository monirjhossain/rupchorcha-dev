"use client";
import React, { createContext, useContext, useEffect, useState, useCallback } from "react";
import FEATURE_CONFIG from "@/app/config/features";

type WishlistItem = {
  id: number;
  product_id: number;
  product: any;
};

type WishlistContextType = {
  wishlist: WishlistItem[];
  loading: boolean;
  addToWishlist: (productId: number, productData?: any) => Promise<void>;
  removeFromWishlist: (productId: number) => Promise<void>;
  isInWishlist: (productId: number) => boolean;
  refreshWishlist: () => Promise<void>;
  isAuthenticated: boolean;
};

const WishlistContext = createContext<WishlistContextType | undefined>(undefined);

export const WishlistProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [wishlist, setWishlist] = useState<WishlistItem[]>([]);
  const [loading, setLoading] = useState(false);
  const [isAuthenticated, setIsAuthenticated] = useState(false);

  const API_BASE = "http://localhost:8000/api/wishlist";

  const getToken = () => {
    if (typeof window === "undefined") return null;
    return localStorage.getItem("token");
  };

  // Check if user is authenticated
  const checkAuth = useCallback(() => {
    if (typeof window === "undefined") return false;
    return !!localStorage.getItem("token");
  }, []);

  const fetchWishlist = useCallback(async () => {
    if (!checkAuth()) {
      // Load guest wishlist from localStorage and fetch product details
      const guestWishlist = JSON.parse(localStorage.getItem("guest_wishlist") || "[]");
      if (guestWishlist.length === 0) {
        setWishlist([]);
        return;
      }
      
      try {
        // Fetch product details for all guest wishlist items
        const productPromises = guestWishlist.map(async (productId: number) => {
          try {
            const res = await fetch(`http://localhost:8000/api/products/${productId}`);
            if (res.ok) {
              const data = await res.json();
              return {
                id: productId,
                product_id: productId,
                product: data.product || data,
              };
            }
            return null;
          } catch {
            return null;
          }
        });
        
        const products = await Promise.all(productPromises);
        const validProducts = products.filter(p => p !== null);
        setWishlist(validProducts as WishlistItem[]);
      } catch (error) {
        console.error("Failed to fetch guest wishlist products:", error);
        setWishlist([]);
      }
      return;
    }

    // Load authenticated wishlist from server
    setLoading(true);
    try {
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 5000); // 5 second timeout
      
      const token = getToken();
      const res = await fetch(API_BASE, { 
        method: "GET",
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: "application/json",
        },
        signal: controller.signal,
      });
      clearTimeout(timeoutId);
      
      if (!res.ok) {
        console.warn("Wishlist API error:", res.status);
        setWishlist([]);
        return;
      }
      
      const data = await res.json();
      setWishlist(data.wishlists || []);
      
      // Sync guest wishlist with server after login
      const guestWishlist = JSON.parse(localStorage.getItem("guest_wishlist") || "[]");
      if (guestWishlist.length > 0) {
        for (const productId of guestWishlist) {
          try {
            await fetch(API_BASE, {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
              },
              body: JSON.stringify({ product_id: productId }),
            });
          } catch (err) {
            console.warn("Failed to sync guest wishlist item:", productId);
          }
        }
        // Clear guest wishlist after syncing
        localStorage.removeItem("guest_wishlist");
      }
    } catch (error: any) {
      if (error.name === 'AbortError') {
        console.warn("Wishlist fetch timeout");
      } else {
        console.warn("Wishlist fetch error:", error.message);
      }
      setWishlist([]);
    } finally {
      setLoading(false);
    }
  }, [checkAuth]);

  useEffect(() => {
    const auth = checkAuth();
    setIsAuthenticated(auth);
    if (auth) {
      fetchWishlist();
    }
  }, [checkAuth, fetchWishlist]);

  // Listen for auth state changes
  useEffect(() => {
    const handleAuthChange = () => {
      const auth = checkAuth();
      setIsAuthenticated(auth);
      if (auth) {
        fetchWishlist();
      }
    };
    
    window.addEventListener('auth-state-changed', handleAuthChange);
    return () => window.removeEventListener('auth-state-changed', handleAuthChange);
  }, [checkAuth, fetchWishlist]);

  const addToWishlist = async (productId: number, productData?: any) => {
    setLoading(true);
    try {
      const token = getToken();
      const isAuth = !!token;

      // Update authentication state
      setIsAuthenticated(isAuth);

      // For guests: add to localStorage
      if (!isAuth) {
        const guestWishlist = JSON.parse(localStorage.getItem("guest_wishlist") || "[]");
        if (!guestWishlist.includes(productId)) {
          guestWishlist.push(productId);
          localStorage.setItem("guest_wishlist", JSON.stringify(guestWishlist));
          
          // Optimistically add to UI with product data if available
          const tempId = Math.max(...wishlist.map(w => w.id), 0) + 1;
          setWishlist(prev => [{ id: tempId, product_id: productId, product: productData || null }, ...prev]);
          
          // Fetch product details if not provided
          if (!productData) {
            try {
              const res = await fetch(`http://localhost:8000/api/products/${productId}`);
              if (res.ok) {
                const data = await res.json();
                setWishlist(prev => prev.map(item => 
                  item.id === tempId ? { ...item, product: data.product || data } : item
                ));
              }
            } catch (error) {
              console.error("Failed to fetch product details:", error);
            }
          }
        }
        return;
      }

      // For authenticated users: sync with server
      // Optimistic update: add to wishlist immediately
      const tempId = Math.max(...wishlist.map(w => w.id), 0) + 1;
      const optimisticItem: WishlistItem = {
        id: tempId,
        product_id: productId,
        product: null,
      };
      setWishlist(prev => [optimisticItem, ...prev]);

      const res = await fetch(API_BASE, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ product_id: productId }),
      });
      if (!res.ok) {
        // Revert optimistic update on error
        setWishlist(prev => prev.filter(w => w.id !== tempId));
        const error = await res.json().catch(() => ({}));
        if (res.status === 401 || res.status === 403) {
          throw new Error("Please login to add items to wishlist");
        }
        throw new Error(error.message || "Failed to add to wishlist");
      }
      // Refresh to get actual data from server
      await fetchWishlist();
    } finally {
      setLoading(false);
    }
  };

  const removeFromWishlist = async (productId: number) => {
    try {
      const token = getToken();
      const isAuth = !!token;

      // Optimistic update: remove from UI immediately
      setWishlist(prev => prev.filter(w => w.product_id !== productId));

      // For guests: remove from localStorage
      if (!isAuth) {
        const guestWishlist = JSON.parse(localStorage.getItem("guest_wishlist") || "[]");
        const updated = guestWishlist.filter((id: number) => id !== productId);
        localStorage.setItem("guest_wishlist", JSON.stringify(updated));
        return;
      }

      // For authenticated users: sync with server in background
      const res = await fetch(`${API_BASE}/${productId}`, {
        method: "DELETE",
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: "application/json",
        },
      });
      
      if (!res.ok) {
        // Revert optimistic update by refetching on error
        await fetchWishlist();
        const error = await res.json().catch(() => ({}));
        if (res.status === 401 || res.status === 403) {
          throw new Error("Please login to manage your wishlist");
        }
        throw new Error(error.message || "Failed to remove from wishlist");
      }
      // No need to refetch - optimistic update already applied
    } catch (error) {
      console.error("Remove from wishlist error:", error);
      throw error;
    }
  };

  const isInWishlist = (productId: number) => {
    // Check in authenticated wishlist
    if (wishlist.some((item) => item.product_id === productId)) {
      return true;
    }
    // Check in guest wishlist (localStorage)
    if (!checkAuth() && typeof window !== 'undefined') {
      const guestWishlist = JSON.parse(localStorage.getItem("guest_wishlist") || "[]");
      return guestWishlist.includes(productId);
    }
    return false;
  };

  return (
    <WishlistContext.Provider
      value={{
        wishlist,
        loading,
        addToWishlist,
        removeFromWishlist,
        isInWishlist,
        refreshWishlist: fetchWishlist,
        isAuthenticated,
      }}
    >
      {children}
    </WishlistContext.Provider>
  );
};

export const useWishlist = () => {
  const ctx = useContext(WishlistContext);
  if (!ctx) throw new Error("useWishlist must be used within a WishlistProvider");
  return ctx;
};
