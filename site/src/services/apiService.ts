/**
 * Centralized API Service
 * Handles all API calls with consistent error handling, token management, and request/response intercepting
 */

export interface ApiResponse<T = any> {
  success: boolean;
  data?: T;
  message?: string;
  errors?: Record<string, string[]>;
}

export interface ApiRequestOptions extends RequestInit {
  requiresAuth?: boolean;
  timeout?: number;
  retryCount?: number;
}

const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api";
const DEFAULT_TIMEOUT = 10000; // 10 seconds
const MAX_RETRIES = 3;

/**
 * Get auth token from localStorage
 */
export const getAuthToken = (): string | null => {
  if (typeof window === "undefined") return null;
  return localStorage.getItem("token");
};

/**
 * Set auth token in localStorage
 */
export const setAuthToken = (token: string): void => {
  if (typeof window === "undefined") return;
  localStorage.setItem("token", token);
};

/**
 * Remove auth token from localStorage
 */
export const removeAuthToken = (): void => {
  if (typeof window === "undefined") return;
  localStorage.removeItem("token");
};

/**
 * Build request headers with auth token
 */
const buildHeaders = (options?: ApiRequestOptions): HeadersInit => {
  const headers: HeadersInit = {
    "Content-Type": "application/json",
    Accept: "application/json",
  };

  const token = getAuthToken();
  if (token && options?.requiresAuth !== false) {
    headers["Authorization"] = `Bearer ${token}`;
  }

  return headers;
};

/**
 * Make API request with timeout, retry logic, and error handling
 */
export const apiRequest = async <T = any>(
  endpoint: string,
  options: ApiRequestOptions = {}
): Promise<ApiResponse<T>> => {
  const {
    method = "GET",
    body,
    requiresAuth = true,
    timeout = DEFAULT_TIMEOUT,
    retryCount = 0,
    ...fetchOptions
  } = options;

  const url = `${API_BASE_URL}${endpoint}`;
  const headers = buildHeaders({ requiresAuth } as ApiRequestOptions);

  try {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), timeout);

    const response = await fetch(url, {
      method,
      headers,
      body: body ? JSON.stringify(body) : undefined,
      signal: controller.signal,
      ...fetchOptions,
    });

    clearTimeout(timeoutId);

    // Parse response
    let responseData: any;
    const contentType = response.headers.get("content-type");
    
    if (contentType?.includes("application/json")) {
      responseData = await response.json();
    } else {
      responseData = await response.text();
    }

    // Handle 401 - Unauthorized (token expired or invalid)
    if (response.status === 401) {
      removeAuthToken();
      if (typeof window !== "undefined") {
        window.dispatchEvent(new Event("auth-expired"));
      }
      throw new Error("Session expired. Please login again.");
    }

    // Handle 403 - Forbidden
    if (response.status === 403) {
      throw new Error("You don't have permission to perform this action.");
    }

    // Handle other errors
    if (!response.ok) {
      const errorMessage =
        typeof responseData === "object"
          ? responseData.message || responseData.error
          : responseData || `HTTP ${response.status}`;

      throw new Error(errorMessage || "An error occurred");
    }

    // Success response
    return {
      success: true,
      data: responseData,
      message: responseData.message,
    };
  } catch (error: any) {
    // Handle network errors and timeouts
    if (error.name === "AbortError") {
      // Retry logic for timeout
      if (retryCount < MAX_RETRIES) {
        console.warn(`Request timeout. Retrying... (${retryCount + 1}/${MAX_RETRIES})`);
        return apiRequest(endpoint, {
          ...options,
          retryCount: retryCount + 1,
        });
      }
      throw new Error("Request timeout. Please check your connection and try again.");
    }

    // Return error response
    return {
      success: false,
      message: error.message || "An unexpected error occurred",
      errors: error.errors,
    };
  }
};

/**
 * Convenience methods for common HTTP methods
 */
export const api = {
  get: <T = any>(endpoint: string, options?: ApiRequestOptions) =>
    apiRequest<T>(endpoint, { ...options, method: "GET" }),

  post: <T = any>(endpoint: string, body?: any, options?: ApiRequestOptions) =>
    apiRequest<T>(endpoint, { ...options, method: "POST", body }),

  put: <T = any>(endpoint: string, body?: any, options?: ApiRequestOptions) =>
    apiRequest<T>(endpoint, { ...options, method: "PUT", body }),

  patch: <T = any>(endpoint: string, body?: any, options?: ApiRequestOptions) =>
    apiRequest<T>(endpoint, { ...options, method: "PATCH", body }),

  delete: <T = any>(endpoint: string, options?: ApiRequestOptions) =>
    apiRequest<T>(endpoint, { ...options, method: "DELETE" }),
};

/**
 * Form validation helper
 */
export const getErrorMessage = (error: any): string => {
  if (typeof error === "string") return error;
  if (error?.message) return error.message;
  if (error?.errors) {
    const firstError = Object.values(error.errors)[0];
    return Array.isArray(firstError) ? firstError[0] : firstError;
  }
  return "An unexpected error occurred";
};

export default api;
