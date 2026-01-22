'use client';

import React, { useEffect, useState } from 'react';
import { useNetworkStatus } from '@/src/hooks/useNetworkStatus';

interface NetworkErrorBoundaryProps {
  children: React.ReactNode;
  fallback?: React.ReactNode;
}

/**
 * Network Error Boundary Component
 * Handles network connectivity issues gracefully
 */
export const NetworkErrorBoundary: React.FC<NetworkErrorBoundaryProps> = ({
  children,
  fallback,
}) => {
  const { isOnline, isApiAvailable } = useNetworkStatus();
  const [showError, setShowError] = useState(false);

  useEffect(() => {
    if (!isOnline || !isApiAvailable) {
      setShowError(true);
    } else {
      setShowError(false);
    }
  }, [isOnline, isApiAvailable]);

  if (showError && !isOnline) {
    return (
      fallback || (
        <div style={{
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center',
          minHeight: '100vh',
          background: '#f5f5f5',
          padding: '2rem',
        }}>
          <div style={{
            textAlign: 'center',
            background: 'white',
            padding: '2rem',
            borderRadius: '12px',
            boxShadow: '0 2px 8px rgba(0,0,0,0.1)',
            maxWidth: '400px',
          }}>
            <h2 style={{ color: '#d32f2f', margin: '0 0 1rem 0' }}>
              No Internet Connection
            </h2>
            <p style={{ color: '#666', marginBottom: '1.5rem' }}>
              Please check your internet connection and try again.
            </p>
            <button
              onClick={() => window.location.reload()}
              style={{
                padding: '0.75rem 1.5rem',
                background: '#d32f2f',
                color: 'white',
                border: 'none',
                borderRadius: '6px',
                cursor: 'pointer',
                fontSize: '1rem',
                fontWeight: 500,
              }}
            >
              Retry
            </button>
          </div>
        </div>
      )
    );
  }

  if (showError && !isApiAvailable) {
    return (
      fallback || (
        <div style={{
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center',
          minHeight: '100vh',
          background: '#f5f5f5',
          padding: '2rem',
        }}>
          <div style={{
            textAlign: 'center',
            background: 'white',
            padding: '2rem',
            borderRadius: '12px',
            boxShadow: '0 2px 8px rgba(0,0,0,0.1)',
            maxWidth: '400px',
          }}>
            <h2 style={{ color: '#ff9800', margin: '0 0 1rem 0' }}>
              Server Temporarily Unavailable
            </h2>
            <p style={{ color: '#666', marginBottom: '1rem' }}>
              The application server is currently unavailable. This usually happens during maintenance.
            </p>
            <p style={{ color: '#999', fontSize: '0.9rem', marginBottom: '1.5rem' }}>
              Please try again in a few moments.
            </p>
            <button
              onClick={() => window.location.reload()}
              style={{
                padding: '0.75rem 1.5rem',
                background: '#ff9800',
                color: 'white',
                border: 'none',
                borderRadius: '6px',
                cursor: 'pointer',
                fontSize: '1rem',
                fontWeight: 500,
              }}
            >
              Try Again
            </button>
          </div>
        </div>
      )
    );
  }

  return <>{children}</>;
};

export default NetworkErrorBoundary;
