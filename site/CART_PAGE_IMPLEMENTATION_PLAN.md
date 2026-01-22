# Cart Page Implementation Plan - Rupchorcha

## 📋 Backend Review & Observations

### Current Backend Structure ✅
- **Model**: `Cart.php` exists with `CartItem` relationship
- **API Controller**: `CartController.php` in `app/Http/Controllers/API/`
- **Routes**: Cart endpoints defined in `routes/api.php`
  - `GET /cart` - Get all cart items
  - `POST /cart/add` - Add item to cart
  - `POST /cart/update` - Update item quantity
  - `POST /cart/remove` - Remove item from cart

### Backend Best Practices Applied ✅
- API controller separation (dedicated `API/` folder)
- Model relationships properly defined
- RESTful API routes
- Cart functionality modularized

### Backend Improvements Needed ❌
1. **Add Cart Service/Repository**: Extract business logic from controller
2. **Add Request Validation**: Create FormRequest classes for cart operations
3. **Add Resource Classes**: Create CartResource for consistent API responses
4. **Error Handling**: Standardized error responses
5. **Database Relationships**: Define relationships with Product, User models

---

## 📱 Frontend Review & Observations

### Current Frontend Structure ✅
- **CartContext**: Central state management (`common/CartContext.tsx`)
- **CartSidebar**: UI component for cart display
- **Integration**: Connected to backend API
- **Image Handling**: Proper image URL resolution

### Frontend Best Practices Applied ✅
- React Context API for state management
- Component modularity
- CSS Modules for styling
- TypeScript for type safety
- Next.js App Router

### Frontend Issues & Improvements Needed ❌
1. **Dedicated Cart Page Missing**: Only sidebar exists, needs full page
2. **Empty State UX**: Basic empty message, needs visual enhancement
3. **Loading States**: No loading indicators
4. **Error Handling**: No error messages for failed operations
5. **Form Validation**: No input validation
6. **Responsive Design**: Inline styles, should use CSS Modules
7. **Constants**: Hardcoded backend URL, should use config
8. **Service Layer**: API calls mixed with component logic

---

## 🏗️ Recommended Folder Structure

### Backend Structure
```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── API/
│   │   │       ├── CartController.php          (Orchestration only)
│   │   │       └── ...
│   │   ├── Requests/                           (NEW)
│   │   │   ├── StoreCartRequest.php
│   │   │   ├── UpdateCartRequest.php
│   │   │   └── ...
│   │   └── Resources/                          (NEW)
│   │       ├── CartResource.php
│   │       ├── CartItemResource.php
│   │       └── ...
│   ├── Services/                               (NEW)
│   │   ├── CartService.php
│   │   └── ...
│   ├── Models/
│   │   ├── Cart.php
│   │   ├── CartItem.php
│   │   └── ...
│   └── Exceptions/
│       └── CartException.php                   (NEW)
├── routes/
│   └── api.php
└── ...
```

### Frontend Structure
```
site/app/
├── cart/                                       (NEW)
│   ├── page.tsx                               (Main cart page)
│   ├── components/
│   │   ├── CartItemsTable.tsx
│   │   ├── CartSummary.tsx
│   │   ├── PromoCodeForm.tsx
│   │   └── EmptyCart.tsx
│   └── hooks/
│       └── useCartOperations.ts                (Custom hooks for cart logic)
├── components/
│   ├── CartSidebar.tsx                        (Keep existing)
│   └── ...
├── services/                                   (NEW)
│   ├── cartService.ts                         (API calls centralized)
│   └── ...
├── config/                                     (NEW)
│   └── api.config.ts                          (Centralize API URLs)
├── contexts/
│   ├── CartContext.tsx                        (Existing)
│   └── ...
└── ...
```

---

## 📝 Implementation Steps

### Phase 1: Backend Setup (Best Practices)

#### Step 1.1: Create Request Validation
```php
// app/Http/Requests/StoreCartRequest.php
class StoreCartRequest extends FormRequest {
    public function rules() {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
        ];
    }
}
```

#### Step 1.2: Create API Resources
```php
// app/Http/Resources/CartItemResource.php
class CartItemResource extends JsonResource {
    // Format cart item data consistently
}

// app/Http/Resources/CartResource.php
class CartResource extends JsonResource {
    // Format entire cart with items
}
```

#### Step 1.3: Create Cart Service
```php
// app/Services/CartService.php
class CartService {
    public function addItem($user, $productId, $quantity)
    public function updateItem($user, $productId, $quantity)
    public function removeItem($user, $productId)
    public function getCart($user)
    public function clearCart($user)
}
```

#### Step 1.4: Update CartController
```php
// Use Service instead of direct DB queries
// Keep controller thin (only orchestration)
```

### Phase 2: Frontend Setup (Best Practices)

#### Step 2.1: Create API Config
```typescript
// app/config/api.config.ts
export const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000';
export const API_ENDPOINTS = {
  CART: '/api/cart',
  PRODUCTS: '/api/products',
  // ...
};
```

#### Step 2.2: Create Cart Service
```typescript
// app/services/cartService.ts
export class CartService {
  static async getCart() { }
  static async addItem(productId, quantity) { }
  static async updateItem(productId, quantity) { }
  static async removeItem(productId) { }
}
```

#### Step 2.3: Create Custom Hook
```typescript
// app/cart/hooks/useCartOperations.ts
export function useCartOperations() {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  
  const addItem = async (productId, quantity) => { }
  const removeItem = async (productId) => { }
  // ...
}
```

#### Step 2.4: Create Cart Page Components
- `CartItemsTable.tsx` - Display items in table format
- `CartSummary.tsx` - Subtotal, tax, shipping, total
- `PromoCodeForm.tsx` - Apply discount codes
- `EmptyCart.tsx` - When no items

