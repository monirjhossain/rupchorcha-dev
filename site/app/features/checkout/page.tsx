
"use client";
import React, { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { cartStorage } from "../../utils/cartStorage";
import "./Checkout.css";

const CheckoutPage = ({ updateCartCount }: { updateCartCount?: () => void }) => {
  const router = useRouter();
  const [cartItems, setCartItems] = useState<any[]>([]);
  const [subtotal, setSubtotal] = useState(0);
  const [shippingMethod, setShippingMethod] = useState("inside_dhaka");
  const [shippingCost, setShippingCost] = useState(0);
  const [loading, setLoading] = useState(false);
  const [shippingMethods, setShippingMethods] = useState<any[]>([]);
  const [freeShippingThreshold, setFreeShippingThreshold] = useState(3000);
  const [freeShippingEnabled, setFreeShippingEnabled] = useState(true);

  // Bangladesh Districts (excluding Dhaka)
  const districts = [
    "Dhaka", "Bagerhat", "Bandarban", "Barguna", "Barisal", "Bhola", "Bogra", "Brahmanbaria",
    "Chandpur", "Chapainawabganj", "Chittagong", "Chuadanga", "Comilla", "Cox's Bazar",
    "Dinajpur", "Faridpur", "Feni", "Gaibandha", "Gazipur", "Gopalganj", "Habiganj",
    "Jamalpur", "Jessore", "Jhalokati", "Jhenaidah", "Joypurhat", "Khagrachari",
    "Khulna", "Kishoreganj", "Kurigram", "Kushtia", "Lakshmipur", "Lalmonirhat",
    "Madaripur", "Magura", "Manikganj", "Meherpur", "Moulvibazar", "Munshiganj",
    "Mymensingh", "Naogaon", "Narail", "Narayanganj", "Narsingdi", "Natore",
    "Netrokona", "Nilphamari", "Noakhali", "Pabna", "Panchagarh", "Patuakhali",
    "Pirojpur", "Rajbari", "Rajshahi", "Rangamati", "Rangpur", "Satkhira",
    "Shariatpur", "Sherpur", "Sirajganj", "Sunamganj", "Sylhet", "Tangail",
    "Thakurgaon"
  ];

  // Form data
  const [formData, setFormData] = useState({
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
    address: "",
    district: "",
    state: "",
    zipCode: "",
    paymentMethod: "bkash",
  });

  const shippingMethodsOld = [
    {
      id: "inside_dhaka",
      title: "Inside Dhaka",
      price: 70,
      deliveryTime: "1-2 days",
    },
    {
      id: "outside_dhaka",
      title: "Outside Dhaka",
      price: 130,
      deliveryTime: "3-5 days",
    },
  ];

  useEffect(() => {
    loadCart();
    loadShippingMethods();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    calculateSubtotal();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [cartItems]);

  useEffect(() => {
    calculateShipping();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [shippingMethod, subtotal, shippingMethods, freeShippingEnabled, freeShippingThreshold]);

  const loadShippingMethods = async () => {
    try {
      const response = await fetch("http://localhost/Ecommerce/backend/public/api/shipping/methods");
      const result = await response.json();
      if (result.success && result.data) {
        const methodsArray = result.data;
        const methods = methodsArray.map((method: any) => ({
          id: method.code || method.id,
          title: method.title,
          price: parseFloat(method.price),
          deliveryTime: method.delivery_time,
        }));
        setShippingMethods(methods);
        if (methods.length > 0) {
          setShippingMethod(methods[0].id);
        }
      }
    } catch (error) {
      setShippingMethods(shippingMethodsOld);
    }
  };

  const loadCart = () => {
    const cart = cartStorage.getCart();
    if (cart.items && cart.items.length > 0) {
      setCartItems(cart.items);
    } else {
      router.push("/cart");
    }
  };

  const calculateSubtotal = () => {
    const total = cartItems.reduce((sum, item) => sum + parseFloat(item.price) * item.quantity, 0);
    setSubtotal(total);
  };

  const calculateShipping = () => {
    if (freeShippingEnabled && subtotal >= freeShippingThreshold) {
      setShippingCost(0);
    } else {
      const method = shippingMethods.find((m) => m.id === shippingMethod);
      setShippingCost(method ? method.price : 0);
    }
  };

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
    if (name === "district") {
      if (value === "Dhaka") {
        setShippingMethod("inside_dhaka");
      } else if (value !== "") {
        setShippingMethod("outside_dhaka");
      }
    }
  };

  const handleShippingChange = (methodId: string) => {
    setShippingMethod(methodId);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    try {
      const orderData = {
        customer: formData,
        items: cartItems.map((item) => ({
          product_id: item.id,
          name: item.name,
          quantity: item.quantity,
          price: parseFloat(item.price),
          image: item.image,
        })),
        subtotal: subtotal,
        shipping_method: shippingMethod,
        shipping_cost: shippingCost,
        total: subtotal + shippingCost,
        payment_method: formData.paymentMethod,
        district: formData.district,
      };
      const response = await fetch("http://localhost/Ecommerce/backend/public/api/orders", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(orderData),
      });
      const result = await response.json();
      if (response.ok && result.success) {
        cartStorage.clearCart();
        if (updateCartCount) updateCartCount();
        // Redirect to success page with order data
        router.push("/OrderSuccess");
      } else {
        throw new Error(result.message || "Failed to place order");
      }
    } catch (error: any) {
      alert("Failed to place order: " + error.message);
    } finally {
      setLoading(false);
    }
  };

  const total = subtotal + shippingCost;

  return (
    <div className="checkout-page">
      <div className="container">
        {/* Progress Steps */}
        <div className="cart-progress">
          <div className="progress-step completed">
            <div className="step-number">✓</div>
            <div className="step-label">Shopping Cart</div>
          </div>
          <div className="progress-line active"></div>
          <div className="progress-step active">
            <div className="step-number">2</div>
            <div className="step-label">Shipping and Checkout</div>
          </div>
          <div className="progress-line"></div>
          <div className="progress-step">
            <div className="step-number">3</div>
            <div className="step-label">Confirmation</div>
          </div>
        </div>
        <form onSubmit={handleSubmit} className="checkout-content">
          {/* Left Side - Checkout Form */}
          <div className="checkout-form-section">
            <h2>Billing Details</h2>
            <div className="form-row">
              <div className="form-group">
                <label>First Name *</label>
                <input type="text" name="firstName" value={formData.firstName} onChange={handleInputChange} required />
              </div>
              <div className="form-group">
                <label>Last Name *</label>
                <input type="text" name="lastName" value={formData.lastName} onChange={handleInputChange} required />
              </div>
            </div>
            <div className="form-group">
              <label>Email Address *</label>
              <input type="email" name="email" value={formData.email} onChange={handleInputChange} required />
            </div>
            <div className="form-group">
              <label>Phone Number *</label>
              <input type="tel" name="phone" value={formData.phone} onChange={handleInputChange} required />
            </div>
            <div className="form-group">
              <label>Address *</label>
              <input type="text" name="address" value={formData.address} onChange={handleInputChange} placeholder="House number and street name" required />
            </div>
            <div className="form-row">
              <div className="form-group">
                <label>District *</label>
                <select name="district" value={formData.district} onChange={handleInputChange} required>
                  <option value="">Select District</option>
                  {districts.map((district) => (
                    <option key={district} value={district}>
                      {district}
                    </option>
                  ))}
                </select>
              </div>
              <div className="form-group">
                <label>State / Division</label>
                <input type="text" name="state" value={formData.state} onChange={handleInputChange} />
              </div>
            </div>
            <div className="form-group">
              <label>Zip Code</label>
              <input type="text" name="zipCode" value={formData.zipCode} onChange={handleInputChange} />
            </div>
            {/* Payment Method */}
            <div className="payment-methods-section">
              <h3>Payment Method</h3>
              <div className="payment-method-option">
                <input type="radio" id="bkash" name="paymentMethod" value="bkash" checked={formData.paymentMethod === "bkash"} onChange={handleInputChange} />
                <label htmlFor="bkash">
                  <span>Bkash</span>
                  <p className="payment-description">Pay with Bkash mobile payment</p>
                </label>
              </div>
              <div className="payment-method-option">
                <input type="radio" id="cod" name="paymentMethod" value="cod" checked={formData.paymentMethod === "cod"} onChange={handleInputChange} />
                <label htmlFor="cod">
                  <span>Cash on Delivery</span>
                  <p className="payment-description">Pay with cash upon delivery</p>
                </label>
              </div>
            </div>
          </div>
          {/* Right Side - Order Summary */}
          <div className="checkout-summary-section">
            <div className="order-summary">
              <h3>Your Order</h3>
              <div className="summary-items">
                {cartItems.map((item) => (
                  <div key={item.id} className="summary-item">
                    <div className="summary-item-image">
                      <img src={item.image || "https://via.placeholder.com/50"} alt={item.name} />
                    </div>
                    <div className="summary-item-details">
                      <h4>{item.name}</h4>
                      <p>Qty: {item.quantity}</p>
                    </div>
                    <div className="summary-item-price">৳ {(parseFloat(item.price) * item.quantity).toFixed(2)}</div>
                  </div>
                ))}
              </div>
              <div className="summary-totals">
                <div className="summary-row">
                  <span>Subtotal:</span>
                  <span>৳ {subtotal.toFixed(2)}</span>
                </div>
                <div className="summary-row">
                  <span>Shipping:</span>
                  <span>{shippingCost === 0 ? "FREE" : `৳ ${shippingCost.toFixed(2)}`}</span>
                </div>
                <div className="summary-row total">
                  <span>Total:</span>
                  <span>৳ {total.toFixed(2)}</span>
                </div>
              </div>
              <button type="submit" className="place-order-btn" disabled={loading}>
                {loading ? "Processing..." : "Place Order"}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  );
};

export default CheckoutPage;
