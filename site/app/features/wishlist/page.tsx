
"use client";
import React, { useState, useEffect } from "react";
import Header from "../components/Header";
import Link from "next/link";
import { wishlistStorage } from "../../utils/wishlistStorage";
import { cartStorage } from "../../utils/cartStorage";
import "./Wishlist.css";

const WishlistPage = ({ updateCartCount, updateWishlistCount }: { updateCartCount?: (count?: number) => void, updateWishlistCount?: () => void }) => {
  const [wishlistItems, setWishlistItems] = useState<any[]>([]);
  const [showToast, setShowToast] = useState(false);
  const [toastMessage, setToastMessage] = useState("");

  useEffect(() => {
    loadWishlist();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const loadWishlist = () => {
    const items = wishlistStorage.getWishlist();
    setWishlistItems(Array.isArray(items.items) ? items.items : []);
  };

  const handleRemove = (productId: number) => {
    wishlistStorage.removeItem(productId);
    loadWishlist();
    if (updateWishlistCount) updateWishlistCount();
    showToastMessage("Removed from wishlist");
  };

  const handleAddToCart = (item: any) => {
    cartStorage.addItem({
      id: item.id,
      name: item.name,
      price: item.special_price || item.price,
      image: item.image,
    }, 1);
    if (updateCartCount) {
      const cart = cartStorage.getCart();
      updateCartCount(cart.count);
    }
    showToastMessage("Added to cart");
  };

  const handleMoveToCart = (item: any) => {
    const result = wishlistStorage.moveToCart(item.id, cartStorage);
    if (result.success) {
      loadWishlist();
      if (updateCartCount) {
        const cart = cartStorage.getCart();
        updateCartCount(cart.count);
      }
      if (updateWishlistCount) updateWishlistCount();
      showToastMessage("Moved to cart");
    }
  };

  const handleClearWishlist = () => {
    if (window.confirm("Are you sure you want to clear your wishlist?")) {
      wishlistStorage.clearWishlist();
      loadWishlist();
      if (updateWishlistCount) updateWishlistCount();
      showToastMessage("Wishlist cleared");
    }
  };

  const showToastMessage = (message: string) => {
    setToastMessage(message);
    setShowToast(true);
    setTimeout(() => setShowToast(false), 3000);
  };

  if (wishlistItems.length === 0) {
    return (
      <>
        <Header />
        <div className="wishlist-page">
          <div className="container">
            <h1>My Wishlist</h1>
            <div className="empty-wishlist">
              <div className="empty-icon">❤️</div>
              <h2>Your wishlist is empty</h2>
              <p>Add products you love to your wishlist</p>
              <Link href="/shop" className="btn-primary">Continue Shopping</Link>
            </div>
          </div>
        </div>
      </>
    );
  }

  return (
    <>
      <Header />
      <div className="wishlist-page">
        {showToast && <div className="toast-notification">{toastMessage}</div>}
        <div className="container">
        <div className="wishlist-header">
          <h1>My Wishlist ({wishlistItems.length} items)</h1>
          <button className="btn-clear-wishlist" onClick={handleClearWishlist}>Clear Wishlist</button>
        </div>
        <div className="wishlist-grid">
          {wishlistItems.map((item) => (
            <div key={item.id} className="wishlist-card">
              <button className="btn-remove" onClick={() => handleRemove(item.id)} title="Remove from wishlist">✕</button>
              <Link href={`/product/${item.id}`} className="product-image-link">
                <img src={item.image || '/placeholder.jpg'} alt={item.name} className="product-image" />
              </Link>
              <div className="product-info">
                <Link href={`/product/${item.id}`}><h3 className="product-name">{item.name}</h3></Link>
                <div className="product-price">
                  {item.special_price ? (
                    <>
                      <span className="sale-price">৳ {item.special_price}</span>
                      <span className="regular-price">৳ {item.price}</span>
                      <span className="discount-badge">{Math.round((1 - item.special_price / item.price) * 100)}% OFF</span>
                    </>
                  ) : (
                    <span className="sale-price">৳ {item.price}</span>
                  )}
                </div>
                <div className="product-actions">
                  <button className="btn-add-to-cart" onClick={() => handleAddToCart(item)}>Add to Cart</button>
                  <button className="btn-move-to-cart" onClick={() => handleMoveToCart(item)}>Move to Cart</button>
                </div>
                <p className="added-date">Added {item.addedAt ? new Date(item.addedAt).toLocaleDateString() : ''}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};
export default WishlistPage;
