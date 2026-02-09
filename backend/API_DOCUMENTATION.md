# Rupchorcha API Documentation

Base URL: `http://localhost:8000/api`

Auth: Sanctum bearer token for protected routes. Public routes explicitly noted.

## Conventions
- Responses: `{ success: boolean, data?: any, message?: string, errors?: object }`
- Auth header: `Authorization: Bearer <token>`
- Pagination: Laravel-style meta/links when using `paginate()`.

## Auth & User
- POST `/register` — Create account (public)
- POST `/login` — Login, returns Sanctum token (public)
- POST `/login/google` or `/auth/google` — Google OAuth login (public)
- POST `/send-otp` — Send OTP (public)
- POST `/verify-otp` — Verify OTP (public)
- POST `/forgot-password` — Send reset link (public)
- POST `/reset-password` — Reset password (public)
- GET `/login` — Returns 405 helper (public)
- GET `/profile` — Get profile (auth)
- PUT `/profile` — Update profile (auth)
- PUT `/change-password` — Change password (auth)
- POST `/logout` — Logout current token (auth)
- POST `/logout-all` — Logout all sessions (auth)
- POST `/complete-profile` — Complete profile (auth)

## Products
- GET `/products` — List products (filters: `page`, `category_id`, `brand_id`, `tag`, `search`, `sort`, `min_price`, `max_price`)
  - `sort`: `default|price_low|price_high|name_asc|name_desc|newest`
- GET `/products/slug/{slug}` — Get product by slug (public; also falls back to ID)
- GET `/products/{id}` — Get product by ID (public)
- GET `/products/price-range` — Min/max price (public)
- POST `/products/related-by-cart` — Related products by cart items (public)
- POST `/products/frequently-bought-together` — FBT suggestions (public)
- POST `/products` — Create product (auth)
- PUT `/products/{id}` — Update product (auth)
- DELETE `/products/{id}` — Delete product (auth)
- POST `/products/import` — Bulk import (auth)
- DELETE `/product-images/{id}` — Delete product image (auth)

## Categories
- GET `/categories` — List categories (public)
- GET `/categories/{id}` — Category details (public)
- POST `/categories` — Create (auth)
- PUT `/categories/{id}` — Update (auth)
- DELETE `/categories/{id}` — Delete (auth)

## Brands
- GET `/brands` — List brands (public)
- GET `/brands/{id}` — Brand details (public)
- GET `/brands/{slug}/products` — Products for brand by slug (public)
- POST `/brands` — Create (auth)
- PUT `/brands/{id}` — Update (auth)
- DELETE `/brands/{id}` — Delete (auth)

## Tags
- GET `/tags/{slug}` — Tag details/products (public)

## Reviews
- GET `/reviews/{product_id}` — List reviews for product (public)
- GET `/products/{id}/rating` — Rating summary (public)
- POST `/reviews` — Create review (auth)
- PUT `/reviews/{id}` — Update review (auth)
- DELETE `/reviews/{id}` — Delete review (auth)

## Wishlist
- GET `/wishlist` — Get wishlist items (auth)
- POST `/wishlist` — Add product to wishlist (auth)
- DELETE `/wishlist/{product}` — Remove product (auth)

## Cart
- GET `/cart` — Get cart (public/guest supported)
- POST `/cart/add` — Add item (public)
- POST `/cart/update` — Update item (public)
- POST `/cart/remove` — Remove item (public)

## Coupons
- POST `/coupons/validate` — Validate coupon (public)

## Orders
- GET `/orders` — List orders (auth)
- GET `/orders/{id}` — Order details (public)
- POST `/orders` — Create order (guest allowed, user auto-detected)
- PUT `/orders/{id}/status` — Update status (auth)
- POST `/orders/{id}/cancel` — Cancel order (auth)

## Shipping
- GET `/shipping-zones` — List zones (public)
- GET `/shipping-zones/{id}` — Zone details (public)
- POST `/shipping-zones` — Create (auth)
- PUT `/shipping-zones/{id}` — Update (auth)
- DELETE `/shipping-zones/{id}` — Delete (auth)
- GET `/shipping-methods` — List methods (public)
- POST `/shipping-methods` — Create (auth)
- PUT `/shipping-methods/{id}` — Update (auth)
- DELETE `/shipping-methods/{id}` — Delete (auth)
- POST `/shipping/calculate` — Calculate shipping (public)
- GET `/shipping/zones-with-methods` — Zones + methods (public)
- POST `/shipping/methods-by-location` — Methods by location (public)
- GET `/shipping/all-methods` — All methods (public)

