'use client';

/**
 * API Interceptor Service
 * Handles authentication, error logging, and request transformation
 */

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000/api';
const TOKEN_KEY = 'auth_token';
const REFRESH_TOKEN_KEY = 'refresh_token';

export interface RequestInterceptorConfig {
  headers?: Record<string, string>;
  skipAuth?: boolean;
  skipContentType?: boolean;
}

/**
 * Add authorization header to requests
 */
export const requestInterceptor = (config: RequestInterceptorConfig): RequestInterceptorConfig => {
  const token = typeof window !== 'undefined' ? localStorage.getItem(TOKEN_KEY) : null;

  if (token && !config.skipAuth) {
    if (!config.headers) {
      config.headers = {};
    }
    config.headers['Authorization'] = `Bearer ${token}`;
  }

  if (!config.skipContentType) {
    if (!config.headers) {
      config.headers = {};
    }
    config.headers['Content-Type'] = 'application/json';
    config.headers['Accept'] = 'application/json';
  }

  return config;
};

/**
 * Handle authentication errors and log issues
 */
export const responseInterceptor = (response: Response, error?: any) => {
  // 401 Unauthorized - token might be invalid
  if (response.status === 401) {
    if (typeof window !== 'undefined') {
      localStorage.removeItem(TOKEN_KEY);
      localStorage.removeItem(REFRESH_TOKEN_KEY);
      // Dispatch custom event for auth state update
      window.dispatchEvent(new CustomEvent('authTokenExpired'));
    }
  }

  // 403 Forbidden - user doesn't have permission
  if (response.status === 403) {
    console.warn('Access forbidden:', response);
  }

  // 5xx Server errors - log for debugging
  if (response.status >= 500) {
    console.error('Server error:', {
      status: response.status,
      statusText: response.statusText,
      error,
    });
  }

  return response;
};

/**
 * Log API requests for debugging
 */
export const logRequest = (
  method: string,
  endpoint: string,
  data?: any,
  error?: any
) => {
  const isDev = process.env.NEXT_PUBLIC_ENV === 'development';

  if (isDev) {
    console.group(`🌐 API Request: ${method} ${endpoint}`);
    if (data) console.table(data);
    if (error) console.error('Error:', error);
    console.groupEnd();
  }
};

/**
 * Log API responses for debugging
 */
export const logResponse = (
  method: string,
  endpoint: string,
  status: number,
  data?: any,
  error?: any
) => {
  const isDev = process.env.NEXT_PUBLIC_ENV === 'development';

  if (isDev) {
    const icon = status < 400 ? '✅' : status < 500 ? '⚠️' : '❌';
    console.group(`${icon} API Response: ${method} ${endpoint} (${status})`);
    if (data) console.table(data);
    if (error) console.error('Error:', error);
    console.groupEnd();
  }
};

/**
 * Validate API response structure
 */
export const validateResponse = (data: any): boolean => {
  // Most API responses should be objects
  return typeof data === 'object' && data !== null;
};

/**
 * Extract error message from various response formats
 */
export const extractErrorMessage = (error: any): string => {
  // Laravel validation errors
  if (error?.errors) {
    const firstError = Object.values(error.errors)[0];
    if (Array.isArray(firstError)) {
      return firstError[0];
    }
    return String(firstError);
  }

  // Standard API error message
  if (error?.message) {
    return error.message;
  }

  // Fallback
  return 'An unexpected error occurred';
};

/**
 * Check if response indicates success
 */
export const isSuccessResponse = (status: number): boolean => {
  return status >= 200 && status < 300;
};

/**
 * Get API base URL
 */
export const getApiUrl = (): string => {
  return API_URL;
};
