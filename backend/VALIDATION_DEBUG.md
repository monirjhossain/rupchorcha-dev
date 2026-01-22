#!/bin/bash
# Order Validation Debug Guide

echo "=== Order Validation Error Debugging ==="
echo ""
echo "STEP 1: Check the order schema"
curl -X GET http://localhost:8000/api/debug/order-schema

echo ""
echo ""
echo "STEP 2: Validate your order payload"
echo "POST to: http://localhost:8000/api/debug/validate-order"
echo "Example payload:"
cat << 'EOF'
{
  "customer_name": "John Doe",
  "customer_email": "john@example.com",
  "customer_phone": "01712345678",
  "shipping_address": "123 Main Street, Apartment 4B",
  "city": "Dhaka",
  "area": "Dhaka Sadar",
  "notes": "Please deliver after 5 PM",
  "payment_method": "cod",
  "shipping_method": "standard",
  "shipping_cost": 60,
  "coupon_code": null,
  "discount_amount": 0,
  "items": [
    {
      "product_id": 1,
      "quantity": 2,
      "price": 500
    },
    {
      "product_id": 3,
      "quantity": 1,
      "price": 1200
    }
  ]
}
EOF

echo ""
echo ""
echo "STEP 3: Common validation errors and fixes:"
echo ""
echo "❌ Error: 'customer_name is required'"
echo "✅ Fix: Make sure customer_name is provided and not empty"
echo ""
echo "❌ Error: 'customer_phone.min' - Phone must be 11+ digits"
echo "✅ Fix: Provide phone number in format: 01712345678 (or similar, 11+ digits)"
echo ""
echo "❌ Error: 'items is required' or 'items.min'"
echo "✅ Fix: Ensure cart has at least one item with product_id, quantity, and price"
echo ""
echo "❌ Error: 'payment_method must be one of: cod, bkash, nagad'"
echo "✅ Fix: Select one of the valid payment methods"
echo ""
echo "❌ Error: 'price must be numeric'"
echo "✅ Fix: Item price must be a number, not a string"
echo ""
echo "=== Browser Console Check ==="
echo "1. Open Chrome DevTools (F12)"
echo "2. Go to Console tab"
echo "3. Look for '📤 Submitting order data:' to see the exact payload being sent"
echo "4. Check for '❌ Order error:' to see the actual error from backend"
echo ""
