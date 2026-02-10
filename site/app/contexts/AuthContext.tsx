"use client";

import React, { createContext, useContext, useState, useCallback, useEffect } from "react";
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
  
  loginWithEmail: (email: string, password: string) => Promise<void>;
  sendOtp: (phone: string) => Promise<void>;
  verifyOtp: (phone: string, otp: string) => Promise<void>;
  register: (data: { name: string; email: string; phone: string; password: string; password_confirmation: string }) => Promise<void>;
  loginWithGoogle: (credential: string) => Promise<void>;
  logout: () => void;
  clearError: () => void;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

const API_BASE = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api";

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [token, setToken] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const router = useRouter();

  // Load token from localStorage on mount
  useEffect(() => {
    const savedToken = localStorage.getItem("token");
    if (savedToken) {
      setToken(savedToken);
      fetchUserProfile(savedToken);
    }
  }, []);

  // Fetch user profile (defensive: clear token on failure)
  const fetchUserProfile = useCallback(async (authToken: string) => {
    setIsLoading(true);
    try {
      const res = await fetch(`${API_BASE}/profile`, {
        headers: { Authorization: `Bearer ${authToken}` },
      });
      if (res.ok) {
        const data = await res.json();
        // Backend returns { success, data: UserResource }
        const userData = data?.data?.user || data?.data || data?.user || null;
        if (userData) {
          setUser(userData);
          console.log("Profile loaded:", userData);
        } else {
          // If response shape is unexpected, treat as invalid session
          localStorage.removeItem("token");
          setToken(null);
          setUser(null);
          setError("Session expired. Please log in again.");
        }
      } else {
        localStorage.removeItem("token");
        setToken(null);
        setUser(null);
        setError("Session expired. Please log in again.");
      }
    } catch (err) {
      console.error("Failed to fetch profile:", err);
      localStorage.removeItem("token");
      setToken(null);
      setUser(null);
      setError("Could not reach server. Please try again.");
    } finally {
      setIsLoading(false);
    }
  }, []);

  const saveAuth = useCallback(async (newToken: string, userData?: User) => {
    localStorage.setItem("token", newToken);
    setToken(newToken);
    if (userData) {
      setUser(userData); // instant context update
    } else {
      await fetchUserProfile(newToken); // fallback if userData missing
    }
    window.dispatchEvent(new Event('auth-state-changed'));
  }, [fetchUserProfile]);

  // Email/Password Login
  const loginWithEmail = useCallback(async (email: string, password: string) => {
    setIsLoading(true);
    setError(null);
    try {
      const response = await api.post("/login", { email, password });
      const payload: any = (response as any)?.data ?? response;
      console.log("Email login successful:", payload);
      const token = payload?.token || payload?.data?.token;
      const userData = payload?.user || payload?.data?.user;
      if (!token) {
        throw new Error("No token received from server");
      }
      await saveAuth(token, userData);
      setError(null);
    } catch (err: any) {
      const msg = err?.message || "Login failed. Please try again.";
      setError(msg);
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, [saveAuth]);

  // Send OTP
  const sendOtp = useCallback(async (phone: string) => {
    setIsLoading(true);
    setError(null);
    try {
      const res = await fetch(`${API_BASE}/send-otp`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ phone }),
      });

      if (!res.ok) {
        let message = "Failed to send OTP";
        try {
          const data = await res.json();
          message = data?.message || message;
        } catch (parseError) {
          console.warn("Send OTP error response is not valid JSON", parseError);
        }
        throw new Error(message);
      }

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
      const res = await fetch(`${API_BASE}/verify-otp`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ phone, otp }),
      });

      if (!res.ok) {
        let message = "OTP verification failed";
        try {
          const data = await res.json();
          message = data?.message || message;
        } catch (parseError) {
          console.warn("Verify OTP error response is not valid JSON", parseError);
        }
        throw new Error(message);
      }

      const data = await res.json();
      console.log("OTP verification successful:", data);
      saveAuth(data.token, data.user);
      setError(null);
    } catch (err: any) {
      const msg = err.message || "OTP verification failed. Please try again.";
      setError(msg);
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, [saveAuth]);

  // Register
  const register = useCallback(async (data: {
    name: string;
    email: string;
    phone: string;
    password: string;
    password_confirmation: string;
  }) => {
    setIsLoading(true);
    setError(null);
    try {
      const res = await fetch(`${API_BASE}/register`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });

      if (!res.ok) {
        let message = "Registration failed";
        try {
          const responseData = await res.json();
          message = responseData?.message || message;
        } catch (parseError) {
          console.warn("Register error response is not valid JSON", parseError);
        }
        throw new Error(message);
      }

      const responseData = await res.json();
      console.log("Register response:", responseData);
      
      // Handle both direct response and wrapped response
      const token = responseData.token || responseData.data?.token;
      const user = responseData.user || responseData.data?.user;
      
      if (!token) {
        console.error("No token in register response:", responseData);
        throw new Error("Token not received from server");
      }
      saveAuth(token, user);
      setError(null);
    } catch (err: any) {
      const msg = err.message || "Registration failed. Please try again.";
      console.error("Register error:", msg, err);
      setError(msg);
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, [saveAuth]);

  // Google OAuth Login
  const loginWithGoogle = useCallback(async (credential: string) => {
    setIsLoading(true);
    setError(null);
    try {
      const res = await fetch(`${API_BASE}/auth/google`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ credential }),
      });

      if (!res.ok) {
        let message = "Google login failed";
        try {
          const responseData = await res.json();
          message = responseData?.message || message;
        } catch (parseError) {
          console.warn("Google login error response is not valid JSON", parseError);
        }
        throw new Error(message);
      }

      const responseData = await res.json();
      console.log("Google login response:", responseData);
      
      if (responseData.token) {
        saveAuth(responseData.token, responseData.user);
        console.log("Token saved successfully");
        setError(null);
      } else {
        throw new Error("No token received from server");
      }
    } catch (err: any) {
      const msg = err.message || "Google login failed. Please try again.";
      console.error("Google login error:", msg);
      setError(msg);
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, [saveAuth]);

  // Logout
  const logout = useCallback(() => {
    localStorage.removeItem("token");
    setToken(null);
    setUser(null);
    setError(null);
    window.dispatchEvent(new Event('auth-state-changed'));
    router.replace("/"); // instant redirect
  }, [router]);

  // Clear error
  const clearError = useCallback(() => {
    setError(null);
  }, []);

  const value: AuthContextType = {
    user,
    token,
    isAuthenticated: !!token && !!user,
    isLoading,
    error,
    loginWithEmail,
    sendOtp,
    verifyOtp,
    register,
    loginWithGoogle,
    logout,
    clearError,
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};

export const useAuth = (): AuthContextType => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error("useAuth must be used within an AuthProvider");
  }
  return context;
};
