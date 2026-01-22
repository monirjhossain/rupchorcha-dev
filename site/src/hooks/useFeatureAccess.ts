/**
 * Hook to check if a feature is accessible based on config and auth status
 */

import { useCallback } from "react";
import { useRouter } from "next/navigation";
import FEATURE_CONFIG from "@/app/config/features";

export const useFeatureAccess = () => {
  const router = useRouter();

  const isAuthenticated = useCallback(() => {
    if (typeof window === "undefined") return false;
    return !!localStorage.getItem("token");
  }, []);

  const canAccessCart = useCallback(() => {
    if (FEATURE_CONFIG.ALLOW_GUEST_CART) return true;
    return isAuthenticated();
  }, [isAuthenticated]);

  const canAccessWishlist = useCallback(() => {
    if (FEATURE_CONFIG.ALLOW_GUEST_WISHLIST) return true;
    return isAuthenticated();
  }, [isAuthenticated]);

  const canAccessCheckout = useCallback(() => {
    if (FEATURE_CONFIG.ALLOW_GUEST_CHECKOUT) return true;
    return isAuthenticated();
  }, [isAuthenticated]);

  const requireLogin = useCallback((feature: string) => {
    if (!isAuthenticated()) {
      openLoginModal();
      throw new Error(`Please login to access ${feature}`);
    }
  }, [isAuthenticated, router]);

  const checkFeatureAccess = useCallback(
    (feature: "cart" | "wishlist" | "checkout"): boolean => {
      if (feature === "cart") return canAccessCart();
      if (feature === "wishlist") return canAccessWishlist();
      if (feature === "checkout") return canAccessCheckout();
      return false;
    },
    [canAccessCart, canAccessWishlist, canAccessCheckout]
  );

  return {
    isAuthenticated: isAuthenticated(),
    canAccessCart: canAccessCart(),
    canAccessWishlist: canAccessWishlist(),
    canAccessCheckout: canAccessCheckout(),
    checkFeatureAccess,
    requireLogin,
    featureMode: FEATURE_CONFIG.MODE,
  };
};
