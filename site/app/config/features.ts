/**
 * Feature Access Configuration
 * 
 * MODE = 1: Only logged-in users can access features (cart, wishlist, checkout)
 * MODE = 2: Both guest and logged-in users can access all features
 */

export const FEATURE_MODE = 2; // Change this between 1 and 2

export const FEATURE_CONFIG = {
  // Mode 1: Only logged-in users
  // Mode 2: Both guest and logged-in users
  MODE: FEATURE_MODE,

  // Feature flags based on mode
  ALLOW_GUEST_CART: FEATURE_MODE === 2,
  ALLOW_GUEST_WISHLIST: FEATURE_MODE === 2,
  ALLOW_GUEST_CHECKOUT: FEATURE_MODE === 2,

  // Messages
  MESSAGES: {
    LOGIN_REQUIRED_CART: "Please login to add items to cart",
    LOGIN_REQUIRED_WISHLIST: "Please login to add items to wishlist",
    LOGIN_REQUIRED_CHECKOUT: "Please login to complete your order",
  },
};

export default FEATURE_CONFIG;
