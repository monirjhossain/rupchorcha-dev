// Simple cartStorage utility for demo
export const cartStorage = {
  getCart() {
    if (typeof window === 'undefined') return { items: [] };
    try {
      const cart = localStorage.getItem('cart');
      return cart ? JSON.parse(cart) : { items: [] };
    } catch {
      return { items: [] };
    }
  },
  removeItem(itemId: number) {
    if (typeof window === 'undefined') return;
    const cart = cartStorage.getCart();
    cart.items = cart.items.filter((item: any) => item.id !== itemId);
    localStorage.setItem('cart', JSON.stringify(cart));
  },
  updateQuantity(itemId: number, quantity: number) {
    if (typeof window === 'undefined') return;
    const cart = cartStorage.getCart();
    cart.items = cart.items.map((item: any) =>
      item.id === itemId ? { ...item, quantity } : item
    );
    localStorage.setItem('cart', JSON.stringify(cart));
  },
};
