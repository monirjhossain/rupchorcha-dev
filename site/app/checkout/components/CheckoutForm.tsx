"use client";
import React, { useState, useMemo } from 'react';
import { useRouter } from 'next/navigation';
import { useCart, CartItem } from '@/app/common/CartContext';
import { useAuth } from '@/src/hooks/useAuth';
import toast from 'react-hot-toast';
import styles from './CheckoutForm.module.css';
import { useCheckout } from '../CheckoutContext';
import { districtList, getAreas } from '../data/bdLocations';
import { openLoginModal } from '@/src/utils/loginModal';
import { getShippingCost } from '../utils/shippingCalculator';

const API_BASE = "http://localhost:8000/api";

interface CheckoutFormData {
  fullName: string;
  email: string;
  phone: string;
  address: string;
  city: string; // District (Zila)
  area: string;
  notes: string;
}

// District list and areas sourced from bdLocations
const cityList = districtList;

export default function CheckoutForm() {
  const router = useRouter();
  const { items, clearCart } = useCart();
  const { user } = useAuth();
  const [loading, setLoading] = useState(false);
  const { updateShippingByArea, selectedShipping, shippingMethods, discountCode, discountAmount } = useCheckout();
  const [paymentMethod, setPaymentMethod] = useState<'cod' | 'bkash' | 'nagad'>('cod');

  const [formData, setFormData] = useState<CheckoutFormData>({
    fullName: user?.name || '',
    email: user?.email || '',
    phone: user?.phone || '',
    address: '',
    city: '',
    area: '',
    notes: '',
  });

  const [errors, setErrors] = useState<Partial<CheckoutFormData>>({});

  // Get areas for selected city
  const availableAreas = useMemo(() => {
    return formData.city ? getAreas(formData.city) : [];
  }, [formData.city]);

  // Calculate cart subtotal
  const subtotal = useMemo(() => {
    return items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  }, [items]);

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
    
    // Clear error when user starts typing
    if (errors[name as keyof CheckoutFormData]) {
      setErrors(prev => ({ ...prev, [name]: undefined }));
    }

    // Reset area when city changes
    if (name === 'city' && value !== formData.city) {
      setFormData(prev => ({ ...prev, area: '' }));
      // Pre-fetch shipping for city (without specific area)
      if (value) {
        updateShippingByArea(value as string, '', subtotal);
      }
    }

    // Auto-select shipping based on area selection
    if (name === 'area' && value && formData.city) {
      updateShippingByArea(formData.city, value, subtotal);
    }
  };

  const validateForm = (): boolean => {
    const newErrors: Partial<CheckoutFormData> = {};

    if (!formData.fullName.trim()) newErrors.fullName = 'Full name is required';
    if (!formData.email.trim() || !/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = 'Valid email is required';
    }
    if (!formData.phone.trim() || formData.phone.length < 11) {
      newErrors.phone = 'Valid phone number is required';
    }
    if (!formData.address.trim()) newErrors.address = 'Address is required';
    if (!formData.city) newErrors.city = 'City is required';
    if (!formData.area) newErrors.area = 'Area is required';

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!validateForm()) {
      toast.error('Please fill in all required fields');
      return;
    }

    // Validate cart is not empty
    if (!items || items.length === 0) {
      toast.error('Cart is empty. Please add items before placing order.');
      return;
    }

    setLoading(true);

    try {
      const token = localStorage.getItem('token');
      const shippingCostValue = getShippingCost(formData.city, formData.area);
      
      const selectedMethod = shippingMethods.find(m => m.id === selectedShipping);
      const orderData = {
        customer_name: formData.fullName,
        customer_email: formData.email,
        customer_phone: formData.phone,
        shipping_address: formData.address,
        city: formData.city,
        area: formData.area,
        notes: formData.notes,
        shipping_method: selectedMethod?.name || selectedShipping || 'standard',
        shipping_cost: shippingCostValue,
        coupon_code: discountCode || null,
        discount_amount: discountAmount || 0,
        payment_method: paymentMethod,
        items: items.map((item: CartItem) => {
          const itemPrice = item.product?.sale_price || item.product?.price || 0;
          console.log(`Item ${item.product_id}: quantity=${item.quantity}, price=${itemPrice}`, item.product);
          return {
            product_id: item.product_id,
            quantity: item.quantity,
            price: Number(itemPrice) || 0,
          };
        }),
      };

      console.log('📤 Submitting order data:', orderData);

      const response = await fetch(`${API_BASE}/orders`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          ...(token && { 'Authorization': `Bearer ${token}` }),
        },
        body: JSON.stringify(orderData),
      });

      let data: any = {};
        console.log('📡 Response received:', {
          status: response.status,
          statusText: response.statusText,
          headers: {
            contentType: response.headers.get('content-type'),
            contentLength: response.headers.get('content-length'),
          }
        });

        const responseText = await response.text();
        console.log('📄 Response text:', responseText);
      
        try {
          data = responseText ? JSON.parse(responseText) : {};
        } catch (jsonError) {
          console.error('❌ Failed to parse response as JSON:', jsonError);
          data = {};
      }

      if (!response.ok) {
        // Extract all validation errors
          let errorMessage = data.message || `HTTP ${response.status}: ${response.statusText}`;
        let detailedErrors: string[] = [];
        
        if (data.errors && typeof data.errors === 'object') {
          const errorMessages = Object.entries(data.errors)
            .flatMap(([field, messages]: [string, any]) => {
              const msgs = Array.isArray(messages) ? messages : [messages];
              return msgs.map((msg: string) => `${field}: ${msg}`);
            });
          detailedErrors = errorMessages;
          
          if (errorMessages.length > 0) {
            errorMessage = errorMessages[0]; // Show first error to user
          }
        }
        
        console.error('📛 Order validation failed:', {
          httpStatus: response.status,
          statusText: response.statusText,
          message: data.message || '(no message)',
          allErrors: detailedErrors,
          rawErrors: data.errors || '(no errors)',
          fullResponse: data,
        });
        throw new Error(errorMessage);
      }

      // Clear cart and redirect to success page
      clearCart();
      toast.success('Order placed successfully!');
      router.push(`/order-success?orderId=${data.order?.id || data.order?.order_id}`);
      
    } catch (error) {
      console.error('❌ Order error:', error);
      const errorMsg = (error as Error).message || 'Failed to place order. Please try again.';
      
      // Show detailed error for debugging
      console.group('Order Placement Error Details');
      console.error('Error Message:', errorMsg);
      console.error('Full Error:', error);
      console.log('Form Data Sent:', {
        fullName: formData.fullName,
        email: formData.email,
        phone: formData.phone,
        city: formData.city,
        area: formData.area,
        address: formData.address,
        items_count: items.length,
      });
      console.groupEnd();
      
      toast.error(errorMsg);
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className={styles.form}>
      {/* Login Prompt */}
      <div className={styles.loginPromptBox}>
        <p>Returning Customer?</p>
        <a
          href="#"
          className={styles.loginPromptLink}
          onClick={(e) => {
            e.preventDefault();
            openLoginModal();
          }}
        >
          Click here to login
        </a>
      </div>

      {/* BILLING & SHIPPING */}
      <h2 className={styles.sectionTitle}>Billing & Shipping</h2>
      
      {/* Full Name */}
      <div className={`${styles.field} ${styles.full}`}>
        <label htmlFor="fullName">
          Full Name <span className={styles.required}>*</span>
        </label>
        <input
          type="text"
          id="fullName"
          name="fullName"
          value={formData.fullName}
          onChange={handleInputChange}
          placeholder="Your Full Name"
          className={errors.fullName ? styles.error : ''}
          required
        />
        {errors.fullName && <span className={styles.errorText}>{errors.fullName}</span>}
      </div>

      {/* Phone & Email */}
      <div className={styles.row}>
        <div className={styles.field}>
          <label htmlFor="phone">
            Phone <span className={styles.required}>*</span>
          </label>
          <input
            type="tel"
            id="phone"
            name="phone"
            value={formData.phone}
            onChange={handleInputChange}
            placeholder="01XXXXXXXXX"
            className={errors.phone ? styles.error : ''}
            required
          />
          {errors.phone && <span className={styles.errorText}>{errors.phone}</span>}
        </div>
        <div className={styles.field}>
          <label htmlFor="email">
            Email <span className={styles.required}>*</span>
          </label>
          <input
            type="email"
            id="email"
            name="email"
            value={formData.email}
            onChange={handleInputChange}
            placeholder="example@email.com"
            className={errors.email ? styles.error : ''}
            required
          />
          {errors.email && <span className={styles.errorText}>{errors.email}</span>}
        </div>
      </div>

      {/* Address */}
      <div className={`${styles.field} ${styles.full}`}>
        <label htmlFor="address">
          Address <span className={styles.required}>*</span>
        </label>
        <input
          type="text"
          id="address"
          name="address"
          value={formData.address}
          onChange={handleInputChange}
          placeholder="House/Road/Area"
          className={errors.address ? styles.error : ''}
          required
        />
        {errors.address && <span className={styles.errorText}>{errors.address}</span>}
      </div>

      {/* City & Area */}
      <div className={styles.row}>
        <div className={styles.field}>
          <label htmlFor="city">
            District (Zila) <span className={styles.required}>*</span>
          </label>
          <select
            id="city"
            name="city"
            value={formData.city}
            onChange={handleInputChange}
            className={errors.city ? styles.error : ''}
            required
          >
            <option value="">Select District</option>
            {cityList.map(city => (
              <option key={city} value={city}>{city}</option>
            ))}
          </select>
          {errors.city && <span className={styles.errorText}>{errors.city}</span>}
        </div>
        <div className={styles.field}>
          <label htmlFor="area">
            Area <span className={styles.required}>*</span>
          </label>
          <select
            id="area"
            name="area"
            value={formData.area}
            onChange={handleInputChange}
            className={errors.area ? styles.error : ''}
            required
            disabled={!formData.city}
          >
            <option value="">
              {!formData.city ? 'Select City First' : 'Select Area'}
            </option>
            {availableAreas.map(area => (
              <option key={area} value={area}>{area}</option>
            ))}
          </select>
          {errors.area && <span className={styles.errorText}>{errors.area}</span>}
        </div>
      </div>

      {/* Order Notes */}
      <div className={`${styles.field} ${styles.full}`}>
        <label htmlFor="notes">Order Note (Optional)</label>
        <textarea
          id="notes"
          name="notes"
          value={formData.notes}
          onChange={handleInputChange}
          placeholder="Special instructions for delivery..."
          rows={3}
        />
      </div>


      {/* Payment Method */}
      <h2 className={styles.sectionTitle}>Choose Payment Method</h2>
      <div className={styles.paymentMethods}>
        <label className={styles.paymentMethod}>
          <input
            type="radio"
            name="payment"
            value="cod"
            checked={paymentMethod === 'cod'}
            onChange={(e) => setPaymentMethod(e.target.value as 'cod' | 'bkash' | 'nagad')}
          />
          <div className={styles.paymentInfo}>
            <div className={styles.paymentName}>Cash on Delivery</div>
            <div className={styles.paymentDesc}>Pay when you receive</div>
          </div>
        </label>
        
        <label className={styles.paymentMethod}>
          <input
            type="radio"
            name="payment"
            value="bkash"
            checked={paymentMethod === 'bkash'}
            onChange={(e) => setPaymentMethod(e.target.value as 'cod' | 'bkash' | 'nagad')}
          />
          <div className={styles.paymentInfo}>
            <div className={styles.paymentName}>Bkash</div>
            <div className={styles.paymentDesc}>Pay with bKash</div>
          </div>
        </label>
        
        <label className={styles.paymentMethod}>
          <input
            type="radio"
            name="payment"
            value="nagad"
            checked={paymentMethod === 'nagad'}
            onChange={(e) => setPaymentMethod(e.target.value as 'cod' | 'bkash' | 'nagad')}
          />
          <div className={styles.paymentInfo}>
            <div className={styles.paymentName}>Pay with Card/Mobile Wallet</div>
            <div className={styles.paymentDesc}>Secure online payment</div>
          </div>
        </label>
      </div>

      <button 
        type="submit" 
        className={styles.submitBtn}
        disabled={loading}
      >
        {loading ? 'Processing...' : 'Place Order'}
      </button>
    </form>
  );
}
