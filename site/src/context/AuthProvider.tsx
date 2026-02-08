"use client";
import React, { createContext, useContext, useState, useEffect, useCallback } from "react";
import { useRouter } from "next/navigation";
import { api } from "@/src/services/apiClient";

export interface User {
  id: number;
  name: string;
  email: string;
  phone?: string;
  role?: string;
}

interface AuthContextType {
  user: User | null;
  token: string | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  error: string | null;
  login: (email: string, password: string) => Promise<void>;
  register: (data: { name: string; email: string; phone: string; password: string; password_confirmation: string }) => Promise<void>;
  loginWithGoogle: (googleToken: string) => Promise<void>;
  logout: () => void;
  clearError: () => void;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

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

  // Listen for auth changes and storage updates
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
    window.addEventListener('auth-state-changed', syncAuth);
    window.addEventListener('storage', syncAuth);
    return () => {
      window.removeEventListener('auth-state-changed', syncAuth);
      window.removeEventListener('storage', syncAuth);
    };
  }, []);

  const fetchUserProfile = useCallback(async (authToken: string) => {
    try {
      const response = await api.get('/profile', {
        headers: { Authorization: `Bearer ${authToken}` },
      });
      const payload: any = response?.data ?? response;
      setUser(payload?.user || payload?.data?.user || null);
    } catch (err) {
      localStorage.removeItem("token");
      setToken(null);
      setUser(null);
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
    window.dispatchEvent(new Event("auth-state-changed"));
  }, [fetchUserProfile]);

  const login = useCallback(async (email: string, password: string) => {
    setIsLoading(true);
    setError(null);
    try {
      const response = await api.post('/login', { email, password });
      const payload: any = response?.data ?? response;
      const token = payload?.token || payload?.data?.token;
      const userData = payload?.user || payload?.data?.user;
      if (!token) throw new Error("No token received from server");
      saveAuth(token, userData);
      setError(null);
      window.dispatchEvent(new Event('auth-state-changed'));
      router.refresh();
    } catch (err: any) {
      setError(err.message || "Login failed. Please try again.");
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, [saveAuth, router]);

  const register = useCallback(async (data: { name: string; email: string; phone: string; password: string; password_confirmation: string }) => {
    setIsLoading(true);
    setError(null);
    try {
      const response = await api.post('/register', data);
      const responseData: any = response?.data ?? response;
      const token = responseData?.token || responseData?.data?.token;
      const user = responseData?.user || responseData?.data?.user;
      if (!token) throw new Error("No token received from server");
      saveAuth(token, user);
      setError(null);
      router.refresh();
      router.push("/");
    } catch (err: any) {
      setError(err.message || "Registration failed. Please try again.");
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, [saveAuth, router]);

  const logout = useCallback(() => {
    setIsLoading(true);
    setToken(null);
    setUser(null);
    setError(null);
    localStorage.removeItem("token");
    window.dispatchEvent(new Event("auth-state-changed"));
    router.refresh();
    router.push("/");
    setIsLoading(false);
  }, [router]);

  const clearError = useCallback(() => {
    setError(null);
  }, []);

  // Google login handler
  const loginWithGoogle = useCallback(async (googleToken: string) => {
    setIsLoading(true);
    setError(null);
    try {
      // Call backend endpoint for Google login
      const response = await api.post('/login/google', { token: googleToken });
      const payload: any = response?.data ?? response;
      const token = payload?.token || payload?.data?.token;
      const userData = payload?.user || payload?.data?.user;
      if (!token) throw new Error("No token received from server");
      saveAuth(token, userData);
      setError(null);
      router.refresh();
    } catch (err: any) {
      setError(err.message || "Google login failed. Please try again.");
      throw err;
    } finally {
      setIsLoading(false);
    }
  }, [saveAuth, router]);

  return (
    <AuthContext.Provider value={{ user, token, isAuthenticated: !!token, isLoading, error, login, register, loginWithGoogle, logout, clearError }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuthContext = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error("useAuthContext must be used within an AuthProvider");
  }
  return context;
};
