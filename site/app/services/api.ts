// Auth API for password reset
export const authAPI = {
  forgotPassword: async (email: string) => {
    const res = await fetch(`${API_BASE_URL}/forgot-password`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email }),
    });
    return await res.json();
  },
  resetPassword: async (email: string, token: string, password: string, password_confirmation: string) => {
    const res = await fetch(`${API_BASE_URL}/reset-password`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email, token, password, password_confirmation }),
    });
    return await res.json();
  },
};
// Centralized API service for frontend-backend connection
export const API_BASE_URL = "http://127.0.0.1:8000/api";

export const productsAPI = {
  getAll: async (page = 1, perPage = 12, params: any = {}) => {
    const url = new URL(`${API_BASE_URL}/products`);
    url.searchParams.append("page", page.toString());
    url.searchParams.append("per_page", perPage.toString());
    Object.keys(params).forEach(key => {
      if (params[key] !== undefined && params[key] !== "") {
        url.searchParams.append(key, params[key]);
      }
    });
    const res = await fetch(url.toString());
    return await res.json();
  },
};

export const categoriesAPI = {
  getAll: async () => {
    const res = await fetch(`${API_BASE_URL}/categories`);
    return await res.json();
  },
};

export const brandsAPI = {
  getAll: async () => {
    const res = await fetch(`${API_BASE_URL}/brands`);
    return await res.json();
  },
};
