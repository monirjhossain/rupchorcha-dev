// Simple wishlistStorage utility for demo
export const wishlistStorage = {
  getWishlist() {
    if (typeof window === 'undefined') return { items: [] };
    try {
      const wishlist = localStorage.getItem('wishlist');
      return wishlist ? JSON.parse(wishlist) : { items: [] };
    } catch {
      return { items: [] };
    }
  },
  addItem(item) {
    if (typeof window === 'undefined') return;
    const wishlist = wishlistStorage.getWishlist();
    if (!wishlist.items.find((i) => i.id === item.id)) {
      wishlist.items.push(item);
      localStorage.setItem('wishlist', JSON.stringify(wishlist));
    }
  },
  removeItem(itemId) {
    if (typeof window === 'undefined') return;
    const wishlist = wishlistStorage.getWishlist();
    wishlist.items = wishlist.items.filter((item) => item.id !== itemId);
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
  },
};
