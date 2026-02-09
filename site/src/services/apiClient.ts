/**
 * Advanced API Client with Network Error Handling, Retry Logic & Health Checks
 * Production-ready API communication layer
 */

export interface ApiConfig {
  baseURL: string;
  timeout: number;
  retries: number;
  retryDelay: number;
  headers?: HeadersInit;
}

export interface ApiResponse<T = any> {
  success: boolean;
  data?: T;
  message?: string;
  status?: number;
  error?: ApiError;
}

export interface ApiError {
  code: string;
  message: string;
  details?: any;
}

export type NetworkErrorType = 
  | 'NETWORK_ERROR'
  | 'TIMEOUT'
  | 'CORS_ERROR'
  | 'SERVER_ERROR'
  | 'UNAUTHORIZED'
  | 'FORBIDDEN'
  | 'NOT_FOUND'
  | 'VALIDATION_ERROR'
  | 'UNKNOWN_ERROR';

const DEFAULT_CONFIG: ApiConfig = {
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000/api',
  timeout: parseInt(process.env.NEXT_PUBLIC_API_TIMEOUT || '30000'), // Increased to 30s
  retries: 5, // Increased retries
  retryDelay: 2000, // Increased delay
};

let apiConfig = DEFAULT_CONFIG;

/**
 * Set custom API configuration
 */
export const setApiConfig = (config: Partial<ApiConfig>) => {
  apiConfig = { ...apiConfig, ...config };
};

/**
 * Get current API configuration
 */
export const getApiConfig = (): ApiConfig => apiConfig;

/**
 * Detect network error type
 */
const detectErrorType = (
  error: any,
  response?: Response
): NetworkErrorType => {
  // Network/fetch errors
  if (error instanceof TypeError) {
    if (error.message.includes('fetch')) {
      return 'NETWORK_ERROR';
    }
    if (error.message.includes('CORS')) {
      return 'CORS_ERROR';
    }
  }

  // Timeout
  if (error.name === 'AbortError') {
    return 'TIMEOUT';
  }

  // HTTP status errors
  if (response) {
    switch (response.status) {
      case 401:
        return 'UNAUTHORIZED';
      case 403:
        return 'FORBIDDEN';
      case 404:
        return 'NOT_FOUND';
      case 422:
        return 'VALIDATION_ERROR';
      case 500:
      case 502:
      case 503:
      case 504:
        return 'SERVER_ERROR';
    }
  }

  return 'UNKNOWN_ERROR';
};

/**
 * Build user-friendly error message
 */
const buildErrorMessage = (errorType: NetworkErrorType, details?: any): string => {
  const messages: Record<NetworkErrorType, string> = {
    NETWORK_ERROR: 'Unable to connect to server. Please check your internet connection.',
    TIMEOUT: 'Request took too long. Please check your connection and try again.',
    CORS_ERROR: 'Cross-origin request failed. This might be a server configuration issue.',
    SERVER_ERROR: 'Server is currently unavailable. Please try again later.',
    UNAUTHORIZED: 'Your session has expired. Please login again.',
    FORBIDDEN: 'You do not have permission to perform this action.',
    NOT_FOUND: 'The requested resource was not found.',
    VALIDATION_ERROR: 'Please check your input and try again.',
    UNKNOWN_ERROR: 'An unexpected error occurred. Please try again.',
  };

  return messages[errorType] || 'An error occurred';
};

/**
 * Check if API is reachable (health check)
 */
export const checkApiHealth = async (): Promise<boolean> => {
  try {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 5000);

    const response = await fetch(`${apiConfig.baseURL}/health`, {
      method: 'GET',
      signal: controller.signal,
    });

    clearTimeout(timeoutId);
    return response.ok;
  } catch (error) {
    console.warn('API health check failed:', error);
    return false;
  }
};

/**
 * Main API request function with retry logic
 */
