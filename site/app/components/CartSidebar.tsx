"use client";
import React, { useState, useEffect } from "react";
import Link from "next/link";
import { cartStorage } from "@/app/utils/cartStorage";
import styles from "./CartSidebar.module.css";

interface CartSidebarProps {
  isOpen: boolean;
  onClose: () => void;
  updateCartCount?: () => void;
}

interface CartItem {
  id: number;
  name: string;
  price: number;
  quantity: number;
  image?: string;
}

const CartSidebar: React.FC<CartSidebarProps> = ({ isOpen, onClose, updateCartCount }) => {
  const [cartItems, setCartItems] = useState<CartItem[]>([]);
  const [subtotal, setSubtotal] = useState(0);

  useEffect(() => {
    if (isOpen) {
      fetchCart();
    }
  }, [isOpen]);

  useEffect(() => {
    calculateSubtotal();
  }, [cartItems]);

  const fetchCart = () => {
    const cart = cartStorage.getCart();
    if (cart.items && Array.isArray(cart.items)) {
      setCartItems(cart.items);
    }
  };

  const calculateSubtotal = () => {
    const total = cartItems.reduce((sum, item) => {
      const price = parseFloat(item.price as any || 0);
      const quantity = parseInt(item.quantity as any || 0);
      return sum + price * quantity;
    }, 0);
    setSubtotal(total);
  };

  const removeItem = (itemId: number) => {
    try {
      cartStorage.removeItem(itemId);
      setCartItems(prevItems => prevItems.filter(item => item.id !== itemId));
      if (updateCartCount) updateCartCount();
    } catch (error) {
      console.error("Error removing item:", error);
    }
  };

  const updateQuantity = (itemId: number, newQuantity: number) => {
    if (newQuantity < 1) return;
    try {
      cartStorage.updateQuantity(itemId, newQuantity);
      setCartItems(prevItems =>
        prevItems.map(item =>
          item.id === itemId ? { ...item, quantity: newQuantity } : item
        )
      );
      if (updateCartCount) updateCartCount();
    } catch (error) {
      console.error("Error updating quantity:", error);
    }
  };

  if (!isOpen) return null;

  return (
    <aside className={styles.cartSidebar} style={{position:'fixed',top:0,right:0,width:370,maxWidth:'100vw',height:'100vh',background:'#fff',boxShadow:'-4px 0 24px #0002',zIndex:9999,display:'flex',flexDirection:'column',borderLeft:'1px solid #f2f2f2',transition:'right 0.2s'}}>
      <div style={{display:'flex',alignItems:'center',justifyContent:'space-between',padding:'2rem 1.5rem 1.2rem 1.5rem',borderBottom:'1px solid #eee',boxShadow:'0 2px 8px #0001'}}>
        <h2 style={{fontSize:'1.3rem',fontWeight:800,color:'#222'}}>Your Cart</h2>
        <button onClick={onClose} style={{background:'none',border:'none',fontSize:'1.7rem',cursor:'pointer',color:'#e91e63',fontWeight:700,lineHeight:1}}>×</button>
      </div>
      <div style={{flex:1,overflowY:'auto',padding:'1.2rem 1.5rem 1.2rem 1.5rem'}}>
        {cartItems.length === 0 ? (
          <div style={{color:'#888',fontSize:'1.1rem',textAlign:'center',marginTop:'3rem'}}>Your cart is empty.</div>
        ) : (
          cartItems.map(item => (
            <div key={item.id} style={{display:'flex',alignItems:'center',gap:'1rem',marginBottom:'1.5rem',background:'#fafafd',borderRadius:12,padding:'0.9rem 1.1rem',boxShadow:'0 2px 8px #0001',border:'1px solid #f2f2f2',position:'relative'}}>
              <img src={item.image} alt={item.name} style={{width:64,height:64,borderRadius:10,objectFit:'cover',boxShadow:'0 2px 8px #0001',border:'1.5px solid #eee'}} />
              <div style={{flex:1}}>
                <div style={{fontWeight:700,color:'#222',fontSize:'1.08rem',marginBottom:'0.3rem'}}>{item.name}</div>
                <div style={{fontWeight:700,color:'#e91e63',fontSize:'1.12rem',marginBottom:'0.2rem'}}>৳ {item.price}</div>
                <div style={{display:'flex',alignItems:'center',gap:'0.5rem'}}>
                  <button onClick={() => updateQuantity(item.id, item.quantity - 1)} style={{background:'#fff',border:'1.5px solid #e91e63',color:'#e91e63',borderRadius:6,width:28,height:28,fontWeight:700,fontSize:'1.1rem',cursor:'pointer',boxShadow:'0 1px 4px #e91e6322'}}>–</button>
                  <span style={{fontWeight:700,fontSize:'1.08rem',color:'#222',minWidth:24,textAlign:'center'}}>{item.quantity}</span>
                  <button onClick={() => updateQuantity(item.id, item.quantity + 1)} style={{background:'#fff',border:'1.5px solid #e91e63',color:'#e91e63',borderRadius:6,width:28,height:28,fontWeight:700,fontSize:'1.1rem',cursor:'pointer',boxShadow:'0 1px 4px #e91e6322'}}>+</button>
                </div>
              </div>
              <button onClick={() => removeItem(item.id)} style={{background:'none',border:'none',color:'#e91e63',fontWeight:700,fontSize:'1.3rem',cursor:'pointer',position:'absolute',top:10,right:10}}>×</button>
            </div>
          ))
        )}
      </div>
      <div style={{borderTop:'1px solid #eee',padding:'1.5rem',background:'#fff',boxShadow:'0 -2px 8px #0001',position:'sticky',bottom:0}}>
        <div style={{display:'flex',alignItems:'center',justifyContent:'space-between',fontWeight:800,fontSize:'1.18rem',marginBottom:'1.2rem'}}>
          <span>Total</span>
          <span style={{color:'#e91e63'}}>৳ {subtotal}</span>
        </div>
        <Link href="/cart" style={{width:'100%',display:'block',background:'#e91e63',color:'#fff',fontWeight:800,padding:'1rem 0',borderRadius:10,border:'none',fontSize:'1.18rem',cursor:'pointer',boxShadow:'0 2px 8px #e91e6322',textAlign:'center',transition:'all 0.2s',textDecoration:'none'}}>Checkout</Link>
      </div>
    </aside>
  );
};

export default CartSidebar;
