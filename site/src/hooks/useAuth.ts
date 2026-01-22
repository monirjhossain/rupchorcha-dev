"use client";

import { useState, useCallback, useEffect } from "react";
import { useRouter } from "next/navigation";
import { api } from "@/src/services/apiClient";

export interface User {
  id: number;
  name: string;
  email: string;
  phone?: string;
  role?: string;
}

export interface AuthContextType {
  user: User | null;
  token: string | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  error: string | null;
  
  // Email/Password methods
  loginWithEmail: (email: string, password: string) => Promise<void>;
  
  // OTP methods
  sendOtp: (phone: string) => Promise<void>;
  verifyOtp: (phone: string, otp: string) => Promise<any>;
  
  // Register method
  register: (data: {
    name: string;
    email: string;
    phone: string;
    password: string;
    password_confirmation: string;
  }) => Promise<void>;
  
  // Google OAuth
  loginWithGoogle: (credential: string) => Promise<void>;

  // Complete profile for new OTP users
  completeProfile: (data: { name: string; password: string; password_confirmation: string }) => Promise<void>;
  
  // Logout
  logout: () => void;
  
  // Clear error
  clearError: () => void;
}

const API_BASE = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api";

export const useAuth = (): AuthContextType => {
  const [user, setUser] = useState<User | null>(null);
  const [token, setToken] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const router = useRouter();

  

  // Fetch user profile
  const fetchUserProfile = useCallback(async (authToken: string) => {
    try {
      const response = await api.get('/profile', {
        headers: { Authorization: `Bearer ${authToken}` },
      });
      const payload: any = (response as any)?.data ?? response;
      setUser(payload?.user || payload?.data?.user || null);
    } catch (err) {
      console.error("Failed to fetch profile:", err);
      localStorage.removeItem("token");
      setToken(null);
    }
  }, []);

  const saveAuth = useCallback((newToken: string, userData?: User) => {
    localStorage.setItem("token", newToken);
    setToken(newToken);
    if (userData) {
      setUser(userData);
    } else {
      fetchUserProfile(newToken);
    }
  }, [fetchUserProfile]);

  // Load token from localStorage on mount (after fetchUserProfile is defined)
  useEffect(() => {
    const savedToken = localStorage.getItem("token");
    if (savedToken) {
      setToken(savedToken);
      fetchUserProfile(savedToken);
    }
  }, [fetchUserProfile]);

  // Listen for auth changes and storage updates (after fetchUserProfile is defined)
  useEffect(() => {
    const syncAuth = () => {
      const current = localStorage.getItem("token");
      setToken(current);
      if (current) {
        fetchUserProfile(current);
      } else {
        setUser(null);
      }
    };

    const storageListener = (e: StorageEvent) => {
      if (e.key === "token") {
        syncAuth();
      }
    };

    window.addEventListener('auth-state-changed', syncAuth);
    window.addEventListener('storage', storageListener);
    return () => {
      window.removeEventListener('auth-state-changed', syncAuth);
      window.removeEventListener('storage', storageListener);
    };
  }, [fetchUserProfile]);

  // Email/Password Login
  const loginWithEmail = useCallback(async (email: string, password: string) => {
    setIsLoading(true);
    setError(null);
    try {
      const response = await api.post('/login', { email, password });
      const payload: any = (response as any)?.data ?? response;
      console.log("Email login successful:", payload);
      const token = payload?.token || payload?.data?.token;
      const userData = payload?.user || payload?.data?.user;
      if (!token) {
        throw new Error("No token received from server");
      }
      saveAuth(token, userData);
      setError(null);
    } catch (err: any) {
      const msg = err.message || "Login failed. Please try again.";
      setError(msg);
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, [saveAuth, router]);

  // Send OTP
  const sendOtp = useCallback(async (phone: string) => {
    setIsLoading(true);
    setError(null);
    try {
      await api.post('/send-otp', { phone });
      setError(null);
    } catch (err: any) {
      const msg = err.message || "Failed to send OTP. Please try again.";
      setError(msg);
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, []);

  // Verify OTP
  const verifyOtp = useCallback(async (phone: string, otp: string) => {
    setIsLoading(true);
    setError(null);
    try {
      const response = await api.post('/verify-otp', { phone, otp });
      const payload: any = (response as any)?.data ?? response;
      console.log("OTP verification successful:", payload);
      const receivedToken = payload?.token || payload?.temp_token || payload?.data?.token || payload?.data?.temp_token;
      if (!receivedToken) {
        throw new Error("No token received from server");
      }
      const userData = payload?.user || payload?.data?.user;
      saveAuth(receivedToken, userData);
      const requiresProfileCompletion = payload?.requires_profile_completion ?? payload?.data?.requires_profile_completion;
      if (requiresProfileCompletion) {
        try { localStorage.setItem("requires_profile_completion", "true"); } catch {}
      } else {
        try { localStorage.removeItem("requires_profile_completion"); } catch {}
      }
      setError(null);
      return payload;
    } catch (err: any) {
      const msg = err.message || "OTP verification failed. Please try again.";
      setError(msg);
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, [saveAuth, router]);

  // Complete profile after temp token
  const completeProfile = useCallback(async (data: { name: string; password: string; password_confirmation: string }) => {
    setIsLoading(true);
    setError(null);
    try {
      if (!token) throw new Error("Not authenticated");
      const response = await api.post('/complete-profile', data, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      const payload: any = (response as any)?.data ?? response;
      const newToken = payload?.token || payload?.data?.token;
      if (!newToken) throw new Error("No token received after profile completion");
      const userData = payload?.user || payload?.data?.user;
      saveAuth(newToken, userData);
      try { localStorage.removeItem("requires_profile_completion"); } catch {}
    } catch (err: any) {
      const msg = err.message || "Profile completion failed. Please try again.";
      setError(msg);
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, [saveAuth, token]);

  // Register
  const register = useCallback(async (data: {
    name: string;
    email: string;
    phone: string;
    password: string;
    password_confirmation: string;
  }) => {
    console.log("🔵 useAuth.register called with:", { 
      ...data, 
      password: "***", 
      password_confirmation: "***" 
    });
    setIsLoading(true);
    setError(null);
    try {
      console.log("📡 Calling API /register...");
        const response = await api.post('/register', data);
        const responseData: any = (response as any)?.data ?? response;
      console.log("✅ Registration API response:", responseData);
      
      // Handle both direct response and wrapped response
        const token = responseData?.token || responseData?.data?.token;
        const user = responseData?.user || responseData?.data?.user;
      
      console.log("🔍 Extracted token:", token ? token.substring(0, 20) + "..." : "NOT FOUND");
      console.log("🔍 Extracted user:", user);
      
      if (!token) {
        console.error("❌ No token in response:", responseData);
        throw new Error("No token received from server");
      }
      
      console.log("✅ Token received:", token.substring(0, 20) + "...");
      saveAuth(token, user);
      setError(null);
      console.log("✅ Auth saved, redirecting...");
      router.push("/");
    } catch (err: any) {
      console.error("❌ Registration error:", err);
      const msg = err.message || "Registration failed. Please try again.";
      setError(msg);
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, [saveAuth, router]);

  // Google OAuth Login (credential = ID token)
  const loginWithGoogle = useCallback(async (credential: string) => {
    setIsLoading(true);
    setError(null);
    try {
      const response = await api.post('/auth/google', { credential });
      const payload: any = (response as any)?.data ?? response;
      console.log("Google login response:", payload);
      const receivedToken = payload?.token || payload?.data?.token;
      if (!receivedToken) throw new Error("No token received from server");
      const userData = payload?.user || payload?.data?.user;
      saveAuth(receivedToken, userData);
      setError(null);
    } catch (err: any) {
      const msg = err.message || "Google login failed. Please try again.";
      console.error("Google login error:", msg, err);
      setError(msg);
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, [saveAuth]);

  // Logout
  const logout = useCallback(async () => {
    try {
      setIsLoading(true);
      const token = localStorage.getItem("token");
      
      // Clear local state FIRST (immediate UI update)
      setToken(null);
      setUser(null);
      setError(null);
      localStorage.removeItem("token");
      localStorage.removeItem("requires_profile_completion");
      localStorage.removeItem("guest_wishlist");
      
      // Call logout API to revoke token on backend (non-blocking)
      if (token) {
        api.post('/logout', {}, {
          headers: { Authorization: `Bearer ${token}` },
        }).catch(err => {
          console.error("Logout API error:", err);
          // Ignore API errors - local logout already done
        });
      }
      
      // Dispatch event for other components immediately
      window.dispatchEvent(new Event("auth-state-changed"));
      
      router.push("/");
    } catch (err: any) {
      console.error("Logout error:", err);
    } finally {
      setIsLoading(false);
    }
  }, [router]);

  // Clear error
  const clearError = useCallback(() => {
    setError(null);
  }, []);

  return {
    user,
    token,
    isAuthenticated: !!token,
    isLoading,
    error,
    loginWithEmail,
    sendOtp,
    verifyOtp,
    register,
    loginWithGoogle,
    completeProfile,
    logout,
    clearError,
  };
};
