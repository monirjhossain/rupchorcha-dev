"use client";
import React, { useState } from "react";
import Header from "./components/Header";
import FloatingCartIcon from "./components/FloatingCartIcon";
import CartSidebar from "./components/CartSidebar";
import { CartProvider } from "./common/CartContext";
import NetworkErrorBoundary from "@/src/components/NetworkErrorBoundary";
import { ErrorBoundary } from "@/src/components/ErrorBoundary";
import NetworkStatusIndicator from "@/src/components/NetworkStatusIndicator";
import { SWRConfig } from 'swr';

// Global SWR configuration for optimal performance
const swrConfig = {
  revalidateOnFocus: false,
  revalidateOnReconnect: false,
  dedupingInterval: 30000, // 30 seconds
  focusThrottleInterval: 60000, // 1 minute
  errorRetryCount: 3,
  errorRetryInterval: 5000,
  // Use localStorage for offline support
  provider: () => {
    if (typeof window !== 'undefined') {
      try {
        const raw = localStorage.getItem('app-cache');
        if (!raw) return new Map();
        const parsed = JSON.parse(raw);
        if (Array.isArray(parsed)) {
          return new Map(parsed as [string, any][]);
        }
      } catch (e) {
        console.warn('Invalid app-cache in localStorage, clearing it', e);
        localStorage.removeItem('app-cache');
      }
      return new Map();
    }
    return new Map();
  },
};

export default function ClientLayout({ children }: { children: React.ReactNode }) {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  return (
    <ErrorBoundary>
      <NetworkErrorBoundary>
        <SWRConfig value={swrConfig}>
          <CartProvider>
            <Header onCartClick={() => setSidebarOpen(true)} />
            {!sidebarOpen && <FloatingCartIcon onClick={() => setSidebarOpen(true)} />}
            <CartSidebar isOpen={sidebarOpen} onClose={() => setSidebarOpen(false)} />
            {children}
            <NetworkStatusIndicator />
          </CartProvider>
        </SWRConfig>
      </NetworkErrorBoundary>
    </ErrorBoundary>
  );
}
