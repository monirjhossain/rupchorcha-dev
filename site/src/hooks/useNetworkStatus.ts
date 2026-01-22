import { useState, useCallback, useEffect } from 'react';
import { checkApiHealth } from '@/src/services/apiClient';

export interface NetworkStatus {
  isOnline: boolean;
  isApiAvailable: boolean;
  lastChecked: number;
}

/**
 * Hook to monitor network and API connectivity
 */
export const useNetworkStatus = () => {
  const [status, setStatus] = useState<NetworkStatus>({
    isOnline: typeof window !== 'undefined' ? navigator.onLine : true,
    isApiAvailable: true,
    lastChecked: Date.now(),
  });

  const checkApiStatus = useCallback(async () => {
    const isAvailable = await checkApiHealth();
    setStatus((prev) => ({
      ...prev,
      isApiAvailable: isAvailable,
      lastChecked: Date.now(),
    }));
  }, []);

  useEffect(() => {
    // Check browser online status
    const handleOnline = () => {
      setStatus((prev) => ({ ...prev, isOnline: true }));
      checkApiStatus();
    };

    const handleOffline = () => {
      setStatus((prev) => ({ ...prev, isOnline: false, isApiAvailable: false }));
    };

    // Initial API check
    checkApiStatus();

    // Listen to network changes
    window.addEventListener('online', handleOnline);
    window.addEventListener('offline', handleOffline);

    // Periodic API health check (every 30 seconds)
    const interval = setInterval(checkApiStatus, 30000);

    return () => {
      window.removeEventListener('online', handleOnline);
      window.removeEventListener('offline', handleOffline);
      clearInterval(interval);
    };
  }, [checkApiStatus]);

  return status;
};
