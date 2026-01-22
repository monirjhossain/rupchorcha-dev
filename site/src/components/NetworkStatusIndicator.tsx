'use client';

import React from 'react';
import { useNetworkStatus } from '@/src/hooks/useNetworkStatus';

/**
 * Component to show network status to user
 */
export const NetworkStatusIndicator: React.FC = () => {
  const { isOnline, isApiAvailable } = useNetworkStatus();

  if (!isOnline) {
    return (
      <div
        style={{
          position: 'fixed',
          bottom: 20,
          left: 20,
          background: '#d32f2f',
          color: 'white',
          padding: '1rem',
          borderRadius: '8px',
          zIndex: 9999,
          boxShadow: '0 4px 12px rgba(211, 47, 47, 0.3)',
          maxWidth: '300px',
        }}
      >
        <strong>No Internet Connection</strong>
        <p style={{ margin: '0.5rem 0 0 0', fontSize: '0.9rem' }}>
          Please check your connection and try again.
        </p>
      </div>
    );
  }

  if (!isApiAvailable) {
    return (
      <div
        style={{
          position: 'fixed',
          bottom: 20,
          left: 20,
          background: '#ff9800',
          color: 'white',
          padding: '1rem',
          borderRadius: '8px',
          zIndex: 9999,
          boxShadow: '0 4px 12px rgba(255, 152, 0, 0.3)',
          maxWidth: '300px',
        }}
      >
        <strong>Server Unavailable</strong>
        <p style={{ margin: '0.5rem 0 0 0', fontSize: '0.9rem' }}>
          The server is temporarily unavailable. Please try again later.
        </p>
      </div>
    );
  }

  return null;
};

export default NetworkStatusIndicator;