export const apiRequest = async <T = any>(
  endpoint: string,
  options: RequestInit & { 
    retryCount?: number;
    throwOnNetworkError?: boolean;
    skipAuth?: boolean;
  } = {}
): Promise<ApiResponse<T>> => {
  const {
    method = 'GET',
    body,
    retryCount = 0,
    throwOnNetworkError = false,
    skipAuth = false,
    ...fetchOptions
  } = options;

  const url = `${apiConfig.baseURL}${endpoint}`;
  const headers: HeadersInit = {
    'Content-Type': 'application/json',
    Accept: 'application/json',
    ...fetchOptions.headers,
  };

  console.log(`🌐 API Request: ${method} ${url}`);
  console.log(`📦 Body:`, body);

  // Add auth token if available and not a public endpoint
  // Public endpoints: login, register, OTP, Google auth, forgot password, reset password
  const publicEndpoints = ['/login', '/register', '/send-otp', '/verify-otp', '/auth/google', '/forgot-password', '/reset-password', '/health'];
  const isPublic = publicEndpoints.some(ep => endpoint.startsWith(ep)) || skipAuth;
  
  if (!isPublic && typeof window !== 'undefined') {
    const token = localStorage.getItem('token');
    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
      console.log(`🔑 Added auth token`);
    }
  }

  try {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), apiConfig.timeout);

    console.log(`📡 Sending request to ${url}...`);
    const response = await fetch(url, {
      method,
      headers,
      body: body ? JSON.stringify(body) : undefined,
      signal: controller.signal,
      cache: 'no-store', // Bypass browser cache
      credentials: 'same-origin', // Include cookies if needed
      mode: 'cors', // Explicitly set CORS mode
      ...fetchOptions,
    });

    clearTimeout(timeoutId);
    console.log(`✅ Response received: ${response.status} ${response.statusText}`);

    // Parse response
    const contentType = response.headers.get('content-type');
    let data: any;

    try {
      if (contentType?.includes('application/json')) {
        data = await response.json();
      } else {
        data = await response.text();
      }
    } catch (parseError) {
      console.error('Failed to parse response:', parseError);
      data = null;
    }

    // Handle 401 - Unauthorized
    if (response.status === 401) {
      const errorData = typeof data === 'object' ? data : null;
      const isAuthError = !isPublic; // Only treat as auth error if not a public endpoint
      
      if (isAuthError && typeof window !== 'undefined') {
        localStorage.removeItem('token');
        window.dispatchEvent(new Event('auth-expired'));
      }
      
      return {
        success: false,
        status: 401,
        message: errorData?.message || (isAuthError ? 'Session expired. Please login again.' : 'Unauthorized'),
        error: {
          code: 'UNAUTHORIZED',
          message: errorData?.message || (isAuthError ? 'Session expired' : 'Unauthorized'),
          details: errorData,
        },
      };
    }

    // Handle other errors
    if (!response.ok) {
      const errorMessage =
        typeof data === 'object'
          ? data.message || data.error || `HTTP ${response.status}`
          : data || `HTTP ${response.status}`;

      const errorType: NetworkErrorType =
        response.status === 422
          ? 'VALIDATION_ERROR'
          : response.status >= 500
            ? 'SERVER_ERROR'
            : 'UNKNOWN_ERROR';

      return {
        success: false,
        status: response.status,
        message: errorMessage,
        error: {
          code: errorType,
          message: buildErrorMessage(errorType),
          details: data,
        },
      };
    }

    // Success
    return {
      success: true,
      status: response.status,
      data: typeof data === 'object' ? data?.data || data : data,
      message: typeof data === 'object' ? data?.message : undefined,
    };
  } catch (error: any) {
    const errorType = detectErrorType(error);
    const errorMessage = buildErrorMessage(errorType);
    const isNetworkError =
      errorType === 'NETWORK_ERROR' ||
      errorType === 'TIMEOUT' ||
      errorType === 'CORS_ERROR';

    // Retry logic for network errors
    if (isNetworkError && retryCount < apiConfig.retries) {
      console.warn(
        `Request failed (${errorType}). Retrying... (${retryCount + 1}/${apiConfig.retries})`
      );
      await new Promise((resolve) =>
        setTimeout(resolve, apiConfig.retryDelay * (retryCount + 1))
      );
      return apiRequest(endpoint, {
        ...options,
        retryCount: retryCount + 1,
      });
    }

    // Suppress console errors in development (only log critical errors)
    // Health checks and network errors are expected during development
    const shouldLog = process.env.NEXT_PUBLIC_ENV === 'production';
    
    if (shouldLog) {
      console.error('API Request Error:', {
        type: errorType,
        message: error.message,
        url,
        method,
      });
    }

    if (throwOnNetworkError && isNetworkError) {
      throw new Error(errorMessage);
    }

    return {
      success: false,
      message: errorMessage,
      error: {
        code: errorType,
        message: errorMessage,
        details: error.message,
      },
    };
  }
};

/**
 * Convenience methods
 */
export const api = {
  get: <T = any>(endpoint: string, options?: RequestInit) =>
    apiRequest<T>(endpoint, { ...options, method: 'GET' }),

  post: <T = any>(endpoint: string, body?: any, options?: RequestInit) =>
    apiRequest<T>(endpoint, { ...options, method: 'POST', body }),

  put: <T = any>(endpoint: string, body?: any, options?: RequestInit) =>
    apiRequest<T>(endpoint, { ...options, method: 'PUT', body }),

  patch: <T = any>(endpoint: string, body?: any, options?: RequestInit) =>
    apiRequest<T>(endpoint, { ...options, method: 'PATCH', body }),

  delete: <T = any>(endpoint: string, options?: RequestInit) =>
    apiRequest<T>(endpoint, { ...options, method: 'DELETE' }),
};

/**
 * SWR Fetcher wrapper
 * Unwraps the response to return just the data property
 */
export const fetcher = async (url: string) => {
  // Remove leading slash if present to avoid double slashes if baseURL ends with slash
  const endpoint = url.startsWith('http') ? url : url; 
  
  // If complete URL is passed (e.g. from next/image or external), use fetch directly
  if (endpoint.startsWith('http')) {
     const res = await fetch(endpoint);
     if (!res.ok) throw new Error('Failed to fetch');
     return res.json();
  }

  const response = await api.get(url);
  
  if (!response.success) {
    throw new Error(response.message || 'An error occurred');
  }
  
  return response.data;
};

export default api;
