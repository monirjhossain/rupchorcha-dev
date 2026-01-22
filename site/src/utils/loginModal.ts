/**
 * Global Login Modal Utility
 * Use this to open the login modal from anywhere in the app
 */

export const openLoginModal = () => {
  if (typeof window !== 'undefined') {
    window.dispatchEvent(new CustomEvent('open-login-modal'));
  }
};

export const closeLoginModal = () => {
  if (typeof window !== 'undefined') {
    window.dispatchEvent(new CustomEvent('close-login-modal'));
  }
};
