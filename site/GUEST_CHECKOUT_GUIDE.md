# Guest Checkout Guide

## Overview
Your e-commerce platform now supports **complete checkout for both Guest Users and Authenticated Users**.

## Feature Configuration
- **Feature Mode**: MODE 2 (Guest + Authenticated Users)
- **Location**: `app/config/features.ts`
- **Current Settings**:
  - `ALLOW_GUEST_CART`: ✅ Enabled
  - `ALLOW_GUEST_CHECKOUT`: ✅ Enabled
  - `ALLOW_GUEST_WISHLIST`: ✅ Enabled

## Guest User Checkout Flow

### Step 1: Browse Products
- Guest users can browse all products without logging in
- No authentication required to view products or product details

### Step 2: Add to Cart
- Guest users can click "Add to Cart" on any product
- Products are stored in **browser localStorage** (persists across sessions)
- Cart is maintained even if user closes and reopens browser

### Step 3: View Cart
- Navigate to `/cart` page
- See all items added to cart with:
  - Product image and name
  - Quantity selector
  - Remove option
  - Order summary

### Step 4: Proceed to Checkout
- Click "Checkout" button from cart page
- **No login required** - checkout page loads directly
- Guest users proceed to fill checkout form

### Step 5: Fill Checkout Form
Guest users fill in:
1. **Personal Info**
   - First Name *
   - Last Name *
   - Email *
   - Phone *

2. **Shipping Address**
   - Street Address *
   - City * (Dropdown)
   - Area * (Auto-populates based on City)

3. **Shipping Method**
   - Inside Dhaka (৳70, 1-2 days)
   - Outside Dhaka (৳130, 3-5 days)

4. **Payment Method**
   - Cash on Delivery (COD)
   - bKash
   - Nagad

5. **Order Notes** (Optional)

### Step 6: Place Order
- Click "Place Order" button
- Order is created in backend database
- Guest user is identified by phone number or email

### Step 7: Order Confirmation
- Redirected to order success page (`/order-success`)
- Shows:
  - Order ID
  - Customer details
  - Shipping information
  - Order total
  - Next steps

## Data Storage

### Guest Cart (localStorage)
```javascript
// Stored in browser localStorage
key: "cart_items"
value: [
  {
    product_id: 1,
    quantity: 2,
    product: { ...product details }
  }
]
```

### Guest Order
```javascript
// Sent to backend API as:
{
  customer_name: "John Doe",
  customer_email: "guest@example.com",
  customer_phone: "01700000000",
  shipping_address: "123 Main St",
  city: "Dhaka",
  area: "Dhanmondi",
  shipping_method: "inside_dhaka",
  shipping_cost: 70,
  payment_method: "cod",
  items: [ { product_id: 1, quantity: 2, price: 100 } ]
}
```

## Authenticated User Checkout Flow

### Differences from Guest
1. **Cart Storage**: Uses backend API instead of localStorage
2. **Pre-filled Form**: Customer info auto-fills from user profile
3. **Order History**: Orders linked to user account
4. **Automatic Login**: Token sent with order request for association

### Enhanced Features for Authenticated Users
- Access order history
- Track orders
- Save preferred addresses
- Faster checkout (pre-filled data)

## API Endpoints

### Create Order (Guest or Authenticated)
```bash
POST /api/orders
Headers:
  Content-Type: application/json
  Authorization: Bearer {token} (optional - only for authenticated users)
  
Request Body:
{
  customer_name: string,
  customer_email: string,
  customer_phone: string,
  shipping_address: string,
  city: string,
  area: string,
  shipping_method: string,
  shipping_cost: number,
  payment_method: string,
  items: Array<{ product_id, quantity, price }>
}
```

**Response**: 
```json
{
  "success": true,
  "order": { "id": 123, "order_number": "ORD-2026-001" }
}
```

## Testing Guest Checkout

### Test Scenario 1: Guest User Adds Items and Checks Out
1. **Open incognito/private browser window** (no login cookies)
2. Navigate to homepage
3. Click on any product
4. Click "Add to Cart"
5. Go to `/cart` page
6. Click "Checkout" button
7. Fill all required fields
8. Select shipping and payment method
9. Click "Place Order"
10. Verify order success page appears

### Test Scenario 2: Guest Cart Persistence
1. Add items to cart as guest
2. Close browser completely
3. Reopen browser and navigate to `/cart`
4. Verify items are still there (localStorage)

### Test Scenario 3: Guest User Converts to Registered
1. Complete guest checkout
2. After order, display signup prompt (optional feature)
3. User creates account
4. Account linked to orders by email/phone

## Configuration Changes

To change behavior, modify `app/config/features.ts`:

```typescript
// Allow only authenticated users
export const FEATURE_MODE = 1;

// Or allow both guest and authenticated
export const FEATURE_MODE = 2;
```

## Security Considerations

✅ **Implemented**:
- Form validation (email, phone format)
- CORS protection on backend
- No sensitive data stored in localStorage
- Token-based auth for authenticated users

⚠️ **Future Enhancements**:
- Email verification for guest orders
- Guest order status tracking via email
- Duplicate order prevention
- Rate limiting on order creation
- CAPTCHA for guest orders (if needed)

## Troubleshooting

### Issue: Guest cart not persisting
- Check if localStorage is enabled in browser
- Clear browser cache and try again
- Check browser console for errors

### Issue: Guest checkout fails
- Verify all required fields are filled
- Check backend API is running
- Verify form validation passed
- Check network tab for API errors

### Issue: Order created but success page not showing
- Check if order ID is being returned correctly
- Verify redirect URL is correct
- Check browser console for redirect errors

## Statistics

- **Checkout Fields**: 8 required, 1 optional
- **Cities Supported**: 10 major Bangladesh cities
- **Areas Per City**: 10-14 areas each
- **Payment Methods**: 3 options (COD, bKash, Nagad)
- **Shipping Methods**: 2 options (Inside/Outside Dhaka)

## Code Files Modified

- `app/checkout/page.tsx` - Removed login requirement
- `app/checkout/components/CheckoutForm.tsx` - Supports both user types
- `app/config/features.ts` - Feature mode configuration
- `app/common/CartContext.tsx` - Guest cart using localStorage

---

**Status**: ✅ Production Ready
**Last Updated**: January 21, 2026
