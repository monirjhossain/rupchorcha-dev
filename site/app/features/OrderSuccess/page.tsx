
"use client";
import React, { useEffect, useState } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import Link from "next/link";
import "../OrderSuccess/OrderSuccess.css";

const OrderSuccessPage = () => {
  const router = useRouter();
  const searchParams = useSearchParams();
  const [orderData, setOrderData] = useState<any>(null);

  useEffect(() => {
    // Try to get order data from localStorage (set after order placement)
    let data = null;
    if (typeof window !== "undefined") {
      const stored = localStorage.getItem("order_success_data");
      if (stored) {
        data = JSON.parse(stored);
        setOrderData(data);
      }
    }
    // If no data, redirect to home
    if (!data) {
      router.push("/");
    }
  }, [router]);

  if (!orderData) {
    return null;
  }

  return (
    <div className="order-success-page">
      <div className="container">
        <div className="success-card">
          <div className="success-icon">
            <svg viewBox="0 0 52 52" xmlns="http://www.w3.org/2000/svg">
              <circle className="success-circle" cx="26" cy="26" r="25" fill="none" />
              <path className="success-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
            </svg>
          </div>
          <h1>Order Placed Successfully!</h1>
          <p className="success-message">
            Thank you for your order. We've received your order and will process it soon.
          </p>
          <div className="order-details">
            <div className="order-number">
              <span className="label">Order Number:</span>
              <span className="value">#{orderData.order_number || 'PROCESSING'}</span>
            </div>
            <div className="order-summary-box">
              <h3>Order Summary</h3>
              <div className="summary-items">
                {orderData.items && orderData.items.map((item: any, index: number) => (
                  <div key={index} className="summary-item">
                    <div className="item-details">
                      <span className="item-name">{item.name}</span>
                      <span className="item-qty">x {item.quantity}</span>
                    </div>
                    <span className="item-price">৳ {(parseFloat(item.price) * item.quantity).toFixed(2)}</span>
                  </div>
                ))}
              </div>
              <div className="summary-totals">
                <div className="total-row">
                  <span>Subtotal:</span>
                  <span>৳ {orderData.subtotal?.toFixed(2)}</span>
                </div>
                <div className="total-row">
                  <span>Shipping:</span>
                  <span>৳ {orderData.shipping_cost?.toFixed(2)}</span>
                </div>
                <div className="total-row grand-total">
                  <span>Total:</span>
                  <span>৳ {orderData.total?.toFixed(2)}</span>
                </div>
              </div>
            </div>
            <div className="delivery-info">
              <h3>Delivery Information</h3>
              <p><strong>Name:</strong> {orderData.customer?.firstName} {orderData.customer?.lastName}</p>
              <p><strong>Phone:</strong> {orderData.customer?.phone}</p>
              <p><strong>Email:</strong> {orderData.customer?.email}</p>
              <p><strong>Address:</strong> {orderData.customer?.address}</p>
              <p><strong>District:</strong> {orderData.customer?.district}</p>
              <p><strong>Payment Method:</strong> {orderData.payment_method === 'bkash' ? 'Bkash' : 'Cash on Delivery'}</p>
            </div>
            <div className="next-steps">
              <h3>What's Next?</h3>
              <ul>
                <li>You will receive a confirmation email shortly</li>
                <li>We'll notify you when your order is shipped</li>
                <li>Expected delivery: {orderData.estimated_delivery || '3-5 business days'}</li>
              </ul>
            </div>
          </div>
          <div className="action-buttons">
            <Link href="/" className="btn-primary">Continue Shopping</Link>
            <Link href="/orders" className="btn-secondary">View My Orders</Link>
          </div>
        </div>
      </div>
    </div>
  );
};

export default OrderSuccessPage;