#### Step 2.5: Create Main Cart Page
```typescript
// app/cart/page.tsx
export default function CartPage() {
  const { items } = useCart();
  const { updateItem, removeItem, loading, error } = useCartOperations();
  
  return (
    <div>
      {loading && <LoadingSpinner />}
      {error && <ErrorAlert message={error} />}
      {items.length === 0 ? <EmptyCart /> : (
        <>
          <CartItemsTable items={items} onUpdate={updateItem} onRemove={removeItem} />
          <CartSummary items={items} />
        </>
      )}
    </div>
  );
}
```

---

## 🎨 UI/UX Best Practices for Cart Page

1. **Table Layout**: Show items in organized table with columns:
   - Product Image
   - Product Name & Details
   - Price per Unit
   - Quantity (editable with +/- buttons)
   - Subtotal (price × quantity)
   - Remove button

2. **Cart Summary Section**: (Right sidebar or bottom)
   - Subtotal
   - Shipping cost (if applicable)
   - Tax calculation
   - Promo code input
   - Total price (bold, highlighted)
   - Checkout button (prominent CTA)
   - Continue Shopping button

3. **Empty State**: 
   - Illustration/Icon
   - "Your cart is empty" message
   - "Continue Shopping" button link

4. **States to Handle**:
   - Loading states (spinner during operations)
   - Error states (toast notifications)
   - Success states (item removed, quantity updated)
   - Stock availability (warn if out of stock)

5. **Responsive Design**:
   - Desktop: Table layout with right sidebar summary
   - Mobile: Stacked layout, summary at bottom

---

## 🔐 API Response Structure (Recommended)

```json
{
  "success": true,
  "message": "Cart retrieved successfully",
  "data": {
    "cart": {
      "id": 1,
      "user_id": 5,
      "items": [
        {
          "id": 1,
          "cart_id": 1,
          "product_id": 10,
          "quantity": 2,
          "product": {
            "id": 10,
            "name": "Product Name",
            "price": 1000,
            "sale_price": 900,
            "main_image": "path/to/image.jpg"
          }
        }
      ],
      "subtotal": 1800,
      "tax": 180,
      "shipping": 100,
      "total": 2080,
      "discount_applied": 0,
      "promoCode": null
    }
  },
  "error": null
}
```

---

## ✅ Checklist for Implementation

### Backend
- [ ] Create `StoreCartRequest`, `UpdateCartRequest` request classes
- [ ] Create `CartItemResource`, `CartResource` resource classes
- [ ] Create `CartService` with business logic
- [ ] Update `CartController` to use service layer
- [ ] Add proper error handling (use HTTP status codes)
- [ ] Add database relationships in models
- [ ] Test all endpoints with Postman/Insomnia
- [ ] Document API responses

### Frontend
- [ ] Create `api.config.ts` for centralized API config
- [ ] Create `cartService.ts` for API calls
- [ ] Create `useCartOperations.ts` custom hook
- [ ] Create Cart page components (ItemsTable, Summary, EmptyCart)
- [ ] Create main `cart/page.tsx`
- [ ] Add loading states with spinners
- [ ] Add error handling with toast notifications
- [ ] Add form validation for quantity input
- [ ] Style with CSS Modules (not inline styles)
- [ ] Make responsive (mobile-first approach)
- [ ] Test all user interactions
- [ ] Add accessibility features (ARIA labels, semantic HTML)

---

## 📚 Files to Create/Modify

### Backend Files
1. Create: `app/Http/Requests/StoreCartRequest.php`
2. Create: `app/Http/Requests/UpdateCartRequest.php`
3. Create: `app/Http/Resources/CartResource.php`
4. Create: `app/Http/Resources/CartItemResource.php`
5. Create: `app/Services/CartService.php`
6. Modify: `app/Http/Controllers/API/CartController.php`
7. Modify: `app/Models/Cart.php` (add relationships)
8. Modify: `app/Models/CartItem.php` (add relationships)

### Frontend Files
1. Create: `app/config/api.config.ts`
2. Create: `app/services/cartService.ts`
3. Create: `app/cart/page.tsx`
4. Create: `app/cart/hooks/useCartOperations.ts`
5. Create: `app/cart/components/CartItemsTable.tsx`
6. Create: `app/cart/components/CartSummary.tsx`
7. Create: `app/cart/components/PromoCodeForm.tsx`
8. Create: `app/cart/components/EmptyCart.tsx`
9. Modify: `app/components/CartSidebar.tsx` (refactor to use services)
10. Modify: `app/contexts/CartContext.tsx` (enhance state management)

---

## 🚀 Best Practices Summary

### Backend
✅ Use Service Layer pattern for business logic
✅ Use Form Requests for validation
✅ Use Resources for consistent API responses
✅ Keep Controllers thin (orchestration only)
✅ Use proper HTTP status codes
✅ Add comprehensive error handling
✅ Document API endpoints

### Frontend
✅ Centralize API calls in service layer
✅ Use custom hooks for reusable logic
✅ Use CSS Modules instead of inline styles
✅ Separate concerns: components, services, contexts
✅ Handle loading, error, and success states
✅ Implement proper error handling
✅ Make responsive design
✅ Use TypeScript for type safety
✅ Add accessibility features

---

## 💡 Additional Features to Consider

1. **Quantity Selector**: Limit based on stock availability
2. **Promo Code**: Apply discount codes
3. **Stock Check**: Warn if items out of stock
4. **Estimated Shipping**: Calculate based on location
5. **Saved for Later**: Save items without checking out
6. **Continue Shopping**: Quick link back to products
7. **Order Summary**: Show before checkout
8. **Guest Checkout**: Allow without login

---

**Ready to implement? Start with Phase 1 (Backend) first, then Phase 2 (Frontend)!**