## Discounts
- GET `/discounts` — List discounts (public)
- GET `/discounts/{id}` — Discount details (public)
- POST `/discounts` — Create (auth)
- PUT `/discounts/{id}` — Update (auth)
- DELETE `/discounts/{id}` — Delete (auth)
- GET `/advance-discounts` — List advanced discounts (public)
- GET `/advance-discounts/{id}` — Advanced discount details (public)
- POST `/advance-discounts` — Create (auth)
- PUT `/advance-discounts/{id}` — Update (auth)
- DELETE `/advance-discounts/{id}` — Delete (auth)

## Addresses (auth)
- GET `/addresses`
- GET `/addresses/{id}`
- POST `/addresses`
- PUT `/addresses/{id}`
- DELETE `/addresses/{id}`

## Payments & Transactions
- GET `/payment/gateways` — Available gateways (public)
- POST `/payment/initiate` — Start payment (auth)
- POST `/payment/callback/{gateway}` — Gateway callback (public)
- GET `/transactions` — List transactions (public)
- GET `/transactions/{id}` — Transaction details (public)
- POST `/transactions` — Create (auth)
- PUT `/transactions/{id}` — Update (auth)
- DELETE `/transactions/{id}` — Delete (auth)

## Payment Gateways (admin CRUD)
- GET `/payment-gateways`
- GET `/payment-gateways/{id}`
- POST `/payment-gateways` (auth)
- PUT `/payment-gateways/{id}` (auth)
- DELETE `/payment-gateways/{id}` (auth)

## Payment Summaries (admin CRUD)
- GET `/payment-summaries`
- GET `/payment-summaries/{id}`
- POST `/payment-summaries` (auth)
- PUT `/payment-summaries/{id}` (auth)
- DELETE `/payment-summaries/{id}` (auth)

## Warehouses
- GET `/warehouses` — List (public)
- GET `/warehouses/{id}` — Detail (public)
- POST `/warehouses` — Create (auth)
- PUT `/warehouses/{id}` — Update (auth)
- DELETE `/warehouses/{id}` — Delete (auth)

## Suppliers
- GET `/suppliers` — List (public)
- GET `/suppliers/{id}` — Detail (public)
- POST `/suppliers` — Create (auth)
- PUT `/suppliers/{id}` — Update (auth)
- DELETE `/suppliers/{id}` — Delete (auth)

## Couriers
- GET `/couriers` — List (public)
- GET `/couriers/{id}` — Detail (public)
- POST `/couriers` — Create (auth)
- PUT `/couriers/{id}` — Update (auth)
- DELETE `/couriers/{id}` — Delete (auth)

## Attributes
- GET `/attributes` — List (public)
- GET `/attributes/{id}` — Detail (public)
- POST `/attributes` — Create (auth)
- PUT `/attributes/{id}` — Update (auth)
- DELETE `/attributes/{id}` — Delete (auth)

## Tags & Product Tags
- GET `/tags/{slug}` — Tag detail/products (public)

## Campaigns, Bulk Email/SMS
- Campaign histories: CRUD on `/campaign-histories` (list/show public; create/update/delete auth)
- Bulk emails: CRUD on `/bulk-emails` (list/show public; create/update/delete auth)
- Bulk SMS: CRUD on `/bulk-sms` (list/show public; create/update/delete auth)

## Refunds
- GET `/refunds` — List (public)
- GET `/refunds/{id}` — Detail (public)
- POST `/refunds` — Create (auth)
- PUT `/refunds/{id}` — Update (auth)
- DELETE `/refunds/{id}` — Delete (auth)

## Orders Support
- POST `/products/related-by-cart` — Related items (public)
- POST `/products/frequently-bought-together` — FBT (public)

## Debug/Health (dev only)
- GET `/debug/order-schema`
- POST `/debug/validate-order`
- GET `/health`, GET `/health/status`

## Notes
- Many admin CRUD routes are protected by `auth:sanctum`; ensure tokens are from authenticated admin users where applicable.
- Price sorting uses the `price` field (not sale price) on backend.
- Product slug fallback: `/products/slug/{slug}` also accepts numeric ID.
